<?php
/**
 * Standalone Contact Us Page
 * Property Station
 */

// 1. Load Central Config
require_once __DIR__ . '/config.php';

// 2. Set Meta Details for SEO
$meta_title = "Contact Us | " . env('APP_NAME', 'Property Station') . " - Get In Touch";
$meta_desc = "Get in touch with Property Station. Speak with our real estate brokers, smart investment analysts, or submit an investment inquiry online.";

// 3. Fetch Active Projects for dropdown
try {
    $db = db();
    $stmt = $db->query("SELECT `title` FROM `projects` ORDER BY `id` ASC");
    $contactProjects = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (\Exception $e) {
    $contactProjects = [];
}

if (empty($contactProjects)) {
    $contactProjects = ['Eco-Solar Villa', 'Cubic Glass Manor', 'Contemporary Mansion'];
}

// 4. Load Header
require_once __DIR__ . '/includes/header.php';
?>

<!-- Custom CSS for Subpage layout -->
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

.contact-detailed-section {
    padding: var(--section-padding-large);
    background-color: var(--color-bg-dark);
    position: relative;
}

/* Custom Operating Hours styling */
.hours-box {
    margin-top: 3rem;
    background-color: var(--color-bg-light);
    border: 1px solid var(--color-border-dark);
    padding: 2rem;
    border-radius: var(--border-radius-custom-md);
    width: 100%;
}

.hours-title {
    font-family: var(--font-heading);
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--color-accent);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.hours-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.hours-row {
    display: flex;
    justify-content: space-between;
    font-size: 0.95rem;
    color: var(--color-text-muted);
}

.hours-row strong {
    color: var(--color-text-light);
}

/* Map Embed Container styling */
.map-section {
    padding: 0 0 var(--section-padding-large) 0;
    background-color: var(--color-bg-dark);
}

.map-wrapper {
    width: 100%;
    height: 450px;
    border-radius: var(--border-radius-custom-lg);
    overflow: hidden;
    border: 1px solid var(--color-border-dark);
    box-shadow: 0 20px 40px rgba(0,0,0,0.06);
}

.map-placeholder {
    width: 100%;
    height: 100%;
    background-color: var(--color-bg-light);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    color: var(--color-text-muted);
}

@media (max-width: 1024px) {
    .subpage-hero {
        padding: 9rem 0 5rem 0;
    }
    .subpage-hero-title {
        font-size: 3rem;
    }
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 4rem;
    }
    .map-wrapper {
        height: 350px;
    }
}

@media (max-width: 768px) {
    .subpage-hero-title {
        font-size: 2.4rem;
    }
}
</style>

<!-- 5. Subpage Hero Header -->
<section class="subpage-hero">
    <div class="outline-text" style="top: 10%; left: 50%; transform: translateX(-50%); font-size: 12vw; opacity: 0.6;">CONNECT</div>
    <div class="container" style="position: relative; z-index: 2;">
        <span class="subpage-hero-subtitle">Get In Touch</span>
        <h1 class="subpage-hero-title">Contact Us</h1>
    </div>
</section>

<!-- 6. Contact Information & Form Section -->
<section class="contact-detailed-section">
    <div class="container">
        <div class="contact-grid grid-2">
            
            <!-- Left Column: Contact info & Hours -->
            <div class="contact-info-pane">
                <span class="section-tagline">Find Your Edge</span>
                <h2 class="section-title">Ready To Connect?</h2>
                <p class="contact-desc">
                    Connect with our luxury estate brokers and smart investment analysts today. Reach us directly at our headquarters or submit your inquiry online.
                </p>

                <div class="contact-items-list">
                    <!-- Phone -->
                    <div class="contact-item-box">
                        <div class="contact-icon">
                            <i data-lucide="phone-call"></i>
                        </div>
                        <div class="contact-text">
                            <span class="contact-label">Call Us Directly</span>
                            <a href="tel:<?php echo htmlspecialchars(env('CONTACT_PHONE_RAW', '+918000810016')); ?>" class="contact-link"><?php echo htmlspecialchars(env('CONTACT_PHONE', '+91 80008 10016')); ?></a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="contact-item-box">
                        <div class="contact-icon">
                            <i data-lucide="mail"></i>
                        </div>
                        <div class="contact-text">
                            <span class="contact-label">Email Our Consultants</span>
                            <a href="mailto:<?php echo htmlspecialchars(env('CONTACT_EMAIL', 'info@mypropertystation.in')); ?>" class="contact-link"><?php echo htmlspecialchars(env('CONTACT_EMAIL', 'info@mypropertystation.in')); ?></a>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="contact-item-box">
                        <div class="contact-icon">
                            <i data-lucide="map-pin"></i>
                        </div>
                        <div class="contact-text">
                            <span class="contact-label">Headquarters</span>
                            <address class="contact-address"><?php echo htmlspecialchars(env('CONTACT_ADDRESS', 'Sector 15, Green Corridor, Phase 1')); ?></address>
                        </div>
                    </div>
                </div>

                <!-- Operating Hours -->
                <div class="hours-box">
                    <h3 class="hours-title">
                        <i data-lucide="clock"></i> Office Hours
                    </h3>
                    <div class="hours-list">
                        <div class="hours-row">
                            <span>Monday - Friday</span>
                            <strong>9:00 AM - 7:00 PM</strong>
                        </div>
                        <div class="hours-row">
                            <span>Saturday</span>
                            <strong>10:00 AM - 5:00 PM</strong>
                        </div>
                        <div class="hours-row">
                            <span>Sunday</span>
                            <strong>Closed (By Appointment)</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Form Card -->
            <div class="contact-form-pane">
                <div class="contact-form-card">
                    <h3 class="form-title">Investment Inquiry</h3>
                    <p class="form-subtitle">Submit your interest and our agent will reach out in 24 hours.</p>
                    
                    <form class="inquiry-form" id="property-inquiry">
                        <!-- Name -->
                        <div class="form-group">
                            <label for="inquiry-name" class="form-label">Full Name</label>
                            <input type="text" id="inquiry-name" name="name" placeholder="John Doe" required class="form-input">
                        </div>

                        <!-- Email & Phone row -->
                        <div class="form-row">
                            <div class="form-group">
                                <label for="inquiry-email" class="form-label">Email Address</label>
                                <input type="email" id="inquiry-email" name="email" placeholder="john@example.com" required class="form-input">
                            </div>
                            <div class="form-group">
                                <label for="inquiry-phone" class="form-label">Phone Number</label>
                                <input type="tel" id="inquiry-phone" name="phone" pattern="[6-9][0-9]{9}" minlength="10" maxlength="10" placeholder="e.g. 9876543210" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required class="form-input" title="Please enter a valid 10-digit Indian mobile number (e.g., 9876543210)">
                            </div>
                        </div>

                        <!-- Property Dropdown -->
                        <div class="form-group">
                            <label for="inquiry-property" class="form-label">Property Interest</label>
                            <div class="select-wrapper">
                                <select id="inquiry-property" name="property_interest" class="form-select">
                                    <optgroup label="Active Projects" style="color: var(--color-accent); font-weight: bold;">
                                        <?php foreach ($contactProjects as $projTitle): ?>
                                            <option value="<?php echo htmlspecialchars($projTitle); ?>" style="color: #495057; font-weight: normal;"><?php echo htmlspecialchars($projTitle); ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="Other Inquiry Options" style="color: #8c8f94; font-weight: bold;">
                                        <option value="General Investment" style="color: #495057; font-weight: normal;">General Smart Investment Enquiry</option>
                                        <option value="Sell Property" style="color: #495057; font-weight: normal;">Listing my property for sale</option>
                                    </optgroup>
                                </select>
                                <i data-lucide="chevron-down" class="select-arrow"></i>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="form-group">
                            <label for="inquiry-message" class="form-label">Message / Budget</label>
                            <textarea id="inquiry-message" name="message" rows="4" placeholder="Tell us about your requirements, timeline, or investment budget..." required class="form-textarea"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-full form-submit-btn" style="width: 100%;">
                            Submit Inquiry <i data-lucide="send"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- 7. Map Embed Section -->
<section class="map-section">
    <div class="container">
        <div class="map-wrapper">
            <!-- Modern dark map placeholder or styled layout, maps can be embedded using an iframe if preferred -->
            <div class="map-placeholder">
                <i data-lucide="map" style="width: 48px; height: 48px; color: var(--color-accent);"></i>
                <h4 style="font-family: var(--font-heading); font-weight: 700; color: var(--color-text-light);">Our Headquarters Location</h4>
                <p style="font-size: 0.9rem; text-align: center; max-width: 320px;"><?php echo htmlspecialchars(env('CONTACT_ADDRESS', 'Sector 15, Green Corridor, Phase 1')); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Include required CDNs and AJAX script for the Contact Form -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#property-inquiry').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find('button[type="submit"]');
        var btnOriginalText = $btn.html();
        
        $btn.prop('disabled', true).html('Sending... <i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: 'submit-contact.php',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Inquiry Submitted!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#D4AF37'
                    });
                    $form[0].reset();
                } else {
                    Swal.fire({
                        title: 'Submission Failed',
                        text: response.message || 'Please check your inputs and try again.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    title: 'System Error',
                    text: 'Unable to submit your enquiry at this time. Please try again later.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            },
            complete: function() {
                $btn.prop('disabled', false).html(btnOriginalText);
            }
        });
    });
});
</script>

<?php
// 8. Load Footer
require_once __DIR__ . '/includes/footer.php';
?>
