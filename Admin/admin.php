<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login/index.php');
    exit;
}

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
    <title>Admin Dashboard - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .admin-container h1 {
            color: #4b2e1e;
        }
        .admin-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .admin-links a {
            padding: 10px 20px;
            background-color: #4b2e1e;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .admin-links a:hover {
            background-color: #6b4e3e;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">Coffee Shop</div>
        <nav class="navbar">
            <!-- <a href="<?php echo $homeLink; ?>">Home</a>
            <a href="../MENU/index.php">Menu</a>
            <a href="../PRODUCTS/index.php">Products</a>
            <a href="../Cart/index.php">Cart</a>
            -->
            <a href="<?php echo $authLinkHref; ?>" class="btn"><?php echo $authLinkText; ?></a> 
            
        <!--</nav>
        <a href="../Book/index.php" class="btn">Book a Table</a>
        <div class="menu-toggle">
            <i class="fa-solid fa-bars icon" onclick="toggleMenu()"></i>
            <div class="menu">
                <ul>
                    <li><a href="<?php echo $homeLink; ?>">Home</a></li>
                    <li><a href="../MENU/index.php">Menu</a></li>
                    <li><a href="../PRODUCTS/index.php">Products</a></li>
                    <li><a href="../Cart/index.php">Cart</a></li>
                    <li><a href="../Book/index.php">Book a Table</a></li>
                    <li><a href="<?php echo $authLinkHref; ?>"><?php echo $authLinkText; ?></a></li>
                </ul> -->
            </div>
        </div>
    </header>

    <section>
        <div class="admin-container">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?> (Admin)</h1>
            <div class="admin-links">
                <a href="manage_products.php">Manage Products</a>
                <a href="view_bookings.php">View Bookings</a>
                <!-- <a href="userinterface.php">User Interface</a> -->
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>© 2024 Coffee Shop. All rights reserved.</p>
    </footer>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>