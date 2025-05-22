<?php
require_once '../assets/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login/index.php');
    exit;
}

// Fetch all bookings
$stmt = $pdo->query("SELECT b.*, u.name as user_name FROM bookings b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC");
$bookings = $stmt->fetchAll();

// Home link for admins
$homeLink = 'admin.php';

// Auth link (always Logout for admins)
$authLinkText = 'Logout';
$authLinkHref = '../Logout/index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>View Bookings - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .bookings-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .bookings-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .bookings-list th, .bookings-list td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .bookings-list th {
            background-color: #4b2e1e;
            color: white;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">Coffee Shop</div>
        <nav class="navbar">
            <a href="<?php echo $homeLink; ?>">Home</a>
            <a href="<?php echo $authLinkHref; ?>" class="btn"><?php echo $authLinkText; ?></a>
        </nav>
        <!-- <a href="../Book/index.php" class="btn">Book a Table</a> -->
    </header>

    <section>
        <div class="bookings-container">
            <h1>Table Bookings</h1>
            <div class="bookings-list">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Persons</th>
                            <th>Booked On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['id'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($booking['user_name'] ?? 'Guest'); ?></td>
                                <td><?php echo htmlspecialchars($booking['name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($booking['email'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_date'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_time'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($booking['persons'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($booking['created_at'] ?? ''); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>© 2024 Coffee Shop. All rights reserved.</p>
    </footer>
</body>
</html>