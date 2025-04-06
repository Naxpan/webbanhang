<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-center">Danh sách sản phẩm</h1>
        <a href="/webbanhang/Cart/index" class="btn btn-outline-primary">
            <i class="fas fa-shopping-cart"></i> Giỏ hàng
        </a>
    </div>

    <!-- Nếu là admin thì hiển thị nút "Thêm sản phẩm mới" -->
    <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
        <div class="mb-3">
            <a href="/webbanhang/Product/add" class="btn btn-success">Thêm sản phẩm mới</a>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <!-- Hình ảnh sản phẩm -->
                        <img src="/webbanhang/<?php echo !empty($product->image) ? htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8') : 'uploads/no-image.png'; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                             style="height: 200px; object-fit: cover;">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" 
                                   class="text-decoration-none text-dark fw-bold">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small">
                                <?php echo nl2br(htmlspecialchars(mb_strimwidth($product->description, 0, 100, '...'), ENT_QUOTES, 'UTF-8')); ?>
                            </p>
                            <p class="text-danger fw-bold"><?php echo number_format($product->price); ?> VND</p>
                            <p class="text-secondary small">Danh mục: <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></p>

                            <div class="mt-auto">
                                <!-- Nếu là admin thì hiển thị nút Sửa và Xóa -->
                                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                    <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                    <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                       Xóa
                                    </a>
                                <?php endif; ?>

                                <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" 
                                   class="btn btn-primary btn-sm">
                                   Thêm vào giỏ hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center text-muted">Không có sản phẩm nào.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
