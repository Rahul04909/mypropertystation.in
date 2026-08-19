<?php
/**
 * Hero Component for Property Station
 */
?>
<section class="hero-section" id="hero">
    <!-- Huge background outline text -->
    <div class="outline-text hero-bg-text">PROPERTY</div>

    <!-- Left Vertical Social Bar -->
    <div class="vertical-bar left-bar">
        <ul class="vertical-socials">
            <li><a href="#" target="_blank" rel="noopener">FACEBOOK</a></li>
            <li class="bullet"></li>
            <li><a href="#" target="_blank" rel="noopener">TWITTER</a></li>
            <li class="bullet"></li>
            <li><a href="#" target="_blank" rel="noopener">INSTAGRAM</a></li>
        </ul>
    </div>

    <!-- Right Vertical Scroll Bar -->
    <div class="vertical-bar right-bar">
        <a href="#about" class="vertical-scroll">
            <span>Scroll</span>
            <i data-lucide="arrow-down" class="scroll-arrow"></i>
        </a>
    </div>

    <!-- Main Container -->
    <div class="container hero-container">
        <div class="hero-grid">
            <!-- Hero Text Content -->
            <div class="hero-content">
                <span class="hero-badge">YOUR DESTINATION FOR LUXURY HOMES</span>
                <h1 class="hero-title">
                    Elevate Lifestyle <br>
                    <span class="hero-title-accent">Luxury Meets</span> <br>
                    Comfort
                </h1>
                <p class="hero-description">
                    Bringing together a team with passion, dedication, and resources to help our clients reach their buying and selling goals. We are with you every step of the way.
                </p>
                <div class="hero-actions">
                    <a href="#projects" class="btn btn-primary">
                        Explore Properties <i data-lucide="arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Hero Image Showcase with Interactive Play overlay -->
            <div class="hero-visual">
                <div class="hero-image-wrapper custom-border-img">
                    <img src="assets/images/hero_house.png" alt="Premium Luxury Villa in Property Station" class="hero-img">
                    
                    <!-- Pulsing Play Button overlay -->
                    <button class="video-play-btn" id="hero-video-trigger" aria-label="Play property video">
                        <span class="play-icon-wrapper">
                            <i data-lucide="play" fill="white"></i>
                        </span>
                    </button>

                    <!-- Embedded Video Player Modal Container (Simulated/Active) -->
                    <div class="video-player-iframe" id="video-iframe-container">
                        <span class="close-video" id="video-iframe-close"><i data-lucide="x"></i></span>
                        <iframe id="hero-promo-video" src="" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
