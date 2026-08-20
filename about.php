<?php
/**
 * Standalone About Us Page
 * Property Station
 */

// 1. Load Central Config
require_once __DIR__ . '/config.php';

// 2. Set Meta Details for SEO
$meta_title = "About Us | " . env('APP_NAME', 'Property Station') . " - Luxury Real Estate";
$meta_desc = "Learn about Property Station's 15+ years legacy of delivering elite property solutions, valuation, management, and smart investment advisory services.";

// 3. Load Header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom CSS for Subpage Layouts -->
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

.about-detailed-section {
    padding: var(--section-padding-large);
    position: relative;
    background-color: var(--color-bg-dark);
}

.story-grid {
    align-items: center;
    gap: 5rem;
}

.story-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.story-lead {
    font-family: var(--font-accent);
    font-size: 1.6rem;
    line-height: 1.5;
    color: var(--color-accent);
    margin-bottom: 2rem;
    font-style: italic;
}

.story-text {
    color: var(--color-text-muted);
    font-size: 1.05rem;
    line-height: 1.8;
    margin-bottom: 1.5rem;
}

.story-visual {
    position: relative;
}

.story-img-wrapper {
    overflow: hidden;
    border-radius: var(--border-radius-custom-img);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid var(--color-border-dark);
}

.story-img {
    width: 100%;
    height: 480px;
    object-fit: cover;
    transition: var(--transition-slow);
}

.story-img-wrapper:hover .story-img {
    transform: scale(1.03);
}

/* Founder Vision / Quote Block styling */
.vision-section {
    padding: var(--section-padding-large);
    background-color: var(--color-bg-light);
    border-top: 1px solid var(--color-border-dark);
    border-bottom: 1px solid var(--color-border-dark);
}

.vision-grid {
    align-items: center;
    gap: 4rem;
}

.vision-card {
    background-color: var(--color-bg-dark-card);
    border: 1px solid var(--color-border-dark);
    padding: 3rem;
    border-radius: var(--border-radius-custom-lg);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
}

.vision-quote {
    font-family: var(--font-accent);
    font-size: 1.8rem;
    line-height: 1.5;
    color: var(--color-text-light);
    font-style: italic;
    margin-bottom: 2.5rem;
    position: relative;
}

.vision-quote::before {
    content: '“';
    font-size: 6rem;
    position: absolute;
    top: -3.5rem;
    left: -1rem;
    opacity: 0.08;
    font-family: serif;
}

.vision-author {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.author-image {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid var(--color-accent);
}

.author-details h4 {
    font-size: 1.15rem;
    font-weight: 700;
}

.author-details p {
    font-size: 0.85rem;
    color: var(--color-text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

.services-intro-bar {
    text-align: center;
    max-width: 700px;
    margin: 0 auto 4rem auto;
}

@media (max-width: 1024px) {
    .subpage-hero {
        padding: 9rem 0 5rem 0;
    }
    .subpage-hero-title {
        font-size: 3rem;
    }
    .story-grid, .vision-grid {
        grid-template-columns: 1fr;
        gap: 3.5rem;
    }
    .story-img {
        height: 380px;
    }
}

@media (max-width: 768px) {
    .subpage-hero-title {
        font-size: 2.4rem;
    }
    .vision-quote {
        font-size: 1.4rem;
    }
}
</style>

<!-- 4. Subpage Hero Header -->
<section class="subpage-hero">
    <div class="outline-text" style="top: 10%; left: 50%; transform: translateX(-50%); font-size: 12vw; opacity: 0.6;">LEGACY</div>
    <div class="container" style="position: relative; z-index: 2;">
        <span class="subpage-hero-subtitle">Who We Are</span>
        <h1 class="subpage-hero-title">About Us</h1>
    </div>
</section>

<!-- 5. Detailed Company Legacy Section -->
<section class="about-detailed-section">
    <div class="container">
        <div class="story-grid grid-2">
            
            <!-- Left: Text content -->
            <div class="story-content">
                <span class="section-tagline">Our Journey</span>
                <h2 class="section-title">Crafting Legacy Real Estate Since 2011</h2>
                <p class="story-lead">
                    Providing elite, sustainable and high-yielding locations for families and forward-thinking investors.
                </p>
                <p class="story-text">
                    For more than 15 years, Property Station has stood as a beacon of trust, sophistication, and excellence in the premium real estate landscape. What started as a boutique brokerage firm has evolved into a premier full-service property destination, catering to high-net-worth individuals, commercial groups, and smart investors.
                </p>
                <p class="story-text">
                    We believe that finding a property is more than a transaction — it's about matching lifestyles and securing futures. Our deep market intelligence, technological innovations, and hyper-personalized customer approach set us apart as industry leaders.
                </p>
                <a href="contact.php" class="btn btn-dark" style="margin-top: 1.5rem;">
                    Partner With Us <i data-lucide="arrow-right"></i>
                </a>
            </div>

            <!-- Right: Beautiful image -->
            <div class="story-visual">
                <div class="story-img-wrapper">
                    <img src="assets/images/about_house_one.png" alt="Property Station Contemporary Residence" class="story-img" onerror="this.src='https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80'">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 6. Vision & Founder Section -->
<section class="vision-section">
    <div class="container">
        <div class="vision-grid grid-2">
            
            <!-- Left: Founder Quote Card -->
            <div class="vision-card">
                <p class="vision-quote">
                    Luxury is not about a high price tag. It is about the seamless integration of convenience, aesthetic brilliance, and long-term security.
                </p>
                <div class="vision-author">
                    <div class="author-image">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGyxIFarf9--OarPvvfqpw70YguxtUkYUWfnLa-3oOhQ&amp;s=10" alt="<?php echo htmlspecialchars(env('CONTACT_AGENT_NAME', 'Anil Mehra')); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="author-details">
                        <h4><?php echo htmlspecialchars(env('CONTACT_AGENT_NAME', 'Anil Mehra')); ?></h4>
                        <p><?php echo htmlspecialchars(env('CONTACT_AGENT_ROLE', 'Founder & Director')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Right: Vision and Mission Text -->
            <div class="vision-details">
                <span class="section-tagline">Our Strategy</span>
                <h2 class="section-title">Driven by Quality, Guided by Integrity</h2>
                <p class="story-text" style="font-size: 1.1rem; color: var(--color-text-light);">
                    Our mission is simple: to make real estate acquisition and management completely seamless, transparent, and rewarding.
                </p>
                <p class="story-text">
                    Through our custom valuation processes and professional management models, we ensure every asset achieves its maximum value. We hold ourselves to the highest ethical standards, ensuring our partners are guided with absolute transparency at every step of their purchasing or selling journey.
                </p>
                <div style="display: flex; gap: 2rem; margin-top: 2rem;">
                    <div>
                        <h4 style="color: var(--color-accent); font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Our Mission</h4>
                        <p class="story-text" style="font-size: 0.95rem;">Empower client success through data-driven advisory and premium luxury property matchmaking.</p>
                    </div>
                    <div>
                        <h4 style="color: var(--color-accent); font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">Our Vision</h4>
                        <p class="story-text" style="font-size: 0.95rem;">To shape sustainable urban landscapes and lead global standards in boutique property hospitality.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 7. Services Overview Section (Reusing card styles) -->
<section class="about-detailed-section" style="background-color: var(--color-bg-dark);">
    <div class="container">
        
        <div class="services-intro-bar">
            <span class="section-tagline">Core Expertise</span>
            <h2 class="section-title">What We Do Best</h2>
            <p class="about-lead">
                Providing comprehensive real estate services with a dedication to detail and efficiency.
            </p>
        </div>

        <div class="services-grid grid-3">
            <!-- Service item 1 -->
            <div class="service-card" id="service-valuation">
                <div class="service-icon-box">
                    <i data-lucide="maximize-2"></i>
                </div>
                <h4 class="service-title">Property Valuation</h4>
                <p class="service-desc">
                    Comprehensive market assessments utilizing structural details, historic indexes, and future indicators to lock in maximum property values.
                </p>
            </div>

            <!-- Service item 2 -->
            <div class="service-card" id="service-management">
                <div class="service-icon-box">
                    <i data-lucide="home"></i>
                </div>
                <h4 class="service-title">Property Management</h4>
                <p class="service-desc">
                    Complete lifecycle care for residential estates and commercial buildings, maintaining occupancy, cash flows, and pristine structural condition.
                </p>
            </div>

            <!-- Service item 3 -->
            <div class="service-card" id="service-investment">
                <div class="service-icon-box">
                    <i data-lucide="briefcase"></i>
                </div>
                <h4 class="service-title">Invest Opportunities</h4>
                <p class="service-desc">
                    Curating high-yield, pre-vetted real estate portfolios, co-living models, and strategic land acquisitions optimized for capital gains.
                </p>
            </div>
        </div>

    </div>
</section>

<!-- 8. Reusable Stats Highlights Bar -->
<section class="stats-section" style="border-top: 1px solid var(--color-border-dark);">
    <div class="container">
        <div class="stats-grid grid-4">
            <div class="stat-item">
                <h3 class="stat-number">15+</h3>
                <p class="stat-label">Years Legacy</p>
            </div>
            <div class="stat-item">
                <h3 class="stat-number">500+</h3>
                <p class="stat-label">Luxury Estates</p>
            </div>
            <div class="stat-item">
                <h3 class="stat-number">18K+</h3>
                <p class="stat-label">Happy Clients</p>
            </div>
            <div class="stat-item">
                <h3 class="stat-number">99%</h3>
                <p class="stat-label">Satisfaction</p>
            </div>
        </div>
    </div>
</section>

<?php
// 9. Load Footer
require_once __DIR__ . '/includes/footer.php';
?>
