<?php
require_once '../assets/db_connect.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../Login/index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: manage_products.php');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: manage_products.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
    $price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $image = filter_input(INPUT_POST, 'image', FILTER_SANITIZE_SPECIAL_CHARS);
    $nutrition = filter_input(INPUT_POST, 'nutrition', FILTER_SANITIZE_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);

    if ($name && $description && $price && $image && $nutrition && in_array($category, ['Menu', 'Products'])) {
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, image = ?, nutrition = ?, category = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $image, $nutrition, $category, $id]);
            $success = "Product updated successfully!";
        } catch (PDOException $e) {
            $error = "Error updating product: " . $e->getMessage();
        }
    } else {
        $error = "Please fill all fields correctly.";
    }
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
    <title>Edit Product - Coffee Shop</title>
    <link rel="stylesheet" href="../assets/css/styles.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .edit-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
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
        <a href="../Book/index.php" class="btn">Book a Table</a>
    </header>

    <section>
        <div class="edit-container">
            <h1>Edit Product</h1>

            <?php if (isset($success)): ?>
                <div class="message success"><?php echo htmlspecialchars($success ?? ''); ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="message error"><?php echo htmlspecialchars($error ?? ''); ?></div>
            <?php endif; ?>

            <form method="POST" class="form">
                <div class="form-group">
                    <input type="text" name="name" class="input" placeholder="Product Name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required />
                </div>
                <div class="form-group">
                    <textarea name="description" class="input" placeholder="Description" required><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                </div>
                <div class="form-group">
                    <input type="number" name="price" class="input" placeholder="Price" step="0.01" value="<?php echo htmlspecialchars($product['price'] ?? ''); ?>" required />
                </div>
                <div class="form-group">
                    <input type="text" name="image" class="input" placeholder="Image Path" value="<?php echo htmlspecialchars($product['image'] ?? ''); ?>" required />
                </div>
                <div class="form-group">
                    <input type="text" name="nutrition" class="input" placeholder="Nutrition Info" value="<?php echo htmlspecialchars($product['nutrition'] ?? ''); ?>" required />
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" class="input" required>
                        <option value="Products" <?php echo ($product['category'] ?? '') === 'Products' ? 'selected' : ''; ?>>Products</option>
                        <option value="Menu" <?php echo ($product['category'] ?? '') === 'Menu' ? 'selected' : ''; ?>>Menu</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-form">Update Product</button>
                </div>
            </form>
        </div>
    </section>

    <footer class="footer">
        <p>© 2024 Coffee Shop. All rights reserved.</p>
    </footer>
</body>
</html>