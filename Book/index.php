<?php
ob_start(); // Start output buffering
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=coffee_shop", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log('Connection failed: ' . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}

ini_set('display_errors', 0); // Disable error display for production
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
error_log('Script started at ' . date('Y-m-d H:i:s'));

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['user', 'admin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in.']);
        exit;
    } else {
        header('Location: ../Login/index.php');
        exit;
    }
}

require_once '../assets/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    ob_clean(); // Clear any previous output
    header('Content-Type: application/json');

    error_log('CSRF Token - Session: ' . ($_SESSION['csrf_token'] ?? 'Not set'));
    error_log('CSRF Token - POST: ' . ($_POST['csrf_token'] ?? 'Not set'));

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token.']);
        exit;
    }

    if (!$pdo) {
        error_log('Database connection failed at ' . date('Y-m-d H:i:s'));
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_SPECIAL_CHARS);
    $persons = filter_input(INPUT_POST, 'persons', FILTER_SANITIZE_NUMBER_INT);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    error_log('Booking attempt from IP ' . $_SERVER['REMOTE_ADDR'] . ' at ' . date('Y-m-d H:i:s') . ': ' . print_r($_POST, true));
    error_log('User ID: ' . ($user_id ? $user_id : 'Not set'));
    error_log("Form data: name=$name, email=$email, date=$date, time=$time, persons=$persons, user_id=$user_id");

    if ($user_id === null) {
        echo json_encode(['success' => false, 'message' => 'User session not found.']);
        exit;
    }

    if ($name && $email && $date && $time && $persons) {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log('Invalid email format: ' . $email);
            echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
            exit;
        }
        // Validate date format (YYYY-MM-DD)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !strtotime($date)) {
            error_log('Invalid date format: ' . $date);
            echo json_encode(['success' => false, 'message' => 'Invalid date format.']);
            exit;
        }
        // Validate time format (HH:MM)
        if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', $time)) {
            error_log('Invalid time format: ' . $time);
            echo json_encode(['success' => false, 'message' => 'Invalid time format.']);
            exit;
        }
        // Validate persons
        if ($persons < 1 || $persons > 10) {
            error_log('Invalid number of persons: ' . $persons);
            echo json_encode(['success' => false, 'message' => 'Number of persons must be between 1 and 10.']);
            exit;
        }
        // Check past date
        $currentDate = date('Y-m-d');
        if (strtotime($date) < strtotime($currentDate)) {
            error_log('Attempt to book past date: ' . $date);
            echo json_encode(['success' => false, 'message' => 'Cannot book for a past date.']);
            exit;
        }
        // Check for booking conflicts
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM bookings WHERE booking_date = ? AND booking_time = ?");
            $stmt->execute([$date, $time]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result['count'] >= 10) {
                error_log('Time slot fully booked: ' . $date . ' ' . $time);
                echo json_encode(['success' => false, 'message' => 'This time slot is fully booked.']);
                exit;
            }
        } catch (PDOException $e) {
            error_log('Booking conflict check failed at ' . date('Y-m-d H:i:s') . ': ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred while checking availability.']);
            exit;
        }
        // Insert booking
        try {
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, name, email, booking_date, booking_time, persons) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $email, $date, $time, $persons]);
            $rowsAffected = $stmt->rowCount();
            error_log('Rows affected by INSERT: ' . $rowsAffected);
            if ($rowsAffected === 0) {
                echo json_encode(['success' => false, 'message' => 'Booking failed: No rows inserted.']);
                exit;
            }
            echo json_encode(['success' => true, 'message' => 'Booking successful!']);
        } catch (PDOException $e) {
            error_log('Database error at ' . date('Y-m-d H:i:s') . ': ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'An error occurred while processing your booking.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please fill all fields.']);
    }
    exit;
}

$homeLink = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? '../admin/admin.php' : '../HOME/index.php';
$authLinkText = 'Logout';
$authLinkHref = '../Logout/index.php';
ob_end_flush(); // Flush output buffer
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Book - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <header class="header">
        <div class="logo">Coffee Shop</div>
        <nav class="navbar">
            <a href="<?php echo $homeLink; ?>">Home</a>
            <a href="../MENU/index.php">Menu</a>
            <a href="../PRODUCTS/index.php">Products</a>
            <a href="../Cart/index.php">Cart</a>
            <a href="<?php echo $authLinkHref; ?>" class="btn"><?php echo $authLinkText; ?></a>
        </nav>
        <div class="menu-toggle">
            <i class="fa-solid fa-bars icon" onclick="toggleMenu()"></i>
            <div class="menu">
                <ul>
                    <li><a href="<?php echo $homeLink; ?>">Home</a></li>
                    <li><a href="../MENU/index.php">Menu</a></li>
                    <li><a href="../PRODUCTS/index.php">Products</a></li>
                    <li><a href="../Cart/index.php">Cart</a></li>
                    <li><a href="index.php">Book a Table</a></li>
                    <li><a href="<?php echo $authLinkHref; ?>"><?php echo $authLinkText; ?></a></li>
                </ul>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="section-container">
            <div class="background">
                <div class="row-container">
                    <div class="card offer-card">
                        <div class="text-container">
                            <h1 class="heading">30% OFF</h1>
                            <h1 class="subheading">For Online Booking</h1>
                        </div>
                        <p class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <ul class="checklist">
                            <li class="item"><i class="fa fa-check icon"></i>Complimentary Welcome Drink</li>
                            <li class="item"><i class="fa fa-check icon"></i>Priority Seating</li>
                            <li class="item"><i class="fa fa-check icon"></i>Loyalty Points</li>
                        </ul>
                    </div>
                    <div class="booking-form-card">
                        <div class="form-container">
                            <h1 class="heading-form">Book Your Table</h1>
                            <form id="bookingForm" class="form" method="POST">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <div class="form-group">
                                    <input type="text" name="name" class="input" placeholder="Name" required />
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="input" placeholder="Email" required />
                                </div>
                                <div class="form-group">
                                    <input type="date" name="date" class="input" required />
                                </div>
                                <div class="form-group">
                                    <input type="time" name="time" class="input" required />
                                </div>
                                <div class="form-group">
                                    <select name="persons" class="input" style="height: 49px;" required>
                                        <option value="" disabled selected>No. Person</option>
                                        <option value="1">Person 1</option>
                                        <option value="2">Person 2</option>
                                        <option value="3">Person 3</option>
                                        <option value="4">Person 4</option>
                                    </select>
                                </div>
                                <div>
                                    <button class="btn-form" type="submit">Book Now</button>
                                </div>
                            </form>
                            <div id="formMessage" style="margin-top: 10px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>© 2024 Coffee Shop. All rights reserved.</p>
    </footer>
    <script>
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const dateInput = this.querySelector('input[name="date"]').value;
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const selectedDate = new Date(dateInput);
            const messageDiv = document.getElementById('formMessage');

            if (selectedDate < today) {
                messageDiv.innerHTML = '<div style="color: red;">Cannot book for a past date.</div>';
                return;
            }

            const formData = new FormData(this);

            fetch('index.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error('Network response was not ok: ' + text);
                    });
                }
                return response.text();
            })
            .then(text => {
                console.log('Raw server response:', text);
                try {
                    const data = JSON.parse(text);
                    messageDiv.innerHTML = `<div style="color: ${data.success ? 'green' : 'red'};">${data.message}</div>`;
                    if (data.success) {
                        messageDiv.innerHTML = '<div style="color: green;">Booking successful! Redirecting...</div>';
                        setTimeout(() => {
                            this.reset();
                            window.location.href = '../HOME/index.php';
                        }, 2000);
                    }
                } catch (e) {
                    console.error('JSON parse error:', e);
                    messageDiv.innerHTML = '<div style="color: red;">An error occurred: Invalid server response. Raw response: ' + text + '</div>';
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                messageDiv.innerHTML = '<div style="color: red;">An error occurred: ' + error.message + '</div>';
            });
        });
    </script>
    <script src="/coffee-shop-website/assets/js/scripts.js"></script>
</body>
</html>