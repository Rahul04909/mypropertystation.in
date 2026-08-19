<?php
/**
 * Central Configuration and Environment Initializer
 * Property Station
 */

// Secure session configuration
if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
    if ($isSecure) {
        ini_set('session.cookie_secure', 1);
    }
    session_start();
}

// Load Composer Autoloader
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Load Environment Variables (.env)
if (file_exists(__DIR__ . '/.env')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
    } catch (Exception $e) {
        // Fallback silently if .env is malformed
    }
}

// Define Global env() Helper Function
if (!function_exists('env')) {
    /**
     * Get the value of an environment variable or return default.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = '') {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        }
        $val = getenv($key);
        return $val !== false ? $val : $default;
    }
}

if (!function_exists('db')) {
    /**
     * Get the PDO database connection instance.
     *
     * @return PDO
     */
    function db() {
        static $pdo = null;
        if ($pdo === null) {
            $host = env('DB_HOST', 'localhost');
            $port = env('DB_PORT', '3306');
            $db   = env('DB_DATABASE', 'mypropertystation');
            $user = env('DB_USERNAME', 'root');
            $pass = env('DB_PASSWORD', '');
            $charset = 'utf8mb4';

            // Connect to MySQL server first to check/create database
            $dsnNoDb = "mysql:host=$host;port=$port;charset=$charset";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            
            try {
                $tempPdo = new PDO($dsnNoDb, $user, $pass, $options);
                $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $tempPdo = null;
            } catch (\Exception $e) {
                // Ignore fallback to let the main DSN connection throw if privileges are insufficient
            }

            // Connect to database
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
            try {
                $pdo = new PDO($dsn, $user, $pass, $options);
                
                // Create Admins Table
                $pdo->exec("CREATE TABLE IF NOT EXISTS `admins` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(100) NOT NULL,
                    `username` VARCHAR(50) NOT NULL UNIQUE,
                    `password` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(100) NOT NULL UNIQUE,
                    `profile_pic` VARCHAR(255) DEFAULT NULL,
                    `mobile` VARCHAR(20) DEFAULT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Create Contact Enquiries Table
                $pdo->exec("CREATE TABLE IF NOT EXISTS `contact_enquiries` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `name` VARCHAR(100) NOT NULL,
                    `email` VARCHAR(100) NOT NULL,
                    `phone` VARCHAR(20) NOT NULL,
                    `property_interest` VARCHAR(100) DEFAULT NULL,
                    `message` TEXT NOT NULL,
                    `status` VARCHAR(20) DEFAULT 'New',
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Create Projects Table
                $pdo->exec("CREATE TABLE IF NOT EXISTS `projects` (
                    `id` INT AUTO_INCREMENT PRIMARY KEY,
                    `slug` VARCHAR(100) NOT NULL UNIQUE,
                    `title` VARCHAR(100) NOT NULL,
                    `location` VARCHAR(255) NOT NULL,
                    `image` VARCHAR(255) NOT NULL,
                    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

                // Seed default admin
                $stmt = $pdo->query("SELECT COUNT(*) FROM `admins`");
                if ($stmt->fetchColumn() == 0) {
                    $defaultName = 'Administrator';
                    $defaultUser = 'admin';
                    $defaultHash = password_hash('admin123', PASSWORD_BCRYPT);
                    $defaultEmail = 'admin@mypropertystation.in';
                    $defaultPic = 'agent_portrait.png';
                    $defaultMobile = '+919876543210';
                    
                    $insert = $pdo->prepare("INSERT INTO `admins` (`name`, `username`, `password`, `email`, `profile_pic`, `mobile`) VALUES (?, ?, ?, ?, ?, ?)");
                    $insert->execute([$defaultName, $defaultUser, $defaultHash, $defaultEmail, $defaultPic, $defaultMobile]);
                }
            } catch (\PDOException $e) {
                throw new \PDOException("Database connection error: " . $e->getMessage(), (int)$e->getCode());
            }
        }
        return $pdo;
    }
}
