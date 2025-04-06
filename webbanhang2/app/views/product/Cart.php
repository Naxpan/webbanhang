<?php include 'app/views/shares/header.php'; ?>

<h1>Giỏ hàng</h1>

<?php if (empty($cart)): ?>
    <p>Giỏ hàng của bạn đang trống.</p>
    <a href="/webbanhang/Product" class="btn btn-secondary">Quay lại danh sách sản phẩm</a>
<?php else: ?>
    <form action="/webbanhang/Product/updateCart" method="POST">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $id => $item): ?>
                    <tr>
                        <td>
                            <?php if (isset($item['image']) && $item['image']): ?>
                                <img src="/webbanhang/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="Product Image" style="max-width: 100px;">
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($item['price'], ENT_QUOTES, 'UTF-8'); ?> VND</td>
                        <td>
                            <input type="number" name="quantity[<?php echo $id; ?>]" value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" min="1" class="form-control" style="width: 80px;">
                        </td>
                        <td><?php echo htmlspecialchars($item['price'] * $item['quantity'], ENT_QUOTES, 'UTF-8'); ?> VND</td>
                        <td>
                            <a href="/webbanhang/Product/removeFromCart/<?php echo $id; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Hiển thị tổng tiền -->
        <h3>Tổng tiền: <?php echo htmlspecialchars(number_format($totalPrice, 2), ENT_QUOTES, 'UTF-8'); ?> VND</h3>

        <button type="submit" class="btn btn-primary">Cập nhật giỏ hàng</button>
        <a href="/webbanhang/Product/checkout" class="btn btn-success">Thanh toán</a>
        <a href="/webbanhang/Product" class="btn btn-secondary">Tiếp tục mua sắm</a>
    </form>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>