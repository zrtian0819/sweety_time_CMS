<?php
require_once("../db_connect.php");

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

    //商品類別
    $sqlClass = "SELECT * from product_class";
    $ClassResult = $conn->query($sqlClass);
    $classRows = $ClassResult->fetch_all(MYSQLI_ASSOC);

    //查看類別用
    // foreach ($classRows as $classRow){
    //     echo print_r($classRow)."<br>";
    // }

    //撈出照片檔
    $photosql = "SELECT * FROM product_photo 
    WHERE is_valid = 1 AND product_id = $id
    ORDER BY product_id";

    $photorResult = $conn->query($photosql);
    $photoRows = $photorResult->fetch_all(MYSQLI_ASSOC);
    $photoCount = $photorResult-> num_rows;

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品編輯頁</title>
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

        .subPhoto{
            width: 120px;
            height: 120px;
            margin: 10px;
            object-fit: cover;
            border-radius: 5px;
        }

        .delPhoto{
            transition:0.2s;
            cursor: pointer;
            position: relative;

            .crossCover{
                z-index: 2;
                position: absolute;
                width: 100%;
                height: 100%;
                color:white;
                font-size: 2rem;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                
                
            }

            &:hover{
                scale: 1.05;
                .crossCover{
                    opacity: 1;
                }

                img{
                    filter: contrast(0.4) brightness(1.2);
                }
            }
            &:active{
                transition:0s;
                scale: 0.98;
            }


        }

        

    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <main class="product main col neumorphic p-5">

            <h2 class="mb-5 text-center">商品編輯</h2>
            <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="product.php?productId=<?=$id?>">
                <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回</span>
            </a>

            <?php if (isset($_GET["productId"])): ?>
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <form action="../function/doUpdateProduct.php" method="post" enctype="multipart/form-data">
                                <div class="row d-flex align-items-center flex-column flex-xl-row">

                                    <div class="col px-2">
                                        <table class="table table-hover">
                                            <tr>
                                                <td class="dontNextLine fw-bold">id</td>
                                                <td>
                                                    <?= $id ?>
                                                    <input type="hidden" name="id" value="<?= $row["product_id"] ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">品名</td>
                                                <td>
                                                    <input name="name" class="form-control form-control-custom" type="text" value="<?= $row["name"] ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">價格</td>
                                                <td>
                                                    <input name="price" class="form-control form-control-custom" type="number" value="<?= $row["price"] ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">庫存</td>
                                                <td>
                                                    <input name="stocks" class="form-control form-control-custom" type="number" value="<?= $row["stocks"] ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">商品分類</td>
                                                <td>
                                                    <select class="form-select form-select-custom" id="country" name="class" required>
                                                        <option selected disabled>類別</option>
                                                        <?php foreach ($classRows as $classRow): ?>
                                                            <option <?= $row["product_class_id"] == $classRow["product_class_id"] ? "selected" : ""; ?> value="<?= $classRow["product_class_id"] ?>"><?= $classRow["class_name"] ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">描述</td>
                                                <td>
                                                    <textarea name="description" class="form-control textarea-custom" id="message" rows="5" placeholder="請輸入描述"><?= $row["description"] ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">關鍵字</td>
                                                <td>
                                                    <input name="keywords" class="form-control form-control-custom" type="text" value="<?= $row["keywords"] ?>" placeholder="請用「,」逗號隔開字串">
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
                                                    <select name="available" class="form-select form-select-custom" id="country" required>
                                                        <option disabled>請選擇上架狀態</option>
                                                        <option <?= $row["available"] == 0 ? "selected" : ""; ?> value="0">下架</option>
                                                        <option <?= $row["available"] == 1 ? "selected" : ""; ?> value="1">上架</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">標籤</td>
                                                <td>
                                                    <input name="label" class="form-control form-control-custom" type="text" value="<?= $row["label"] ?>">
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="photo-upload">
                                        <h4 class="text-center">刪除與新增圖片</h4>
                                        
                                        <div class="container">
                                            <h6>請選擇欲刪除的圖片</h6>
                                            <div class="row row-cols-6 d-flex" id="fileEdit">
                                                <?php foreach($photoRows as $photoRow): ?>
                                                    <div class="subPhoto delPhoto overflow-hidden">
                                                        <div class="crossCover"><i class="fa-solid fa-xmark"></i></div>
                                                        <img class="w-100 h-100 object-fit-cover" src="../images/products/<?=$photoRow["file_name"]?>" alt="">
                                                        <input class="delControl" type="hidden" name="delFiles[]" value="<?=$photoRow["product_photo_id"]?>" disabled>
                                                    </div>
                                                <?php endforeach; ?> 
                                            </div>
                                        </div>

                                        <div class="container d-flex flex-column">
                                            <label for="fileUpload" class="custom-file-upload my-2">
                                                新增圖片<i class="fa-solid fa-arrow-down"></i>
                                            </label>
                                            <input type="file" name="pic[]" id="fileUpload" class="file-input" accept=".jpg, .png, .jpeg, .gif" multiple>
                                            <div class="row row-cols-6 d-flex" id="preview-imgbox">
                                                <!-- 圖片預覽區 -->
                                            </div>
                                        </div>

                                    </div>

                                    <div class="option-area d-flex justify-content-center mt-4 ">
                                        <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="product.php?productId=<?= $row["product_id"] ?>">取消</a>
                                        <button class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="#" type="submit">儲存</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

            <?php else: ?>
                <p><?= $message ?></p>
            <?php endif; ?>

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
                        img.classList.add("subPhoto");
                        previewImgBox.appendChild(img); // 將圖片添加到預覽區域
                    }

                    reader.readAsDataURL(file); // 將文件讀取為 Data URL
                }
            }
        });


        document.querySelectorAll('.delPhoto .crossCover').forEach(function(cover) {
        cover.addEventListener('click', function() {
            console.log("click",this);
            var input = this.parentElement.querySelector('.delControl');
            var img = this.parentElement.querySelector('img');

            if (input.disabled) {
                input.disabled = false;
                this.style.opacity = "1";
                img.style.opacity = "0.3"
            } else {
                input.disabled = true;
                this.style.opacity = "0";
                img.style.opacity = "1"
            }
            });
        });


    </script>
</body>

</html>