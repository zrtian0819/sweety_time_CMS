<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增優惠券</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .coupon-input_bar{
            /* width: 1000px; */
        }
        .coupon-submit-btn{
            width: 300px;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <div class="">
                <a href="./coupon-home.php">優惠券管理</a>>
                <a href="./coupon-create.php">新增優惠券</a>
            </div>
            <hr>
            <div class="container">
                <form action="../function/doCreateCoupon.php" method="post">
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <label class="form-label" for="name">優惠券名稱</label>
                            <input type="text" class="form-control coupon-input_bar " name="name" required>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <label class="form-label" for="discount_rate">折扣率%</label>
                            <input type="number" class="form-control coupon-input_bar" id="score" name="discount_rate" min="0" max="100" step="1" required>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <label class="form-label" for="start_time">啟用日</label>
                            <input type="date" class="form-control coupon-input_bar" name="start_time" required>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <label class="form-label" for="end_date">到期日(未填則視為永久有效)</label>
                            <input type="date" class="form-control coupon-input_bar" name="end_date">
                        </div> 
                    </div>
                    <div class="mb-2 row">
                        <label class="form-label" for="phone">啟用狀態</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="activation" value="1" checked>
                            <label class="form-check-label" for="activation">啟用</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="activation" value="0">
                            <label class="form-check-label" for="activation">停用</label>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <button class="btn btn-neumorphic coupon-submit-btn" type="submit">新增他！</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    
    <?php include("../js.php"); ?>
    
    <!-- 顯示新增成功or失敗訊息 -->
    <!-- 有時間可以用中介頁面來避免使用GET -->
    <!-- 有時間可以用別的設計取代alert -->
    <?php
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    ?>
</body>
</html>