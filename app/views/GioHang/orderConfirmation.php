<?php include 'app/views/shares/header.php'; ?> 

<h1>Xác nhận đơn hàng</h1> 

<?php if (!isset($_SESSION['order_success'])): ?>
    <p>Bạn chưa đặt đơn hàng nào. <a href="/webbanhang/Product">Quay lại mua sắm</a></p>
<?php else: ?>
    <p>Cảm ơn bạn đã đặt hàng. Đơn hàng của bạn đã được xử lý thành công.</p> 
    <a href="/webbanhang/Product" class="btn btn-primary mt-2">Tiếp tục mua sắm</a> 
    <?php unset($_SESSION['order_success']); ?>
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
