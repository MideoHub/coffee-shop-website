
USE coffee_shop;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    persons INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    session_id VARCHAR(255) NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    product_image VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    nutrition VARCHAR(255) NOT NULL
);

INSERT INTO products (name, description, image, price, nutrition) VALUES
('Caffè Americano', 'A rich, full-bodied coffee with a hint of caramel.', '../assets/images/caffe-americano.jpg', 2.10, 'Serving Size: 16 fl oz|Calories: 15|Calories from Fat: 0'),
('Caffè Misto', 'A one-to-one combination of fresh-brewed coffee and steamed milk.', '../assets/images/caffe-misto.jpg', 2.60, 'Serving Size: 16 fl oz|Calories: 110|Calories from Fat: 35'),
('Espresso', 'Rich and full-bodied with a hint of caramel.', '../assets/images/espresso.jpg', 2.79, 'Serving Size: 1.5 fl oz|Calories: 5|Calories from Fat: 0'),
('Espresso Macchiato', 'A rich, full-bodied espresso with a dash of steamed milk.', '../assets/images/espresso-macchiato.jpg', 2.05, 'Serving Size: 1.5 fl oz|Calories: 15|Calories from Fat: 5'),
('Dark Roast Coffee', 'A full-bodied coffee with bold, robust flavors.', '../assets/images/caffe-americano.jpg', 2.20, 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0'),
('Flat White', 'Smooth ristretto shots of espresso with velvety steamed milk.', '../assets/images/flat-white.jpg', 2.80, 'Serving Size: 12 fl oz|Calories: 170|Calories from Fat: 90'),
('Decaf Roast', 'A decaffeinated coffee with a smooth, rich flavor.', '../assets/images/caffe-misto.jpg', 2.25, 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0'),
('Cappuccino', 'A perfect balance of espresso, steamed milk, and foam.', '../assets/images/cappuccino.jpg', 2.59, 'Serving Size: 12 fl oz|Calories: 120|Calories from Fat: 50'),
('Blonde Roast', 'A light-bodied coffee with a sweet, citrusy flavor.', '../assets/images/caffe-americano.jpg', 2.59, 'Serving Size: 16 fl oz|Calories: 5|Calories from Fat: 0');