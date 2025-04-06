<?php include 'app/views/shares/header.php'; ?>

<h1>Giỏ hàng</h1>

<?php if (!empty($cart)): ?>
    <table class="table">
        <thead>
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
                            style="max-width: 80px;">
                    </td>
                    <td><?php echo number_format($item['price']); ?> VND</td>
                    <td>
                        <form action="/webbanhang/Product/updateCart" method="POST">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <input
                                type="number"
                                name="quantity"
                                value="<?php echo $item['quantity']; ?>"
                                min="1"
                                class="form-control"
                                style="width: 60px; display: inline-block;"
                                onchange="this.form.submit()">
                        </form>
                    </td>

                    <td><?php echo number_format($subtotal); ?> VND</td>
                    <td>
                        <a href="/webbanhang/Product/removeFromCart/<?php echo $id; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            Xóa
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Tổng cộng: <?php echo number_format($total); ?> VND</h3>

    <a href="/webbanhang/Product" class="btn btn-secondary">Tiếp tục mua sắm</a>

    <?php if ($total > 0): ?>
        <a href="/webbanhang/Product/checkout" class="btn btn-success">Thanh Toán</a>
    <?php endif; ?>

<?php else: ?>
    <p>Giỏ hàng của bạn đang trống.</p>
    <a href="/webbanhang/Product" class="btn btn-primary">Tiếp tục mua sắm</a>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>