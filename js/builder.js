function appUrl(path){path=String(path||'').replace(/^\/+/,'');var b=(typeof APP_BASE==='string')?APP_BASE:'';return(b?b:'')+'/'+path;}

// GUITARGHAR - GUITAR BUILDER

// Current state
var currentShape  = 'strat';
var currentColor  = '#c8382a';
var loadedImages  = {};

var IMAGE_PATHS = {
    strat: appUrl('img/strat.png'),
    lespaul: appUrl('img/lespaul.png'),
    sg: appUrl('img/sg.png'),
    acoustic: appUrl('img/acoustic.png')
};

var SHAPE_NAMES = {
    strat: 'Stratocaster',
    lespaul: 'Les Paul',
    sg: 'SG',
    acoustic: 'Acoustic'
};

var TONE_DESC = {
    'Alder':     'Bright, punchy tone with excellent sustain. Versatile across genres. Classic Fender character.',
    'Mahogany':  'Warm, thick tone with excellent sustain. Deep midrange. Classic Gibson sound.',
    'Basswood':  'Balanced, even tone. Light weight. Very player-friendly. Popular for metal.',
    'Ash':       'Bright and resonant with a pronounced twang. Used in classic Telecasters.',
    'Maple':     'Very bright and defined. High sustain. Cuts through the mix clearly.',
    'Spruce':    'Crisp, articulate acoustic tone with wide dynamic range. Most popular acoustic top wood.'
};

// Check authentication status before permitting customization
function handleCustomization(callback, param) {
    if (typeof IS_LOGGED_IN !== 'undefined' && !IS_LOGGED_IN) {
        showLoginPrompt();
        return;
    }
    if (typeof callback === 'function') {
        callback(param);
    }
}

// Display login/register cards in place of canvas
function showLoginPrompt() {
    var canvas = document.getElementById('guitar-canvas');
    var prompt = document.getElementById('builder-login-prompt');
    if (canvas) canvas.style.display = 'none';
    if (prompt) prompt.style.display = 'flex';
}

function preloadImages() {
    var shapes = Object.keys(IMAGE_PATHS);
    var loaded  = 0;

    shapes.forEach(function(shape) {
        var img   = new Image();
        img.crossOrigin = 'anonymous';

        img.onload = function() {
            loadedImages[shape] = img;
            loaded++;
            if (loaded === shapes.length) {
                drawGuitar();
            }
        };

        img.onerror = function() {
            console.error('Could not load image: ' + IMAGE_PATHS[shape]);
            loaded++;
            if (loaded === shapes.length) {
                drawGuitar();
            }
        };

        img.src = IMAGE_PATHS[shape];
    });
}

function setShape(shape, btn) {
    currentShape = shape;

    document.querySelectorAll('.shape-btn').forEach(function(b) {
        b.classList.remove('active');
    });
    if (btn) btn.classList.add('active');

    document.getElementById('canvas-label').textContent = SHAPE_NAMES[shape];
    document.getElementById('spec-shape').textContent = SHAPE_NAMES[shape];

    drawGuitar();
}

function setColor(hex) {
    currentColor = hex;
    document.getElementById('b-color').value = hex;
    document.getElementById('color-hex').textContent = hex.toUpperCase();
    document.getElementById('spec-dot').style.background = hex;
    document.getElementById('spec-color').innerHTML =
        '<span class="color-dot" id="spec-dot" style="background:' + hex + ';"></span>' +
        hex.toUpperCase();
    applyColor();
}

function applyColor() {
    currentColor = document.getElementById('b-color').value;
    var hex = currentColor.toUpperCase();

    document.getElementById('color-hex').textContent = hex;
    document.getElementById('spec-dot').style.background = currentColor;
    document.getElementById('spec-color').innerHTML =
        '<span class="color-dot" id="spec-dot" style="background:' + currentColor + ';"></span>' +
        hex;

    var intensity = document.getElementById('b-intensity').value;
    document.getElementById('intensity-val').textContent = intensity + '%';

    drawGuitar();
}

function drawGuitar() {
    var canvas = document.getElementById('guitar-canvas');
    if (!canvas) return;

    var ctx = canvas.getContext('2d');
    var W   = canvas.width;
    var H   = canvas.height;

    ctx.clearRect(0, 0, W, H);

    var img = loadedImages[currentShape];
    if (!img) return;

    var imgRatio    = img.naturalWidth / img.naturalHeight;
    var canvasRatio = W / H;
    var drawW, drawH, drawX, drawY;

    if (imgRatio > canvasRatio) {
        drawW = W * 0.92;
        drawH = drawW / imgRatio;
    } else {
        drawH = H * 0.95;
        drawW = drawH * imgRatio;
    }

    drawX = (W - drawW) / 2;
    drawY = (H - drawH) / 2;

    ctx.drawImage(img, drawX, drawY, drawW, drawH);

    var imageData = ctx.getImageData(0, 0, W, H);
    var data      = imageData.data;

    var origR = 170;
    var newR = parseInt(currentColor.slice(1, 3), 16);
    var newG = parseInt(currentColor.slice(3, 5), 16);
    var newB = parseInt(currentColor.slice(5, 7), 16);

    var intensity = parseInt(document.getElementById('b-intensity').value) / 100;

    for (var i = 0; i < data.length; i += 4) {
        var pr = data[i];
        var pg = data[i + 1];
        var pb = data[i + 2];
        var pa = data[i + 3];

        if (pa < 10) continue;
        if (pr > 180 && pg > 170 && pb > 170) continue;

        var brightness = (pr + pg + pb) / 3;
        if (brightness < 25) continue;
        if (pr < 120 && pg < 100 && pb < 90) continue;

        if (pr > 130 && pg > 90 && pg < 210 && pb < 120) continue;
        if (pr > 180 && pg > 180 && pb > 180) continue;

        var isBodyPixel = (
            pr > 90 &&
            pr > pg * 1.35 &&
            pr > pb * 1.35 &&
            (pr - Math.max(pg, pb)) > 35
        );

        if (!isBodyPixel) continue;

        var bodyBrightness = pr / origR;
        bodyBrightness = Math.max(0.1, Math.min(2.0, bodyBrightness));

        var tintR = Math.min(255, newR * bodyBrightness);
        var tintG = Math.min(255, newG * bodyBrightness);
        var tintB = Math.min(255, newB * bodyBrightness);

        data[i]     = Math.round(pr * (1 - intensity) + tintR * intensity);
        data[i + 1] = Math.round(pg * (1 - intensity) + tintG * intensity);
        data[i + 2] = Math.round(pb * (1 - intensity) + tintB * intensity);
    }

    ctx.putImageData(imageData, 0, 0);
}

function updateSpecs() {
    var wood     = document.getElementById('b-wood').value.split(' — ')[0];
    var neckwood = document.getElementById('b-neckwood').value.split(' — ')[0];
    var fb       = document.getElementById('b-fb').value;
    var pickup   = document.getElementById('b-pickup').value.split(' — ')[0];
    var bridge   = document.getElementById('b-bridge').value;
    var hw       = document.getElementById('b-hw').value;

    document.getElementById('spec-wood').textContent     = wood;
    document.getElementById('spec-neckwood').textContent = neckwood;
    document.getElementById('spec-fb').textContent       = fb;
    document.getElementById('spec-pickup').textContent   = pickup;
    document.getElementById('spec-bridge').textContent   = bridge;
    document.getElementById('spec-hw').textContent       = hw;

    var toneKey = document.getElementById('b-wood').value.split(' — ')[0];
    document.getElementById('tone-desc').textContent = TONE_DESC[toneKey] || '';

    document.getElementById('build-summary').textContent =
        SHAPE_NAMES[currentShape] + ' · ' + wood + ' · ' + pickup + ' · ' + hw;
}

function saveBuild() {
    var shape    = currentShape;
    var color    = currentColor;
    var wood     = document.getElementById('b-wood').value.split(' — ')[0];
    var neckwood = document.getElementById('b-neckwood').value.split(' — ')[0];
    var fb       = document.getElementById('b-fb').value;
    var pickup   = document.getElementById('b-pickup').value.split(' — ')[0];
    var bridge   = document.getElementById('b-bridge').value;
    var hw       = document.getElementById('b-hw').value;

    var formData = new FormData();
    formData.append('shape',      shape);
    formData.append('color',      color);
    formData.append('body_wood',   wood);
    formData.append('neck_wood',   neckwood);
    formData.append('fingerboard', fb);
    formData.append('pickups',    pickup);
    formData.append('bridge',     bridge);
    formData.append('hardware',   hw);

    fetch(appUrl('save_build.php'), {
        method: 'POST',
        body:   formData
    })
    .then(function(r) { return r.json(); })
    .then(function(data) {
        var msg = document.getElementById('save-msg');
        if (data.success) {
            msg.textContent  = 'Build saved to My Designs!';
            msg.className    = 'save-msg success';
        } else {
            msg.textContent  = 'Could not save. Try again.';
            msg.className    = 'save-msg error';
        }
        setTimeout(function() { msg.textContent = ''; }, 3000);
    });
}

// Initialise
preloadImages();
updateSpecs();