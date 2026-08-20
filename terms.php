<?php
/**
 * Standalone Terms & Conditions Page
 * Property Station
 */

// 1. Load Central Config
require_once __DIR__ . '/config.php';

// 2. Set Meta Details for SEO
$meta_title = "Terms & Conditions | " . env('APP_NAME', 'Property Station') . " - Legal Details";
$meta_desc = "Terms and Conditions of use for Property Station. Review our user agreement, property disclaimer policies, and Indian governing law details.";

// 3. Load Header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom CSS for Legal Subpages Layout -->
<style>
.subpage-hero {
    position: relative;
    padding: 11rem 0 7rem 0;
    background-color: var(--color-bg-light);
    border-bottom: 1px solid var(--color-border-dark);
    text-align: center;
    overflow: hidden;
}

.subpage-hero-title {
    font-size: 3.8rem;
    font-weight: 800;
    margin-bottom: 1rem;
    letter-spacing: -1px;
    color: var(--color-text-light);
}

.subpage-hero-subtitle {
    font-size: 1.1rem;
    color: var(--color-accent);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 3px;
    font-family: var(--font-heading);
}

.legal-layout {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 5rem;
    padding: var(--section-padding-large);
    background-color: var(--color-bg-dark);
}

.legal-sidebar {
    position: sticky;
    top: 7.5rem;
    align-self: start;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.legal-nav-link {
    font-family: var(--font-heading);
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--color-text-muted);
    padding: 0.75rem 1.25rem;
    border-left: 2px solid var(--color-border-dark);
    transition: var(--transition-fast);
}

.legal-nav-link:hover, .legal-nav-link.active {
    color: var(--color-accent);
    border-color: var(--color-accent);
    background-color: var(--color-bg-light);
}

.legal-content-pane {
    max-width: 820px;
}

.legal-section {
    margin-bottom: 4rem;
    scroll-margin-top: 9rem; /* Prevent overlap with fixed header on click navigation scroll */
}

.legal-section-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    color: var(--color-text-light);
    border-bottom: 1px solid var(--color-border-dark);
    padding-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.legal-section-title i,
.legal-section-title svg {
    width: 22px;
    height: 22px;
    color: var(--color-accent);
}

.legal-text {
    font-size: 1rem;
    line-height: 1.8;
    color: var(--color-text-muted);
    margin-bottom: 1.25rem;
}

.legal-list {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
    list-style-type: square;
    color: var(--color-text-muted);
}

.legal-list li {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

@media (max-width: 1024px) {
    .subpage-hero {
        padding: 9rem 0 5rem 0;
    }
    .subpage-hero-title {
        font-size: 3rem;
    }
    .legal-layout {
        grid-template-columns: 1fr;
        gap: 3rem;
    }
    .legal-sidebar {
        display: none; /* Hide sidebar menu on smaller devices */
    }
}

@media (max-width: 768px) {
    .subpage-hero-title {
        font-size: 2.4rem;
    }
    .legal-section-title {
        font-size: 1.5rem;
    }
}
</style>

<!-- 4. Subpage Hero Header -->
<section class="subpage-hero">
    <div class="outline-text" style="top: 10%; left: 50%; transform: translateX(-50%); font-size: 12vw; opacity: 0.6;">TERMS</div>
    <div class="container" style="position: relative; z-index: 2;">
        <span class="subpage-hero-subtitle">User Agreement</span>
        <h1 class="subpage-hero-title">Terms & Conditions</h1>
    </div>
</section>

<!-- 5. Legal Layout Container -->
<section class="legal-layout-section" style="background-color: var(--color-bg-dark);">
    <div class="container">
        <div class="legal-layout">
            
            <!-- Sticky Sidebar Directory -->
            <aside class="legal-sidebar">
                <a href="#agreement" class="legal-nav-link active">1. Agreement to Terms</a>
                <a href="#ip" class="legal-nav-link">2. Intellectual Property</a>
                <a href="#eligibility" class="legal-nav-link">3. User Eligibility</a>
                <a href="#prohibited" class="legal-nav-link">4. Prohibited Acts</a>
                <a href="#listings" class="legal-nav-link">5. Listings Disclaimer</a>
                <a href="#liability" class="legal-nav-link">6. Liability Limits</a>
                <a href="#governing" class="legal-nav-link">7. Governing Law</a>
                <a href="#contact" class="legal-nav-link">8. Contact Legal</a>
            </aside>

            <!-- Main Legal Content Pane -->
            <div class="legal-content-pane">
                
                <!-- Section 1 -->
                <section class="legal-section" id="agreement">
                    <h2 class="legal-section-title">
                        <i data-lucide="book-open"></i> 1. Agreement to Terms
                    </h2>
                    <p class="legal-text">
                        These Terms and Conditions constitute a legally binding agreement made between you, whether personally or on behalf of an entity, and <strong>Property Station</strong>, concerning your access to and use of this website. 
                    </p>
                    <p class="legal-text">
                        By accessing this website, you agree that you have read, understood, and agreed to be bound by all of these Terms and Conditions. If you do not agree with all of these terms, then you are expressly prohibited from using this website and must discontinue use immediately.
                    </p>
                </section>

                <!-- Section 2 -->
                <section class="legal-section" id="ip">
                    <h2 class="legal-section-title">
                        <i data-lucide="copyright"></i> 2. Intellectual Property
                    </h2>
                    <p class="legal-text">
                        Unless otherwise indicated, the website is our proprietary property and all source code, databases, functionality, software, website designs, audio, video, text, photographs, and graphics on the website (collectively, the "Content") and the trademarks, service marks, and logos contained therein (the "Marks") are owned or controlled by us or licensed to us, and are protected by copyright and trademark laws.
                    </p>
                    <p class="legal-text">
                        No part of the website and no Content or Marks may be copied, reproduced, aggregated, republished, uploaded, posted, publicly displayed, encoded, translated, transmitted, distributed, sold, licensed, or otherwise exploited for any commercial purpose whatsoever, without our express prior written permission.
                    </p>
                </section>

                <!-- Section 3 -->
                <section class="legal-section" id="eligibility">
                    <h2 class="legal-section-title">
                        <i data-lucide="user-check"></i> 3. User Eligibility
                    </h2>
                    <p class="legal-text">
                        By using the website, you represent and warrant that:
                    </p>
                    <ul class="legal-list">
                        <li>All registration or inquiry information you submit will be true, accurate, current, and complete.</li>
                        <li>You will maintain the accuracy of such information and promptly update such information as necessary.</li>
                        <li>You have the legal capacity and you agree to comply with these Terms and Conditions.</li>
                        <li>You are not a minor in the jurisdiction in which you reside.</li>
                        <li>Your use of the website will not violate any applicable law or regulation, including the Real Estate (Regulation and Development) Act, 2016 (RERA) and regional property transactions laws.</li>
                    </ul>
                </section>

                <!-- Section 4 -->
                <section class="legal-section" id="prohibited">
                    <h2 class="legal-section-title">
                        <i data-lucide="alert-triangle"></i> 4. Prohibited Activities
                    </h2>
                    <p class="legal-text">
                        You may not access or use the website for any purpose other than that for which we make the website available. The website may not be used in connection with any commercial endeavors except those that are specifically endorsed or approved by us. As a user of the website, you agree not to:
                    </p>
                    <ul class="legal-list">
                        <li>Systematically retrieve data or other content from the website to create or compile, directly or indirectly, a collection, compilation, database, or directory without written permission from us.</li>
                        <li>Circumvent, disable, or otherwise interfere with security-related features of the website, including PDO-based form inputs or administration controls.</li>
                        <li>Trick, defraud, or mislead us and other users, especially in any attempt to learn sensitive account information or send false property listing inquiries.</li>
                        <li>Harass, abuse, or harm another person, including our corporate consultants or property advisors.</li>
                    </ul>
                </section>

                <!-- Section 5 -->
                <section class="legal-section" id="listings">
                    <h2 class="legal-section-title">
                        <i data-lucide="home"></i> 5. Listings Disclaimer
                    </h2>
                    <p class="legal-text">
                        The property listings, specifications, floor plans, configurations, pricing, and visual mockups displayed on this website are for general informational purposes only. While we endeavor to keep database records accurate and updated, details are subject to change.
                    </p>
                    <p class="legal-text">
                        Property Station does not guarantee the complete accuracy, availability, or valuation estimates of any villa, apartment, or land listing. All transactions are subject to physical verification, due diligence, and execution of formal sale agreements. RERA registrations should be independently cross-verified prior to completing financial investments.
                    </p>
                </section>

                <!-- Section 6 -->
                <section class="legal-section" id="liability">
                    <h2 class="legal-section-title">
                        <i data-lucide="shield-alert"></i> 6. Liability Limits
                    </h2>
                    <p class="legal-text">
                        In no event will we or our directors, employees, or agents be liable to you or any third party for any direct, indirect, consequential, exemplary, incidental, special, or punitive damages, including lost profit, lost revenue, loss of data, or other damages arising from your use of the website, even if we have been advised of the possibility of such damages.
                    </p>
                    <p class="legal-text">
                        We make no warranties or representations about the accuracy or completeness of the website's content or the content of any websites linked to this website. We assume no liability or responsibility for any errors, mistakes, or inaccuracies of content and materials.
                    </p>
                </section>

                <!-- Section 7 -->
                <section class="legal-section" id="governing">
                    <h2 class="legal-section-title">
                        <i data-lucide="scale"></i> 7. Governing Law
                    </h2>
                    <p class="legal-text">
                        These Terms and Conditions and your use of the website are governed by and construed in accordance with the laws of India. Any legal action or disputes arising under these terms shall be subject to the exclusive jurisdiction of the competent courts located in the region of our corporate headquarters (Phase 1, Green Corridor area).
                    </p>
                </section>

                <!-- Section 8 -->
                <section class="legal-section" id="contact">
                    <h2 class="legal-section-title">
                        <i data-lucide="mail"></i> 8. Contact Legal
                    </h2>
                    <p class="legal-text">
                        In order to resolve a complaint regarding the website or to receive further information regarding use of the website, please contact our legal counsel:
                    </p>
                    <p class="legal-text" style="color: var(--color-text-light); font-weight: 600;">
                        Property Station Legal & Operations<br>
                        Email: <a href="mailto:<?php echo htmlspecialchars(env('CONTACT_EMAIL', 'info@mypropertystation.in')); ?>" style="color: var(--color-accent);"><?php echo htmlspecialchars(env('CONTACT_EMAIL', 'info@mypropertystation.in')); ?></a><br>
                        Phone: <a href="tel:<?php echo htmlspecialchars(env('CONTACT_PHONE_RAW', '+918000810016')); ?>" style="color: var(--color-accent);"><?php echo htmlspecialchars(env('CONTACT_PHONE', '+91 80008 10016')); ?></a>
                    </p>
                </section>

            </div>

        </div>
    </div>
</section>

<!-- Scroll Spy script to highlight active sidebar index link -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    document.querySelectorAll('.legal-nav-link').forEach(link => {
                        link.classList.remove('active');
                    });
                    const activeLink = document.querySelector(`.legal-nav-link[href="#${entry.target.id}"]`);
                    if (activeLink) {
                        activeLink.classList.add('active');
                    }
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -70% 0px' });

        document.querySelectorAll('.legal-section').forEach(section => {
            observer.observe(section);
        });
    }
});
</script>

<?php
// 6. Load Footer
require_once __DIR__ . '/includes/footer.php';
?>
