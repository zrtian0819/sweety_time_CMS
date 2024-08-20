<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once("../db_connect.php");
include("../function/function.php");

$SessRole = $_SESSION["user"]["role"];

//判定角色以決定呈現的資料結果
if ($SessRole == "shop") {
    $shopId = $_SESSION["shop"]["shop_id"];
    $sql = "SELECT * FROM product WHERE shop_id=$shopId AND deleted = 0 ORDER BY product_id";
} elseif ($SessRole == "admin") {
    $sql = "SELECT * FROM product ORDER BY product_id";
}

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);
$productCount = $result->num_rows;


//商品類別
$sqlClass = "SELECT * from product_class";
$ClassResult = $conn->query($sqlClass);
$classRows = $ClassResult->fetch_all(MYSQLI_ASSOC);

//商品類別陣列
$classArr = [];
foreach ($classRows as $classRow) {
    $classArr[$classRow["product_class_id"]] = $classRow["class_name"];
}


//店家
$sqlStore = "SELECT shop_id,name from shop";
$storeResult = $conn->query($sqlStore);
$storeRows = $storeResult->fetch_all(MYSQLI_ASSOC);

//店家名陣列
$storeArr = [];
foreach ($storeRows as $storeRow) {
    $storeArr[$storeRow["shop_id"]] = $storeRow["name"];
}

// print_r($storeArr);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>您的商品列表清單</title>
    <?php include("../css/css_Joe.php"); ?>

    <style>
        .bdrs {
            border-radius: 20px;
        }

        .dontNextLine {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-4">

            <h2>商品列表</h2>


            <p>共<?= $productCount ?>筆</p>
            <div class="container-fluid">
                <form action="" class="mb-4">
                    <ul class="nav nav-tabs-custom">
                        <li class="nav-item">
                            <a class="main-nav nav-link active <?= $status === 'all' ? 'active' : '' ?>" href="?status=all">全部</a>
                        </li>
                        <li class="nav-item">
                            <a class="main-nav nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on">上架中</a>
                        </li>
                        <li class="nav-item">
                            <a class="main-nav nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off">已下架</a>
                        </li>
                    </ul>
                    <hr>
                </form>

                <?php if ($productCount > 0): ?>
                    <table class="table table-bordered table-hover bdrs table-responsive align-middle">
                        <thead class="text-center table-dark">
                            <tr>
                                <th class="dontNextLine">商品編號</th>
                                <th class="dontNextLine">名稱</th>
                                <th class="dontNextLine">商家</th>
                                <th class="dontNextLine">類別</th>
                                <th class="dontNextLine">價格</th>
                                <th class="dontNextLine">描述</th>
                                <th class="dontNextLine">狀態</th>
                                <th class="dontNextLine">庫存數量</th>
                                <?= $SessRole == "admin" ? "<th class='dontNextLine'>刪除</th>" : "" ?>
                                <th class="dontNextLine">詳細資訊</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td class="text-center"><?= $row["product_id"] ?></td>
                                    <td><?= $row["name"] ?></td>
                                    <td class="text-center"><?= $storeArr[$row["shop_id"]] ?></td>
                                    <td class="dontNextLine text-center"><?= $classArr[$row["product_class_id"]] ?></td>
                                    <td class="text-center"><?= number_format($row["price"]) ?></td>
                                    <td><?= getLeftChar($row["description"], 100) . "..." ?></td>
                                    <td class="text-center">
                                        <?php
                                        if ($row["available"] == 1) {
                                            echo '<span class="text-success dontNextLine">上架中</span>';
                                        } else {
                                            echo '<span class="text-danger dontNextLine">已下架</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center"><?= $row["stocks"] ?></td>
                                    <?php if ($SessRole == "admin"): ?>
                                        <td class="text-center"><?= $row["deleted"] == 0 ? "<span class='text-success'></span>" : "<span class='text-danger'>被刪除</span>"; ?></td>
                                    <?php endif;  ?>
                                    <td class="text-center">
                                        <a href="product.php?productId=<?= $row["product_id"] ?>" class="btn btn-custom">
                                            <i class="fa-solid fa-list"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    暫無符合條件的商品
                <?php endif; ?>
            </div>




        </div>

    </div>

    <?php include("../js.php"); ?>
</body>

</html>


<?php $conn->close(); ?>