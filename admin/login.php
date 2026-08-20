<?php
/**
 * Standalone Admin Login Page
 * Property Station
 */
require_once __DIR__ . '/../config.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

// Handle AJAX Post Authentication
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill in both Username and Password.']);
        exit;
    }
    
    try {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($admin && password_verify($password, $admin['password'])) {
            // Set session variables
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            
            echo json_encode(['success' => true, 'message' => 'Authentication successful! Redirecting...']);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
            exit;
        }
    } catch (\Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error occurred: ' . $e->getMessage()]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Property Station</title>
    <link rel="icon" href="../favicon.png" type="image/png">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;700;800&display=swap" rel="stylesheet">
    
    <!-- CDNs -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --color-bg-dark: #FFFFFF;
            --color-bg-light: #F8F9FA;
            --color-accent: #D4AF37;
            --color-accent-hover: #B89324;
            --color-text-light: #0A0A0C;
            --color-text-muted: #66666D;
            --color-border-dark: rgba(0, 0, 0, 0.08);
            --font-heading: 'Outfit', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--color-bg-light);
            font-family: var(--font-body);
            color: var(--color-text-light);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 1rem;
        }

        .login-box {
            width: 100%;
            max-width: 420px;
        }

        .login-card {
            background-color: #FFFFFF;
            border: 1px solid var(--color-border-dark);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.04);
            padding: 3rem 2.5rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: var(--color-accent);
        }

        .login-logo {
            display: block;
            margin: 0 auto 1.5rem auto;
            max-height: 52px;
            width: auto;
            object-fit: contain;
        }

        .login-title {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--color-text-light);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .login-subtitle {
            font-size: 0.88rem;
            color: var(--color-text-muted);
            margin-bottom: 2.5rem;
        }

        .form-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-family: var(--font-heading);
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--color-text-muted);
            letter-spacing: 1.5px;
            margin-bottom: 0.5rem;
            display: block;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--color-text-muted);
            opacity: 0.7;
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 1rem 0.85rem 2.75rem;
            background-color: #FFFFFF;
            border: 1.5px solid #cbd5e1; /* Well-defined and visible border */
            border-radius: 6px;
            color: var(--color-text-light);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3.5px rgba(212, 175, 55, 0.15); /* Themed focus glow */
            outline: none;
        }

        .toggle-password {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--color-text-muted);
            opacity: 0.7;
            z-index: 10;
            font-size: 0.95rem;
            transition: all 0.2s ease;
        }

        .toggle-password:hover {
            color: var(--color-accent) !important;
            opacity: 1 !important;
        }

        .btn-login {
            background-color: var(--color-accent);
            color: #FFFFFF;
            font-family: var(--font-heading);
            font-weight: 600;
            padding: 1rem;
            width: 100%;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-login:hover {
            background-color: var(--color-accent-hover);
            transform: translateY(-2px);
        }

        .btn-login:disabled {
            background-color: var(--color-text-muted);
            cursor: not-allowed;
            transform: none;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="login-card">
            <!-- Brand Logo -->
            <img src="../assets/logo/logo.jpeg" alt="Property Station Logo" class="login-logo">
            
            <h1 class="login-title">Control Panel</h1>
            <p class="login-subtitle">Sign in to manage your real estate portal</p>
            
            <form id="admin-login-form">
                <!-- Username -->
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                        <input type="text" id="username" name="username" required placeholder="Enter username" class="form-control" autocomplete="username">
                    </div>
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                        <input type="password" id="password" name="password" required placeholder="Enter password" class="form-control" autocomplete="current-password" style="padding-right: 2.75rem;">
                        <span class="toggle-password" id="toggle-password-btn"><i class="fa-solid fa-eye"></i></span>
                    </div>
                </div>
                
                <!-- Submit -->
                <button type="submit" class="btn-login" id="submit-btn">
                    Sign In <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Toggle Password Visibility
        $('#toggle-password-btn').on('click', function() {
            var $input = $('#password');
            var isPassword = $input.attr('type') === 'password';
            $input.attr('type', isPassword ? 'text' : 'password');
            $(this).find('i').toggleClass('fa-eye fa-eye-slash');
        });

        $('#admin-login-form').on('submit', function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $btn = $('#submit-btn');
            
            $btn.prop('disabled', true).html('Signing In... <i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: 'login.php',
                type: 'POST',
                data: $form.serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Access Granted!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'index.php';
                        });
                    } else {
                        Swal.fire({
                            title: 'Access Denied',
                            text: response.message || 'Invalid login credentials.',
                            icon: 'error',
                            confirmButtonColor: '#D4AF37'
                        });
                        $btn.prop('disabled', false).html('Sign In <i class="fa-solid fa-arrow-right-to-bracket"></i>');
                    }
                },
                error: function() {
                    Swal.fire({
                        title: 'System Error',
                        text: 'An error occurred during authentication. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#DC3545'
                    });
                    $btn.prop('disabled', false).html('Sign In <i class="fa-solid fa-arrow-right-to-bracket"></i>');
                }
            });
        });
    });
    </script>
</body>
</html>
