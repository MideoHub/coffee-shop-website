<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../middleware/authMiddleware.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);
$user = authMiddleware();

if ($method === 'POST') {
    $name = $input['name'] ?? null;
    $email = $input['email'] ?? null;
    $date = $input['date'] ?? null;
    $time = $input['time'] ?? null;
    $persons = $input['persons'] ?? null;

    if (!$name || !$email || !$date || !$time || !$persons) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing required fields']);
        exit;
    }

    $booking = new Booking();
    if ($booking->create($user['userId'], $name, $email, $date, $time, $persons)) {
        http_response_code(201);
        echo json_encode(['message' => 'Booking created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Server error']);
    }
} elseif ($method === 'GET') {
    $booking = new Booking();
    $bookings = $booking->findByUserId($user['userId']);
    echo json_encode($bookings);
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
?>