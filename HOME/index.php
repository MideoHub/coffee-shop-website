<?php
session_start();
require_once '../assets/db_connect.php';

// Determine the Home link based on user role
$homeLink = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? '../admin/admin.php' : 'index.php';

// Determine login/logout link based on session
$authLinkText = isset($_SESSION['user_id']) ? 'Logout' : 'Login';
$authLinkHref = isset($_SESSION['user_id']) ? '../Logout/index.php' : '../Login/index.php';

// Determine banner text based on role
$bannerHeading = 'A Great Coffee Time';
$bannerSubheading = 'Brewing Joy and Creating Moments, One Cup at a Time';
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $bannerHeading = 'Welcome, Admin!';
    $bannerSubheading = 'Manage Your Coffee Shop with Ease';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Coffee Shop</title>
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
                </ul>
            </div>
        </div>
    </header>

    <section class="home-section">
        <div class="home-content">
            <div class="image-container">
                <img src="../assets/images/coffee-home-page.png" alt="Coffee Shop" class="home-img" />
            </div>
            <div class="home-text">
                <h1><?php echo htmlspecialchars($bannerHeading); ?></h1>
                <p><?php echo htmlspecialchars($bannerSubheading); ?></p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <p>© 2024 Coffee Shop. All rights reserved.</p>
    </footer>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>