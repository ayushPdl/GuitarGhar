<?php include 'includes/navbar.php'; ?>
<link rel="stylesheet" href="/guitarghar/css/index.css">

<!-- HERO SECTION -->
<section id="hero">

    <div class="hero-content">

        <div class="hero-badge">
            <i class="fa-solid fa-location-dot"></i>
            Kathmandu, Nepal
        </div>

        <h1>
            Your Complete
            <span class="hero-title-red">Guitar Companion</span>
        </h1>

        <p class="hero-desc">
            Tune, learn, build and get AI-powered guitar
            recommendations. Four powerful tools built for
            every guitarist in Nepal.
        </p>

        <div class="hero-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="/guitarghar/recommender.php" class="btn-red btn-lg">
                    Get Recommendation
                </a>
                <a href="/guitarghar/lessons.php" class="btn-outline btn-lg">
                    Start Learning
                </a>
            <?php else: ?>
                <a href="/guitarghar/register.php" class="btn-red btn-lg">
                    Get Started Free
                </a>
                <a href="/guitarghar/login.php" class="btn-outline btn-lg">
                    Login
                </a>
            <?php endif; ?>
        </div>

        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-number">4</div>
                <div class="hero-stat-label">Unique Tools</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-number">17</div>
                <div class="hero-stat-label">Guitar Lessons</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-number">AI</div>
                <div class="hero-stat-label">Powered</div>
            </div>
            <div class="hero-stat-divider"></div>
            <div class="hero-stat">
                <div class="hero-stat-number">Free</div>
                <div class="hero-stat-label">To Use</div>
            </div>
        </div>

    </div>

</section>

<!-- FEATURES SECTION -->
<section id="features" class="section-p1">

    <div class="section-eyebrow">What GuitarGhar Offers</div>
    <h2 class="section-title">4 Unique Features</h2>
    <p class="section-subtitle">
        No other guitar platform in Nepal has all of these in one place.
    </p>

    <div class="features-grid">

        <a href="/guitarghar/recommender.php" class="feature-card">
            <div class="feature-icon">
                <i class="fa-solid fa-robot"></i>
            </div>
            <h4>AI Guitar Recommender</h4>
            <p>
                Tell us your skill level, favourite genre and budget.
                Our AI gives you a personalised guitar recommendation
                matched exactly to your budget range.
            </p>
            <div class="feature-tags">
                <span class="tag">AI Powered</span>
                <span class="tag">NPR Budget</span>
                <span class="tag">Personalised</span>
            </div>
            <div class="feature-link">
                Try it <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <a href="/guitarghar/builder.php" class="feature-card">
            <div class="feature-icon">
                <i class="fa-solid fa-palette"></i>
            </div>
            <h4>Guitar Builder</h4>
            <p>
                Design your dream guitar with real luthier options.
                See your guitar update live with a real photo overlay.
                Save your custom builds to your account.
            </p>
            <div class="feature-tags">
                <span class="tag">Live Preview</span>
                <span class="tag">Real Photos</span>
                <span class="tag">Save Builds</span>
            </div>
            <div class="feature-link">
                Start building <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <a href="/guitarghar/tuner.php" class="feature-card">
            <div class="feature-icon">
                <i class="fa-solid fa-music"></i>
            </div>
            <h4>Real-Time Guitar Tuner</h4>
            <p>
                Browser-based microphone tuner using the Web Audio
                API. Colour-coded feedback. Supports standard and
                alternate tunings. No app needed.
            </p>
            <div class="feature-tags">
                <span class="tag">No Install</span>
                <span class="tag">Alternate Tunings</span>
                <span class="tag">Real-Time</span>
            </div>
            <div class="feature-link">
                Open tuner <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

        <a href="/guitarghar/lessons.php" class="feature-card">
            <div class="feature-icon">
                <i class="fa-solid fa-book-open"></i>
            </div>
            <h4>Guitar Learning Resources</h4>
            <p>
                Structured lessons from beginner to advanced.
                Each lesson includes a practice video.
                Track your progress linked to your account.
            </p>
            <div class="feature-tags">
                <span class="tag">Beginner to Advanced</span>
                <span class="tag">Video Lessons</span>
                <span class="tag">Progress Tracking</span>
            </div>
            <div class="feature-link">
                Start learning <i class="fa-solid fa-arrow-right"></i>
            </div>
        </a>

    </div>

</section>

