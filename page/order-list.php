<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION["user"]["role"]; //判斷登入角色

// 根据角色重定向到不同頁面
// if ($role == "shop" || $role == "admin") {
//     header("Location: shop-info.php");
//     exit;
// }



// 執行SQL查詢
$sql = "SELECT users.name AS user_name, shop.name AS shop_name, coupon.name AS coupon_name, coupon.discount_rate, orders.delivery_address, orders.delivery_name, orders.delivery_phone, orders.order_time, orders.total_price 
        FROM orders 
        JOIN shop ON orders.shop_id = shop.shop_id 
        JOIN users ON orders.user_id = users.user_id 
        LEFT JOIN coupon ON orders.coupon_id = coupon.coupon_id";

// 根據角色修改 SQL 查詢
if ($role == "shop") {
    $shop_id = $_SESSION["shop"]["shop_id"];
    $sql .= " WHERE orders.shop_id = ?";
}

$stmt = $conn->prepare($sql);

if ($role == "shop") {
    $stmt->bind_param("i", $shop_id);
}

$stmt->execute();
$result = $stmt->get_result();

$orders = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理訂單列表</title>
    <?php include("../css/css_Joe.php"); ?>

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Su.php"); ?>
        <div class="main col neumorphic p-4">
            <h2>訂單列表</h2>
            <table class="table table-hover">
                <thead class="table-pink">
                    <tr>
                        <th>用戶名稱</th>
                        <th>商店名稱</th>
                        <th>優惠券名稱</th>
                        <th>折扣率</th>
                        <th>配送地址</th>
                        <th>收件人姓名</th>
                        <th>收件人電話</th>
                        <th>訂單時間</th>
                        <th>訂單總額</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td><?= htmlspecialchars($order["user_name"]) ?></td>
                                <td><?= htmlspecialchars($order["shop_name"]) ?></td>
                                <td><?= htmlspecialchars($order["coupon_name"]) ?></td>
                                <td><?= htmlspecialchars($order["discount_rate"]) ?></td>
                                <td><?= htmlspecialchars($order["delivery_address"]) ?></td>
                                <td><?= htmlspecialchars($order["delivery_name"]) ?></td>
                                <td><?= htmlspecialchars($order["delivery_phone"]) ?></td>
                                <td><?= htmlspecialchars($order["order_time"]) ?></td>
                                <td><?= htmlspecialchars($order["total_price"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">沒有找到訂單</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include("../js.php"); ?>
</body>

</html>

<?php 
$result->free();
$conn->close(); 
?>