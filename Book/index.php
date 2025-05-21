<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_log('Script started at ' . date('Y-m-d H:i:s'));

session_start();
error_log('Session data: ' . print_r($_SESSION, true));

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    header('Content-Type: application/json');

    if (!$pdo) {
        error_log('Database connection failed at ' . date('Y-m-d H:i:s'));
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
    $persons = filter_input(INPUT_POST, 'persons', FILTER_SANITIZE_NUMBER_INT);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    error_log('User ID: ' . ($user_id ? $user_id : 'Not set'));
    error_log("Form data at " . date('Y-m-d H:i:s') . ": name=$name, email=$email, date=$date, time=$time, persons=$persons, user_id=$user_id");

    if ($user_id === null) {
        echo json_encode(['success' => false, 'message' => 'User session not found.']);
        exit;
    }

    if ($name && $email && $date && $time && $persons) {
        try {
            $currentDate = date('Y-m-d');
            if (strtotime($date) < strtotime($currentDate)) {
                echo json_encode(['success' => false, 'message' => 'Cannot book for a past date.']);
                exit;
            }

            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, name, email, booking_date, booking_time, persons) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $email, $date, $time, $persons]);
            echo json_encode(['success' => true, 'message' => 'Booking successful!']);
        } catch (PDOException $e) {
            error_log('Database error at ' . date('Y-m-d H:i:s') . ': ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please fill all fields.']);
    }
    exit;
}

$homeLink = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? '../admin/admin.php' : '../HOME/index.php';
$authLinkText = 'Logout';
$authLinkHref = '../Logout/index.php';
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
    <!-- Rest of the HTML remains the same as previously provided -->
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
            const today = new Date().toISOString().split('T')[0];
            const messageDiv = document.getElementById('formMessage');

            if (dateInput < today) {
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
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.text(); // Get raw response
            })
            .then(text => {
                console.log('Raw server response:', text); // Log raw response
                const data = JSON.parse(text); // Try to parse
                messageDiv.innerHTML = `<div style="color: ${data.success ? 'green' : 'red'};">${data.message}</div>`;
                if (data.success) {
                    this.reset();
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                messageDiv.innerHTML = '<div style="color: red;">An error occurred: ' + error.message + '</div>';
            });
        });
    </script>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>