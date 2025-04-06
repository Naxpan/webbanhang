<?php
// Kiểm tra và khởi động session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Require các file cần thiết
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    // Xem chi tiết sản phẩm
    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    }

    // Hiển thị form thêm sản phẩm (CHỈ ADMIN)
    public function add()
    {
        $this->checkAdmin();
        $categories = (new CategoryModel($this->db))->getCategories();
        include 'app/views/product/add.php';
    }

    // Lưu sản phẩm mới (CHỈ ADMIN)
    public function save()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $image = isset($_FILES['image']) ? $this->uploadImage($_FILES['image']) : '';

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
            header('Location: /webbanhang/Product');
        }
    }

    // Hiển thị form sửa sản phẩm (CHỈ ADMIN)
    public function edit($id)
    {
        $this->checkAdmin();
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        include 'app/views/product/edit.php';
    }

    // Cập nhật sản phẩm (CHỈ ADMIN)
    public function update()
    {
        $this->checkAdmin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $image = isset($_FILES['image']) ? $this->uploadImage($_FILES['image']) : $_POST['existing_image'];

            $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            header('Location: /webbanhang/Product');
        }
    }

    // Xóa sản phẩm (CHỈ ADMIN)
    public function delete($id)
    {
        $this->checkAdmin();
        $this->productModel->deleteProduct($id);
        header('Location: /webbanhang/Product');
    }

    // Kiểm tra quyền admin
    private function checkAdmin()
    {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            die("Bạn không có quyền truy cập chức năng này!");
        }
    }

    // Xử lý upload ảnh
    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!getimagesize($file["tmp_name"])) {
            die("File không phải là hình ảnh.");
        }
        if ($file["size"] > 10 * 1024 * 1024) {
            die("Hình ảnh có kích thước quá lớn.");
        }
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            die("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            die("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name'  => $product->name,     // Hoặc $product->NAME nếu DB cột là NAME
                'price' => $product->price,    // Hoặc $product->PRICE nếu DB cột là PRICE
                'quantity' => 1,
                'image' => $product->image
            ];
        }

        header('Location: /webbanhang/Product/cart');
        exit;
    }


    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/GioHang/cart.php';
    }

    public function checkout()
    {
        include 'app/views/GioHang/checkout.php';
    }

    public function processCheckout()
    {
        // Đảm bảo session đã được khởi động
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            die("Lỗi: Bạn chưa đăng nhập!");
        }

        $user_id = $_SESSION['user_id'];
        $name = $_POST['name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $address = $_POST['address'] ?? '';

        // Kiểm tra giỏ hàng có sản phẩm không
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            echo "Giỏ hàng trống.";
            return;
        }

        // Tính tổng tiền đơn hàng
        $total_price = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        // Bắt đầu transaction
        $this->db->beginTransaction();

        try {
            // Tạo đơn hàng mới
            $query = "INSERT INTO orders (user_id, name, phone, address, total_price) 
                  VALUES (:user_id, :name, :phone, :address, :total_price)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':total_price', $total_price);
            $stmt->execute();
            $order_id = $this->db->lastInsertId(); // Lấy ID đơn hàng vừa tạo

            // Lưu thông tin sản phẩm vào bảng order_details
            foreach ($_SESSION['cart'] as $product_id => $item) {
                $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                      VALUES (:order_id, :product_id, :quantity, :price)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
                $stmt->bindParam(':price', $item['price'], PDO::PARAM_STR);
                $stmt->execute();
            }

            // Xóa giỏ hàng sau khi đặt hàng thành công
            unset($_SESSION['cart']);
            $_SESSION['order_success'] = true;

            // Commit transaction
            $this->db->commit();

            // Chuyển hướng đến trang xác nhận đơn hàng
            header('Location: /webbanhang/Product/orderConfirmation');
            exit;
        } catch (Exception $e) {
            // Rollback nếu có lỗi
            $this->db->rollBack();
            echo "Lỗi xử lý đơn hàng: " . $e->getMessage();
        }
    }





    public function orderConfirmation()
    {
        include 'app/views/GioHang/orderConfirmation.php';
    }

    public function removeFromCart($id)
    {
        // Kiểm tra xem sản phẩm có trong giỏ hàng không
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]); // Xóa sản phẩm khỏi giỏ
        }

        // Điều hướng về trang giỏ hàng
        header('Location: /webbanhang/Product/cart');
        exit;
    }


    public function updateCart()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;

            // Nếu giỏ hàng chưa tồn tại hoặc sản phẩm không tồn tại trong giỏ
            if (!isset($_SESSION['cart']) || !isset($_SESSION['cart'][$id])) {
                echo "Sản phẩm không tồn tại trong giỏ hàng.";
                return;
            }

            // Ép kiểu và đảm bảo số lượng >= 1
            $quantity = (int)$quantity;
            if ($quantity < 1) {
                $quantity = 1;
            }

            // Cập nhật số lượng
            $_SESSION['cart'][$id]['quantity'] = $quantity;

            // Chuyển hướng về trang giỏ hàng
            header('Location: /webbanhang/Product/cart');
            exit;
        }
    }
}
