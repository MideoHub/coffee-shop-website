<?php
require_once '../assets/db_connect.php';

$stmt = $pdo->query("SELECT * FROM products WHERE category = 'Products'");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Update image paths for products page
$image_map = [
    'Caffè Americano' => '../assets/images/p1.jpeg',
    'Caffè Misto' => '../assets/images/p2.jpeg',
    'Espresso' => '../assets/images/p3.jpeg',
    'Espresso Macchiato' => '../assets/images/p4.jpeg',
    'Dark Roast Coffee' => '../assets/images/p5.jpeg',
    'Flat White' => '../assets/images/p6.jpeg',
    'Decaf Roast' => '../assets/images/Coffee Shop or Café Logo Design.jpeg',
    'Cappuccino' => '../assets/images/cappuccino.jpg',
    'Blonde Roast' => '../assets/images/caffe-americano.jpg'
];

// Determine the Home link based on user role
$homeLink = (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') ? '../admin/admin.php' : '../HOME/index.php';

// Determine login/logout link based on session
$authLinkText = isset($_SESSION['user_id']) ? 'Logout' : 'Login';
$authLinkHref = isset($_SESSION['user_id']) ? '../Logout/index.php' : '../Login/index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Products - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>
<body>
    <header class="header">
        <div class="logo">Coffee Shop</div>
        <nav class="navbar">
            <a href="<?php echo $homeLink; ?>">Home</a>
            <a href="../MENU/index.php">Menu</a>
            <a href="index.php">Products</a>
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
                    <li><a href="index.php">Products</a></li>
                    <li><a href="../Cart/index.php">Cart</a></li>
                    <li><a href="../Book/index.php">Book a Table</a></li>
                    <li><a href="<?php echo $authLinkHref; ?>"><?php echo $authLinkText; ?></a></li>
                </ul>
            </div>
        </div>
    </header>

    <section>
        <div class="edit-container">
            <h1>Choose Your Product</h1>
            <form method="GET" action="index.php" class="form">
                <div class="form-group">
                    <select name="id" class="input" required>
                        <option value="">Select a Product</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['name'] ?? ''); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-form">View Product</button>
                </div>
            </form>
        </div>

        <div class="container">
            <?php
            $selected_product = null;
            if (isset($_GET['id'])) {
                $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
                foreach ($products as $product) {
                    if ($product['id'] == $id) {
                        $selected_product = $product;
                        break;
                    }
                }
            }
            ?>

            <?php if ($selected_product): ?>
                <div class="box">
                    <div class="img-container">
                        <?php
                        $image_path = isset($image_map[$selected_product['name']]) ? $image_map[$selected_product['name']] : '../assets/images/default.jpg';
                        $alt_text = htmlspecialchars($selected_product['name'] ?? 'Default Product');
                        ?>
                        <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo $alt_text; ?>" />
                        <div class="box-btns">
                            <a href="#"
                               class="box-btn view-btn"
                               data-name="<?php echo htmlspecialchars($selected_product['name'] ?? ''); ?>"
                               data-description="<?php echo htmlspecialchars($selected_product['description'] ?? ''); ?>"
                               data-image="<?php echo htmlspecialchars($image_path); ?>"
                               data-nutrition="<?php echo htmlspecialchars($selected_product['nutrition'] ?? ''); ?>">
                                View
                            </a>
                            <a href="#" class="box-btn cart-btn" data-name="<?php echo htmlspecialchars($selected_product['name'] ?? ''); ?>" data-image="<?php echo htmlspecialchars($image_path); ?>">Add to Cart</a>
                        </div>
                    </div>
                    <div class="coffee-item">
                        <h2><?php echo htmlspecialchars($selected_product['name'] ?? ''); ?></h2>
                        <p>$<?php echo number_format($selected_product['price'] ?? 0, 2); ?></p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="box">
                        <div class="img-container">
                            <?php
                            $image_path = isset($image_map[$product['name']]) ? $image_map[$product['name']] : '../assets/images/default.jpg';
                            $alt_text = htmlspecialchars($product['name'] ?? 'Default Product');
                            ?>
                            <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo $alt_text; ?>" />
                            <div class="box-btns">
                                <a href="#"
                                   class="box-btn view-btn"
                                   data-name="<?php echo htmlspecialchars($product['name'] ?? ''); ?>"
                                   data-description="<?php echo htmlspecialchars($product['description'] ?? ''); ?>"
                                   data-image="<?php echo htmlspecialchars($image_path); ?>"
                                   data-nutrition="<?php echo htmlspecialchars($product['nutrition'] ?? ''); ?>">
                                    View
                                </a>
                                <a href="#" class="box-btn cart-btn" data-name="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" data-image="<?php echo htmlspecialchars($image_path); ?>">Add to Cart</a>
                            </div>
                        </div>
                        <div class="coffee-item">
                            <h2><?php echo htmlspecialchars($product['name'] ?? ''); ?></h2>
                            <p>$<?php echo number_format($product['price'] ?? 0, 2); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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