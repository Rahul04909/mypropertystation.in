<?php
/**
 * Standalone Privacy Policy Page
 * Property Station
 */

// 1. Load Central Config
require_once __DIR__ . '/config.php';

// 2. Set Meta Details for SEO
$meta_title = "Privacy Policy | " . env('APP_NAME', 'Property Station') . " - Elite Real Estate";
$meta_desc = "Privacy Policy for Property Station. Learn how we handle your personal information, cookie data, and user inquiry security protocols.";

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
    <div class="outline-text" style="top: 10%; left: 50%; transform: translateX(-50%); font-size: 12vw; opacity: 0.6;">POLICY</div>
    <div class="container" style="position: relative; z-index: 2;">
        <span class="subpage-hero-subtitle">Privacy Protection</span>
        <h1 class="subpage-hero-title">Privacy Policy</h1>
    </div>
</section>

<!-- 5. Legal Layout Container -->
<section class="legal-layout-section" style="background-color: var(--color-bg-dark);">
    <div class="container">
        <div class="legal-layout">
            
            <!-- Sticky Sidebar Directory -->
            <aside class="legal-sidebar">
                <a href="#intro" class="legal-nav-link active">1. Introduction</a>
                <a href="#info" class="legal-nav-link">2. Info We Collect</a>
                <a href="#use" class="legal-nav-link">3. Usage of Data</a>
                <a href="#security" class="legal-nav-link">4. Protection & Security</a>
                <a href="#cookies" class="legal-nav-link">5. Cookies & Tracking</a>
                <a href="#rights" class="legal-nav-link">6. Your Rights</a>
                <a href="#contact" class="legal-nav-link">7. Contact Policy</a>
            </aside>

            <!-- Main Legal Content Pane -->
            <div class="legal-content-pane">
                
                <!-- Section 1 -->
                <section class="legal-section" id="intro">
                    <h2 class="legal-section-title">
                        <i data-lucide="info"></i> 1. Introduction
                    </h2>
                    <p class="legal-text">
                        Welcome to <strong>Property Station</strong>. We respect your privacy and are committed to protecting your personal data. This privacy policy informs you about how we look after your personal data when you visit our website (regardless of where you visit it from) and tells you about your privacy rights and how the law protects you.
                    </p>
                    <p class="legal-text">
                        Please read this policy carefully to understand our policies and practices regarding your information and how we will treat it. If you do not agree with our policies and practices, your choice is not to use our website.
                    </p>
                </section>

                <!-- Section 2 -->
                <section class="legal-section" id="info">
                    <h2 class="legal-section-title">
                        <i data-lucide="database"></i> 2. Information We Collect
                    </h2>
                    <p class="legal-text">
                        We collect several types of information from and about users of our website, including:
                    </p>
                    <ul class="legal-list">
                        <li><strong>Personal Identification Data:</strong> Name, email address, telephone/mobile number, and property preferences submitted through our inquiry forms.</li>
                        <li><strong>Technical/Usage Data:</strong> Internet protocol (IP) address, your login data, browser type and version, time zone setting and location, browser plug-in types and versions, operating system and platform, and other technology on the devices you use to access this website.</li>
                        <li><strong>Interaction Details:</strong> Records of correspondence, communications, and budget feedback you provide when submitting property listings interest.</li>
                    </ul>
                </section>

                <!-- Section 3 -->
                <section class="legal-section" id="use">
                    <h2 class="legal-section-title">
                        <i data-lucide="shuffle"></i> 3. Usage of Data
                    </h2>
                    <p class="legal-text">
                        We use the information we collect about you or that you provide to us, including any personal information:
                    </p>
                    <ul class="legal-list">
                        <li>To present our website and its contents to you in an optimized, responsive format.</li>
                        <li>To process and address your real estate investment inquiry form submissions.</li>
                        <li>To contact you directly through our certified advisors about properties you have expressed interest in.</li>
                        <li>To carry out our obligations and enforce our rights arising from any contracts entered into between you and us.</li>
                        <li>To improve our customer service, website analytics, and property management standards.</li>
                    </ul>
                </section>

                <!-- Section 4 -->
                <section class="legal-section" id="security">
                    <h2 class="legal-section-title">
                        <i data-lucide="shield-check"></i> 4. Protection & Security
                    </h2>
                    <p class="legal-text">
                        We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed. 
                    </p>
                    <p class="legal-text">
                        All information you provide to us is stored on secure, access-controlled local and cloud servers. Database transactions (including form submittals) are structured securely with PDO prepared statements to block SQL injection vulnerability. 
                    </p>
                    <p class="legal-text">
                        However, the transmission of information via the internet is not completely secure. Although we do our best to protect your personal data, we cannot guarantee the security of your data transmitted to our site; any transmission is at your own risk.
                    </p>
                </section>

                <!-- Section 5 -->
                <section class="legal-section" id="cookies">
                    <h2 class="legal-section-title">
                        <i data-lucide="cookie"></i> 5. Cookies & Tracking
                    </h2>
                    <p class="legal-text">
                        Our website uses cookies and similar tracking technologies to distinguish you from other users of our website. This helps us to provide you with a high-end experience when you browse our website and also allows us to improve our site performance.
                    </p>
                    <p class="legal-text">
                        You can set your browser to refuse all or some browser cookies, or to alert you when websites set or access cookies. If you disable or refuse cookies, please note that some parts of this website may become inaccessible or not function properly.
                    </p>
                </section>

                <!-- Section 6 -->
                <section class="legal-section" id="rights">
                    <h2 class="legal-section-title">
                        <i data-lucide="user-check"></i> 6. Your Rights
                    </h2>
                    <p class="legal-text">
                        Under certain circumstances, you have rights under data protection laws in relation to your personal data. These rights include:
                    </p>
                    <ul class="legal-list">
                        <li><strong>Request Access:</strong> Request a copy of the personal data we hold about you to verify its legality.</li>
                        <li><strong>Request Correction:</strong> Ask us to rectify incomplete or inaccurate records we hold.</li>
                        <li><strong>Request Deletion:</strong> Ask us to remove or delete personal data where there is no good reason for us continuing to process it.</li>
                        <li><strong>Withdraw Consent:</strong> Withdraw your consent at any time where we are relying on consent to process your personal data.</li>
                    </ul>
                </section>

                <!-- Section 7 -->
                <section class="legal-section" id="contact">
                    <h2 class="legal-section-title">
                        <i data-lucide="mail"></i> 7. Contact Policy
                    </h2>
                    <p class="legal-text">
                        If you have any questions about this privacy policy or our privacy practices, please contact our data coordinator directly:
                    </p>
                    <p class="legal-text" style="color: var(--color-text-light); font-weight: 600;">
                        Property Station Sales & Support<br>
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
