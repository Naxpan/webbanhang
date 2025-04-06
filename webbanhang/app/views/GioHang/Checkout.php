<?php include 'app/views/shares/header.php'; ?>

<h1>Thanh toán</h1>

<?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
    <p>Giỏ hàng của bạn đang trống.</p>
    <a href="/webbanhang/Product" class="btn btn-primary">Tiếp tục mua sắm</a>
<?php else: ?>
    <form method="POST" action="/webbanhang/Product/processCheckout"> 
        <div class="form-group"> 
            <label for="name">Họ tên:</label> 
            <input type="text" id="name" name="name" class="form-control" required> 
        </div> 
        <div class="form-group"> 
            <label for="phone">Số điện thoại:</label> 
            <input type="text" id="phone" name="phone" class="form-control" required> 
        </div> 
        <div class="form-group"> 
            <label for="address">Địa chỉ:</label> 
            <textarea id="address" name="address" class="form-control" required></textarea> 
        </div> 
        <button type="submit" class="btn btn-primary">Thanh toán</button> 
    </form> 

    <a href="/webbanhang/Product/cart" class="btn btn-secondary mt-2">Quay lại giỏ hàng</a> 
<?php endif; ?>

<?php include 'app/views/shares/footer.php'; ?>
