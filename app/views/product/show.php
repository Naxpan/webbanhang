<?php include 'app/views/shares/header.php'; ?>

<h1>Chi tiết sản phẩm</h1>

<!-- Kiểm tra nếu $product tồn tại -->
<?php if ($product): ?>
    <h2><?php echo htmlspecialchars($product->name , ENT_QUOTES, 'UTF-8'); ?></h2>

    
    <?php if (!empty($product->image)): ?>
        <img src="/webbanhang/<?php echo $product->image; ?>" alt="Product Image" style="max-width: 200px;">
    <?php endif; ?>

    <p>Mô tả: <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Giá: <?php echo number_format($product->price); ?> VND</p>
    
    <!-- Nếu bạn đã JOIN bảng category để lấy category_name -->
    <?php if (isset($product->category_name)): ?>
        <p>Danh mục: <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <a href="/webbanhang/Product" class="btn btn-secondary mt-2">Quay lại danh sách sản phẩm</a>
<?php else: ?>
    <p>Không tìm thấy sản phẩm.</p>
    <a href="/webbanhang/Product" class="btn btn-primary mt-2">Quay lại danh sách sản phẩm</a>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
