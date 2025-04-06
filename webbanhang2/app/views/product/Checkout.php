<?php include 'app/views/shares/header.php'; ?>

<h1>Thanh toán</h1>

<form method="POST" action="/webbanhang/Product/processCheckout">
    <div class="form-group">
        <label for="name_orders">Họ tên người thanh toán:</label>
        <input type="text" id="name_orders" name="name_orders" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="name_receives">Họ tên người nhận:</label>
        <input type="text" id="name_receives" name="name_receives" class="form-control" required>
    </div>
    <div class="form-group">

        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="address_orders">Địa chỉ thanh toán:</label>
        <textarea id="address_orders" name="address_orders" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="address_receives">Địa chỉ nhận hàng:</label>
        <textarea id="address_receives" name="address_receives" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Thanh toán</button>
</form>

<a href="/webbanhang/Product/cart" class="btn btn-secondary mt-2">Quay lại giỏ
    hàng</a>

<?php include 'app/views/shares/footer.php'; ?>