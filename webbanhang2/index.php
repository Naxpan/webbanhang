<?php
session_start();

require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';
require_once 'app/controllers/ProductApiController.php';
require_once 'app/controllers/CategoryApiController.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'DefaultController';

$action = $urlParts[1] ?? 'index';

if ($controllerName === 'ApiController' && isset($urlParts[1])) {
    $apiControllerName = ucfirst($urlParts[1]) . 'ApiController';

    if (file_exists('app/controllers/' . $apiControllerName . '.php')) {
        require_once 'app/controllers/' . $apiControllerName . '.php';
        $controller = new $apiControllerName();

        $method = $_SERVER['REQUEST_METHOD'];

        $id = $urlParts[2] ?? null;

        switch ($method) {
            case 'GET':
                $action = $id ? 'show' : 'index';
                break;
            case 'POST':
                $action = 'store';
                break;
            case 'PUT':
                $action = $id ? 'update' : null;
                break;
            case 'DELETE':
                $action = $id ? 'destroy' : null;
                break;
            default:
                header('Content-Type: application/json');
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
                exit;
        }

        if (method_exists($controller, $action)) {
            if ($id) {
                call_user_func_array([$controller, $action], [$id]);
            } else {
                call_user_func_array([$controller, $action], []);
            }
        } else {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['message' => 'Action not found']);
        }
        exit;
    } else {
        header('Content-Type: application/json');
        http_response_code(404);
        echo json_encode(['message' => 'Controller not found']);
        exit;
    }
}

if (file_exists('app/controllers/' . $controllerName . '.php')) {
    require_once 'app/controllers/' . $controllerName . '.php';
    $controller = new $controllerName();
} else {
    die('Controller not found');
}

if (method_exists($controller, $action)) {
    call_user_func_array([$controller, $action], array_slice($urlParts, 2));
} else {
    die('Action not found');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f4f8; 
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #2c3e50; 
            text-align: center;
            margin-bottom: 30px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        table {
            width: 70%; 
            margin: 0 auto; 
            border-collapse: collapse;
            background-color: #ffffff; 
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
            border-radius: 8px; 
            overflow: hidden; 
        }

        th, td {
            padding: 12px 15px; 
            text-align: left;
            border-bottom: 1px solid #e0e0e0; 
        }

        th {
            background-color: #3498db; 
            color: #ffffff;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
        }

        td {
            color: #34495e;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f1f8ff; 
            transition: background-color 0.3s ease; 
        }

        tr:nth-child(even) {
            background-color: #f9f9f9; 
        }

        td:nth-child(3) {
            text-align: right;
            font-weight: 500;
            color: #e74c3c; 
        }
    </style>
</head>
<body>
    <h1>Danh sách sản phẩm</h1>
    <table id="productTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
            </tr>
        </thead>
        <tbody id="productList">
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'http://localhost:8080/webbanhang/api/product',
                type: 'GET', 
                dataType: 'json', 
                success: function(response) {
                    console.log('Phản hồi từ API:', response);
                    if (Array.isArray(response)) {
                        response = {
                            status: 'success',
                            data: response
                        };
                    }
                    if (response.status === 'success') {
                        let products = response.data;
                        let productList = $('#productList');
                        productList.empty();

                        products.forEach(function(product) {
                            productList.append(`
                                <tr>
                                    <td>${product.id}</td>
                                    <td>${product.name}</td>
                                    <td>${product.price}</td>
                                </tr>
                            `);
                        });
                    } else {
                        alert('Lỗi: Không lấy được dữ liệu sản phẩm');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Lỗi:', error);
                    console.log('Phản hồi lỗi:', xhr.responseText); // Debug lỗi
                    alert('Đã có lỗi xảy ra khi gọi API');
                }
            });
        });
    </script>
</body>
</html>