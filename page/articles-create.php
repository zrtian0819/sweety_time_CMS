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

// 文章
$sql = "SELECT * FROM articles";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// print_r($row);

// 商品類別
$sqlClass = "SELECT * FROM product_class";
$ClassResult = $conn->query($sqlClass);
$classRows = $ClassResult->fetch_all(MYSQLI_ASSOC);
$shopClassArr = [];
foreach ($classRows as $classRow) {
    $shopClassArr[$classRow["product_class_id"]] = $classRow["class_name"];
}
// print_r($shopClassArr);

//使用者
$sqlUsers = "SELECT user_id,name FROM users";
$usersResult = $conn->query($sqlUsers);
$usersRows = $usersResult->fetch_all(MYSQLI_ASSOC);

//使用者名陣列
$usersArr = [];
foreach ($usersRows as $usersRow) {
    $usersArr[$usersRow["user_id"]] = $usersRow["name"];
}
// print_r($usersArr);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章新增頁</title>
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
            <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="articles.php">
                <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回文章列表</span>
            </a>

            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-12">
                        <form action="../function/doAdd4articles.php" method="post" enctype="multipart/form-data">
                            <div class="row d-flex align-items-center flex-column flex-xl-row">

                                <div class="col px-2">
                                    <h4 class="text-center">文章資訊</h4>
                                    <table class="table table-hover">

                                        <tr>
                                            <td class="dontNextLine fw-bold">主題</td>
                                            <td>
                                                <input name="title" class="form-control form-control-custom" type="text" required>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">建立時間</td>
                                            <td><input type="datetime-local" class="form-control form-control-custom" name="createTime"></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">文章分類</td>
                                            <td>
                                                <select class="form-select form-select-custom" id="" name="class" required>
                                                    <option value="">選擇文章類別</option>
                                                    <?php foreach ($shopClassArr as $shopClassKey => $shopClassValue): ?>
                                                        <option value="<?= $shopClassKey ?>"><?= $shopClassValue ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">文章內容</td>
                                            <td>
                                                <textarea name="content" class="form-control textarea-custom" id="tiny" rows="5" placeholder="請輸入文章內容"></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="photo-upload">
                                    
                                        <h4 class="text-center">圖片上傳</h4>

                                        <div class="container d-flex flex-column">
                                            <label for="fileUpload" class="custom-file-upload my-2">
                                                新增圖片
                                            </label>
                                            <input type="file" name="pic[]" id="fileUpload" class="file-input" accept=".jpg, .png, .jpeg, .gif" multiple>
                                            <div class="row row-cols-6 d-flex" id="preview-imgbox">
                                                <!-- 圖片預覽區 -->
                                            </div>
                                        </div>
                                    </div>

                                <div class="option-area d-flex justify-content-center mt-4 ">
                                    <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="articles.php">取消</a>
                                    <button class="btn btn-neumorphic px-4 mx-3 fw-bolder" type="submit">新增文章</button>
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
        const fileUpload = document.querySelector("#fileUpload");

fileUpload.addEventListener('change', function(event) {

    const files = event.target.files; // 取得所有選擇的文件
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

        //所見即所得編輯器
        tinymce.init({
            selector: 'textarea#tiny'
        });
        document.addEventListener('focusin', (e) => {
            if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
                e.stopImmediatePropagation();
            }
        });
    </script>
</body>

</html>