<?php
require_once("../db_connect.php");      //避免sidebar先載入錯誤,人天先加的
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard-home_Joe</title>
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
                    <div class="col-12 col-md-6 col-lg-5 position-relative d-flex justify-content-center mb-3 mb-md-0">
                        <a href="">
                            <img class="shop-info-logo" src="../images/shop_logo/法國主廚的甜點Nosif_logo.jpg" alt="店家Logo">
                            <button class="btn btn-secondary change-image-btn">更換圖片</button>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5 px-4 shop-info-detail">
                        <h3 class="mb-3">店家資訊</h3>
                        <p>店名：法國主廚的甜點Nosif</p>
                        <p>電話：0912345678</p>
                        <p>地址：台北市中山區中山路一段123號</p>
                        <p>開業時間：09:00 - 21:00</p>
                        <p>網站：<a href="URL_ADDRESS">URL_ADDRESS</a></p>
                        <p>電子信箱：nosif@gmail.com</p>
                    </div>
                </div>
            </div>



        </div>
    </div>

    <?php include("../js.php"); ?>
</body>

</html>