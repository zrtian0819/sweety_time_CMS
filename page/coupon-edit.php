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
            /* font-weight: 500; */
            font-size: 18px;
        }
        .last-edited-text{
            font-size: 16px;
        }
        .edit-area{
            width: 1000px;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <div class="container edit-area">
                <div class="mb-4 row d-flex justify-content-center">
                    <div class="col-8 col-xl-10 d-flex justify-content-center">
                        <h2>編輯優惠券</h2>
                    </div>
                </div>
                <form action="../function/doEditCoupon.php" method="post">
                    <div class="row d-flex justify-content-center">
                        <div class="col-8 col-xl-10 d-flex justify-content-end">
                            <div class="">
                                <div class="d-flex justify-content-end">
                                    <p class="coupon-id-text mt-3">現正編輯的優惠券id：<?= $coupon_id ?></p>
                                </div>
                                <p class="last-edited-text text-end">最後編輯：<?php echo $row["last_edited_at"] == NULL ? "無" : $row["last_edited_at"] ?></p>
                            </div>
                            <input type="hidden" name="coupon_id" value="<?= $row["coupon_id"] ?>">
                        </div>
                    </div>
                    <div class="mb-2 row d-flex justify-content-center">
                        <div class="col-8 col-xl-10">
                            <label class="form-label text-dark fw-bold" for="name">優惠券名稱</label>
                            <input type="text" class="form-control form-control-custom coupon-input_bar " name="name" value="<?= $row["name"] ?>" required>
                        </div>
                    </div>
                    <div class="mb-2 row d-flex justify-content-center">
                        <div class="col-8 col-xl-10">
                            <label class="form-label text-dark fw-bold mt-3" for="discount_rate">折扣率%</label>
                            <input type="number" class="form-control form-control-custom coupon-input_bar" id="score" name="discount_rate" min="0" max="100" step="1" value="<?= $row["discount_rate"]*100 ?>" required>
                        </div>
                    </div>
                    <div class="mb-2 row d-flex justify-content-center">
                        <div class="col-8 col-xl-10">
                            <label class="form-label text-dark fw-bold mt-3" for="start_time">啟用日</label>
                            <input type="date" class="form-control form-control-custom coupon-input_bar" name="start_time" value="<?= $row["start_time"] ?>" required>
                        </div>
                    </div>
                    <div class="mb-2 row d-flex justify-content-center">
                        <div class="col-8 col-xl-10">
                            <label class="form-label text-dark fw-bold mt-3" for="end_date">到期日(未填則視為永久有效)</label>
                            <input type="date" class="form-control form-control-custom coupon-input_bar" name="end_date" value="<?= $row["end_date"] ?>">
                        </div> 
                    </div>
                    <div class="mb-2 row d-flex justify-content-center">
                        <div class="col-8 col-xl-10">
                            <label class="form-label  text-dark fw-bold mt-3 me-3">啟用狀態</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-custom" type="radio" name="activation" value="1" <?php echo $row["activation"] == 1 ? "checked" : "" ?>>
                                <label class="form-check-label form-control-custom" for="activation">啟用</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input form-control-custom" type="radio" name="activation" value="0" <?php echo $row["activation"] == 0 ? "checked" : "" ?>>
                                <label class="form-check-label form-control-custom" for="activation">停用</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2 row d-flex justify-content-center">
                        <div class="col-8 col-xl-10 d-flex justify-content-center">
                            <button class="btn btn-neumorphic coupon-submit-btn" type="submit">確定編輯！</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include("../js.php"); ?>
</body>
</html>