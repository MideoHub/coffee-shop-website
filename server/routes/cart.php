<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../middleware/authMiddleware.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$user = authMiddleware();

$cart = new Cart();

if ($method === 'POST') {
    $name = $input['name'] ?? null;
    $quantity = $input['quantity'] ?? 1;

    if (!$name) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing required fields']);
        exit;
    }

    $product = $cart->findProductByName($name);
    if (!$product) {
        http_response_code(404);
        echo json_encode(['message' => 'Product not found']);
        exit;
    }

    if ($cart->addItem($user['userId'], $product['id'], $quantity)) {
        http_response_code(201);
        echo json_encode(['message' => 'Item added to cart']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Server error']);
    }
} elseif ($method === 'GET') {
    $items = $cart->getItems($user['userId']);
    echo json_encode($items);
} elseif ($method === 'DELETE') {
    if ($cart->clearCart($user['userId'])) {
        echo json_encode(['message' => 'Cart cleared']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Server error']);
    }
} elseif ($method === 'POST' && isset($_GET['action']) && $_GET['action'] === 'checkout') {
    if ($cart->clearCart($user['userId'])) {
        echo json_encode(['message' => 'Checkout successful']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Server error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
?>