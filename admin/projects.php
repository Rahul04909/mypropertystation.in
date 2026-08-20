<?php
/**
 * Admin Projects Management Listing
 * Property Station
 */
require_once __DIR__ . '/header.php'; // Header handles sessions and database connection ($db)

// 1. Handle AJAX Delete Request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_project') {
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    
    $projectId = intval($_POST['id'] ?? 0);
    
    try {
        // Fetch project details to delete images from storage
        $stmt = $db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$projectId]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($project) {
            // Delete featured image
            if (!empty($project['image']) && file_exists(__DIR__ . '/../' . $project['image'])) {
                @unlink(__DIR__ . '/../' . $project['image']);
            }
            // Delete banner
            if (!empty($project['banner']) && file_exists(__DIR__ . '/../' . $project['banner'])) {
                @unlink(__DIR__ . '/../' . $project['banner']);
            }
            // Delete gallery images
            if (!empty($project['gallery'])) {
                $gallery = json_decode($project['gallery'], true);
                if (is_array($gallery)) {
                    foreach ($gallery as $img) {
                        if (!empty($img) && file_exists(__DIR__ . '/../' . $img)) {
                            @unlink(__DIR__ . '/../' . $img);
                        }
                    }
                }
            }
            
            // Delete from database
            $delete = $db->prepare("DELETE FROM projects WHERE id = ?");
            $delete->execute([$projectId]);
            
            echo json_encode(['success' => true, 'message' => 'Project and its associated images deleted successfully.']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Project not found in the database.']);
            exit;
        }
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error deleting project: ' . $e->getMessage()]);
        exit;
    }
}

// 2. Fetch Projects
try {
    $stmt = $db->query("SELECT * FROM projects ORDER BY id DESC");
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\Exception $e) {
    $projects = [];
}
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <p class="text-muted">Manage, edit, or delete existing property projects listed on the site.</p>
        </div>
        <a href="add-project.php" class="btn btn-warning text-white font-weight-bold" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important;">
            <i class="fas fa-plus mr-1"></i> Add New Project
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-outline card-primary" style="border-top-color: var(--primary-green) !important;">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);">
                    <i class="fas fa-building mr-2 text-warning"></i> Active Property Projects (<?php echo count($projects); ?>)
                </h3>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-striped mb-0 vertical-align-middle" style="vertical-align: middle;">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 80px;">Thumbnail</th>
                            <th>Project Title</th>
                            <th>Location</th>
                            <th>Price Range</th>
                            <th>Amenities</th>
                            <th>Date Created</th>
                            <th class="text-right" style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($projects)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3" style="opacity: 0.3;"></i>
                                <p class="mb-0 font-weight-bold">No projects found. Add your first project!</p>
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($projects as $project): ?>
                            <?php 
                            $amenitiesList = json_decode($project['amenities'] ?? '[]', true); 
                            $featuredImg = !empty($project['image']) ? '../' . htmlspecialchars($project['image']) : '../assets/images/about_house_one.png';
                            ?>
                            <tr id="project-row-<?php echo $project['id']; ?>">
                                <td>
                                    <img src="<?php echo $featuredImg; ?>" 
                                         alt="Thumbnail" 
                                         class="img-thumbnail rounded"
                                         style="width: 60px; height: 45px; object-fit: cover;">
                                </td>
                                <td>
                                    <div class="font-weight-bold"><?php echo htmlspecialchars($project['title']); ?></div>
                                    <small class="text-muted">Slug: <?php echo htmlspecialchars($project['slug']); ?></small>
                                </td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger mr-1" style="font-size: 0.85rem;"></i> 
                                    <?php echo htmlspecialchars($project['location']); ?>
                                </td>
                                <td>
                                    <span class="badge badge-success px-2 py-1" style="background-color: rgba(40, 167, 69, 0.1); color: #28a745; font-size: 0.85rem;">
                                        <?php echo !empty($project['price_range']) ? htmlspecialchars($project['price_range']) : 'N/A'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (empty($amenitiesList)): ?>
                                        <small class="text-muted">None</small>
                                    <?php else: ?>
                                        <div class="d-flex flex-wrap gap-1" style="gap: 4px;">
                                            <?php 
                                            $count = 0;
                                            foreach ($amenitiesList as $amenity): 
                                                if ($count >= 3) {
                                                    echo '<span class="badge badge-secondary font-weight-normal">+' . (count($amenitiesList) - 3) . '</span>';
                                                    break;
                                                }
                                                echo '<span class="badge badge-light border font-weight-normal">' . htmlspecialchars($amenity) . '</span>';
                                                $count++;
                                            endforeach; 
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo date('M d, Y', strtotime($project['created_at'])); ?>
                                </td>
                                <td class="text-right">
                                    <a href="edit-project.php?id=<?php echo $project['id']; ?>" class="btn btn-sm btn-info text-white mr-1" title="Edit details">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-danger" onclick="deleteProject(<?php echo $project['id']; ?>, '<?php echo htmlspecialchars(addslashes($project['title'])); ?>')" title="Delete listing">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function deleteProject(projectId, projectTitle) {
    Swal.fire({
        title: 'Delete Project?',
        text: 'Are you sure you want to delete "' + projectTitle + '"? This will permanently delete the project and all its uploaded gallery images from the server.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'projects.php',
                type: 'POST',
                data: {
                    action: 'delete_project',
                    id: projectId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#D4AF37'
                        }).then(() => {
                            $('#project-row-' + projectId).fadeOut('slow', function() {
                                $(this).remove();
                                if ($('tbody tr').length === 0) {
                                    window.location.reload();
                                }
                            });
                        });
                    } else {
                        Swal.fire({
                            title: 'Action Failed',
                            text: response.message,
                            icon: 'error',
                            confirmButtonColor: '#D4AF37'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'System Error',
                        text: 'An error occurred while deleting the project. Please check database permissions.',
                        icon: 'error',
                        confirmButtonColor: '#DC3545'
                    });
                }
            });
        }
    });
}
</script>

<?php
require_once __DIR__ . '/footer.php';
?>
