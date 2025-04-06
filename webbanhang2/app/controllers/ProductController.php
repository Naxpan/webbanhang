<?php
// Require SessionHelper and other necessary files 
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

    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        // Gọi model lấy thông tin sản phẩm
        $product = $this->productModel->getProductById($id);

        // Nếu tìm thấy sản phẩm
        if ($product) {
            // Hiển thị view "show.php"
            include 'app/views/product/show.php';
        } else {
            // Nếu không tìm thấy, báo lỗi
            echo "Không thấy sản phẩm.";
        }
    }


    public function add()
    {
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }

            $result = $this->productModel->addProduct(
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
            }
        }
    }

    // Hiển thị form sửa thông tin sản phẩm
    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();

        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }

            $edit = $this->productModel->updateProduct(
                $id,
                $name,
                $description,
                $price,
                $category_id,
                $image
            );

            if ($edit) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    public function delete($id)
    {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        // Kiểm tra và tạo thư mục nếu chưa tồn tại 
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Kiểm tra xem file có phải là hình ảnh không 
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes) 
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        // Chỉ cho phép một số định dạng hình ảnh nhất định 
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif")
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        // Lưu file 
        if (!move_uploaded_file($file["tmp_name"], $target_file))
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
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
