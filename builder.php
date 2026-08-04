<?php include 'includes/navbar.php'; ?>
<link rel="stylesheet" href="/guitarghar/css/builder.css">

<?php include 'includes/db.php'; ?>

<section id="builder-page">

    <div class="builder-header">
        <h1>Guitar Builder</h1>
        <p>
            Design your dream guitar. Select a body shape,
            choose your finish color and customise every detail.
            The preview updates in real time.
        </p>
    </div>

    <div class="builder-layout">

        <!-- LEFT: Controls -->
        <div class="builder-controls">

            <div class="control-section">
                <h4>Body Shape</h4>
                <div class="shape-grid">
                    <button class="shape-btn active" onclick="setShape('strat', this)">
                        <img src="/guitarghar/img/strat.png" alt="Stratocaster">
                        <span>Stratocaster</span>
                    </button>
                    <button class="shape-btn" onclick="setShape('lespaul', this)">
                        <img src="/guitarghar/img/lespaul.png" alt="Les Paul">
                        <span>Les Paul</span>
                    </button>
                    <button class="shape-btn" onclick="setShape('sg', this)">
                        <img src="/guitarghar/img/sg.png" alt="SG">
                        <span>SG</span>
                    </button>
                    <button class="shape-btn" onclick="setShape('acoustic', this)">
                        <img src="/guitarghar/img/acoustic.png" alt="Acoustic">
                        <span>Acoustic</span>
                    </button>
                </div>
            </div>

            <div class="control-section">
                <h4>Body Finish Color</h4>
                <!-- Color picker — user picks finish color applied to guitar body -->
                <div class="color-picker-wrap">
                    <input
                        type="color"
                        id="b-color"
                        value="#c8382a"
                        onchange="applyColor()"
                        title="Pick body color"
                    >
                    <span id="color-hex">#c8382a</span>
                </div>
                <!-- Preset colors for quick selection -->
                <div class="color-presets">
                    <button class="preset-color" style="background:#c8382a;" onclick="setColor('#c8382a')" title="Candy Apple Red"></button>
                    <button class="preset-color" style="background:#1a1a1a;" onclick="setColor('#1a1a1a')" title="Jet Black"></button>
                    <button class="preset-color" style="background:#f5f5f5;" onclick="setColor('#f5f5f5')" title="Arctic White"></button>
                    <button class="preset-color" style="background:#1a3a6a;" onclick="setColor('#1a3a6a')" title="Lake Placid Blue"></button>
                    <button class="preset-color" style="background:#2d5a1b;" onclick="setColor('#2d5a1b')" title="British Racing Green"></button>
                    <button class="preset-color" style="background:#8b4513;" onclick="setColor('#8b4513')" title="Sunburst Brown"></button>
                    <button class="preset-color" style="background:#4a0080;" onclick="setColor('#4a0080')" title="Purple"></button>
                    <button class="preset-color" style="background:#d4af37;" onclick="setColor('#d4af37')" title="Gold"></button>
                </div>
            </div>

            <div class="control-section">
                <h4>Color Intensity</h4>
                <div class="slider-wrap">
                    <input
                        type="range"
                        id="b-intensity"
                        min="0"
                        max="100"
                        value="65"
                        oninput="applyColor()"
                    >
                    <span id="intensity-val">65%</span>
                </div>
                <p class="control-hint">
                    Lower = more natural wood shows through.
                    Higher = deeper solid color.
                </p>
            </div>

            <div class="control-section">
                <h4>Body Wood</h4>
                <select id="b-wood" onchange="updateSpecs()">
                    <option value="Alder">Alder — bright, punchy tone</option>
                    <option value="Mahogany">Mahogany — warm, thick tone</option>
                    <option value="Basswood">Basswood — balanced, lightweight</option>
                    <option value="Ash">Ash — bright, resonant twang</option>
                    <option value="Maple">Maple — very bright, high sustain</option>
                    <option value="Spruce">Spruce — crisp acoustic tone</option>
                </select>
            </div>

            <div class="control-section">
                <h4>Neck Wood</h4>
                <select id="b-neckwood" onchange="updateSpecs()">
                    <option value="Maple">Maple — bright, fast feel</option>
                    <option value="Mahogany">Mahogany — warm, smooth</option>
                    <option value="Rosewood">Rosewood — warm, dark</option>
                </select>
            </div>

            <div class="control-section">
                <h4>Fingerboard</h4>
                <select id="b-fb" onchange="updateSpecs()">
                    <option value="Rosewood">Rosewood</option>
                    <option value="Maple">Maple</option>
                    <option value="Ebony">Ebony</option>
                </select>
            </div>

            <div class="control-section">
                <h4>Pickups</h4>
                <select id="b-pickup" onchange="updateSpecs(); drawGuitar();">
                    <option value="SSS">SSS — 3 Single Coils</option>
                    <option value="HSS">HSS — Humbucker + 2 Singles</option>
                    <option value="HH">HH — 2 Humbuckers</option>
                    <option value="P90">P90s</option>
                    <option value="None">None — Acoustic</option>
                </select>
            </div>

            <div class="control-section">
                <h4>Bridge</h4>
                <select id="b-bridge" onchange="updateSpecs()">
                    <option value="Synchronized Tremolo">Synchronized Tremolo</option>
                    <option value="Hardtail">Hardtail</option>
                    <option value="Tune-O-Matic">Tune-O-Matic</option>
                    <option value="Floyd Rose">Floyd Rose</option>
                    <option value="Acoustic Bridge">Acoustic Bridge</option>
                </select>
            </div>

            <div class="control-section">
                <h4>Hardware Color</h4>
                <select id="b-hw" onchange="updateSpecs()">
                    <option value="Chrome">Chrome</option>
                    <option value="Gold">Gold</option>
                    <option value="Black">Black</option>
                    <option value="Nickel">Nickel</option>
                </select>
            </div>

            <!-- Save button — requires login -->
            <div class="control-section">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button class="save-build-btn" onclick="saveBuild()">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save My Build
                    </button>
                    <div class="save-msg" id="save-msg"></div>
                <?php else: ?>
                    <a href="/guitarghar/login.php" class="save-build-btn">
                        <i class="fa-solid fa-lock"></i>
                        Login to Save Build
                    </a>
                <?php endif; ?>
            </div>

        </div>

        <!-- CENTER: Canvas Preview -->
        <div class="builder-canvas-wrap">

            <div class="canvas-label" id="canvas-label">
                Stratocaster
            </div>

            <!-- Main canvas where guitar image is drawn and color applied -->
            <canvas id="guitar-canvas" width="500" height="700"></canvas>

            <div class="build-summary" id="build-summary"></div>

        </div>

        <!-- RIGHT: Specs -->
        <div class="builder-specs">

            <h4>Build Specifications</h4>

            <div class="spec-list" id="spec-list">
                <div class="spec-item">
                    <span class="spec-key">Shape</span>
                    <span class="spec-val" id="spec-shape">Stratocaster</span>
                </div>
                <div class="spec-item">
                    <span class="spec-key">Finish Color</span>
                    <span class="spec-val" id="spec-color">
                        <span class="color-dot" id="spec-dot" style="background:#c8382a;"></span>
                        #c8382a
                    </span>
                </div>
                <div class="spec-item">
                    <span class="spec-key">Body Wood</span>
                    <span class="spec-val" id="spec-wood">Alder</span>
                </div>
                <div class="spec-item">
                    <span class="spec-key">Neck Wood</span>
                    <span class="spec-val" id="spec-neckwood">Maple</span>
                </div>
                <div class="spec-item">
                    <span class="spec-key">Fingerboard</span>
                    <span class="spec-val" id="spec-fb">Rosewood</span>
                </div>
                <div class="spec-item">
                    <span class="spec-key">Pickups</span>
                    <span class="spec-val" id="spec-pickup">SSS</span>
                </div>
                <div class="spec-item">
                    <span class="spec-key">Bridge</span>
                    <span class="spec-val" id="spec-bridge">Synchronized Tremolo</span>
                </div>
                <div class="spec-item">
                    <span class="spec-key">Hardware</span>
                    <span class="spec-val" id="spec-hw">Chrome</span>
                </div>
            </div>

            <div class="tone-box">
                <h6>Tone Profile</h6>
                <p id="tone-desc">
                    Bright, punchy tone with excellent sustain.
                    Versatile across genres. Classic Fender character.
                </p>
            </div>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

<script src="/guitarghar/js/builder.js"></script>