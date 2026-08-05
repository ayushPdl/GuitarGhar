<footer class="site-footer">
    <div class="footer-container">

        <div class="footer-col footer-brand">
            <a href="<?php echo htmlspecialchars(url('index.php')); ?>" class="footer-logo">
                <img src="<?php echo htmlspecialchars(url('img/logo.png')); ?>" alt="GuitarGhar" class="footer-logo-img">
            </a>
            <p>Your ultimate interactive platform for learning, tuning, and exploring guitars.</p>
            <div class="footer-socials">
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                <a href="#" aria-label="X (Twitter)"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>

        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="<?php echo htmlspecialchars(url('index.php')); ?>">Home</a></li>
                <li><a href="<?php echo htmlspecialchars(url('recommender.php')); ?>">Recommender</a></li>
                <li><a href="<?php echo htmlspecialchars(url('builder.php')); ?>">Builder</a></li>
                <li><a href="<?php echo htmlspecialchars(url('tuner.php')); ?>">Guitar Tuner</a></li>
                <li><a href="<?php echo htmlspecialchars(url('lessons.php')); ?>">Lessons</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Account & Support</h4>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="<?php echo htmlspecialchars(url('my-designs.php')); ?>">My Designs</a></li>
                    <li>
                        <a href="<?php echo htmlspecialchars(url('logout.php')); ?>" onclick="event.preventDefault(); document.getElementById('footer-logout-form').submit();">Logout</a>
                        <form id="footer-logout-form" action="<?php echo htmlspecialchars(url('logout.php')); ?>" method="POST" style="display:none;"></form>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo htmlspecialchars(url('login.php')); ?>">Login</a></li>
                    <li><a href="<?php echo htmlspecialchars(url('register.php')); ?>">Register Account</a></li>
                <?php endif; ?>
                <li><a href="<?php echo htmlspecialchars(url('lessons.php')); ?>">Help & Lessons</a></li>
            </ul>
        </div>

        <div class="footer-col">
            <h4>Contact Us</h4>
            <ul class="footer-contact-info">
                <li><i class="fa-solid fa-location-dot"></i> Kathmandu, Nepal</li>
                <li><i class="fa-solid fa-envelope"></i> support@guitarghar.com</li>
                <li><i class="fa-solid fa-phone"></i> +977 9800000000</li>
            </ul>
        </div>

    </div>

    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> GuitarGhar. All rights reserved.</p>
    </div>
</footer>

</body>
</html>