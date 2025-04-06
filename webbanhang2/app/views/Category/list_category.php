<?php include 'app/views/shares/header.php'; ?>

<h1>Danh sách danh mục</h1>

<?php if (!empty($categories)): ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo $category->id; ?></td>
                    <td><?php echo htmlspecialchars($category->NAME, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($category->DESCRIPTION, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                        <a href="/webbanhang/Category/edit_category/<?php echo $category->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="/webbanhang/Category/delete_category/<?php echo $category->id; ?>"  class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này không?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Không có danh mục nào.</p>
<?php endif; ?>

<a href="/webbanhang/Category/add_category" class="btn btn-primary">Thêm danh mục mới</a>
<a href="/webbanhang" class="btn btn-secondary mt-3">Quay lại trang chủ</a>

<?php include 'app/views/shares/footer.php'; ?>
