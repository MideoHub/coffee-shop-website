

-- Use the database
USE coffee_shop;

-- Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    time VARCHAR(50) NOT NULL,
    persons INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    nutrition TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create cart_items table
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insert sample products
INSERT INTO products (name, description, price, image, nutrition) VALUES
('Caffè Americano', 'A rich, full-bodied coffee with a hint of caramel.', 2.10, 'assets/images/caffe-americano.jpg', 'Serving Size: 16 fl oz|Calories: 15|Calories from Fat: 0'),
('Caffè Misto', 'A one-to-one combination of fresh-brewed coffee and steamed milk.', 2.60, 'assets/images/caffe-misto.jpg', 'Serving Size: 16 fl oz|Calories: 110|Calories from Fat: 35'),
('Espresso', 'Rich and full-bodied with a hint of caramel.', 2.79, 'assets/images/espresso.jpg', 'Serving Size: 1.5 fl oz|Calories: 5|Calories from Fat: 0'),
('Espresso Macchiato', 'A rich, full-bodied espresso with a dash of steamed milk.', 2.05, 'assets/images/espresso-macchiato.jpg', 'Serving Size: 1.5 fl oz|Calories: 15|Calories from Fat: 5'),
('Dark Roast Coffee', 'A full-bodied coffee with bold, robust flavors.', 2.20, 'assets/images/p5.jpeg', 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0'),
('Flat White', 'Smooth ristretto shots of espresso with velvety steamed milk.', 2.80, 'assets/images/flat-white.jpg', 'Serving Size: 12 fl oz|Calories: 170|Calories from Fat: 90'),
('Decaf Roast', 'A decaffeinated coffee with a smooth, rich flavor.', 2.25, 'assets/images/Coffee Shop or Café Logo Design.jpeg', 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0'),
('Cappuccino', 'A perfect balance of espresso, steamed milk, and foam.', 2.59, 'assets/images/cappuccino.jpg', 'Serving Size: 12 fl oz|Calories: 120|Calories from Fat: 50'),
('Blonde Roast', 'A light-bodied coffee with a sweet, citrusy flavor.', 2.59, 'assets/images/caffe-americano.jpg', 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0');