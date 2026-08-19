<?php
/**
 * About Us Component for Property Station
 */
?>
<section class="about-section" id="about">
    <!-- Huge background outline text -->
    <div class="outline-text about-bg-text">ABOUT</div>

    <div class="container">
        <!-- Top Title & Info Bar -->
        <div class="about-top-bar">
            <div class="about-title-area">
                <span class="section-tagline">WHO WE ARE</span>
                <h2 class="section-title">About Us</h2>
                <p class="about-lead">
                    We are a real estate firm with over 15 years of expertise, and our main goal is to provide amazing locations to our partners.
                </p>
            </div>
            <div class="about-cta-area">
                <a href="#contact" class="btn btn-secondary">
                    Learn More <i data-lucide="arrow-right"></i>
                </a>
            </div>
        </div>

        <!-- Overlapping/Dual Images Grid -->
        <div class="about-images-grid">
            <div class="about-img-box img-left custom-border-img">
                <img src="assets/images/about_house_one.png" alt="Contemporary Villa Exterior" class="about-gallery-img">
            </div>
            <div class="about-img-box img-right custom-border-img">
                <img src="assets/images/about_house_two.png" alt="Minimalist Luxury Residential Exterior" class="about-gallery-img">
            </div>
        </div>

        <!-- Middle Info Bar with Agent Stamp -->
        <div class="about-middle-bar">
            <div class="about-middle-text">
                <h3 class="about-middle-heading">
                    All-inclusive real estate services to facilitate the easy and confident purchase, sale, and management of your properties.
                </h3>
            </div>
            
            <div class="about-stamp-container">
                <!-- Circular stamp badge -->
                <div class="circular-stamp">
                    <svg viewBox="0 0 100 100" class="stamp-svg-rotate">
                        <path id="textCirclePath" d="M 50, 50 m -38, 0 a 38,38 0 1,1 76,0 a 38,38 0 1,1 -76,0" fill="transparent" />
                        <text>
                            <textPath href="#textCirclePath" class="stamp-svg-text" startOffset="0%">
                                <?php echo htmlspecialchars(strtoupper(env('APP_NAME', 'Property Station'))); ?> • <?php echo htmlspecialchars(strtoupper(env('CONTACT_AGENT_NAME', 'Anil Mehra'))); ?> •
                            </textPath>
                        </text>
                    </svg>
                    <div class="stamp-inner-image">
                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSGyxIFarf9--OarPvvfqpw70YguxtUkYUWfnLa-3oOhQ&amp;s=10" alt="Executive Agent Portrait">
                    </div>
                </div>
            </div>
        </div>

        <!-- Three Columns Services List -->
        <div class="services-grid grid-3">
            <!-- Service item 1 -->
            <div class="service-card" id="service-valuation">
                <div class="service-icon-box">
                    <i data-lucide="maximize-2"></i>
                </div>
                <h4 class="service-title">Property Valuation</h4>
                <p class="service-desc">
                    All-inclusive real estate services to facilitate the easy and confident purchase, sale, and management of your properties.
                </p>
            </div>

            <!-- Service item 2 -->
            <div class="service-card" id="service-management">
                <div class="service-icon-box">
                    <i data-lucide="home"></i>
                </div>
                <h4 class="service-title">Property Management</h4>
                <p class="service-desc">
                    Business consulting involves providing expert advice and services to real estate firms to improve performance and growth.
                </p>
            </div>

            <!-- Service item 3 -->
            <div class="service-card" id="service-investment">
                <div class="service-icon-box">
                    <i data-lucide="briefcase"></i>
                </div>
                <h4 class="service-title">Invest Opportunities</h4>
                <p class="service-desc">
                    Real estate services facilitate the easy and confident purchase, sale, and management of your property assets.
                </p>
            </div>
        </div>
    </div>
</section>
