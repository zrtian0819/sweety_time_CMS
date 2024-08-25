<?php
require_once("../db_connect.php");      //避免sidebar先載入錯誤,人天先加的

include("../function/login_status_inspect.php");

$shop_id = $_SESSION["shop"]["shop_id"];


if(isset($_SESSION["shop"]["shop_id"])){

    if(isset($_GET["shopId"])){
        if( $_GET["shopId"]!=$_SESSION["shop"]["shop_id"] ){
            header("location: shop-info.php?shopId=".$_SESSION["shop"]["shop_id"]);
        }
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
    <script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script>
    <style>
        .shop-logo-container {
            min-width: 150px;
            width: 100%;
            max-width: 300px; 
            display: flex;
            justify-content: center;
            align-items: center;
            background-color:rgba(0, 0, 0, 0.1);

        }
        .shop-logo-container img {
            width: 100%;
            height: auto; 
            object-fit: contain; 
            max-height: 300px; 
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <div class="d-flex justify-content-between">
                <h2 class="mb-3">基本資料</h2>
                <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="shop-info-admin.php">
                    <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回</span>
                </a>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 col-lg-5 position-relative d-flex justify-content-center align-items-center mb-3 mb-md-0">
                            <div class="shop-logo-container">
                                <img src="../images/shop_logo/<?=$logo_path;?>?t=<?=time();?>" alt="<?= htmlspecialchars($shop_name) ?> Logo">
                            </div>
                        </div>
                    <div class="col-12 col-md-6 col-lg-6 px-4 shop-info-detail">
                        <h3 class="mb-3 <?= $activation == 0 ? 'text-danger' : 'text-success'; ?>"><?= $activation == 0 ? '關閉中' : '啟用中'; ?></h3>
                    <form action="../function/doUploadLogo.php" method="POST" enctype="multipart/form-data">
                                <!-- 隱藏字段，用於傳遞shop_id -->
                                <input type="hidden" name="shop_id" value="<?= $shop_id ?>">

                                <!-- 檔案上傳字段 -->
                                <div class="mb-3">
                                    <label for="shop_logo" class="form-label">店家Logo</label>
                                    <input type="file" class="form-control" id="shop_logo" name="shop_logo">
                                </div>

                                <!-- 其他表單字段 -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">店名</label>
                                    <input type="text" class="form-control" id="name" name="name" value="<?= $shop_name ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">電話</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?= $phone ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">地址</label>
                                    <input type="text" class="form-control" id="address" name="address" value="<?= $address ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">店家簡介</label>
                                    <textarea class="form-control" id="editor" name="description" rows="5" required><?= $description ?></textarea>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-secondary m-2">儲存變更</button>
                                </div>
                            </form>
                            <?php if ($activation == 0): ?>
                            <div class="d-flex justify-content-end">
                                <form action="../function/doReUseShop.php" method="POST">
                                    <input type="hidden" name="shop_id" value="<?= $shop_id ?>"> 
                                    <button type="submit" class="btn btn-success m-2 p-2"><i class="fa-solid fa-check"></i> 啟用商家</button>
                                </form>
                            </div>
                            <?php endif; ?>
                            <?php if ($activation == 1): ?>
                            <div class="d-flex justify-content-end">
                                <form action="../function/doDeleteShop.php" method="POST">
                                    <input type="hidden" name="shop_id" value="<?= $shop_id ?>"> 
                                    <button type="submit" class="btn btn-danger m-2 p-2"><i class="fa-solid fa-trash"></i> 刪除商家</button>
                                </form>
                            </div>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
    
    <!-- ck editor -->
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>