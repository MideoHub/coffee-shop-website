<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../assets/db_connect.php';

// header('Content-Type: application/json'); // Ensure JSON response for AJAX

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);
    $persons = filter_input(INPUT_POST, 'persons', FILTER_SANITIZE_NUMBER_INT);
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if (!$pdo) {
        echo json_encode(['success' => false, 'message' => 'Database connection failed']);
        exit;
    }

    if ($name && $email && $date && $time && $persons) {
        try {
            $stmt = $pdo->prepare("INSERT INTO bookings (user_id, name, email, booking_date, booking_time, persons) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $name, $email, $date, $time, $persons]);
            echo json_encode(['success' => true, 'message' => 'Booking successful!']);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Please fill all fields.']);
    }
    exit; // Stop further processing
}
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
            <a href="../HOME/index.php">Home</a>
            <a href="../MENU/index.php">Menu</a>
            <a href="../PRODUCTS/index.php">Products</a>
            <a href="../Cart/index.php">Cart</a>
        </nav>
        <a href="index.php" class="btn">Book a Table</a>
        <div class="menu-toggle">
            <i class="fa-solid fa-bars icon" onclick="toggleMenu()"></i>
            <div class="menu">
                <ul>
                    <li><a href="../HOME/index.php">Home</a></li>
                    <li><a href="../MENU/index.php">Menu</a></li>
                    <li><a href="../PRODUCTS/index.php">Products</a></li>
                    <li><a href="../Cart/index.php">Cart</a></li>
                    <li><a href="index.php">Book a Table</a></li>
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
                                    <input type="date" name="date" class="input" placeholder="Date" required />
                                </div>
                                <div class="form-group">
                                    <input type="time" name="time" class="input" placeholder="Time" required />
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>© 2024 Coffee Shop. All rights reserved.</p>
    </footer>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>