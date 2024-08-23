<?php
require_once("../db_connect.php");      //避免sidebar先載入錯誤,人天先加的

if (session_status() == PHP_SESSION_NONE) {  //啟動session
    session_start();
}

// 检查用户是否已登录以及是否有角色信息
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION["user"]["role"];
 //假設session之中沒有shop_id則為NULL
$shop_id = $_SESSION["shop"]["shop_id"] ?? null; 

// 根据角色重定向到不同頁面
if ($role == "admin") {
    header("Location: shop-info-admin.php");
    exit;
} elseif ($role != "shop" || !$shop_id) {
    // 如果不是admin也不是shop，或者没有shop_id
    header("location: dashboard-home_Joe.php");
    exit;
}

if(isset($_SESSION["shop"]["shop_id"])){
    
    if($shop_id == "admin"){
        header("location: dashboard-home_Joe.php");
    }

    if (isset($_GET["shopId"]) && $_GET["shopId"] != $shop_id) {    //防止用戶改網頁的id來進入別人的頁面
        header("location: shop-info.php?shopId=$shop_id");
        exit;
    }    

}else{
    header("location: dashboard-home_Joe.php");
}


if ($shop_id > 0) {
    // 根據 shop_id 查詢店家資訊
    $sql_shop_info = "  SELECT * 
                        FROM shop
                        WHERE shop.shop_id = $shop_id";


    $result_shop_info = $conn->query($sql_shop_info);

    if ($result_shop_info->num_rows > 0) {
        $shop_info = $result_shop_info->fetch_assoc();
        $shop_name = $shop_info["name"];
        $phone = $shop_info["phone"];
        $address = $shop_info["address"];
        $description = $shop_info["description"];
        $sign_up_time = $shop_info["sign_up_time"];
        $latitude = $shop_info["latitude"];
        $longitude = $shop_info["longitude"];
        $logo_path = $shop_info["logo_path"];
        $activation = $shop_info["activation"];

    } else {
        echo "找不到指定的店家";
        exit;
    }
} else {
    echo "未提供有效的shopId";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商家基本資料</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <h2 class="mb-3">基本資料</h2>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-5 position-relative d-flex justify-content-center align-items-center mb-3 mb-md-0">
                        <a href="">
                            <img class="shop-info-logo" src="../images/shop_logo/<?=$logo_path;?>?t=<?=time();?>" alt="店家Logo">
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5 px-4 shop-info-detail">
                        <h3 class="mb-3 <?= $activation == 0 ? 'text-danger' : 'text-success'; ?>"><?= $activation == 0 ? '關閉中' : '啟用中'; ?></h3>
                        <h3 class="mb-3">店家資訊</h3>
                        <ul class="list-unstyled">
                            <li class="my-2">店名：<?= $shop_name;?></li>
                            <li class="my-2">電話：<?= $phone;?></li>
                            <li class="my-2">地址：<?= $address;?></li>
                            <li class="my-2">註冊時間：<?= $sign_up_time;?></li>
                            <li class="my-2">經緯度：<?= $longitude;?>,<?= $latitude;?></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <h2 class="mb-3">店家簡介</h2>
                <div class="container">
                    <div class="row">
                        <div class="col-12 position-relative d-flex justify-content-center mb-3 mb-md-0">
                            <ul class="list-unstyled">
                                <li class="my-2"><?= $description;?></li>
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="shop-info-edit.php" class="btn btn-secondary"><i class="fa-solid fa-user-pen"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
</body>

</html>