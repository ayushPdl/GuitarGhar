// ==========================================
// GUITARGHAR - GUITAR TUNER
// Uses Web Audio API and pitch detection
// ==========================================

var TUNINGS = {
    standard: ['E2', 'A2', 'D3', 'G3', 'B3', 'E4'],
    dropd:    ['D2', 'A2', 'D3', 'G3', 'B3', 'E4'],
    openg:    ['D2', 'G2', 'D3', 'G3', 'B3', 'D4'],
    dadgad:   ['D2', 'A2', 'D3', 'G3', 'A3', 'D4'],
    opend:    ['D2', 'A2', 'D3', 'F#3', 'A3', 'D4'],
    opene:    ['E2', 'B2', 'E3', 'G#3', 'B3', 'E4']
};

var NOTE_NAMES = ['C', 'C#', 'D', 'D#', 'E', 'F', 'F#', 'G', 'G#', 'A', 'A#', 'B'];

var NOTE_FREQ = {};
for (var midi = 0; midi < 128; midi++) {
    var noteName = NOTE_NAMES[midi % 12] + Math.floor(midi / 12 - 1);
    NOTE_FREQ[noteName] = 440 * Math.pow(2, (midi - 69) / 12);
}

// State
var currentTuning  = 'standard';
var targetNote     = null;
var audioCtx       = null;
var analyser       = null;
var mediaStream    = null;
var rafId          = null;
var isRunning      = false;

function setText(id, text) {
    var el = document.getElementById(id);
    if (el) el.textContent = text;
}

function setStyle(id, prop, value) {
    var el = document.getElementById(id);
    if (el) el.style[prop] = value;
}

function setClass(id, className) {
    var el = document.getElementById(id);
    if (el) el.className = className;
}


// ==========================================
// MODAL CONTROLS
// ==========================================

function showLoginModal() {
    window.location.href = '/guitarghar/login.php';
}

function closeLoginModal() {
    var modal = document.getElementById('loginModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

// Close modal when clicking on the dark overlay background
window.addEventListener('click', function(event) {
    var modal = document.getElementById('loginModal');
    if (event.target === modal) {
        closeLoginModal();
    }
});

// ==========================================
// BUILD STRING BUTTONS
// ==========================================

function buildStringButtons() {
    var tuning    = TUNINGS[currentTuning];
    var container = document.getElementById('string-buttons');
    if (!container) return;
    
    container.innerHTML = '';

    var stringNames = ['6th String', '5th String', '4th String', '3rd String', '2nd String', '1st String'];

    for (var i = 0; i < tuning.length; i++) {
        var btn = document.createElement('button');
        btn.className   = 'string-btn';
        btn.dataset.note = tuning[i];
        btn.innerHTML   =
            '<span class="string-number">' + stringNames[i] + '</span>' +
            '<span class="string-note">' + tuning[i] + '</span>' +
            '<span class="string-freq">' + NOTE_FREQ[tuning[i]].toFixed(1) + ' Hz</span>';

        btn.addEventListener('click', function() {
            var allBtns = document.querySelectorAll('.string-btn');
            for (var j = 0; j < allBtns.length; j++) {
                allBtns[j].classList.remove('active');
            }
            this.classList.add('active');
            targetNote = this.dataset.note;

            setText('tuner-target', 'Target: ' + targetNote + ' (' + NOTE_FREQ[targetNote].toFixed(1) + ' Hz)');

            setText('tuner-status', 'Play the string now');
            setClass('tuner-status', 'tuner-status');
        });

        container.appendChild(btn);
    }
}

// ==========================================
// SET TUNING
// ==========================================

function setTuning(tuning, btn) {
    currentTuning = tuning;
    targetNote    = null;

    var allBtns = document.querySelectorAll('.tuning-btn');
    for (var i = 0; i < allBtns.length; i++) {
        allBtns[i].classList.remove('active');
    }
    btn.classList.add('active');

    buildStringButtons();

    setText('tuner-note', '-');
    setText('tuner-freq', '-- Hz');
    setText('tuner-target', '');
    setText('tuner-status', 'Select a string to begin');
    setClass('tuner-status', 'tuner-status');
    setStyle('meter-bar', 'width', '50%');
    setStyle('meter-bar', 'background', '#e8352a');
}

// ==========================================
// PITCH DETECTION
// ==========================================

function detectPitch(buffer, sampleRate) {
    var rms = 0;
    for (var i = 0; i < buffer.length; i++) {
        rms += buffer[i] * buffer[i];
    }
    rms = Math.sqrt(rms / buffer.length);
    if (rms < 0.01) {
        return -1;
    }

    var r1 = 0;
    var r2 = buffer.length - 1;
    var threshold = 0.2;

    for (var i = 0; i < buffer.length / 2; i++) {
        if (Math.abs(buffer[i]) >= threshold) {
            r1 = i;
            break;
        }
    }
    for (var i = 1; i < buffer.length / 2; i++) {
        if (Math.abs(buffer[buffer.length - i]) >= threshold) {
            r2 = buffer.length - i;
            break;
        }
    }

    buffer = buffer.slice(r1, r2);
    var SIZE = buffer.length;
    if (SIZE < 10) {
        return -1;
    }

    var c = new Float32Array(SIZE).fill(0);
    for (var i = 0; i < SIZE; i++) {
        for (var j = 0; j < SIZE - i; j++) {
            c[i] += buffer[j] * buffer[j + i];
        }
    }

    var d = 0;
    while (d < SIZE - 1 && c[d] > c[d + 1]) {
        d++;
    }

    var maxVal = -1;
    var maxPos = -1;
    for (var i = d; i < SIZE; i++) {
        if (c[i] > maxVal) {
            maxVal = c[i];
            maxPos = i;
        }
    }

    if (maxPos < 1 || maxPos >= SIZE - 1) {
        return -1;
    }

    var T0 = maxPos;
    var x1  = c[T0 - 1];
    var x2  = c[T0];
    var x3  = c[T0 + 1];
    var a   = (x1 + x3 - 2 * x2) / 2;
    var b   = (x3 - x1) / 2;
    if (a) {
        T0 = T0 - b / (2 * a);
    }

    return sampleRate / T0;
}

function getClosestNote(freq) {
    var closest = null;
    var minDiff = Infinity;
    for (var note in NOTE_FREQ) {
        var diff = Math.abs(NOTE_FREQ[note] - freq);
        if (diff < minDiff) {
            minDiff = diff;
            closest = note;
        }
    }
    return closest;
}

function getCents(freq, note) {
    return 1200 * Math.log2(freq / NOTE_FREQ[note]);
}

function updatePitch() {
    if (!isRunning) {
        return;
    }

    var buffer = new Float32Array(analyser.fftSize);
    analyser.getFloatTimeDomainData(buffer);

    var freq = detectPitch(buffer, audioCtx.sampleRate);

    if (freq > 50 && freq < 1500) {

        var note  = getClosestNote(freq);
        // Compare against selected target string when set, otherwise closest note
        var refNote = targetNote || note;
        var cents = getCents(freq, refNote);

        setText('tuner-note', note);
        setText('tuner-freq', freq.toFixed(1) + ' Hz');

        var bar = document.getElementById('meter-bar');
        // Clamp meter around center using cents vs target (-50..+50 -> 0..100)
        var pct = Math.min(100, Math.max(0, 50 + (cents / 50) * 50));
        if (bar) bar.style.width = pct + '%';

        if (bar) {
            if (Math.abs(cents) < 5) {
                bar.style.background = '#2ecc71';
            } else if (Math.abs(cents) < 15) {
                bar.style.background = '#f39c12';
            } else {
                bar.style.background = '#e8352a';
            }
        }

        if (targetNote) {
            if (Math.abs(cents) < 5) {
                setText('tuner-status', 'In Tune!');
                setClass('tuner-status', 'tuner-status status-intune');
            } else if (cents < -5) {
                setText('tuner-status', 'Too Flat - tune up');
                setClass('tuner-status', 'tuner-status status-flat');
            } else if (cents > 5) {
                setText('tuner-status', 'Too Sharp - tune down');
                setClass('tuner-status', 'tuner-status status-sharp');
            } else {
                setText('tuner-status', 'Almost there...');
                setClass('tuner-status', 'tuner-status status-close');
            }
        }

    } else {
        setText('tuner-note', '-');
        setText('tuner-freq', '-- Hz');
        setStyle('meter-bar', 'width', '50%');
    }

    rafId = requestAnimationFrame(updatePitch);
}


// ==========================================
// START / STOP TUNER
// ==========================================

function startTuner() {
    // Check if user is logged in (variable passed from PHP in tuner.php)
    if (typeof IS_LOGGED_IN !== 'undefined' && !IS_LOGGED_IN) {
        showLoginModal();
        return;
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('Your browser does not support microphone access. Please use Chrome or Firefox.');
        return;
    }

    navigator.mediaDevices.getUserMedia({ audio: true })
        .then(function(stream) {
            mediaStream = stream;
            audioCtx    = new (window.AudioContext || window.webkitAudioContext)();
            analyser    = audioCtx.createAnalyser();
            analyser.fftSize = 2048;

            var source = audioCtx.createMediaStreamSource(stream);
            source.connect(analyser);

            isRunning = true;

            setStyle('start-btn', 'display', 'none');
            setStyle('stop-btn', 'display', 'inline-flex');

            updatePitch();
        })
        .catch(function(err) {
            alert('Microphone access was denied. Please allow microphone access in your browser settings and try again.');
        });
}

function stopTuner() {
    isRunning = false;

    if (rafId) {
        cancelAnimationFrame(rafId);
        rafId = null;
    }

    if (mediaStream) {
        mediaStream.getTracks().forEach(function(track) {
            track.stop();
        });
        mediaStream = null;
    }

    if (audioCtx) {
        audioCtx.close();
        audioCtx = null;
    }

    setText('tuner-note', '-');
    setText('tuner-freq', '-- Hz');
    setStyle('meter-bar', 'width', '50%');
    setStyle('meter-bar', 'background', '#e8352a');
    setText('tuner-status', 'Select a string to begin');
    setClass('tuner-status', 'tuner-status');
    setStyle('start-btn', 'display', 'inline-flex');
    setStyle('stop-btn', 'display', 'none');
}

// Initial setup — skip if guest view has no tuner DOM
if (document.getElementById('string-buttons')) {
    buildStringButtons();
}
