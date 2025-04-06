<?php

require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController
{
    private $categoryModel;
    private $db;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        // Tạo đối tượng CategoryModel với tham số kết nối cơ sở dữ liệu
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index() {
        $categories = $this->categoryModel->getCategories(); // Lấy danh sách danh mục
        include 'app/views/Category/list_category.php'; // Hiển thị danh sách danh mục
    }

    public function delete_category($id)
{
    $result = $this->categoryModel->deleteCategory($id);

    if ($result) {
        header('Location: /webbanhang/Category');
        exit;
    } else {
        // Hiển thị lỗi nếu danh mục vẫn còn sản phẩm
        echo "Không thể xóa danh mục vì có sản phẩm đang tham chiếu. Hãy xóa hoặc cập nhật sản phẩm trước.";
    }
}


    public function update_category()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên danh mục không được để trống';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả không được để trống';
        }

        if (!empty($errors)) {
            $category = (object) ['id' => $id, 'NAME' => $name, 'DESCRIPTION' => $description];
            include 'app/views/Category/edit_category.php';
        } else {
            if ($this->categoryModel->updateCategory($id, $name, $description)) {
                header('Location: /webbanhang/Category');
                exit;
            } else {
                echo "Đã xảy ra lỗi khi cập nhật danh mục.";
            }
        }
    }
}

    public function edit_category($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
            if (!$category) {
                 echo "Danh mục không tồn tại.";
                return;
    }

            include 'app/views/Category/edit_category.php';
    }


    // Phương thức hiển thị form thêm danh mục
    public function add_category()
    {
        include 'app/views/Category/add_category.php'; // Gọi view thêm danh mục
    }

    // Phương thức lưu danh mục vào cơ sở dữ liệu
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            // Kiểm tra dữ liệu và lưu vào cơ sở dữ liệu
            $errors = [];
            if (empty($name)) {
                $errors['name'] = 'Tên danh mục không được để trống';
            }

            if (empty($description)) {
                $errors['description'] = 'Mô tả không được để trống';
            }

            if (!empty($errors)) {
                // Nếu có lỗi, hiển thị lại form với lỗi
                include 'app/views/Category/aadd_categorydd.php';
            } else {
                // Nếu không có lỗi, thêm danh mục vào cơ sở dữ liệu
                if ($this->categoryModel->addCategory($name, $description)) {
                    header('Location: /webbanhang/Category');
                    exit;
                } else {
                    echo "Đã xảy ra lỗi khi lưu danh mục.";
                }
            }
        }
    }
}
?>
