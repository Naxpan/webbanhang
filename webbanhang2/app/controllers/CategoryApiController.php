<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // Lấy danh sách danh mục
    public function index()
    {
        header('Content-Type: application/json');
        $categories = $this->categoryModel->getCategories();
        echo json_encode($categories);
    }

    // Thêm danh mục mới
    public function store()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        // Kiểm tra dữ liệu đầu vào
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên danh mục không được để trống';
        }

        if (count($errors) > 0) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }

        // Gọi phương thức addCategory từ CategoryModel
        $result = $this->categoryModel->addCategory($name, $description);

        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Category created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category creation failed']);
        }
    }

    // Cập nhật danh mục theo ID
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);

        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        // Kiểm tra dữ liệu đầu vào
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên danh mục không được để trống';
        }

        if (count($errors) > 0) {
            http_response_code(400);
            echo json_encode(['errors' => $errors]);
            return;
        }

        // Kiểm tra xem danh mục có tồn tại không
        if (!$this->categoryModel->getCategoryById($id)) {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
            return;
        }

        // Gọi phương thức updateCategory từ CategoryModel
        $result = $this->categoryModel->updateCategory($id, $name, $description);

        if ($result) {
            echo json_encode(['message' => 'Category updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category update failed']);
        }
    }

    // Xóa danh mục theo ID
    public function destroy($id)
    {
        header('Content-Type: application/json');

        // Kiểm tra xem danh mục có tồn tại không
        if (!$this->categoryModel->getCategoryById($id)) {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
            return;
        }

        // Kiểm tra xem danh mục có sản phẩm liên quan không
        $products = $this->categoryModel->getProductsByCategoryId($id);
        if (!empty($products)) {
            http_response_code(400);
            echo json_encode(['message' => 'Không thể xóa danh mục vì danh mục này đang được sử dụng bởi sản phẩm']);
            return;
        }

        // Gọi phương thức deleteCategory từ CategoryModel
        $result = $this->categoryModel->deleteCategory($id);

        if ($result) {
            echo json_encode(['message' => 'Category deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category deletion failed']);
        }
    }
}
?>