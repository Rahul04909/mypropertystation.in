<?php
/**
 * Projects Component for Property Station
 */

try {
    $db = db();
    $stmt = $db->query("SELECT * FROM `projects` ORDER BY `id` ASC");
    $dbProjectsList = $stmt->fetchAll();
} catch (\Exception $e) {
    $dbProjectsList = [];
}

// Fallback to hardcoded ones if DB returns empty
if (empty($dbProjectsList)) {
    $sliderProjects = [
        [
            'slug' => 'eco-solar',
            'title' => 'Eco-Solar Villa',
            'location' => 'Sector 15, Green Corridor, Phase 1',
            'image' => 'assets/images/project_one.png'
        ],
        [
            'slug' => 'cubic-glass',
            'title' => 'Cubic Glass Manor',
            'location' => 'Phase 2, Green Corridor, Sector 15',
            'image' => 'assets/images/project_two.png'
        ],
        [
            'slug' => 'contemporary-mansion',
            'title' => 'Contemporary Mansion',
            'location' => 'Sector 15, Property Station Zone',
            'image' => 'assets/images/hero_house.png'
        ]
    ];
} else {
    $sliderProjects = [];
    foreach ($dbProjectsList as $dbProj) {
        $sliderProjects[] = [
            'slug' => $dbProj['slug'],
            'title' => $dbProj['title'],
            'location' => $dbProj['location'],
            'image' => $dbProj['image']
        ];
    }
}
?>
<section class="projects-section" id="projects">
    <!-- Huge background outline text -->
    <div class="outline-text projects-bg-text">PROJECTS</div>

    <div class="container projects-container-wrapper">
        <div class="projects-layout-grid">
            <!-- Left Side: Navigation Details & Info -->
            <div class="projects-info-pane">
                <!-- Vertical index indicator -->
                <div class="projects-index-indicator">
                    <span class="index-line-track"></span>
                    <span class="index-number-bubble" id="project-active-num">01</span>
                </div>

                <div class="projects-text-content">
                    <span class="section-tagline">EXQUISITE WORK</span>
                    <h2 class="section-title">Discover Modern Living At Property Station.</h2>
                    <p class="projects-desc">
                        Residence takes advantage of abundant sunlight by incorporating solar panels and smart automation into its architecture.
                    </p>
                    <a href="#contact" class="btn btn-primary">
                        Explore More <i data-lucide="arrow-right"></i>
                    </a>
                </div>
            </div>

            <!-- Right Side: Project Cards Showcase Slider -->
            <div class="projects-showcase-pane">
                <div class="projects-slider-wrapper" id="projects-slider">
                    <?php foreach ($sliderProjects as $idx => $slide): ?>
                        <?php 
                        $indexNum = str_pad($idx + 1, 2, '0', STR_PAD_LEFT); 
                        $activeClass = $idx === 0 ? 'active' : '';
                        
                        // Resolve image source
                        $imgSrc = $slide['image'];
                        if (strpos($imgSrc, 'assets/') === false && strpos($imgSrc, 'uploads/') === false) {
                            $imgSrc = 'uploads/projects/' . $imgSrc;
                        }
                        ?>
                        <!-- Project Slide <?php echo $indexNum; ?> -->
                        <div class="project-slide <?php echo $activeClass; ?>" data-index="<?php echo $indexNum; ?>">
                            <div class="project-card-image custom-border-img">
                                <img src="<?php echo htmlspecialchars($imgSrc); ?>" alt="Property Station <?php echo htmlspecialchars($slide['title']); ?>">
                                <div class="project-card-overlay">
                                    <h4 class="project-card-title"><?php echo htmlspecialchars($slide['title']); ?></h4>
                                    <p class="project-card-location"><?php echo htmlspecialchars($slide['location']); ?></p>
                                </div>
                                <a href="#contact" class="project-card-btn" aria-label="View Details">
                                    <i data-lucide="arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Bottom Horizontal Indicators -->
                <div class="projects-slider-nav">
                    <?php foreach ($sliderProjects as $idx => $slide): ?>
                        <span class="slide-nav-dot <?php echo $idx === 0 ? 'active' : ''; ?>" data-slide="<?php echo $idx; ?>"></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
