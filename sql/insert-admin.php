<?php
/**
 * Create/Ensure Default Admin User seeder
 * Property Station
 */
require_once __DIR__ . '/../config.php';

try {
    $db = db();
    
    $name = 'Administrator';
    $username = 'admin';
    $password = 'admin123';
    $email = 'info@mypropertystation.in';
    $profile_pic = 'profile_picture/default.png'; // default avatar path relative to admin/src/images/
    $mobile = '+91 80008 10016';
    
    // Hash password using BCRYPT
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    
    // Check if username already exists
    $stmt = $db->prepare("SELECT id FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();
    
    if ($admin) {
        // Update existing admin
        $update = $db->prepare("UPDATE admins SET name = ?, password = ?, email = ?, profile_pic = ?, mobile = ? WHERE id = ?");
        $update->execute([$name, $passwordHash, $email, $profile_pic, $mobile, $admin['id']]);
        $message = "Default Admin user was updated successfully.";
    } else {
        // Insert new admin
        $insert = $db->prepare("INSERT INTO admins (name, username, password, email, profile_pic, mobile) VALUES (?, ?, ?, ?, ?, ?)");
        $insert->execute([$name, $username, $passwordHash, $email, $profile_pic, $mobile]);
        $message = "Default Admin user was created successfully.";
    }
    
    $success = true;
} catch (\Exception $e) {
    $success = false;
    $message = "Database connection or execution failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Seeder | Property Station</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8F9FA;
            color: #0A0A0C;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background: #FFFFFF;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.08);
            text-align: center;
            max-width: 450px;
            width: 100%;
        }
        .logo-img {
            max-height: 50px;
            width: auto;
            margin-bottom: 2rem;
        }
        h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 0 1rem 0;
            color: <?php echo $success ? '#D4AF37' : '#DC3545'; ?>;
        }
        p {
            color: #66666D;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }
        .details {
            text-align: left;
            background: #F8F9FA;
            padding: 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            font-family: monospace;
            border: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
            line-height: 1.5;
        }
        .btn {
            display: inline-block;
            background-color: #D4AF37;
            color: #FFFFFF;
            padding: 0.85rem 1.75rem;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-family: 'Outfit', sans-serif;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #B89324;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="../assets/logo/logo.jpeg" alt="Property Station Logo" class="logo-img" onerror="this.style.display='none';">
        <h2><?php echo $success ? 'Execution Successful' : 'Execution Failed'; ?></h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <?php if ($success): ?>
        <div class="details">
            <strong>Name:</strong> <?php echo htmlspecialchars($name); ?><br>
            <strong>Username:</strong> <?php echo htmlspecialchars($username); ?><br>
            <strong>Password:</strong> admin123<br>
            <strong>Email:</strong> <?php echo htmlspecialchars($email); ?><br>
            <strong>Profile Pic:</strong> <?php echo htmlspecialchars($profile_pic); ?><br>
            <strong>Mobile:</strong> <?php echo htmlspecialchars($mobile); ?>
        </div>
        <a href="../admin/login.php" class="btn">Go to Login Page</a>
        <?php endif; ?>
    </div>
</body>
</html>
