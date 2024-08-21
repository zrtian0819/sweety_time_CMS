<?php

require_once("../db_connect.php");
include("../function/function.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//避免使用者亂改網址
// if(isset($_GET["shopId"])){

//     if( $_GET["shopId"]!=$_SESSION["shop"]["shop_id"] ){
//         header("location: product-list.php?shopId=".$_SESSION["shop"]["shop_id"]);
//     }

// }

$SessRole = $_SESSION["user"]["role"];
$nav_page_name = "product-list.php?";    //導頁名

//判定角色以決定呈現的資料結果
if ($SessRole == "shop") {
    $shopId = $_SESSION["shop"]["shop_id"];
    $sql = "SELECT * FROM product WHERE shop_id=$shopId AND deleted = 0 ORDER BY product_id ASC";

} elseif ($SessRole == "admin") {
    $sql = "SELECT * FROM product ORDER BY product_id ASC";
}

$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);
$allProductCount = $result->num_rows;

//預設值
$page = 1;
$per_page = 15;
$start_item = 0;
//頁碼的處理
$total_page = ceil($allProductCount / $per_page);   //計算總頁數(無條件進位)

//分頁的處理
if(isset($_GET["p"])){
    $page = $_GET["p"];
    $start_item = ($page-1)*$per_page;

    // $nav_page_name .= "&p=".$page ;
}
$sql_page = "LIMIT $start_item, $per_page";

if ($SessRole == "shop") {
    $sql = "SELECT * FROM product WHERE shop_id=$shopId AND deleted = 0 ORDER BY product_id ASC $sql_page";
} elseif ($SessRole == "admin") {
    $sql = "SELECT * FROM product ORDER BY product_id ASC $sql_page";
}

//排序的處理
if(isset($_GET["order"])){
    $order = $_GET["order"];

    switch($order){
        case "ida":
            $sql_order = "ORDER BY product_id ASC";
            break;
        case "idd":
            $sql_order = "ORDER BY product_id DESC";
            break;
        case "pria":
            $sql_order = "ORDER BY price ASC";
            break;
        case "prid":
            $sql_order = "ORDER BY price DESC";
            break;
        case "stoa":
            $sql_order = "ORDER BY stocks ASC";
            break;
        case "stod":
            $sql_order = "ORDER BY stocks DESC";
            break;
        default:
            $sql_order = "ORDER BY product_id ASC";
    }

    $nav_page_name .= "&order=".$order;

}else{
    $sql_order = "ORDER BY product_id ASC";
}

if ($SessRole == "shop") {
    $sql = "SELECT * FROM product WHERE shop_id=$shopId AND deleted = 0 $sql_order $sql_page";
} elseif ($SessRole == "admin") {
    $sql = "SELECT * FROM product $sql_order $sql_page";
}

//篩選狀態判定
if (isset($_GET["status"])) {
    $status = $_GET["status"];
    switch($status){
        case "on":
            $sql_status = "available = 1";
            break;
        case "off":
            $sql_status = "available = 0";
            break;
        default:
            $status ="all";
            $sql_status = "";
    }
}else{
    $status ="all";
    $sql_status = "";
}

if ($SessRole == "shop") {

    if($sql_status != ""){
        $sql_status = "AND " . $sql_status;
    }
    $sql = "SELECT * FROM product WHERE shop_id=$shopId AND deleted = 0 $sql_status $sql_order $sql_page";

} elseif ($SessRole == "admin") {

    if($sql_status != ""){
        $sql_status = "WHERE " . $sql_status;
    }
    $sql = "SELECT * FROM product $sql_status $sql_order $sql_page";

}


if(isset($_GET["search"]) && !empty($_GET["search"])){
    $search = $_GET["search"];

    if ($SessRole == "shop"){
        $sql = "SELECT * FROM product WHERE shop_id=$shopId AND name LIKE '%$search%' AND deleted = 0 $sql_status $sql_order $sql_page";
    }elseif($SessRole == "admin"){
        $sql = "SELECT * FROM product WHERE name LIKE '%$search%' $sql_status $sql_order $sql_page";
    }

    $nav_page_name .= "&search=".$search;
}

echo $sql;
echo $nav_page_name;

$filter_result = $conn->query($sql);
$filter_rows = $filter_result->fetch_all(MYSQLI_ASSOC);
$productCount = $filter_result->num_rows;

$filter_total_page = ceil($productCount / $per_page);   //計算總頁數(無條件進位)

echo $filter_total_page . '<BR>';
echo $productCount;

//↓做成陣列的資料

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


            <p><?= $productCount ?>/<?= $allProductCount ?>筆 </p>
            <div class="container-fluid">
                <ul class="nav nav-tabs-custom">
                    <li class="nav-item">
                        <a class="main-nav nav-link <?= $status === 'all' ? 'active' : '' ?>" href="?status=all">全部</a>
                    </li>
                    <li class="nav-item">
                        <a class="main-nav nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on">上架中</a>
                    </li>
                    <li class="nav-item">
                        <a class="main-nav nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off">已下架</a>
                    </li>
                </ul>

                <!-- 篩選表單 -->
                <div class="">
                    <form action="product-list.php" method="get">
                        <div class="input-group">
                            <input type="search" class="form-control" placeholder="品名關鍵字" name="search" value="<?=isset($_GET["search"])?$_GET["search"]:"";?>">
                            <!-- <select class="form-select" aria-label="Default select example" name="class">
                                <option value="all">分類</option>
                                <option value="1">蛋糕</option>
                                <option value="2">餅乾</option>
                                <option value="3">塔 / 派</option>
                                <option value="4">泡芙</option>
                                <option value="5">冰淇淋</option>
                                <option value="6">其他</option>
                            </select> -->
                            <!-- <select class="form-select" aria-label="Default select example" name="sort">
                                <option value="id">依課程編號排序(預設)</option>
                                <option value="count">依報名人數排序</option>
                                <option value="date">依時間排序</option>
                            </select> -->
                            <button class="btn neumorphic" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>

                <hr>

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
                                        <?php if ($row["available"] == 1): ?>
                                            <a class="btn btn-success dontNextLine" href="../function/doProductValidSwitch.php?productId=<?= $row["product_id"] ?>">上架中</a>
                                        <?php else: ?>
                                            <a class="btn btn-danger dontNextLine" href="../function/doProductValidSwitch.php?productId=<?= $row["product_id"] ?>">已下架</a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $row["stocks"] ?></td>
                                    <?php if ($SessRole == "admin"): ?>
                                        <td class="text-center"><?= $row["deleted"] == 0 ? "" : "<span class='btn btn-danger'>已刪除</span>"; ?></td>
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

                    <?php if (isset($page)) : ?>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination d-flex justify-content-center">
                                <?php for ($i = 1; $i <= $filter_total_page; $i++): ?>

                                    <?php if($i >= $page-5 && $i<= $page+5): ?>
                                        <li class="page-item px-1 <?= $i==$page?"active":""; ?>">
                                            <a class="page-link btn-custom" href="
                                                <?php if(isset($_GET["p"])){
                                                    echo "$nav_page_name" . "&p=" . $i ;
                                                }?>
                                            "><?=$i?>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                <?php endfor; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
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