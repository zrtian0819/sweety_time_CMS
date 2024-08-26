<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$role = $_SESSION["user"]["role"]; //判斷登入角色

// 根据角色重定向到不同頁面
if ($role != "admin") {
    header("Location: shop-info.php");
    exit;
}



// 執行SQL查詢
$sql = "SELECT users.name AS user_name, shop.name AS shop_name, coupon.name AS coupon_name, coupon.discount_rate, orders.delivery_address, orders.delivery_name, orders.delivery_phone, orders.order_time, orders.total_price 
        FROM orders 
        JOIN shop ON orders.shop_id = shop.shop_id 
        JOIN users ON orders.user_id = users.user_id 
        LEFT JOIN coupon ON orders.coupon_id = coupon.coupon_id";
$result = $conn->query($sql);

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
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>".$row["user_name"]."</td>";
                            echo "<td>".$row["shop_name"]."</td>";
                            echo "<td>".$row["coupon_name"]."</td>";
                            echo "<td>".$row["discount_rate"]."</td>";
                            echo "<td>".$row["delivery_address"]."</td>";
                            echo "<td>".$row["delivery_name"]."</td>";
                            echo "<td>".$row["delivery_phone"]."</td>";
                            echo "<td>".$row["order_time"]."</td>";
                            echo "<td>".$row["total_price"]."</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>沒有找到訂單</td></tr>";
                    }
                    ?>
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