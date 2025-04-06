-- Xóa database cũ nếu đã tồn tại
DROP DATABASE IF EXISTS my_store;
CREATE DATABASE my_store;
USE my_store;

-- Bảng danh mục sản phẩm
CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

-- Bảng sản phẩm
CREATE TABLE product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE SET NULL
);

-- Bảng người dùng
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng đơn hàng
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL, -- Thêm cột tổng tiền
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Bảng chi tiết đơn hàng
CREATE TABLE order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id) ON DELETE CASCADE
);

-- Thêm dữ liệu mẫu vào bảng category
INSERT INTO category (name, description) VALUES
('Đồ điện tử', 'Các sản phẩm điện tử như điện thoại, laptop, máy ảnh, v.v.'),
('Thời trang', 'Quần áo, giày dép, phụ kiện thời trang cho nam và nữ.'),
('Đồ gia dụng', 'Các thiết bị và vật dụng gia đình như nồi, chảo, máy xay sinh tố.'),
('Sách', 'Các loại sách từ văn học, khoa học, đến kỹ năng sống.'),
('Thực phẩm', 'Các loại thực phẩm đóng gói, tươi sống, đồ uống.'),
('Đồ chơi', 'Đồ chơi cho trẻ em và người lớn, từ búp bê đến mô hình lắp ráp.');
