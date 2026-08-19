/* assets/js/main.js */

document.addEventListener('DOMContentLoaded', () => {

    // ==========================================
    // 1. Header Scroll Shadow & Backdrop
    // ==========================================
    const siteHeader = document.getElementById('site-header');
    const backToTopBtn = document.getElementById('back-to-top-btn');

    const handleScroll = () => {
        if (window.scrollY > 50) {
            siteHeader.classList.add('scrolled');
        } else {
            siteHeader.classList.remove('scrolled');
        }

        if (backToTopBtn) {
            if (window.scrollY > 400) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        }
    };

    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Init on load


    // ==========================================
    // 2. Mobile Nav Drawer Controls
    // ==========================================
    const menuToggleBtn = document.getElementById('menu-toggle-btn');
    const mobileDrawer = document.getElementById('mobile-drawer');
    const drawerCloseBtn = document.getElementById('drawer-close-btn');
    const drawerOverlay = document.getElementById('drawer-overlay');
    const mobileLinks = document.querySelectorAll('.mobile-nav-link');

    if (menuToggleBtn && mobileDrawer && drawerCloseBtn && drawerOverlay) {
        const openDrawer = () => {
            mobileDrawer.classList.add('active');
            drawerOverlay.classList.add('active');
            menuToggleBtn.classList.add('active');
            mobileDrawer.setAttribute('aria-hidden', 'false');
            menuToggleBtn.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden'; // Lock background scroll
        };

        const closeDrawer = () => {
            mobileDrawer.classList.remove('active');
            drawerOverlay.classList.remove('active');
            menuToggleBtn.classList.remove('active');
            mobileDrawer.setAttribute('aria-hidden', 'true');
            menuToggleBtn.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = ''; // Unlock scroll
        };

        menuToggleBtn.addEventListener('click', () => {
            if (mobileDrawer.classList.contains('active')) {
                closeDrawer();
            } else {
                openDrawer();
            }
        });

        drawerCloseBtn.addEventListener('click', closeDrawer);
        drawerOverlay.addEventListener('click', closeDrawer);

        mobileLinks.forEach(link => {
            link.addEventListener('click', closeDrawer);
        });
    }


    // ==========================================
    // 3. Projects Showcase Carousel Slider
    // ==========================================
    const projectsSlider = document.getElementById('projects-slider');
    const projectSlides = document.querySelectorAll('.project-slide');
    const navDots = document.querySelectorAll('.slide-nav-dot');
    const activeIndexBubble = document.getElementById('project-active-num');

    if (projectsSlider && projectSlides.length > 0 && navDots.length > 0 && activeIndexBubble) {
        let currentProjectIndex = 0;

        const updateProjectSlider = (index) => {
            currentProjectIndex = index;
            const slideWidth = projectSlides[0].offsetWidth;
            const gap = 24; // matches 1.5rem CSS gap
            
            // Translate slider track container
            projectsSlider.style.transform = `translateX(-${index * (slideWidth + gap)}px)`;

            // Update active class on slides
            projectSlides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });

            // Update dots indicators
            navDots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.add('active');
                } else {
                    dot.classList.remove('active');
                }
            });

            // Update left vertical index bubble number
            const slideNum = index + 1;
            activeIndexBubble.textContent = `0${slideNum}`;
        };

        navDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                updateProjectSlider(index);
            });
        });

        // Resize listener to recalculate width values if user scales screen
        window.addEventListener('resize', () => {
            updateProjectSlider(currentProjectIndex);
        });
    }


    // ==========================================
    // 4. Testimonials Review Carousel Slider
    // ==========================================
    const testimonialsTrack = document.getElementById('testimonials-track');
    const testimonialCards = document.querySelectorAll('.testimonial-card');
    const prevBtn = document.getElementById('testimonial-prev');
    const nextBtn = document.getElementById('testimonial-next');

    if (testimonialsTrack && testimonialCards.length > 0 && prevBtn && nextBtn) {
        let currentTestimonialIndex = 0;

        const updateTestimonials = (index) => {
            currentTestimonialIndex = index;
            const cardWidth = testimonialCards[0].offsetWidth;
            const gap = 32; // matches 2rem CSS gap

            // Offset index position
            testimonialsTrack.style.transform = `translateX(-${index * (cardWidth + gap)}px)`;

            // Update active review card
            testimonialCards.forEach((card, i) => {
                if (i === index) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            });
        };

        prevBtn.addEventListener('click', () => {
            let index = currentTestimonialIndex - 1;
            if (index < 0) index = testimonialCards.length - 1;
            updateTestimonials(index);
        });

        nextBtn.addEventListener('click', () => {
            let index = currentTestimonialIndex + 1;
            if (index >= testimonialCards.length) index = 0;
            updateTestimonials(index);
        });
    }


    // ==========================================
    // 5. Video Walkthrough Popup Modals
    // ==========================================
    const setupVideoModal = (triggerId, containerId, closeId, iframeId, videoUrl) => {
        const trigger = document.getElementById(triggerId);
        const container = document.getElementById(containerId);
        const close = document.getElementById(closeId);
        const iframe = document.getElementById(iframeId);

        if (trigger && container && close && iframe) {
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                container.classList.add('active');
                iframe.setAttribute('src', videoUrl);
            });

            close.addEventListener('click', (e) => {
                e.stopPropagation();
                container.classList.remove('active');
                iframe.setAttribute('src', '');
            });
        }
    };

    // Video 1 (Hero House Tour): Luxury house tour video embed
    setupVideoModal(
        'hero-video-trigger',
        'video-iframe-container',
        'video-iframe-close',
        'hero-promo-video',
        'https://www.youtube.com/embed/m7wG0g7K-X4?autoplay=1&rel=0'
    );

    // Video 2 (Modern Apartment Highlights): Modern apartment design tour
    setupVideoModal(
        'promo-video-trigger',
        'promo-video-iframe-container',
        'promo-video-iframe-close',
        'promo-youtube-video',
        'https://www.youtube.com/embed/w8U3WvXJ8c8?autoplay=1&rel=0'
    );


    // ==========================================
    // 6. Back To Top Action
    // ==========================================
    if (backToTopBtn) {
        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

});
