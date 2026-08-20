<?php
/**
 * Admin Profile Management
 * Property Station
 */
require_once __DIR__ . '/header.php'; // Header enforces login and defines $adminUser, $db, and starts session

// 1. Handle AJAX Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Clear buffer and set JSON headers
    ob_clean();
    header('Content-Type: application/json; charset=utf-8');
    
    $action = $_POST['action'];
    $adminId = $_SESSION['admin_id'] ?? 1;
    
    if ($action === 'update_profile') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mobile = trim($_POST['mobile'] ?? '');
        
        if (empty($name) || empty($email) || empty($mobile)) {
            echo json_encode(['success' => false, 'message' => 'Please fill out all required fields (Name, Email, Mobile).']);
            exit;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success' => false, 'message' => 'Invalid email address format.']);
            exit;
        }
        
        // Handle file upload
        $profilePicPath = $adminUser['profile_pic']; // default to current
        
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
            $fileName = $_FILES['profile_pic']['name'];
            $fileSize = $_FILES['profile_pic']['size'];
            $fileType = $_FILES['profile_pic']['type'];
            
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                echo json_encode(['success' => false, 'message' => 'Invalid file extension. Only JPG, JPEG, PNG, and GIF are allowed.']);
                exit;
            }
            
            if ($fileSize > 5 * 1024 * 1024) { // 5MB limit
                echo json_encode(['success' => false, 'message' => 'File size exceeds maximum limit of 5MB.']);
                exit;
            }
            
            // Define upload path relative to admin/src/images/
            $uploadDir = __DIR__ . '/src/images/profile_picture/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $newFileName = 'profile_' . $adminId . '_' . time() . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;
            
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Delete old profile picture if it's not the default avatar
                if (!empty($adminUser['profile_pic']) && $adminUser['profile_pic'] !== 'profile_picture/default.png' && file_exists(__DIR__ . '/src/images/' . $adminUser['profile_pic'])) {
                    @unlink(__DIR__ . '/src/images/' . $adminUser['profile_pic']);
                }
                $profilePicPath = 'profile_picture/' . $newFileName;
            } else {
                echo json_encode(['success' => false, 'message' => 'Error moving uploaded profile picture. Check folder write permissions.']);
                exit;
            }
        }
        
        try {
            $stmt = $db->prepare("UPDATE admins SET name = ?, email = ?, mobile = ?, profile_pic = ? WHERE id = ?");
            $stmt->execute([$name, $email, $mobile, $profilePicPath, $adminId]);
            
            echo json_encode(['success' => true, 'message' => 'Profile details updated successfully!']);
            exit;
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
            exit;
        }
    }
    
    if ($action === 'update_password') {
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        if (empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
            echo json_encode(['success' => false, 'message' => 'Please fill out all password fields.']);
            exit;
        }
        
        if (!password_verify($oldPassword, $adminUser['password'])) {
            echo json_encode(['success' => false, 'message' => 'Incorrect current password.']);
            exit;
        }
        
        if ($newPassword !== $confirmPassword) {
            echo json_encode(['success' => false, 'message' => 'New password and confirmation password do not match.']);
            exit;
        }
        
        if (strlen($newPassword) < 6) {
            echo json_encode(['success' => false, 'message' => 'New password must be at least 6 characters long.']);
            exit;
        }
        
        try {
            $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
            $stmt = $db->prepare("UPDATE admins SET password = ? WHERE id = ?");
            $stmt->execute([$newPasswordHash, $adminId]);
            
            echo json_encode(['success' => true, 'message' => 'Password changed successfully!']);
            exit;
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
            exit;
        }
    }
}
?>

<div class="row">
    <!-- Left Card: Update Profile Details -->
    <div class="col-md-6 mb-4">
        <div class="card card-primary card-outline" style="border-top-color: var(--primary-green) !important;">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);">
                    <i class="fas fa-user-edit mr-2 text-warning"></i> Update Profile Details
                </h3>
            </div>
            <div class="card-body">
                <form id="profile-details-form" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <!-- Avatar Preview & Upload -->
                    <div class="form-group text-center mb-4">
                        <div class="mb-3">
                            <img id="avatar-preview" 
                                 src="<?php echo !empty($adminUser['profile_pic']) ? './src/images/' . htmlspecialchars($adminUser['profile_pic']) : './src/images/user-avtar.png'; ?>" 
                                 class="img-circle border" 
                                 alt="Avatar Preview" 
                                 style="width: 110px; height: 110px; object-fit: cover;">
                        </div>
                        <div class="custom-file" style="max-width: 250px; margin: 0 auto;">
                            <input type="file" class="custom-file-input" id="profile_pic" name="profile_pic" accept="image/*">
                            <label class="custom-file-label text-left" for="profile_pic">Choose image</label>
                        </div>
                        <small class="form-text text-muted mt-2">Accepted formats: JPG, PNG, GIF. Max: 5MB.</small>
                    </div>

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name" class="font-weight-bold text-secondary">Full Name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="name" name="name" required value="<?php echo htmlspecialchars($adminUser['name']); ?>" placeholder="Enter full name">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label for="email" class="font-weight-bold text-secondary">Email Address</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control" id="email" name="email" required value="<?php echo htmlspecialchars($adminUser['email']); ?>" placeholder="Enter email address">
                        </div>
                    </div>

                    <!-- Mobile -->
                    <div class="form-group">
                        <label for="mobile" class="font-weight-bold text-secondary">Mobile Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" id="mobile" name="mobile" required value="<?php echo htmlspecialchars($adminUser['mobile']); ?>" placeholder="Enter mobile number">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 font-weight-bold text-white mt-3" id="details-submit-btn" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important;">
                        Save Details <i class="fas fa-save ml-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Card: Change Password -->
    <div class="col-md-6 mb-4">
        <div class="card card-primary card-outline" style="border-top-color: var(--primary-green) !important;">
            <div class="card-header bg-white">
                <h3 class="card-title font-weight-bold" style="color: var(--sidebar-color);">
                    <i class="fas fa-key mr-2 text-warning"></i> Change Password
                </h3>
            </div>
            <div class="card-body">
                <form id="profile-password-form">
                    <input type="hidden" name="action" value="update_password">

                    <!-- Old Password -->
                    <div class="form-group">
                        <label for="old_password" class="font-weight-bold text-secondary">Current Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                            </div>
                            <input type="password" class="form-control" id="old_password" name="old_password" required placeholder="Enter current password">
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="form-group">
                        <label for="new_password" class="font-weight-bold text-secondary">New Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            </div>
                            <input type="password" class="form-control" id="new_password" name="new_password" required placeholder="Enter new password (min 6 chars)">
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label for="confirm_password" class="font-weight-bold text-secondary">Confirm New Password</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                            </div>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Re-enter new password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 font-weight-bold text-white mt-3" id="password-submit-btn" style="background-color: var(--primary-green) !important; border-color: var(--primary-green) !important;">
                        Update Password <i class="fas fa-shield-alt ml-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // 1. Live image preview and label updater
    $('#profile_pic').on('change', function() {
        var file = this.files[0];
        if (file) {
            // Update label text
            $(this).next('.custom-file-label').html(file.name);
            
            // Update preview image
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#avatar-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // 2. Submit Profile Details form via AJAX
    $('#profile-details-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $('#details-submit-btn');
        var formData = new FormData(this);
        
        $btn.prop('disabled', true).html('Saving Details... <i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: 'profile.php',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Profile Updated!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#D4AF37'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Update Failed',
                        text: response.message,
                        icon: 'error',
                        confirmButtonColor: '#D4AF37'
                    });
                    $btn.prop('disabled', false).html('Save Details <i class="fas fa-save ml-1"></i>');
                }
            },
            error: function() {
                Swal.fire({
                    title: 'System Error',
                    text: 'An error occurred while saving your details. Please check logs.',
                    icon: 'error',
                    confirmButtonColor: '#DC3545'
                });
                $btn.prop('disabled', false).html('Save Details <i class="fas fa-save ml-1"></i>');
            }
        });
    });

    // 3. Submit Password Change form via AJAX
    $('#profile-password-form').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $('#password-submit-btn');
        
        var newPass = $('#new_password').val();
        var confPass = $('#confirm_password').val();
        
        if (newPass.length < 6) {
            Swal.fire('Password Length', 'New password must be at least 6 characters long.', 'warning');
            return;
        }
        
        if (newPass !== confPass) {
            Swal.fire('Mismatch', 'New passwords do not match.', 'warning');
            return;
        }
        
        $btn.prop('disabled', true).html('Updating Password... <i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: 'profile.php',
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Password Changed!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#D4AF37'
                    }).then(() => {
                        $form[0].reset();
                        $btn.prop('disabled', false).html('Update Password <i class="fas fa-shield-alt ml-1"></i>');
                    });
                } else {
                    Swal.fire({
                        title: 'Change Failed',
                        text: response.message,
                        icon: 'error',
                        confirmButtonColor: '#D4AF37'
                    });
                    $btn.prop('disabled', false).html('Update Password <i class="fas fa-shield-alt ml-1"></i>');
                }
            },
            error: function() {
                Swal.fire({
                    title: 'System Error',
                    text: 'An error occurred while updating your password. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#DC3545'
                });
                $btn.prop('disabled', false).html('Update Password <i class="fas fa-shield-alt ml-1"></i>');
            }
        });
    });
});
</script>

<?php
require_once __DIR__ . '/footer.php';
?>