<footer class="site-footer">
    <div class="footer-container">
        
        <!-- Column 1: Brand Info -->
        <div class="footer-col footer-brand">
            <a href="/guitarghar/index.php" class="footer-logo">
                <img src="/guitarghar/img/logo.png" alt="GuitarGhar" class="footer-logo-img">
            </a>
            <p>Your ultimate interactive platform for learning, tuning, and exploring guitars.</p>
            <div class="footer-socials">
                <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                <a href="#" aria-label="X (Twitter)"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>

        <!-- Column 2: Quick Links -->
        <div class="footer-col">
            <h4>Quick Links</h4>
            <ul>
                <li><a href="/guitarghar/index.php">Home</a></li>
                <li><a href="/guitarghar/recommender.php">Recommender</a></li>
                <li><a href="/guitarghar/builder.php">Builder</a></li>
                <li><a href="/guitarghar/tuner.php">Guitar Tuner</a></li>
                <li><a href="/guitarghar/lessons.php">Lessons</a></li>
            </ul>
        </div>

        <!-- Column 3: Account & Support -->
        <div class="footer-col">
            <h4>Account & Support</h4>
            <ul>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="/guitarghar/my-designs.php">My Designs</a></li>
                    <li><a href="/guitarghar/logout.php" onclick="event.preventDefault(); document.getElementById('footer-logout-form').submit();">Logout</a><form id="footer-logout-form" action="/guitarghar/logout.php" method="POST" style="display:none;"></form></li>
                <?php else: ?>
                    <li><a href="/guitarghar/login.php">Login</a></li>
                    <li><a href="/guitarghar/register.php">Register Account</a></li>
                <?php endif; ?>
                <li><a href="/guitarghar/lessons.php">Help & Lessons</a></li>
            </ul>
        </div>

        <!-- Column 4: Contact Details -->
        <div class="footer-col">
            <h4>Contact Us</h4>
            <ul class="footer-contact-info">
                <li><i class="fa-solid fa-location-dot"></i> Kathmandu, Nepal</li>
                <li><i class="fa-solid fa-envelope"></i> ayush.poudel24@apexcollege.edu.np</li>
                <li><i class="fa-solid fa-phone"></i> +977 9866022588</li>
            </ul>
        </div>

    </div>

    <!-- Bottom Copyright Bar -->
    <div class="footer-bottom">
        <p>&copy; <?php echo date('Y'); ?> GuitarGhar. All rights reserved.</p>
    </div>
</footer>

</body>
</html>