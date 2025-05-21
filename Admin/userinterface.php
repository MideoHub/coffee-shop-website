<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login/index.php');
    exit;
}

// Determine the Home link for admins
$homeLink = '../admin/admin.php';

// Auth link (should always be Logout for admins since they're logged in)
$authLinkText = 'Logout';
$authLinkHref = '../Logout/index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Interface Preview - Coffee Shop</title>
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

    <section>
        <div class="admin-container">
            <h1>User Interface Preview</h1>
            <p>This is a preview of what regular users see. You can navigate using the menu above.</p>
            <p><a href="admin.php">Back to Admin Dashboard</a></p>
        </div>
    </section>

    <footer class="footer">
        <p>© 2024 Coffee Shop. All rights reserved.</p>
    </footer>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>