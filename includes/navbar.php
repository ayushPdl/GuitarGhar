<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GuitarGhar</title>
    <link rel="stylesheet" href="/guitarghar/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<div id="header">

    <a href="/guitarghar/index.php" class="logo">
        <img src="/guitarghar/img/logo.png" alt="GuitarGhar" class="navbar-logo-img">
    </a>

    <ul id="navbar">
        <li>
            <a href="/guitarghar/index.php"
               class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                Home
            </a>
        </li>
        <li>
            <a href="/guitarghar/recommender.php"
               class="<?php echo ($current_page == 'recommender.php') ? 'active' : ''; ?>">
                Recommender
            </a>
        </li>
        <li>
            <a href="/guitarghar/builder.php"
               class="<?php echo ($current_page == 'builder.php') ? 'active' : ''; ?>">
                Builder
            </a>
        </li>
        <li>
            <a href="/guitarghar/tuner.php"
               class="<?php echo ($current_page == 'tuner.php') ? 'active' : ''; ?>">
                Tuner
            </a>
        </li>
        <li>
            <a href="/guitarghar/lessons.php"
               class="<?php echo ($current_page == 'lessons.php') ? 'active' : ''; ?>">
                Lessons
            </a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-auth-mobile">
                <a href="/guitarghar/my-designs.php">My Designs</a>
            </li>
            <li class="nav-auth-mobile">
                <form action="/guitarghar/logout.php" method="POST" style="margin: 0;">
                    <button type="submit" class="nav-logout-mobile">Logout</button>
                </form>
            </li>
        <?php else: ?>
            <li class="nav-auth-mobile">
                <a href="/guitarghar/login.php">Login</a>
            </li>
            <li class="nav-auth-mobile">
                <a href="/guitarghar/register.php" class="nav-register-mobile">Register</a>
            </li>
        <?php endif; ?>

        <li class="nav-close-wrap">
            <i id="close" class="fa-solid fa-xmark" onclick="closeMobileMenu()" aria-label="Close menu"></i>
        </li>
    </ul>

    <div class="navbar-auth" id="navbar-auth">

        <?php if (isset($_SESSION['user_id'])): ?>

            <div class="user-menu" id="user-menu">
                <button class="user-menu-btn" onclick="toggleUserMenu()">
                    <div class="user-avatar">
                        <?php echo strtoupper(substr($_SESSION['full_name'], 0, 1)); ?>
                    </div>
                    Hi, <?php echo htmlspecialchars(explode(' ', $_SESSION['full_name'])[0]); ?>
                    <i class="fa-solid fa-chevron-down" style="font-size: 11px;"></i>
                </button>
                <div class="user-dropdown" id="user-dropdown">
                    <a href="/guitarghar/my-designs.php">
                        <i class="fa-solid fa-guitar" style="margin-right: 8px;"></i>
                        My Designs
                    </a>
                    <div class="dropdown-sep"></div>
                    <form action="/guitarghar/logout.php" method="POST" style="margin: 0;">
                        <button type="submit" class="logout-btn">
                            <i class="fa-solid fa-right-from-bracket" style="margin-right: 8px;"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>

        <?php else: ?>

            <a href="/guitarghar/login.php" class="btn-ghost btn-sm">Login</a>
            <a href="/guitarghar/register.php" class="btn-red btn-sm">Register</a>

        <?php endif; ?>

    </div>

    <div id="mobile">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/guitarghar/my-designs.php" title="My Designs">
                <i class="fa-solid fa-guitar" style="font-size: 20px; color: #e8352a; padding-left: 20px;"></i>
            </a>
        <?php endif; ?>
        <i id="bar" class="fa-solid fa-bars" onclick="openMobileMenu()" aria-label="Open menu"></i>
    </div>

</div>

<div id="nav-overlay" onclick="closeMobileMenu()"></div>

<script>
function openMobileMenu() {
    document.getElementById('navbar').classList.add('active');
    document.getElementById('nav-overlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeMobileMenu() {
    document.getElementById('navbar').classList.remove('active');
    document.getElementById('nav-overlay').classList.remove('active');
    document.body.style.overflow = '';
}

function toggleUserMenu() {
    document.getElementById('user-dropdown').classList.toggle('open');
}

document.addEventListener('click', function(e) {
    var menu = document.getElementById('user-menu');
    if (menu && !menu.contains(e.target)) {
        var dropdown = document.getElementById('user-dropdown');
        if (dropdown) {
            dropdown.classList.remove('open');
        }
    }
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeMobileMenu();
    }
});
</script>
