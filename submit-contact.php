<?php
/**
 * Submit General Contact Enquiry AJAX Endpoint
 * Property Station
 */

// Tell browser we return JSON
header('Content-Type: application/json; charset=utf-8');

// Load config and DB initialization helper
require_once __DIR__ . '/config.php';

$response = [
    'success' => false,
    'message' => ''
];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    echo json_encode($response);
    exit;
}

$name             = trim($_POST['name'] ?? '');
$email            = trim($_POST['email'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$propertyInterest = trim($_POST['property_interest'] ?? '');
$message          = trim($_POST['message'] ?? '');

// Simple validations
if (empty($name) || empty($email) || empty($phone) || empty($message)) {
    $response['message'] = 'Please fill out all required fields (Name, Email, Phone, Message).';
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Invalid email address.';
    echo json_encode($response);
    exit;
}

// Enforce 10-digit Indian Mobile Number validation
$cleanPhone = preg_replace('/[^0-9]/', '', $phone);
if (strlen($cleanPhone) !== 10 || !preg_match('/^[6-9][0-9]{9}$/', $cleanPhone)) {
    $response['message'] = 'Please enter a valid 10-digit Indian phone number (e.g. 9876543210).';
    echo json_encode($response);
    exit;
}
$phone = $cleanPhone;

try {
    $db = db();
    
    // Insert contact enquiry
    $stmt = $db->prepare("INSERT INTO `contact_enquiries` 
        (`name`, `email`, `phone`, `property_interest`, `message`, `status`) 
        VALUES (?, ?, ?, ?, ?, ?)");
        
    $stmt->execute([
        $name,
        $email,
        $phone,
        !empty($propertyInterest) ? $propertyInterest : 'General Investment Enquiry',
        $message,
        'New'
    ]);
    
    $response['success'] = true;
    $response['message'] = 'Your inquiry has been received! Our specialist property advisors will contact you shortly.';
} catch (\Exception $e) {
    $response['message'] = 'Database error occurred: ' . $e->getMessage();
}

echo json_encode($response);
exit;
