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
