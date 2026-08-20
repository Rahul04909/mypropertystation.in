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
                <a href="index.php" class="logo-area footer-logo">
                    <img src="assets/logo/logo.jpeg" alt="Property Station Logo" class="logo-img footer-logo-img">
                </a>
                <p class="footer-desc">
                    All-inclusive real estate services to facilitate the easy and confident purchase, sale, and management of your properties.
                </p>
                <div class="footer-socials">
                    <!-- Inline Facebook SVG -->
                    <a href="#" class="social-icon" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <!-- Inline Instagram SVG -->
                    <a href="#" class="social-icon" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
                    </a>
                    <!-- Inline Twitter SVG -->
                    <a href="#" class="social-icon" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                    </a>
                    <!-- Inline Linkedin SVG -->
                    <a href="#" class="social-icon" aria-label="Linkedin">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                </div>
            </div>

            <!-- Sitemap Quick Links -->
            <div class="footer-col">
                <h3 class="footer-title">Navigation</h3>
                <ul class="footer-links-list">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="index.php#projects">Projects</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>

            <!-- Contact/Legal Info Column -->
            <div class="footer-col">
                <h3 class="footer-title">Legal & Sales</h3>
                <ul class="footer-links-list">
                    <li>
                        <a href="contact.php" style="display: flex; align-items: center; gap: 0.5rem;">
                            <!-- Inline Map Pin SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                            Sector 15 Green Corridor
                        </a>
                    </li>
                    <li>
                        <a href="tel:<?php echo htmlspecialchars(env('CONTACT_PHONE_RAW', '+918000810016')); ?>" style="display: flex; align-items: center; gap: 0.5rem;">
                            <!-- Inline Phone SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            Call: +91 80008 10016
                        </a>
                    </li>
                    <li>
                        <a href="mailto:<?php echo htmlspecialchars(env('CONTACT_EMAIL', 'info@mypropertystation.in')); ?>" style="display: flex; align-items: center; gap: 0.5rem;">
                            <!-- Inline Mail SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                            Email Us
                        </a>
                    </li>
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
                            <!-- Inline Send SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" x2="11" y1="2" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
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
                    <a href="privacy-policy.php">Privacy Policy</a>
                    <a href="terms.php">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="back-to-top-btn" aria-label="Back to top">
        <!-- Inline Chevron Up SVG -->
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
    </button>

    <!-- Main Client-Side Interactions Script -->
    <script src="assets/js/main.js"></script>
    <script>
        // Initialize Lucide Icons after DOM loads
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
</body>
</html>
