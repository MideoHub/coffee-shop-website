<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../assets/db_connect.php';

header('Content-Type: application/json');

// Custom sanitization function to replace FILTER_SANITIZE_STRING
function sanitizeString($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON input']);
    exit;
}

$name = sanitizeString($input['name']);
$image = sanitizeString($input['image']);
$price = filter_var($input['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$session_id = session_id();
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Allow price to be 0 by explicitly checking for null or empty values
if ($name !== null && $name !== '' && $image !== null && $image !== '' && $price !== null) {
    try {
        $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, session_id, product_name, product_image, price, quantity) 
                             VALUES (?, ?, ?, ?, ?, 1)
                             ON DUPLICATE KEY UPDATE quantity = quantity + 1");
        $stmt->execute([$user_id, $session_id, $name, $image, $price]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
}
?>