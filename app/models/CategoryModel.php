<?php

class CategoryModel
{
    private $conn;
    private $table_name = "category";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tất cả danh mục
    public function getCategories()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Thêm danh mục
    public function addCategory($name, $description)
    {
        $query = "INSERT INTO " . $this->table_name . " (name, description) VALUES (:name, :description)";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    // Lấy danh mục theo ID
    public function getCategoryById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM category WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Cập nhật danh mục
    public function updateCategory($id, $name, $description)
    {
        $stmt = $this->conn->prepare("UPDATE category SET NAME = ?, DESCRIPTION = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $id]);
    }

    // Xóa danh mục
    public function deleteCategory($id)
{
    // Kiểm tra trước
    if ($this->isCategoryUsed($id)) {
        // Nếu còn sản phẩm đang tham chiếu, trả về false
        return false;
    }

    // Ngược lại, thực hiện xóa
    $stmt = $this->conn->prepare("DELETE FROM category WHERE id = ?");
    return $stmt->execute([$id]);
}


    public function isCategoryUsed($id)
    {
        // Kiểm tra xem bảng product có sản phẩm nào dùng category_id = $id không
        $query = "SELECT COUNT(*) AS total FROM product WHERE category_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);

        return ($row && $row->total > 0); // true nếu có sản phẩm
    }
}
