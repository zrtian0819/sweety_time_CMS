<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
include("../function/rebuildURL.php");

$order_id = $_GET["order_id"];

// 撈 orders 的訂單資訊
$sql_orders = "SELECT * FROM orders WHERE order_id =?";
$stmt_orders = $conn -> prepare($sql_orders);
$stmt_orders -> bind_param("i", $order_id);
$stmt_orders -> execute();
$result_orders = $stmt_orders -> get_result();
$row_orders = $result_orders -> fetch_assoc();

// 撈 order_items 的訂單明細
$sql_order_items = "SELECT * FROM orders_items WHERE order_id =?";
$stmt_order_items = $conn -> prepare($sql_order_items);
$stmt_order_items -> bind_param("i", $order_id);
$stmt_order_items -> execute();
$result_order_items = $stmt_order_items -> get_result();
$rows_order_items = $result_order_items -> fetch_all(MYSQLI_ASSOC);

// 撈 users 的 name
$sql_users = "SELECT name FROM users WHERE user_id =?";
$stmt_users = $conn -> prepare($sql_users);
$stmt_users -> bind_param("i", $row_orders["user_id"]);
$stmt_users -> execute();
$result_users = $stmt_users -> get_result();
$row_users = $result_users->fetch_assoc();

// 撈 shop 的 name
$sql_shops = "SELECT name FROM shop WHERE shop_id =?";
$stmt_shops = $conn -> prepare($sql_shops);
$stmt_shops -> bind_param("i", $row_orders["shop_id"]);
$stmt_shops -> execute();
$result_shops = $stmt_shops -> get_result();
$row_shops = $result_shops->fetch_assoc();

// 撈 coupon 的 name
$sql_coupons = "SELECT name FROM coupon WHERE coupon_id =?";
$stmt_coupons = $conn -> prepare($sql_coupons);
$stmt_coupons -> bind_param("i", $row_orders["coupon_id"]);
$stmt_coupons -> execute();
$result_coupons = $stmt_coupons -> get_result();
$row_coupons = $result_coupons->fetch_assoc();

// 撈 product 的 name
$sql_products = "SELECT * FROM product";
$stmt_products = $conn -> prepare($sql_products);
$stmt_products -> execute();
$result_products = $stmt_products -> get_result();
$row_products = $result_products->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂單資訊</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .coupon-input-bar{
            width: 50px;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <!-- 訂單資訊 -->
            <h2 class="mb-4">訂單資訊</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>訂單編號<br>(order_id)</th>
                        <th>訂單狀態<br>(status)</th>
                        <th>會員名稱<br></th>
                        <th>店家名稱<br></th>
                        <th>使用的優惠券<br></th>
                        <th>收貨地址<br>(delivery_address)</th>
                        <th>收件人姓名<br>(delivery_name)</th>
                        <th>收件人電話<br>(delivery_phone)</th>
                        <th>訂單成立時間<br>(order_time)</th>
                        <th>總金額<br>(total_price)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $row_orders["order_id"];?></td>
                        <td><?php echo $row_orders["status"];?></td>
                        <td><?php echo $row_users["name"];?></td>
                        <td><?php echo $row_shops["name"];?></td>
                        <td><?php echo $row_coupons["name"];?></td>
                        <td><?php echo $row_orders["delivery_address"];?></td>
                        <td><?php echo $row_orders["delivery_name"];?></td>
                        <td><?php echo $row_orders["delivery_phone"];?></td>
                        <td><?php echo $row_orders["order_time"];?></td>
                        <td><?php echo $row_orders["total_price"];?></td>
                    </tr>
                </tbody>
            </table>
            <!-- 訂單明細 -->
            <h2 class="mb-4">訂單明細</h2>
            <!-- 顯示資料的表格 -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>商品id<br>(product_id)</th>
                        <th>數量<br>(amount)</th>
                        <th>小計(原價)<br>(that_time_price)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows_order_items as $order_items_row) : ?>
                        <tr>
                            <td><?= $row_products[$order_items_row['product_id'] - 1]["name"];?></td>
                            <td><?= $order_items_row['amount'];?></td>
                            <td><?= $order_items_row['that_time_price'];?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    
    </div>

    <!-- 顯示新增和編輯的成功or失敗訊息 -->
    <!-- 有時間可以用中介頁面來避免使用GET -->
    <!-- 有時間可以用別的設計取代alert -->
    <?php
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    ?>


    <!-- Javascript 寫這裡 -->
    <?php include("../js.php"); ?>
    <script>

    </script>
</body>

</html>