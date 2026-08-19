<?php
/**
 * Property Station Landing Page
 * Rebuilt as per the approved layout
 */

// 1. Load Central Config & Autoloader
require_once __DIR__ . '/config.php';

// 2. Load Header (includes doctype, meta tags, assets styles & navigation menu)
require_once __DIR__ . '/includes/header.php';

// 3. Load Hero Section (welcome banner, brand badge & video popup)
require_once __DIR__ . '/components/hero.php';

// 4. Load Statistics Section (four numeric counts highlight bar)
require_once __DIR__ . '/components/stats.php';

// 5. Load About Us Section (company details, rotating stamp & cards grid)
require_once __DIR__ . '/components/about.php';

// 6. Load Exclusive Projects Showcase (horizontal properties slider with vertical indicator)
require_once __DIR__ . '/components/projects.php';

// 7. Load Highlighted Modern Apartment Promo (CEO quote profile & play trigger)
require_once __DIR__ . '/components/promo.php';

// 8. Load Testimonials Slider (customer review cards)
require_once __DIR__ . '/components/testimonials.php';

// 9. Load Contact & Investment Inquiry Section (inquiry form & office contacts)
require_once __DIR__ . '/components/contact.php';

// 10. Load Footer (links, socials, newsletter subscription & scripts)
require_once __DIR__ . '/includes/footer.php';
