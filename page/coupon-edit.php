<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");

if(!isset($_GET['coupon_id'])) {
    header("location:./coupon-list.php?message=請選擇要編輯哪張優惠券！");
    exit;
}else{
    $coupon_id = $_GET['coupon_id'];

    $sql = "SELECT * FROM coupon WHERE coupon_id =?";
    $stmt = $conn -> prepare($sql);
    $stmt -> bind_param("i", $coupon_id);
    $stmt -> execute();
    $result = $stmt -> get_result();
    $row = $result -> fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯優惠券內容</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .coupon-input_bar{
            /* width: 1000px; */
        }
        .coupon-submit-btn{
            width: 300px;
        }
        .coupon-id-text{
            font-size: 18px;
            font-weight: 500;
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
                <a href="./coupon-list.php">優惠券種類一覽</a>>
                <a href="./coupon-edit.php">編輯優惠券內容</a>
            </div>
            <hr>
            <div class="container">
                <form action="../function/doEditCoupon.php" method="post">
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <p class="coupon-id-text">現正編輯的優惠券id：<?= $coupon_id ?></p>
                            <input type="hidden" name="coupon_id" value="<?= $row["coupon_id"] ?>">
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <label class="form-label" for="name">優惠券名稱</label>
                            <input type="text" class="form-control coupon-input_bar " name="name" value="<?= $row["name"] ?>" required>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <label class="form-label" for="discount_rate">折扣率%</label>
                            <input type="number" class="form-control coupon-input_bar" id="score" name="discount_rate" min="0" max="100" step="1" value="<?= $row["discount_rate"]*100 ?>" required>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <label class="form-label" for="start_time">啟用日</label>
                            <input type="date" class="form-control coupon-input_bar" name="start_time" value="<?= $row["start_time"] ?>" required>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <div class="col-xl-6 col-lg-12">
                            <label class="form-label" for="end_date">到期日(未填則視為永久有效)</label>
                            <input type="date" class="form-control coupon-input_bar" name="end_date" value="<?= $row["end_date"] ?>">
                        </div> 
                    </div>
                    <div class="mb-2 row">
                        <label class="form-label" for="phone">啟用狀態</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="activation" value="1" <?php echo $row["activation"] == 1 ? "checked" : "" ?>>
                            <label class="form-check-label" for="activation">啟用</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="activation" value="0" <?php echo $row["activation"] == 0 ? "checked" : "" ?>>
                            <label class="form-check-label" for="activation">停用</label>
                        </div>
                    </div>
                    <div class="mb-2 row">
                        <button class="btn btn-neumorphic coupon-submit-btn" type="submit">確定編輯！</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include("../js.php"); ?>
</body>
</html>