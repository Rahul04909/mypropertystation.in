<?php
/**
 * Contact/Investment Inquiry Component for Property Station
 */

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
?>
<section class="contact-section" id="contact">
    <!-- Huge background outline text -->
    <div class="outline-text contact-bg-text">CONNECT</div>

    <div class="container">
        <div class="contact-grid grid-2">
            <!-- Left Side: General Info & Addresses -->
            <div class="contact-info-pane">
                <span class="section-tagline">GET IN TOUCH</span>
                <h2 class="section-title">Ready To Find Your Edge?</h2>
                <p class="contact-desc">
                    Connect with our luxury estate brokers and smart investment analysts today. Fill out the form, or reach us directly at our regional offices.
                </p>

                <!-- Contact items -->
                <div class="contact-items-list">
                    <!-- Phone -->
                    <div class="contact-item-box">
                        <div class="contact-icon">
                            <i data-lucide="phone-call"></i>
                        </div>
                        <div class="contact-text">
                            <span class="contact-label">Call Us Directly</span>
                            <a href="tel:<?php echo htmlspecialchars(env('CONTACT_PHONE_RAW', '+919310104249')); ?>" class="contact-link"><?php echo htmlspecialchars(env('CONTACT_PHONE', '+91 93101 04249')); ?></a>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="contact-item-box">
                        <div class="contact-icon">
                            <i data-lucide="mail"></i>
                        </div>
                        <div class="contact-text">
                            <span class="contact-label">Email Our Consultants</span>
                            <a href="mailto:<?php echo htmlspecialchars(env('CONTACT_EMAIL', 'sales@mypropertystation.in')); ?>" class="contact-link"><?php echo htmlspecialchars(env('CONTACT_EMAIL', 'sales@mypropertystation.in')); ?></a>
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
            </div>

            <!-- Right Side: Premium Contact / Inquiry Form -->
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
