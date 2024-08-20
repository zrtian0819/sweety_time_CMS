<?php
require_once("../db_connect.php");      //避免sidebar先載入錯誤,人天先加的

// 獲取網址中的 shopId 參數
$shop_id = isset($_GET['shopId']) ? intval($_GET['shopId']) : 0;

if ($shop_id > 0) {
    // 根據 shop_id 查詢店家資訊
    $sql_shop_info = "SELECT * FROM shop WHERE shop_id = $shop_id";
    $result_shop_info = $conn->query($sql_shop_info);

    if ($result_shop_info->num_rows > 0) {
        $shop_info = $result_shop_info->fetch_assoc();
        $name = $shop_info["name"];
        $phone = $shop_info["phone"];
        $address = $shop_info["address"];
        $description = $shop_info["description"];
        $sign_up_time = $shop_info["sign_up_time"];
        $latitude = $shop_info["latitude"];
        $longitude = $shop_info["longitude"];
    } else {
        echo "找不到指定的店家";
        exit;
    }

    // 根據 shop_id 查詢店家照片
    $sql_shop_photo = "SELECT * FROM shop_photo WHERE shop_id = ?";
    $stmt_shop_photo = $conn->prepare($sql_shop_photo);
    $stmt_shop_photo->bind_param("i", $shop_id);
    $stmt_shop_photo->execute();
    $result_shop_photo = $stmt_shop_photo->get_result();

    if ($result_shop_photo->num_rows > 0) {
        $shop_photo = $result_shop_photo->fetch_assoc();
        $file_name = $shop_photo["file_name"];
    } else {
        echo "找不到指定的店家照片";
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
                            <img class="shop-info-logo" src="../images/shop_logo/<?=$file_name;?>" alt="店家Logo">
                            <button class="btn btn-secondary change-image-btn">更換圖片</button>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5 px-4 shop-info-detail">
                        <h3 class="mb-3">店家資訊</h3>
                        <ul class="list-unstyled">
                            <li class="my-2">店名：<input type="text" class="form-control" name="name" value="<?= $name;?>"></li>
                            <li class="my-2">電話：<input type="text" class="form-control" name="phone" value="<?= $phone;?>"></li>
                            <li class="my-2">地址：<input type="text" class="form-control" name="address" value="<?= $address;?>"></li>
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
                    <div>
                        <a href="shop-edit.php?id=<?=$row["shop_id"]?>" class="btn btn-primary"><i class="fa-solid fa-user-pen"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
</body>

</html>