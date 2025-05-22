<?php
ob_start();
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'user') {
    header('Location: ../Login/index.php');
    exit;
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Dashboard - Coffee Shop</title>
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
        <a href="../Book/index.php" class="btn">Book a Table</a>
        <div class="menu-toggle">
            <i class="fa-solid fa-bars icon" onclick="toggleMenu()"></i>
            <div class="menu">
                <ul>
                    <li><a href="../HOME/index.php">Home</a></li>
                    <li><a href="../MENU/index.php">Menu</a></li>
                    <li><a href="../PRODUCTS/index.php">Products</a></li>
                    <li><a href="../Cart/index.php">Cart</a></li>
                    <li><a href="../Book/index.php">Book a Table</a></li>
                    <li><a href="../Logout/index.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <section>
        <div class="user-container">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <p>Use the menu above to explore the coffee shop.</p>
        </div>
    </section>

    <footer class="footer">
        <p>© 2025 Coffee Shop. All rights reserved.</p>
    </footer>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>