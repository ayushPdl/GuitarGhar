<?php
$page_title = 'Guitar Tuner | GuitarGhar';
$page_css = '/guitarghar/css/tuner.css';
include 'includes/navbar.php';
include 'includes/db.php';
?>

<section id="tuner-page">

    <div class="page-header tuner-header">
        <h1>Guitar Tuner</h1>
        <p>
            Real-time browser-based tuner. Allow microphone
            access and play any string to detect its pitch.
        </p>
    </div>

    <div class="tuner-layout">

        <!-- LEFT: Controls -->
        <div class="tuner-controls">

            <div class="tuner-control-box">
                <h4>Select Tuning</h4>
                <div class="tuning-options">
                    <button class="tuning-btn active" onclick="setTuning('standard', this)">
                        Standard <span>E A D G B e</span>
                    </button>
                    <button class="tuning-btn" onclick="setTuning('dropd', this)">
                        Drop D <span>D A D G B e</span>
                    </button>
                    <button class="tuning-btn" onclick="setTuning('openg', this)">
                        Open G <span>D G D G B D</span>
                    </button>
                    <button class="tuning-btn" onclick="setTuning('dadgad', this)">
                        DADGAD <span>D A D G A D</span>
                    </button>
                    <button class="tuning-btn" onclick="setTuning('opend', this)">
                        Open D <span>D A D F# A D</span>
                    </button>
                    <button class="tuning-btn" onclick="setTuning('opene', this)">
                        Open E <span>E B E G# B E</span>
                    </button>
                </div>
            </div>

            <div class="tuner-control-box">
                <h4>Select String</h4>
                <div class="string-buttons" id="string-buttons">
                    <!-- Filled by JS -->
                </div>
            </div>

            <div class="tuner-control-box tuner-info-box">
                <h4>How to use</h4>
                <ul class="tuner-tips">
                    <li><i class="fa-solid fa-circle-dot"></i> Click Start Tuner and allow microphone</li>
                    <li><i class="fa-solid fa-circle-dot"></i> Select your tuning above</li>
                    <li><i class="fa-solid fa-circle-dot"></i> Select the string you want to tune</li>
                    <li><i class="fa-solid fa-circle-dot"></i> Play the string and watch the meter</li>
                    <li><i class="fa-solid fa-circle-dot"></i> Green means in tune</li>
                </ul>
            </div>

        </div>

        <!-- RIGHT: Tuner Display / Login Prompt Area -->
        <div class="tuner-display" id="tuner-display-panel">

            <?php if (!isset($_SESSION['user_id'])): ?>

                <!-- Inline Login Prompt (Replaces Tuner Panel when Guest) -->
                <div class="tuner-login-card">
                    <div class="lock-icon">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h2>Login Required</h2>
                    <p>You need a GuitarGhar account to use the real-time Guitar Tuner.</p>
                    <div class="btn-group">
                        <a href="/guitarghar/login.php" class="tuner-btn">Login</a>
                        <a href="/guitarghar/register.php" class="tuner-btn-outline">Create Free Account</a>
                    </div>
                </div>

            <?php else: ?>

                <!-- Active Tuner Interface (Loaded for Logged In Users) -->
                <div class="tuner-note-wrap">
                    <div class="tuner-note" id="tuner-note">-</div>
                    <div class="tuner-freq" id="tuner-freq">-- Hz</div>
                </div>

                <div class="tuner-meter-wrap">
                    <span class="meter-label">Flat</span>
                    <div class="tuner-meter">
                        <div class="meter-center"></div>
                        <div class="meter-bar" id="meter-bar"></div>
                    </div>
                    <span class="meter-label">Sharp</span>
                </div>

                <div class="tuner-status" id="tuner-status">
                    Select a string to begin
                </div>

                <div class="tuner-target" id="tuner-target"></div>

                <div class="tuner-buttons">
                    <button class="tuner-btn" id="start-btn" onclick="startTuner()">
                        <i class="fa-solid fa-microphone"></i> Start Tuner
                    </button>
                    <button class="tuner-btn-stop" id="stop-btn" onclick="stopTuner()" style="display: none;">
                        <i class="fa-solid fa-stop"></i> Stop
                    </button>
                </div>

                <p class="tuner-mic-note">
                    <i class="fa-solid fa-circle-info"></i> Allow microphone access when your browser prompts you
                </p>

            <?php endif; ?>

        </div>

    </div>

</section>

<script>
    var IS_LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>
<script src="/guitarghar/js/tuner.js"></script>

<?php include 'includes/footer.php'; ?>