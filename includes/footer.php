<?php
/**
 * Footer Component for Property Station
 */
?>
    <!-- Footer Section -->
    <footer class="main-footer">
        <div class="container footer-container">
            <!-- Brand & Socials -->
            <div class="footer-col">
                <a href="#hero" class="logo-area footer-logo">
                    <span class="logo-text">
                        <span class="logo-title"><?php 
                            $appName = env('APP_NAME', 'Property Station');
                            $words = explode(' ', $appName);
                            echo htmlspecialchars(strtoupper($words[0] . (isset($words[1]) ? ' ' . $words[1] : '')));
                        ?></span>
                        <span class="logo-subtitle"><?php 
                            echo htmlspecialchars(strtoupper(isset($words[2]) ? $words[2] : ''));
                        ?></span>
                    </span>
                </a>
                <p class="footer-desc">
                    All-inclusive real estate services to facilitate the easy and confident purchase, sale, and management of your properties.
                </p>
                <div class="footer-socials">
                    <a href="#" class="social-icon" aria-label="Facebook"><i data-lucide="facebook"></i></a>
                    <a href="#" class="social-icon" aria-label="Instagram"><i data-lucide="instagram"></i></a>
                    <a href="#" class="social-icon" aria-label="Twitter"><i data-lucide="twitter"></i></a>
                    <a href="#" class="social-icon" aria-label="Linkedin"><i data-lucide="linkedin"></i></a>
                </div>
            </div>

            <!-- Sitemap Quick Links -->
            <div class="footer-col">
                <h3 class="footer-title">Navigation</h3>
                <ul class="footer-links-list">
                    <li><a href="#hero">Home</a></li>
                    <li><a href="#about">About Us</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact/Legal Info Column -->
            <div class="footer-col">
                <h3 class="footer-title">Legal & Sales</h3>
                <ul class="footer-links-list">
                    <li><a href="#contact"><i data-lucide="map-pin"></i> Sector 15 Green Corridor</a></li>
                    <li><a href="tel:<?php echo htmlspecialchars(env('CONTACT_PHONE_RAW', '+919310104249')); ?>"><i data-lucide="phone"></i> Call: +91 93101 04249</a></li>
                    <li><a href="mailto:<?php echo htmlspecialchars(env('CONTACT_EMAIL', 'sales@mypropertystation.in')); ?>"><i data-lucide="mail"></i> Email Us</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="footer-col">
                <h3 class="footer-title">Newsletter</h3>
                <p class="newsletter-desc">Subscribe to our newsletter for the latest property updates.</p>
                <form class="newsletter-form" onsubmit="alert('Subscription verified (simulation).'); this.reset(); return false;">
                    <div class="newsletter-input-group">
                        <input type="email" placeholder="Your email address" class="newsletter-input" required aria-label="Subscribe Email">
                        <button type="submit" class="newsletter-btn" aria-label="Submit Email Subscription">
                            <i data-lucide="send"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Copyright and Disclaimers -->
        <div class="footer-bottom">
            <div class="container footer-bottom-container">
                <p>&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars(env('APP_NAME', 'Property Station')); ?>. All rights reserved.</p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="back-to-top-btn" aria-label="Back to top">
        <i data-lucide="chevron-up"></i>
    </button>

    <!-- Main Client-Side Interactions Script -->
    <script src="assets/js/main.js"></script>
    <script>
        // Initialize Lucide Icons after DOM loads
        lucide.createIcons();
    </script>
</body>
</html>
