<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
include("../function/rebuildURL.php");

if (!isset($_GET["user_id"])) {
    echo "請正確帶入 get user_id 變數";
    exit;
}

$user_id = $_GET["user_id"];
if(isset($_GET["order_id"])){
    $order_id = $_GET["order_id"];
}


// 每頁顯示的訂單數量
$per_page = 10;

// 獲取當前頁碼
$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
$page = max(1, $page);
$start_item = ($page - 1) * $per_page;

// 撈 orders 的訂單資訊
$sql_orders_count = "SELECT COUNT(*) as count FROM orders WHERE user_id = ?";
$stmt_orders_count = $conn->prepare($sql_orders_count);
$stmt_orders_count->bind_param("i", $user_id);
$stmt_orders_count->execute();
$result_orders_count = $stmt_orders_count->get_result();
$total_orders = $result_orders_count->fetch_assoc()['count'];
$total_pages = ceil($total_orders / $per_page);

// 撈訂單資料，依當前頁面及每頁顯示數量限制查詢
if(isset($order_id)){
    $sql_orders = "SELECT * FROM orders WHERE user_id = ? AND order_id = ? LIMIT ?, ?";
    $stmt_orders = $conn->prepare($sql_orders);
    $stmt_orders->bind_param("iiii", $user_id, $order_id, $start_item, $per_page);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();
    $rows_orders = $result_orders->fetch_all(MYSQLI_ASSOC);
}else{
    $sql_orders = "SELECT * FROM orders WHERE user_id = ? LIMIT ?, ?";
    $stmt_orders = $conn->prepare($sql_orders);
    $stmt_orders->bind_param("iii", $user_id, $start_item, $per_page);
    $stmt_orders->execute();
    $result_orders = $stmt_orders->get_result();
    $rows_orders = $result_orders->fetch_all(MYSQLI_ASSOC);

}

// 撈 users 的 name
$sql_users = "SELECT name FROM users WHERE user_id = ?";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->bind_param("i", $user_id);
$stmt_users->execute();
$result_users = $stmt_users->get_result();
$row_users = $result_users->fetch_assoc();

// 撈所有 product 的資料
$sql_products = "SELECT * FROM product";
$result_products = $conn->query($sql_products);
$products = [];
while ($product = $result_products->fetch_assoc()) {
    $products[$product['product_id']] = $product['name'];
}

// 撈所有 shop 的資料
$shops = [];
$sql_shops = "SELECT * FROM shop";
$result_shops = $conn->query($sql_shops);
while ($shop = $result_shops->fetch_assoc()) {
    $shops[$shop['shop_id']] = $shop['name'];
}

// 撈所有 coupon 的資料
$coupons = [];
$sql_coupons = "SELECT * FROM coupon";
$result_coupons = $conn->query($sql_coupons);
while ($coupon = $result_coupons->fetch_assoc()) {
    $coupons[$coupon['coupon_id']] = $coupon['name'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂單資訊</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .order-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            background-color: #fff;
        }

        .order-header {
            padding: 10px;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }


        .table th,
        .table td {
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <?php if (!empty($rows_orders)): ?>
            <div class="main col neumorphic p-5">
                <div class="d-flex align-items-center">
                    <a href="users.php" class="btn-animation btn btn-custom d-flex flex-row align-items-center">
                        <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block"> 返回會員列表</span>
                    </a>
                    <h2 class="mx-2 p-0">會員 <?= ($row_users["name"]) ?> 的訂單資訊</h2>
                </div>

                <?php foreach ($rows_orders as $row_orders): ?>
                    <div class="order-card shadow-sm">
                        <div class="order-header">訂單編號: <?= ($row_orders["order_id"]) ?></div>
                        <table class="table table-hover">
                            <thead class="table-pink">
                                <tr>
                                    <th>訂單狀態</th>
                                    <th>店家名稱</th>
                                    <th>使用的優惠券</th>
                                    <th>收貨地址</th>
                                    <th>收件人姓名</th>
                                    <th>收件人電話</th>
                                    <th>訂單成立時間</th>
                                    <th>總金額</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?= ($row_orders["status"]) ?></td>
                                    <td><?= ($shops[$row_orders["shop_id"]] ?? '未知店家') ?></td>
                                    <td><?= ($coupons[$row_orders["coupon_id"]] ?? '無使用優惠券') ?></td>
                                    <td><?= ($row_orders["delivery_address"]) ?></td>
                                    <td><?= ($row_orders["delivery_name"]) ?></td>
                                    <td><?= ($row_orders["delivery_phone"]) ?></td>
                                    <td><?= ($row_orders["order_time"]) ?></td>
                                    <td><?= ($row_orders["total_price"]) ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <h4 class="text-center mb-3">訂單明細</h4>
                        <table class="table table-hover">
                            <thead class="table-pink">
                                <tr>
                                    <th>商品名稱</th>
                                    <th>數量</th>
                                    <th>小計(原價)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // 撈 order_items 的訂單明細
                                $sql_order_items = "SELECT * FROM orders_items WHERE order_id = ?";
                                $stmt_order_items = $conn->prepare($sql_order_items);
                                $stmt_order_items->bind_param("i", $row_orders["order_id"]);
                                $stmt_order_items->execute();
                                $result_order_items = $stmt_order_items->get_result();
                                $rows_order_items = $result_order_items->fetch_all(MYSQLI_ASSOC);

                                foreach ($rows_order_items as $order_items_row): ?>
                                    <tr>
                                        <td><?= ($products[$order_items_row['product_id']] ?? '未知商品') ?></td>
                                        <td><?= ($order_items_row['amount']) ?></td>
                                        <td><?= ($order_items_row['that_time_price']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>

                <!-- 分頁 -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?user_id=<?= $user_id ?>&p=1">第一頁</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?user_id=<?= $user_id ?>&p=<?= $page - 1 ?>">上一頁</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?user_id=<?= $user_id ?>&p=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?user_id=<?= $user_id ?>&p=<?= $page + 1 ?>">下一頁</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="?user_id=<?= $user_id ?>&p=<?= $total_pages ?>">最後一頁</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>

            </div>
        <?php else: ?>
            <div class="main col neumorphic p-5">
                <h2>會員 <?= ($row_users["name"]) ?> 暫無訂單</h2>
            </div>
        <?php endif; ?>
    </div>

    <?php
    if (isset($_GET['message'])) {
        $message = ($_GET['message']);
        echo "<script type='text/javascript'>alert('$message');</script>";
    }
    ?>

    <?php include("../js.php"); ?>
</body>

</html>
