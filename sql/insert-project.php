<?php
/**
 * Safe Database Migration for Projects Table
 * Property Station
 */
require_once __DIR__ . '/../config.php';

try {
    $db = db();
    
    // Check existing columns of 'projects' table
    $stmt = $db->query("DESCRIBE `projects`");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Map new columns and their SQL definitions
    $newColumns = [
        'short_desc' => 'TEXT NULL',
        'description' => 'LONGTEXT NULL',
        'banner' => 'VARCHAR(255) NULL',
        'gallery' => 'TEXT NULL',
        'price_range' => 'VARCHAR(100) NULL',
        'amenities' => 'TEXT NULL',
        'seo_title' => 'VARCHAR(255) NULL',
        'seo_desc' => 'TEXT NULL',
        'seo_keywords' => 'TEXT NULL',
        'seo_schema' => 'LONGTEXT NULL',
        'og_info' => 'TEXT NULL'
    ];
    
    $addedColumns = [];
    foreach ($newColumns as $colName => $colDef) {
        if (!in_array($colName, $columns)) {
            $db->exec("ALTER TABLE `projects` ADD `$colName` $colDef");
            $addedColumns[] = $colName;
        }
    }
    
    if (empty($addedColumns)) {
        $message = "Database migration check complete. All projects columns are already present and verified.";
    } else {
        $message = "Database migration executed successfully. Added columns: " . implode(', ', $addedColumns);
    }
    
    $success = true;
} catch (\Exception $e) {
    $success = false;
    $message = "Database migration execution failed: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects Migration Check | Property Station</title>
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
            padding: 1rem;
        }
        .card {
            background: #FFFFFF;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 0, 0, 0.08);
            text-align: center;
            max-width: 500px;
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
            font-size: 0.95rem;
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
        <h2><?php echo $success ? 'Migration Successful' : 'Migration Failed'; ?></h2>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="../admin/projects.php" class="btn">Go to Projects Board</a>
    </div>
</body>
</html>
