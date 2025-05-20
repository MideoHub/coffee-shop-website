<?php
require_once __DIR__ . '/vendor/autoload.php';

header('Access-Control-Allow-Origin: http://localhost:5500');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if (isset($uri[1]) && $uri[1] === 'api') {
    if (isset($uri[2])) {
        if ($uri[2] === 'auth' && isset($uri[3])) {
            require_once __DIR__ . '/routes/auth.php';
        } elseif ($uri[2] === 'bookings') {
            require_once __DIR__ . '/routes/bookings.php';
        } elseif ($uri[2] === 'cart') {
            require_once __DIR__ . '/routes/cart.php';
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Not found']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Not found']);
    }
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Not found']);
}
?>