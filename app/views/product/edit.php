<?php include 'app/views/shares/header.php'; ?>

<h1>Sửa sản phẩm</h1>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" action="/webbanhang/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->id ?? '', ENT_QUOTES, 'UTF-8'); ?>">

    <!-- Nhập tên sản phẩm -->
    <div class="form-group">
        <label for="name">Tên sản phẩm:</label>
        <input type="text" id="name" name="name" class="form-control"
            value="<?php echo htmlspecialchars($product->name ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>

    <!-- Nhập mô tả sản phẩm -->
    <div class="form-group">
        <label for="description">Mô tả:</label>
        <textarea id="description" name="description" class="form-control" required>
    <?php echo htmlspecialchars($product->description ?? '', ENT_QUOTES, 'UTF-8'); ?>
</textarea>
    </div>

    <!-- Nhập giá sản phẩm -->
    <div class="form-group">
        <label for="price">Giá:</label>
        <input type="number" id="price" name="price" class="form-control" step="0.01"
            value="<?php echo htmlspecialchars($product->price ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
    </div>

    <!-- Chọn danh mục sản phẩm -->
    <div class="form-group">
        <label for="category_id">Danh mục:</label>
        <select id="category_id" name="category_id" class="form-control" required>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo htmlspecialchars($category->id, ENT_QUOTES, 'UTF-8'); ?>" <?php echo ($category->id == ($product->category_id ?? '')) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category->NAME ?? '', ENT_QUOTES, 'UTF-8'); ?>
                </option>

                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Tải lên hình ảnh sản phẩm -->
    <div class="form-group">
        <label for="image">Hình ảnh:</label>
        <input type="file" id="image" name="image" class="form-control">
        <input type="hidden" name="existing_image" value="<?php echo $product->image; ?>">

<?php if ($product->image): ?>
    <img src="/webbanhang/<?php echo $product->image; ?>" alt="Product Image" style="max-width: 100px;">
<?php endif; ?>

</div>

<button type="submit" class="btn btn-primary">Lưu thay đổi</button>
</form>

<a href="/webbanhang/Product" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>

<?php include 'app/views/shares/footer.php'; ?>