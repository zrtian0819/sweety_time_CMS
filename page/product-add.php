<?php

require_once("../db_connect.php");
include("../function/function.php");
include("../function/login_status_inspect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$SessRole = $_SESSION["user"]["role"];
if ($SessRole == "shop") {
    $shopId = $_SESSION["shop"]["shop_id"];
}

goto a;

if (!isset($_GET["productId"])) {
    $message = "請依照正常管道進入此頁";
} else {
    // 有取得商品id時的情況
    $id = $_GET["productId"];
    $sql = "SELECT * from product WHERE product_id = $id";

    $result = $conn->query($sql);
    $count = $result->num_rows;
    $row = $result->fetch_assoc();

    // echo $row["available"];

    if (isset($row["shop_id"])) {
        $shopId = $row["shop_id"];
        $shopsql = "SELECT * from shop WHERE shop_id = $shopId";
        $shopResult = $conn->query($shopsql);
        $shopRow = $shopResult->fetch_assoc();

        $shopName = $shopRow["name"];
    }

    if (isset($row["product_class_id"])) {
        $classId = $row["product_class_id"];
        $classsql = "SELECT * from product_class WHERE product_class_id = $classId";
        $classResult = $conn->query($classsql);
        $classRow = $classResult->fetch_assoc();

        $className = $classRow["class_name"];
        // $shopsql = "SELECT "
    }

    a:
    // 商品類別
    $sqlClass = "SELECT * from product_class";
    $ClassResult = $conn->query($sqlClass);
    $classRows = $ClassResult->fetch_all(MYSQLI_ASSOC);
    $shopClassArr = [];
    foreach ($classRows as $classRow) {
        $shopClassArr[$classRow["product_class_id"]] = $classRow["class_name"];
    }
    print_r($shopClassArr);

    //撈出照片檔
    // $photosql = "SELECT * FROM product_photo 
    // WHERE is_valid = 1 AND product_id = $id
    // ORDER BY product_id";

    // $photorResult = $conn->query($photosql);
    // $photoRows= $photorResult->fetch_all(MYSQLI_ASSOC);


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

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品新增頁</title>
    <?php include("../css/css_Joe.php"); ?>

    <style>
        .dontNextLine {
            white-space: nowrap;
            text-align: end;
            padding-right: 30px !important;
            /* background-color: var(--primary-color); */
        }

        .img-box {
            aspect-ratio: 1;
            border-radius: 20px;
            margin: 20px;
            /* box-shadow: 0 0 15px #F4A293; */
            overflow: hidden;
            transition: 0.5s;
        }

        .img-small {
            aspect-ratio: 1;
        }

        .text-attention {
            color: red !important;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <main class="product main col neumorphic p-5">

            <h2 class="mb-5 text-center">新增商品</h2>

                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <form action="../function/doUpdateProduct.php" method="post">
                                <div class="row d-flex align-items-center flex-column flex-xl-row">

                                    <div class="col px-2">
                                        <table class="table table-hover">
                                            <?php if($SessRole="admin"):?>
                                            <tr>
                                                <td class="dontNextLine fw-bold">商家</td>
                                                <td>
                                                    
                                                    <select name="shop" id="" class="form-control form-control-custom" require>
                                                        <option value="" disabled selected>選擇您的商家</option>
                                                        <?php foreach($storeArr as $shopKey => $shopValue ): ?>
                                                            <option value="<?=$shopKey?>"><?=$shopValue?></option>
                                                        <?php endforeach; ?>
                                                    
                                                    </select>
                                                    
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <td class="dontNextLine fw-bold">品名</td>
                                                <td>
                                                    <input name="name" class="form-control form-control-custom" type="text">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">價格</td>
                                                <td>
                                                    <input name="price" class="form-control form-control-custom" type="number" placeholder="請輸入整數">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">庫存</td>
                                                <td>
                                                    <input name="stocks" class="form-control form-control-custom" type="number" value="" placeholder="請輸入整數">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">商品分類</td>
                                                <td>
                                                    <select class="form-select form-select-custom" id="" name="shops" require>
                                                        <option value="">選擇商品類別</option>
                                                        <?php foreach ($shopClassArr as $shopClassKey => $shopClassValue ): ?>
                                                            <option value="<?=$shopClassKey?>"><?= $shopClassValue ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">描述</td>
                                                <td>
                                                    <textarea name="description" class="form-control textarea-custom" id="message" rows="5" placeholder="請輸入描述"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">關鍵字</td>
                                                <td>
                                                    <input name="keywords" class="form-control form-control-custom" type="text" value="" placeholder="請用「,」逗號隔開字串">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">折扣</td>
                                                <td>
                                                    <input name="discount" class="form-control form-control-custom" placeholder="輸入小數點  例如:0.8" step="0.01" type="number" value="<?= $row["discount"] ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">上架</td>
                                                <td>
                                                    <select name="available" class="form-select form-select-custom" id="country" require>
                                                        <option value="0">下架</option>
                                                        <option value="1" selected>上架</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">標籤</td>
                                                <td>
                                                    <input name="label" class="form-control form-control-custom" type="text" value="">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="option-area d-flex justify-content-center mt-4 ">
                                        <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="product-list.php">取消</a>
                                        <button class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="#" type="submit">新增商品</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>


                <p><?= $message ?></p>


        </main>

    </div>

    <?php include("../js.php"); ?>
</body>

</html>