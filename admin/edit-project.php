<?php
/**
 * Admin Edit Property Project Page
 * Property Station
 */
require_once __DIR__ . '/header.php'; // Enforces login and imports DB connection ($db)

$projectId = intval($_GET['id'] ?? 0);

// Fetch existing project details
try {
    $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->execute([$projectId]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $project = null;
}

if (!$project) {
    echo '<div class="alert alert-danger my-5 p-4 text-center"><h4><i class="fas fa-exclamation-triangle"></i> Project Not Found</h4><p>The project listing you are trying to edit does not exist or has been deleted.</p><a href="projects.php" class="btn btn-light border mt-3 font-weight-bold">Back to Projects</a></div>';
    require_once __DIR__ . '/footer.php';
    exit;
}

// 1. Process Form AJAX POST Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'edit_project') {
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
    
    // Check if slug is unique (excluding current project)
    try {
        $check = $db->prepare("SELECT id FROM projects WHERE slug = ? AND id != ?");
        $check->execute([$slug, $projectId]);
        if ($check->fetch()) {
            echo json_encode(['success' => false, 'message' => 'A project with this slug already exists. Please modify the slug.']);
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
    
    $featuredImgPath = $project['image']; // Default to current
    $bannerImgPath   = $project['banner']; // Default to current
    
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    // A. Handle Featured Image Update
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['featured_image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['featured_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExtensions)) {
            $newFileName = 'featured_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
                // Delete old image
                if (!empty($project['image']) && file_exists(__DIR__ . '/../' . $project['image'])) {
                    @unlink(__DIR__ . '/../' . $project['image']);
                }
                $featuredImgPath = 'uploads/projects/' . $newFileName;
            }
        }
    }
    
    // B. Handle Project Banner Update
    if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['banner_image']['tmp_name'];
        $ext = strtolower(pathinfo($_FILES['banner_image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExtensions)) {
            $newFileName = 'banner_' . time() . '_' . rand(100, 999) . '.' . $ext;
            if (move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
                // Delete old banner
                if (!empty($project['banner']) && file_exists(__DIR__ . '/../' . $project['banner'])) {
                    @unlink(__DIR__ . '/../' . $project['banner']);
                }
                $bannerImgPath = 'uploads/projects/' . $newFileName;
            }
        }
    }
    
    // C. Handle Gallery Images (Merge retained files with any new uploads)
    $retainedGallery = json_decode($_POST['existing_gallery'] ?? '[]', true);
    if (!is_array($retainedGallery)) {
        $retainedGallery = [];
    }
    
    // Unlink old gallery images that have been removed
    $originalGallery = json_decode($project['gallery'] ?? '[]', true);
    if (is_array($originalGallery)) {
        foreach ($originalGallery as $oldImg) {
            if (!in_array($oldImg, $retainedGallery)) {
                if (!empty($oldImg) && file_exists(__DIR__ . '/../' . $oldImg)) {
                    @unlink(__DIR__ . '/../' . $oldImg);
                }
            }
        }
    }
    
    // Upload newly appended gallery files
    if (isset($_FILES['gallery_images'])) {
        $files = $_FILES['gallery_images'];
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $tmpName = $files['tmp_name'][$i];
                $ext = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                if (in_array($ext, $allowedExtensions)) {
                    $newFileName = 'gallery_' . time() . '_' . $i . '_' . rand(100, 999) . '.' . $ext;
                    if (move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
                        $retainedGallery[] = 'uploads/projects/' . $newFileName;
                    }
                }
            }
        }
    }
    
    $galleryJson = json_encode($retainedGallery);
    
    // Fallbacks for SEO fields
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
    
    // Database Update
    try {
        $stmt = $db->prepare("UPDATE projects SET 
            title = ?, slug = ?, location = ?, image = ?, short_desc = ?, description = ?, banner = ?, gallery = ?, price_range = ?, amenities = ?, seo_title = ?, seo_desc = ?, seo_keywords = ?, seo_schema = ?, og_info = ?
            WHERE id = ?");
        
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
            $ogInfoJson,
            $projectId
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Project listings updated successfully!']);
        exit;
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database update error: ' . $e->getMessage()]);
        exit;
    }
}
?>

<!-- Quill Rich Text Editor stylesheet -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

<div class="row">
    <div class="col-12">
        <form id="project-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="edit_project">
            
            <div class="row">
                <!-- Left Details Pane -->
                <div class="col-lg-8">
                    
                    <!-- General Details Card -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-edit text-warning mr-1"></i> Edit Project Information</h3>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group">
                                <label for="title" class="font-weight-bold text-secondary">Project Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required value="<?php echo htmlspecialchars($project['title']); ?>" placeholder="Enter project title">
                            </div>
                            
                            <div class="form-group">
                                <label for="slug" class="font-weight-bold text-secondary">Slug Url <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="slug" name="slug" required value="<?php echo htmlspecialchars($project['slug']); ?>" placeholder="slug-path">
                                <small class="form-text text-muted">Unique browser URL path segment.</small>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location" class="font-weight-bold text-secondary">Location <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="location" name="location" required value="<?php echo htmlspecialchars($project['location']); ?>" placeholder="e.g. Sector 15, Green Corridor">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="price_range" class="font-weight-bold text-secondary">Price Range <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="price_range" name="price_range" required value="<?php echo htmlspecialchars($project['price_range']); ?>" placeholder="e.g. ₹1.5 Cr - ₹3.5 Cr">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="short_desc" class="font-weight-bold text-secondary">Short Description <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="short_desc" name="short_desc" rows="3" required placeholder="Summarize features..."><?php echo htmlspecialchars($project['short_desc'] ?? ''); ?></textarea>
                            </div>
                            
                        </div>
                    </div>
                    
                    <!-- Long Description Card (Quill Editor) -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-file-alt text-warning mr-1"></i> Rich Content Description <span class="text-danger">*</span></h3>
                        </div>
                        <div class="card-body p-0">
                            <div id="quill-editor" style="height: 350px; border: none; font-size: 1rem;"></div>
                            <input type="hidden" name="description" id="description-input">
                        </div>
                    </div>
                    
                    <!-- Gallery Media Manager -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-images text-warning mr-1"></i> Project Gallery Images</h3>
                        </div>
                        <div class="card-body">
                            
                            <!-- Existing Gallery Images -->
                            <div class="mb-4">
                                <h5 class="font-weight-bold text-secondary mb-3" style="font-size: 0.95rem;">Current Gallery Images</h5>
                                <div id="existing-gallery-grid" class="row" style="gap: 12px 0;"></div>
                                <input type="hidden" name="existing_gallery" id="existing-gallery-json">
                            </div>
                            
                            <hr>
                            
                            <!-- Upload New Gallery Images -->
                            <div>
                                <h5 class="font-weight-bold text-secondary mb-3" style="font-size: 0.95rem;">Upload Additional Images</h5>
                                <div class="gallery-upload-area border d-flex flex-column align-items-center justify-content-center p-5 rounded bg-light" style="cursor: pointer; border-style: dashed !important; border-width: 2px !important; border-color: #cbd5e1 !important;">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-warning mb-2"></i>
                                    <span class="font-weight-bold">Select Gallery Files</span>
                                    <input type="file" id="gallery-input" name="gallery_images[]" multiple accept="image/*" style="display: none;">
                                </div>
                                <div id="gallery-preview-grid" class="row mt-4" style="gap: 12px 0;"></div>
                            </div>
                            
                        </div>
                    </div>

                    <!-- SEO & OG Information -->
                    <div class="card card-outline card-primary mb-4" style="border-top-color: var(--primary-green) !important;">
                        <div class="card-header bg-white">
                            <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);"><i class="fas fa-search text-warning mr-1"></i> Search Engine Optimization (SEO)</h3>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group">
                                <label for="seo_title" class="font-weight-bold text-secondary">Meta Title</label>
                                <input type="text" class="form-control" id="seo_title" name="seo_title" value="<?php echo htmlspecialchars($project['seo_title'] ?? ''); ?>" placeholder="Meta title for listings">
                            </div>
                            
                            <div class="form-group">
                                <label for="seo_desc" class="font-weight-bold text-secondary">Meta Description</label>
                                <textarea class="form-control" id="seo_desc" name="seo_desc" rows="3" placeholder="Meta description snippet"><?php echo htmlspecialchars($project['seo_desc'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="seo_keywords" class="font-weight-bold text-secondary">Meta Keywords</label>
                                <input type="text" class="form-control" id="seo_keywords" name="seo_keywords" value="<?php echo htmlspecialchars($project['seo_keywords'] ?? ''); ?>" placeholder="e.g. premium villa, luxury apartment">
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
                                Save Changes <i class="fas fa-save ml-1"></i>
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
                                <label class="font-weight-bold text-secondary d-block">Featured Image</label>
                                <div class="text-center mb-3">
                                    <img id="featured-preview" src="<?php echo !empty($project['image']) ? '../' . htmlspecialchars($project['image']) : '../assets/images/about_house_one.png'; ?>" class="img-thumbnail rounded" style="max-height: 140px; width: 100%; object-fit: cover;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="featured_image" name="featured_image" accept="image/*">
                                    <label class="custom-file-label text-left" for="featured_image">Choose Featured</label>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Banner Image -->
                            <div class="form-group">
                                <label class="font-weight-bold text-secondary d-block">Project Banner</label>
                                <div class="text-center mb-3">
                                    <img id="banner-preview" src="<?php echo !empty($project['banner']) ? '../' . htmlspecialchars($project['banner']) : '../assets/images/about_house_two.png'; ?>" class="img-thumbnail rounded" style="max-height: 100px; width: 100%; object-fit: cover;">
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="banner_image" name="banner_image" accept="image/*">
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
// Load existing elements
var amenitiesList = <?php echo $project['amenities'] ?: '[]'; ?>;
var existingGallery = <?php echo $project['gallery'] ?: '[]'; ?>;
var galleryFiles = []; // Holds new uploads file references

$(document).ready(function() {
    
    // 1. Initialize Quill Editor
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Write description details...'
    });
    
    // Pre-inject long description HTML
    quill.root.innerHTML = <?php echo json_encode($project['description'] ?? ''); ?>;
    
    // 2. Render pre-loaded components
    renderAmenities();
    renderExistingGallery();
    
    // 3. Slug Auto-generation tracker
    $('#title').on('input', function() {
        var val = $(this).val();
        var slug = val.toLowerCase()
                     .replace(/[^a-z0-9\s-]/g, '')
                     .replace(/\s+/g, '-')
                     .replace(/-+/g, '-');
        $('#slug').val(slug);
    });
    
    // 4. Cover image previews updates
    $('#featured_image').on('change', function() {
        var file = this.files[0];
        if (file) {
            $(this).next('.custom-file-label').html(file.name);
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#featured-preview').attr('src', e.target.result);
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
                $('#banner-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // 5. Wordpress-style new gallery files upload
    $('.gallery-upload-area').on('click', function() {
        $('#gallery-input').trigger('click');
    });
    
    $('#gallery-input').on('change', function() {
        var newFiles = Array.from(this.files);
        
        newFiles.forEach(function(file) {
            galleryFiles.push(file);
            
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

    // 6. Amenities Adder Controls
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

    // 7. Form Submission POST Handler
    $('#project-form').on('submit', function(e) {
        e.preventDefault();
        
        // Populate hidden description textarea
        var htmlContent = quill.getSemanticHTML();
        if (quill.getText().trim() === '') {
            Swal.fire('Content Required', 'Please add description text for the project.', 'warning');
            return;
        }
        $('#description-input').val(htmlContent);
        
        var $btn = $('#submit-btn');
        $btn.prop('disabled', true).html('Saving... <i class="fas fa-spinner fa-spin"></i>');
        
        var formData = new FormData(this);
        formData.delete('gallery_images[]');
        
        // Append actual new select files
        galleryFiles.forEach(function(file) {
            if (file !== null) {
                formData.append('gallery_images[]', file);
            }
        });
        
        $.ajax({
            url: 'edit-project.php?id=' + <?php echo $projectId; ?>,
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Saved!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#D4AF37'
                    }).then(() => {
                        window.location.href = 'projects.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Save Failed',
                        text: response.message,
                        icon: 'error',
                        confirmButtonColor: '#D4AF37'
                    });
                    $btn.prop('disabled', false).html('Save Changes <i class="fas fa-save ml-1"></i>');
                }
            },
            error: function() {
                Swal.fire({
                    title: 'System Error',
                    text: 'An error occurred during update. Check parameters.',
                    icon: 'error',
                    confirmButtonColor: '#DC3545'
                });
                $btn.prop('disabled', false).html('Save Changes <i class="fas fa-save ml-1"></i>');
            }
        });
    });
});

// Pre-fill existing gallery thumbnails
function renderExistingGallery() {
    var $grid = $('#existing-gallery-grid');
    $grid.empty();
    
    if (existingGallery.length === 0) {
        $grid.append('<div class="col-12 text-muted small italic">No gallery images uploaded.</div>');
    } else {
        existingGallery.forEach(function(imgPath, index) {
            var fileName = imgPath.split('/').pop();
            var thumbHtml = `
                <div class="col-md-3 col-sm-4 col-6" id="existing-gallery-thumb-${index}" style="position: relative;">
                    <div class="card p-1 border">
                        <img src="../${imgPath}" class="card-img-top rounded" style="height: 100px; object-fit: cover;">
                        <div class="p-1 text-truncate small">${fileName}</div>
                    </div>
                    <button type="button" class="btn btn-sm btn-danger rounded-circle" onclick="removeExistingGalleryFile(${index})" 
                            style="position: absolute; top: -5px; right: 5px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; padding: 0; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">
                        <i class="fas fa-times" style="font-size: 0.75rem;"></i>
                    </button>
                </div>
            `;
            $grid.append(thumbHtml);
        });
    }
    $('#existing-gallery-json').val(JSON.stringify(existingGallery));
}

function removeExistingGalleryFile(index) {
    existingGallery.splice(index, 1);
    renderExistingGallery();
}

// New upload remove helper
function removeGalleryFile(index) {
    galleryFiles[index] = null;
    $('#gallery-thumb-' + index).fadeOut('slow', function() {
        $(this).remove();
    });
}

// Amenities lists
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
</script>

<?php
require_once __DIR__ . '/footer.php';
?>
