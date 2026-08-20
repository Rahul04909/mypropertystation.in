<?php
/**
 * Admin Add Property Project Page
 * Property Station
 */
require_once __DIR__ . '/header.php'; // Enforces login and imports DB connection ($db)

// 1. Process Form POST Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_project') {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    
    $title       = trim($_POST['title'] ?? '');
    $slug        = trim($_POST['slug'] ?? '');
    $location    = trim($_POST['location'] ?? '');
    $priceRange  = trim($_POST['price_range'] ?? '');
    $shortDesc   = trim($_POST['short_desc'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $amenities   = trim($_POST['amenities'] ?? '[]');
    
    // SEO inputs
    $seoTitle    = trim($_POST['seo_title'] ?? '');
    $seoDesc     = trim($_POST['seo_desc'] ?? '');
    $seoKeywords = trim($_POST['seo_keywords'] ?? '');
    
    // Simple Validations
    if (empty($title) || empty($slug) || empty($location) || empty($priceRange) || empty($shortDesc) || empty($description)) {
        echo json_encode(['success' => false, 'message' => 'Please fill out all required fields.']);
        exit;
    }
    
    // Check if slug is unique
    try {
        $check = $db->prepare("SELECT id FROM projects WHERE slug = ?");
        $check->execute([$slug]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'A project with this slug already exists. Please choose a different title or modify the slug.']);
            exit;
        }
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit;
    }
    
    // File Upload Setup
    $uploadDir = __DIR__ . '/../uploads/projects/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $featuredImgPath = '';
    $bannerImgPath   = '';
    $galleryPaths    = [];
    
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // A. Handle Featured Image
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['featured_image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExtensions)) {
            $newFileName = 'featured_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
                $featuredImgPath = 'uploads/projects/' . $newFileName;
            }
        }
    }
    
    // B. Handle Project Banner
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['banner_image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExtensions)) {
            $newFileName = 'banner_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
                $bannerImgPath = 'uploads/projects/' . $newFileName;
            }
        }
    }
    
    // C. Handle Multiple Gallery Images
    if (isset($_FILES['gallery_images'])) {
        $files = $_FILES['gallery_images'];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];
                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                if (in_array($ext, $allowedExtensions)) {
                    $newFileName = 'gallery_' . time() . '_' . $i . '_' . rand(100, 999) . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
                        $galleryPaths[] = 'uploads/projects/' . $newFileName;
                    }
                }
            }
        }
    }
    
    // Dynamic/Auto generation fallback for SEO fields
    if (empty($seoTitle)) {
        $seoTitle = $title . ' | ' . env('APP_NAME', 'Property Station');
    }
    if (empty($seoDesc)) {
        $seoDesc = mb_strimwidth(strip_tags($shortDesc), 0, 155, "...");
    }
    if (empty($seoKeywords)) {
        $seoKeywords = implode(', ', explode(' ', $title)) . ', luxury property, real estate';
    }
    
    // Automatic SEO Schema (JSON-LD)
    $appUrl = env('APP_URL', 'https://mypropertystation.in');
    $schema = [
        "@context" => "https://schema.org",
        "@type" => "RealEstateAgent",
        "name" => $title,
        "description" => $shortDesc,
        "image" => $appUrl . '/' . ($featuredImgPath ?: 'assets/images/about_house_one.png'),
        "address" => [
            "@type" => "PostalAddress",
            "addressLocality" => $location,
            "addressCountry" => "IN"
        ],
        "offers" => [
            "@type" => "AggregateOffer",
            "priceCurrency" => "INR",
            "price" => $priceRange
        ]
    ];
    $seoSchemaJson = json_encode($schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
    // Automatic Open Graph Information
    $og = [
        "og:title" => $seoTitle,
        "og:description" => $seoDesc,
        "og:image" => $appUrl . '/' . ($featuredImgPath ?: 'assets/images/about_house_one.png'),
        "og:type" => "website",
        "og:url" => $appUrl . '/project/' . $slug
    ];
    $ogInfoJson = json_encode($og, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
    $galleryJson = json_encode($galleryPaths);
    
    // Database Insertion
    try {
        $stmt = $db->prepare("INSERT INTO projects (
            title, slug, location, image, short_desc, description, banner, gallery, price_range, amenities, seo_title, seo_desc, seo_keywords, seo_schema, og_info
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
        )");
        
        $stmt->execute([
            $title,
            $slug,
            $location,
            $featuredImgPath,
            $shortDesc,
            $description,
            $bannerImgPath,
            $galleryJson,
            $priceRange,
            $amenities,
            $seoTitle,
            $seoDesc,
            $seoKeywords,
            $seoSchemaJson,
            $ogInfoJson
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Project created and published successfully!']);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
        exit;
    }
}
?>

<!-- Quill Rich Text Editor stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<div class="row">
    <div class="col-12">
        <form id="project-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="add_project">
            
            <div class="row">
                <!-- Left Details Pane -->
                <div class="col-lg-8">
                    
                    <!-- General Details Card -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-edit text-warning mr-1"></i> Project Information</h3>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group">
                                <label for="title" class="font-weight-bold text-secondary">Project Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="Enter project title (e.g. Ultra Luxury Estate)">
                            </div>
                            
                            <div class="form-group">
                                <label for="slug" class="font-weight-bold text-secondary">Slug Url <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="slug" name="slug" required placeholder="auto-generated-slug-path">
                                <small class="form-text text-muted">Unique browser URL path segment.</small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location" class="font-weight-bold text-secondary">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="location" name="location" required placeholder="e.g. Sector 15, Green Corridor">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_range" class="font-weight-bold text-secondary">Price Range <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="price_range" name="price_range" required placeholder="e.g. ₹1.5 Cr - ₹3.5 Cr">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="short_desc" class="font-weight-bold text-secondary">Short Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="short_desc" name="short_desc" rows="3" required placeholder="Summarize listing features in 2-3 sentences..."></textarea>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!-- Long Description Card (Quill Editor) -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-file-alt text-warning mr-1"></i> Rich Content Description <span class="text-danger">*</span></h3>
                        </div>
                        <div class="card-body p-0">
                            <!-- Quill editor container -->
                            <div id="quill-editor" style="height: 350px; border: none; font-size: 1rem;"></div>
                            <!-- Hidden input holding actual HTML -->
                            <input type="hidden" name="description" id="description-input">
                        </div>
                    </div>
                    
                    <!-- Gallery Media Manager -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-images text-warning mr-1"></i> Project Gallery Images</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">Select multiple pictures. Click the thumbnail's cross button to discard files.</p>
                            
                            <div class="gallery-upload-area border d-flex flex-column align-items-center justify-content-center p-5 rounded bg-light" style="cursor: pointer; border-style: dashed !important; border-width: 2px !important; border-color: #cbd5e1 !important;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-warning mb-2"></i>
                                <span class="font-weight-bold">Select Gallery Files</span>
                                <input type="file" id="gallery-input" name="gallery_images[]" multiple accept="image/*" style="display: none;">
                            </div>
                            
                            <!-- Thumbnail Preview Grid -->
                            <div id="gallery-preview-grid" class="row mt-4" style="gap: 12px 0;"></div>
                        </div>
                    </div>

                    <!-- SEO & OG Information -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-search text-warning mr-1"></i> Search Engine Optimization (SEO)</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted small">These fields generate meta headers. Leaving them empty will auto-populate them using the title and short description.</p>
                            
                            <div class="form-group">
                                <label for="seo_title" class="font-weight-bold text-secondary">Meta Title</label>
                                <input type="text" class="form-control" id="seo_title" name="seo_title" placeholder="Meta title for Google listings">
                            </div>
                            
                            <div class="form-group">
                                <label for="seo_desc" class="font-weight-bold text-secondary">Meta Description</label>
                                <textarea class="form-control" id="seo_desc" name="seo_desc" rows="3" placeholder="Meta description snippet (under 160 characters)"></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="seo_keywords" class="font-weight-bold text-secondary">Meta Keywords</label>
                                <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" placeholder="e.g. premium villa, luxury apartment, green corridor">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Side Info Card -->
                <div class="col-lg-4">
                    
                    <!-- Publish Box -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-paper-plane text-warning mr-1"></i> Publication Status</h3>
                        </div>
                        <div class="card-body">
                            <button type="submit" class="btn btn-warning w-100 font-weight-bold text-white mb-2" id="submit-btn" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important;">
                                Publish Project <i class="fas fa-check-circle ml-1"></i>
                            </button>
                            <a href="projects.php" class="btn btn-light border w-100 font-weight-bold text-secondary">
                                Cancel & Return
                            </a>
                        </div>
                    </div>
                    
                    <!-- Featured and Banner Images -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-image text-warning mr-1"></i> Cover & Banner Images</h3>
                        </div>
                        <div class="card-body">
                            
                            <!-- Featured Image -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-secondary d-block">Featured Image <span class="text-danger">*</span></label>
                                <div class="text-center mb-3">
                                    <img id="featured-preview" src="../assets/images/about_house_one.png" class="img-thumbnail rounded" style="max-height: 140px; width: 100%; object-fit: cover; opacity: 0.5;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="featured_image" name="featured_image" accept="image/*" required>
                                    <label class="custom-file-label text-left" for="featured_image">Choose Featured</label>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Banner Image -->
                            <div class="form-group">
                                <label class="font-weight-bold text-secondary d-block">Project Banner <span class="text-danger">*</span></label>
                                <div class="text-center mb-3">
                                    <img id="banner-preview" src="../assets/images/about_house_two.png" class="img-thumbnail rounded" style="max-height: 100px; width: 100%; object-fit: cover; opacity: 0.5;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="banner_image" name="banner_image" accept="image/*" required>
                                    <label class="custom-file-label text-left" for="banner_image">Choose Banner</label>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!-- Amenities Manager -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-concierge-bell text-warning mr-1"></i> Amenities Manager</h3>
                        </div>
                        <div class="card-body">
                            <div class="input-group mb-3">
                                <input type="text" id="amenity-input" class="form-control" placeholder="e.g. Swimming Pool">
                                <div class="input-group-append">
                                    <button class="btn btn-warning text-white" type="button" id="add-amenity-btn" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important;">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div id="amenities-container" class="d-flex flex-wrap" style="gap: 6px;"></div>
                            
                            <!-- Hidden input holding amenities JSON array -->
                            <input type="hidden" name="amenities" id="amenities-json" value="[]">
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </form>
    </div>
</div>

<!-- Quill Rich Text JS Library -->
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

<script>
var amenitiesList = [];
var galleryFiles = []; // Holds file references to select/remove gallery images wordpress-style

$(document).ready(function() {
    
    // 1. Initialize Quill Editor
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Write comprehensive property features, descriptions, dimensions...'
    });
    
    // 2. Slug Auto-generation from Title
    $('#title').on('input', function() {
        var val = $(this).val();
        var slug = val.toLowerCase()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-')
                     .replace(/-+/g, '-');
        $('#slug').val(slug);
        
        // Auto SEO Title Generation
        if ($('#seo_title').val() === '' || $('#seo_title').data('auto') === true) {
            $('#seo_title').val(val ? val + ' | Property Station' : '').data('auto', true);
        }
    });
    
    // SEO inputs tracking
    $('#seo_title').on('input', function() {
        $(this).data('auto', false);
    });
    
    $('#short_desc').on('input', function() {
        var val = $(this).val();
        if ($('#seo_desc').val() === '' || $('#seo_desc').data('auto') === true) {
            $('#seo_desc').val(val.substring(0, 155)).data('auto', true);
        }
    });
    
    $('#seo_desc').on('input', function() {
        $(this).data('auto', false);
    });
    
    // 3. Featured & Banner file selectors preview
    $('#featured_image').on('change', function() {
        var file = this.files[0];
        if (file) {
            $(this).next('.custom-file-label').html(file.name);
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#featured-preview').attr('src', e.target.result).css('opacity', '1');
            }
            reader.readAsDataURL(file);
        }
    });
    
    $('#banner_image').on('change', function() {
        var file = this.files[0];
        if (file) {
            $(this).next('.custom-file-label').html(file.name);
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#banner-preview').attr('src', e.target.result).css('opacity', '1');
            }
            reader.readAsDataURL(file);
        }
    });

    // 4. Wordpress-style multiple file upload selection with remove buttons
    $('.gallery-upload-area').on('click', function() {
        $('#gallery-input').trigger('click');
    });
    
    $('#gallery-input').on('change', function() {
        var newFiles = Array.from(this.files);
        
        newFiles.forEach(function(file) {
            // Append file to our memory list
            galleryFiles.push(file);
            
            // Read and render thumbnail preview element
            var reader = new FileReader();
            reader.onload = function(e) {
                var fileIndex = galleryFiles.length - 1;
                var thumbHtml = `
                    <div class="col-md-3 col-sm-4 col-6 gallery-thumb-item" id="gallery-thumb-${fileIndex}" style="position: relative;">
                        <div class="card p-1 border">
                            <img src="${e.target.result}" class="card-img-top rounded" style="height: 100px; object-fit: cover;">
                            <div class="p-1 text-truncate small">${file.name}</div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger rounded-circle" onclick="removeGalleryFile(${fileIndex})" 
                                style="position: absolute; top: -5px; right: 5px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; padding: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                            <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                        </button>
                    </div>
                `;
                $('#gallery-preview-grid').append(thumbHtml);
            };
            reader.readAsDataURL(file);
        });
    });

    // 5. Amenities Adder Logic
    $('#add-amenity-btn').on('click', function() {
        var val = $('#amenity-input').val().trim();
        if (val !== '') {
            if (amenitiesList.indexOf(val) === -1) {
                amenitiesList.push(val);
                renderAmenities();
            }
            $('#amenity-input').val('');
        }
    });
    
    $('#amenity-input').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#add-amenity-btn').trigger('click');
        }
    });

    // 6. AJAX Submission Handler
    $('#project-form').on('submit', function(e) {
        e.preventDefault();
        
        // Populate hidden description textarea
        var htmlContent = quill.getSemanticHTML();
        if (quill.getText().trim() === '') {
            Swal.fire('Content Required', 'Please add a comprehensive description text of the property project.', 'warning');
            return;
        }
        $('#description-input').val(htmlContent);
        
        var $btn = $('#submit-btn');
        $btn.prop('disabled', true).html('Publishing... <i class="fas fa-spinner fa-spin"></i>');
        
        // Construct FormData manually to include selected files in galleryFiles array
        var formData = new FormData(this);
        
        // Remove standard multiple gallery images files which are empty or old
        formData.delete('gallery_images[]');
        
        // Append actual selected files from our array
        galleryFiles.forEach(function(file) {
            if (file !== null) {
                formData.append('gallery_images[]', file);
            }
        });
        
        $.ajax({
            url: 'add-project.php',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Published!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#D4AF37'
                    }).then(() => {
                        window.location.href = 'projects.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Creation Failed',
                        text: response.message,
                        icon: 'error',
                        confirmButtonColor: '#D4AF37'
                    });
                    $btn.prop('disabled', false).html('Publish Project <i class="fas fa-check-circle ml-1"></i>');
                }
            },
            error: function() {
                Swal.fire({
                    title: 'System Error',
                    text: 'An error occurred during project database insert. Check connection and folder permissions.',
                    icon: 'error',
                    confirmButtonColor: '#DC3545'
                });
                $btn.prop('disabled', false).html('Publish Project <i class="fas fa-check-circle ml-1"></i>');
            }
        });
    });
});

// Remove single amenity helper
function removeAmenity(index) {
    amenitiesList.splice(index, 1);
    renderAmenities();
}

function renderAmenities() {
    var $container = $('#amenities-container');
    $container.empty();
    amenitiesList.forEach(function(item, index) {
        $container.append(
            `<span class="badge badge-light border p-2 mr-2 mb-2 d-inline-flex align-items-center" style="gap: 8px;">
                ${item}
                <i class="fas fa-times text-danger" onclick="removeAmenity(${index})" style="cursor: pointer;"></i>
            </span>`
        );
    });
    $('#amenities-json').val(JSON.stringify(amenitiesList));
}

// Remove single gallery thumbnail selector
function removeGalleryFile(index) {
    // Nullify reference
    galleryFiles[index] = null;
    $('#gallery-thumb-' + index).fadeOut('slow', function() {
        $(this).remove();
    });
}
</script>

<?php
require_once __DIR__ . '/footer.php';
?>
