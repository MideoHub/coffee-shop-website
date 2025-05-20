<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../models/User.php';

use Firebase\JWT\JWT;

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

if ($method === 'POST' && isset($_GET['action'])) {
    $user = new User();

    if ($_GET['action'] === 'signup') {
        $name = $input['name'] ?? null;
        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;

        if (!$name || !$email || !$password) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing required fields']);
            exit;
        }

        if ($user->findByEmail($email)) {
            http_response_code(400);
            echo json_encode(['message' => 'User already exists']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        if ($user->create($name, $email, $hashedPassword)) {
            $userData = $user->findByEmail($email);
            $payload = ['userId' => $userData['id']];
            $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
            http_response_code(201);
            echo json_encode([
                'token' => $token,
                'user' => ['id' => $userData['id'], 'name' => $name, 'email' => $email]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Server error']);
        }
    } elseif ($_GET['action'] === 'login') {
        $email = $input['email'] ?? null;
        $password = $input['password'] ?? null;

        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(['message' => 'Missing required fields']);
            exit;
        }

        $userData = $user->findByEmail($email);
        if (!$userData || !password_verify($password, $userData['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid credentials']);
            exit;
        }

        $payload = ['userId' => $userData['id']];
        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');
        echo json_encode([
            'token' => $token,
            'user' => ['id' => $userData['id'], 'name' => $userData['name'], 'email' => $email]
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}
?>