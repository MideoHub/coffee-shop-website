<?php
require_once '../assets/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login/index.php');
    exit;
}

// Handle Add Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_SPECIAL_CHARS);
    $nutrition = filter_input(INPUT_POST, 'nutrition', FILTER_SANITIZE_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);

    if ($name && $description && $price && $image && $nutrition && in_array($category, ['Menu', 'Products'])) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, image, nutrition, category) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $image, $nutrition, $category]);
            $success = "Product added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding product: " . $e->getMessage();
        }
    } else {
        $error = "Please fill all fields correctly.";
    }
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = filter_input(INPUT_GET, 'delete', FILTER_SANITIZE_NUMBER_INT);
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $success = "Product deleted successfully!";
    } catch (PDOException $e) {
        $error = "Error deleting product: " . $e->getMessage();
    }
}

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll();

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
    <title>Manage Products - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .manage-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-container, .products-list {
            margin-top: 20px;
        }
        .products-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .products-list th, .products-list td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .products-list th {
            background-color: #4b2e1e;
            color: white;
        }
        .products-list a {
            color: #ff0000;
            text-decoration: none;
        }
        .products-list a:hover {
            text-decoration: underline;
        }
        .message {
            padding: 10px;
            margin-bottom: 10px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
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
        <div class="manage-container">
            <h1>Manage Products</h1>

            <?php if (isset($success)): ?>
                <div class="message success"><?php echo htmlspecialchars($success ?? ''); ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="message error"><?php echo htmlspecialchars($error ?? ''); ?></div>
            <?php endif; ?>

            <!-- Add Product Form -->
            <div class="form-container">
                <h2>Add New Product</h2>
                <form method="POST" class="form">
                    <div class="form-group">
                        <input type="text" name="name" class="input" placeholder="Product Name" required />
                    </div>
                    <div class="form-group">
                        <textarea name="description" class="input" placeholder="Description" required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="number" name="price" class="input" placeholder="Price" step="0.01" required />
                    </div>
                    <div class="form-group">
                        <input type="text" name="image" class="input" placeholder="Image Path (e.g., ../assets/images/product.jpg)" required />
                    </div>
                    <div class="form-group">
                        <input type="text" name="nutrition" class="input" placeholder="Nutrition Info" required />
                    </div>
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <select name="category" class="input" required>
                            <option value="Products">Products</option>
                            <option value="Menu">Menu</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" name="add_product" class="btn-form">Add Product</button>
                    </div>
                </form>
            </div>

            <!-- Products List -->
            <div class="products-list">
                <h2>Products</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($product['id'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($product['name'] ?? ''); ?></td>
                                <td>$<?php echo htmlspecialchars($product['price'] ?? '0'); ?></td>
                                <td><?php echo htmlspecialchars($product['category'] ?? ''); ?></td>
                                <td>
                                    <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a> |
                                    <a href="?delete=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                </td>
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