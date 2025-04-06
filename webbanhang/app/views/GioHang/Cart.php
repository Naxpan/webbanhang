<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Giỏ hàng của bạn</h1>

    <?php if (!empty($cart)): ?>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($cart as $id => $item):
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <img src="/webbanhang/<?php echo !empty($item['image']) ? $item['image'] : 'uploads/default.png'; ?>"
                                            alt="Product Image"
                                            class="img-fluid rounded"
                                            style="max-width: 60px;">
                                    </td>
                                    <td><?php echo number_format($item['price']); ?> VND</td>
                                    <td>
                                        <form action="/webbanhang/Product/updateCart" method="POST" class="d-flex align-items-center">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <input
                                                type="number"
                                                name="quantity"
                                                value="<?php echo $item['quantity']; ?>"
                                                min="1"
                                                class="form-control form-control-sm"
                                                style="width: 70px;"
                                                onchange="this.form.submit()">
                                        </form>
                                    </td>
                                    <td><?php echo number_format($subtotal); ?> VND</td>
                                    <td>
                                        <a href="/webbanhang/Product/removeFromCart/<?php echo $id; ?>"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                <h3 class="mb-0">Tổng cộng: <span class="text-success"><?php echo number_format($total); ?> VND</span></h3>
                <div>
                    <a href="/webbanhang/Product" class="btn btn-outline-secondary me-2">Tiếp tục mua sắm</a>
                    <?php if ($total > 0): ?>
                        <a href="/webbanhang/Product/checkout" class="btn btn-primary">Thanh Toán</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center" role="alert">
            <p class="mb-0">Giỏ hàng của bạn đang trống.</p>
            <a href="/webbanhang/Product" class="btn btn-primary mt-3">Tiếp tục mua sắm</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>