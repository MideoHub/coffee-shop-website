<?php
require_once '../assets/db_connect.php';

$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <header class="header">
        <div class="logo">Coffee Shop</div>
        <nav class="navbar">
            <a href="../HOME/index.php">Home</a>
            <a href="index.php">Menu</a>
            <a href="../PRODUCTS/index.php">Products</a>
            <a href="../Cart/index.php">Cart</a>
        </nav>
        <a href="../Book/index.php" class="btn">Book a Table</a>
        <div class="menu-toggle">
            <i class="fa-solid fa-bars icon" onclick="toggleMenu()"></i>
            <div class="menu">
                <ul>
                    <li><a href="../HOME/index.php">Home</a></li>
                    <li><a href="index.php">Menu</a></li>
                    <li><a href="../PRODUCTS/index.php">Products</a></li>
                    <li><a href="../Cart/index.php">Cart</a></li>
                    <li><a href="../Book/index.php">Book a Table</a></li>
                </ul>
            </div>
        </div>
    </header>
    <section>
        <div class="container">
            <?php foreach ($products as $product): ?>
            <div class="box">
                <div class="img-container">
                    <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                    <div class="box-btns">
                        <a
                            href="#"
                            class="box-btn view-btn"
                            data-name="<?php echo htmlspecialchars($product['name']); ?>"
                            data-description="<?php echo htmlspecialchars($product['description']); ?>"
                            data-image="<?php echo htmlspecialchars($product['image']); ?>"
                            data-nutrition="<?php echo htmlspecialchars($product['nutrition']); ?>"
                        >
                            View
                        </a>
                        <a href="#" class="box-btn cart-btn" data-name="<?php echo htmlspecialchars($product['name']); ?>" data-image="<?php echo htmlspecialchars($product['image']); ?>">Add to Cart</a>
                    </div>
                </div>
                <div class="coffee-item">
                    <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                    <p>$<?php echo number_format($product['price'], 2); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="overlay" id="overlay">
            <div class="modal">
                <span class="close-btn" id="closeBtn">×</span>
                <h2 id="modalTitle"></h2>
                <p id="modalDescription"></p>
                <img id="modalImage" src="" alt="" style="width: 100%" />
                <h3>Nutritional Information</h3>
                <hr />
                <div id="modalNutrition"></div>
                <hr />
            </div>
        </div>
    </section>
    <footer class="footer">
        <p>© 2024 Coffee Shop. All rights reserved.</p>
    </footer>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>