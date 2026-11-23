CREATE DATABASE IF NOT EXISTS glass_store;
USE glass_store;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    is_featured TINYINT(1) DEFAULT 0
);

-- Orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    shipping_address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Order Items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Sample Products (First 3 populate the =|===|= banner)
INSERT INTO products (name, category, price, description, image_url, is_featured) VALUES
('Aura ANC Headphones', 'Audio', 249.00, 'Studio-grade active noise cancellation with 40-hour battery life and spatial audio drivers.', 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80', 1),
('Chronos Glass Watch', 'Wearables', 329.00, 'Sapphire crystal AMOLED display with titanium bezel, ECG tracking, and 10-day battery.', 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80', 1),
('Vortex Mech Keyboard', 'Tech', 179.00, 'Hot-swappable tactile switches, frosted polycarbonate case, and per-key ambient glow.', 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80', 1),
('Lumina Rangefinder', 'Tech', 599.00, 'Compact mirrorless street camera with f/1.4 prime lens and instant wireless transfer.', 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=900&q=80', 0),
('Aero Knit Runners', 'Lifestyle', 145.00, 'Featherweight breathable mesh sneakers engineered with responsive cloud-foam cushioning.', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80', 0),
('Prism Desk Lamp', 'Lifestyle', 89.00, 'Minimalist architectural LED lamp with stepless color temperature dimming and touch bar.', 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=80', 0),
('Echo Mini Pod', 'Audio', 119.00, '360-degree omnidirectional smart speaker wrapped in acoustic mesh with deep bass.', 'https://images.unsplash.com/photo-1589003077984-894e133dabab?auto=format&fit=crop&w=900&q=80', 0),
('Solaris Aviators', 'Lifestyle', 165.00, 'Polarized anti-reflective lenses mounted in ultra-thin brushed aerospace alloy frames.', 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=900&q=80', 0);