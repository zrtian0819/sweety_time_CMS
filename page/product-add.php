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
// echo $SessRole;

// 商品類別
$sqlClass = "SELECT * from product_class";
$ClassResult = $conn->query($sqlClass);
$classRows = $ClassResult->fetch_all(MYSQLI_ASSOC);
$shopClassArr = [];
foreach ($classRows as $classRow) {
    $shopClassArr[$classRow["product_class_id"]] = $classRow["class_name"];
}
// print_r($shopClassArr);

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
            background-color: #ccc;
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
            <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="product-list.php">
                <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回</span>
            </a>

            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-12">
                        <form action="../function/doAddProduct.php" method="post" enctype="multipart/form-data">
                            <div class="row d-flex align-items-center flex-column flex-xl-row">

                                <div class="col px-2">
                                    <h4 class="text-center">商品資訊</h4>
                                    <table class="table table-hover">

                                        <tr>
                                            <td class="dontNextLine fw-bold">商家</td>
                                            <td>
                                                <?php if ($SessRole == "admin"): ?>
                                                    <select name="shop_id" id="" class="form-control form-control-custom" require>
                                                        <option value="" disabled selected>選擇您的商家</option>
                                                        <?php foreach ($storeArr as $shopKey => $shopValue): ?>
                                                            <option value="<?= $shopKey ?>"><?= $shopValue ?></option>
                                                        <?php endforeach; ?>

                                                    </select>
                                                <?php elseif ($SessRole == "shop"): ?>
                                                    <input type="hidden" name="shop_id" value="<?= $shopId ?>">
                                                    <?= $storeArr[$shopId] ?>
                                                <?php endif; ?>
                                            </td>


                                        </tr>

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
                                                <select class="form-select form-select-custom" id="" name="class" require>
                                                    <option value="">選擇商品類別</option>
                                                    <?php foreach ($shopClassArr as $shopClassKey => $shopClassValue): ?>
                                                        <option value="<?= $shopClassKey ?>"><?= $shopClassValue ?></option>
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

                                <div class="photo-upload">
                                    <h4 class="text-center">圖片上傳</h4>

                                    <div class="container d-flex flex-column">
                                        <label for="fileUpload" class="custom-file-upload my-2">
                                            選擇圖片
                                        </label>
                                        <input type="file" name="pic[]" id="fileUpload" class="file-input" accept=".jpg, .png, .jpeg, .gif" multiple>
                                        <div class="row row-cols-6 d-flex" id="preview-imgbox">
                                            <!-- 圖片預覽區 -->
                                        </div>
                                    </div>

                                </div>


                                <div class="option-area d-flex justify-content-center mt-4 ">
                                    <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="product-list.php">取消</a>
                                    <button class="btn btn-neumorphic px-4 mx-3 fw-bolder" type="submit">新增商品</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </main>

    </div>

    <?php include("../js.php"); ?>

    <script>
        // const previewImgBox = document.querySelector("#preview-imgbox");
        const fileUpload = document.querySelector("#fileUpload");

        fileUpload.addEventListener('change', function(event) {

            const files = event.target.files; // 取得所有選擇的文件
            // const preview = document.getElementById('preview');
            const previewImgBox = document.querySelector("#preview-imgbox");
            previewImgBox.innerHTML = ''; // 清空預覽區域

            if (files) {
                // 遍歷所有選擇的文件
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.width = '120px';
                        img.style.height = '120px';
                        img.style.margin = '10px';
                        img.style.objectFit = "cover";
                        img.style.borderRadius = '5px';

                        previewImgBox.appendChild(img); // 將圖片添加到預覽區域
                    }

                    reader.readAsDataURL(file); // 將文件讀取為 Data URL
                }
            }
        });
    </script>
</body>

</html>