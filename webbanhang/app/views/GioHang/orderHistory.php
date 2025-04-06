<?php
session_start();
require_once __DIR__ . '/../../config/database.php'; 
if (!isset($_SESSION['user_id'])) {
    header("Location: /webbanhang/account/login.php");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

$user_id = $_SESSION['user_id'];

$sql = "SELECT o.id AS order_id, o.created_at, o.total_price, o.address AS shipping_address, 
               od.product_id, p.name AS product_name, od.quantity, od.price 
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        JOIN product p ON od.product_id = p.id
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]); 
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Xử lý dữ liệu đơn hàng
$orders = [];
foreach ($result as $row) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'created_at' => $row['created_at'],
            'total_price' => $row['total_price'],
            'shipping_address' => $row['shipping_address'], // Lưu địa chỉ nhận hàng vào mảng
            'items' => []
        ];
    }
    $orders[$order_id]['items'][] = [
        'product_name' => $row['product_name'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua hàng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .card-header {
            background-color: #007bff;
            color: #fff;
        }

        .card-body {
            background-color: #f9f9f9;
        }

        .table th {
            background-color: #f1f1f1;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }

        .order-info {
            margin-top: 10px;
            font-weight: bold;
            color: #333;
        }

        .order-item {
            margin-bottom: 20px;
        }

        .btn-back {
            background-color: #28a745;
            color: #fff;
        }

        .btn-back:hover {
            background-color: #218838;
        }

        .card-footer {
            background-color: #f1f1f1;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h2 class="mb-4">Lịch sử mua hàng</h2>
        <a href="/webbanhang/Product/" class="btn btn-back mb-4"><i class="fas fa-arrow-left"></i> Quay lại cửa hàng</a>

        <?php if (empty($orders)) : ?>
            <p class="alert alert-warning">Bạn chưa có đơn hàng nào.</p>
        <?php else : ?>
            <?php foreach ($orders as $order_id => $order) : ?>
                <div class="card mb-4 shadow-sm">
                    <div class="card-header">
                        <strong>Đơn hàng #<?= htmlspecialchars($order_id) ?></strong> 
                        - Ngày đặt: <?= date("d-m-Y H:i:s", strtotime($order['created_at'])) ?>
                    </div>
                    <div class="card-body">
                        <p class="order-info"><i class="fas fa-map-marker-alt"></i> <strong>Địa chỉ nhận hàng:</strong> <?= htmlspecialchars($order['shipping_address']) ?></p>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order['items'] as $item) : ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                                        <td><?= number_format($item['price'], 0, ',', '.') ?> VND</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <strong>Tổng tiền: <?= number_format($order['total_price'], 0, ',', '.') ?> VND</strong>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
