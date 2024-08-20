<?php
require_once("../db_connect.php");

if(!isset($_GET["productId"])){
    $message = "請依照正常管道進入此頁";

}else{
    // 有取得商品id時的情況
    $id = $_GET["productId"];
    $sql = "SELECT * from product WHERE product_id = $id";

    $result = $conn->query($sql);
    $count = $result->num_rows;
    $row = $result -> fetch_assoc();

    // echo $row["available"];

    if(isset($row["shop_id"])){
        $shopId = $row["shop_id"];
        $shopsql = "SELECT * from shop WHERE shop_id = $shopId";
        $shopResult = $conn->query($shopsql);
        $shopRow = $shopResult -> fetch_assoc();

        $shopName = $shopRow["name"];
    }

    if(isset($row["product_class_id"])){
        $classId = $row["product_class_id"];
        $classsql = "SELECT * from product_class WHERE product_class_id = $classId";
        $classResult = $conn->query($classsql);
        $classRow = $classResult -> fetch_assoc();

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
    // $photosql = "SELECT * FROM product_photo 
    // WHERE is_valid = 1 AND product_id = $id
    // ORDER BY product_id";
    
    // $photorResult = $conn->query($photosql);
    // $photoRows= $photorResult->fetch_all(MYSQLI_ASSOC);
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
        main *{
            /* border: 1px solid red; */
        }

        .dontNextLine{
            white-space: nowrap;
            text-align: end;
            padding-right: 30px !important;
            /* background-color: var(--primary-color); */
        }

        .img-box{
            aspect-ratio: 1;
            border-radius: 20px;
            margin: 20px;
            /* box-shadow: 0 0 15px #F4A293; */
            overflow: hidden;
            transition: 0.5s;

            &:hover{
                /* box-shadow: 0 0 40px #F4A293; */
            }
        }

        .img-small{
            aspect-ratio: 1;
        }

        .text-attention{
            color: red !important;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <main class="product main col neumorphic p-5">

            <h2 class="mb-5 text-center">商品編輯</h2>

            <?php if(isset($_GET["productId"])): ?>
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <form action="../function/doUpdateProduct.php" method="post">
                                <div class="row d-flex align-items-center flex-column flex-xl-row">
                                    
                                    <div class="col px-2">
                                        <table class="table table-hover">
                                            <tr>
                                                <td class="dontNextLine fw-bold">id</td>
                                                <td>
                                                    <?=$id?>
                                                    <input type="hidden" name="id" value="<?= $row["product_id"] ?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">品名</td>
                                                <td>
                                                    <input name="name" class="form-control form-control-custom" type="text" value="<?=$row["name"]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">價格</td>
                                                <td>
                                                    <input name="price" class="form-control form-control-custom" type="number" value="<?=$row["price"]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">庫存</td>
                                                <td>
                                                    <input name="stocks" class="form-control form-control-custom" type="number" value="<?= $row["stocks"]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">商品分類</td>
                                                <td>
                                                    <select class="form-select form-select-custom" id="country" name="class">
                                                        <option selected disabled>類別</option>
                                                        <?php foreach($classRows as $classRow): ?>
                                                        <option  <?=$row["product_class_id"]==$classRow["product_class_id"]?"selected":"";?>  value="<?=$classRow["product_class_id"]?>"><?=$classRow["class_name"]?></option>
                                                        <?php endforeach; ?>      
                                                    </select>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">描述</td>
                                                <td>
                                                    <textarea name="description" class="form-control textarea-custom" id="message" rows="5" placeholder="請輸入描述" >
                                                        <?=$row["description"]?>
                                                    </textarea>
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
                                                    <input name="discount" class="form-control form-control-custom" type="number" value="<?=$row["discount"]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">上架</td>
                                                <td>
                                                    <select name="available" class="form-select form-select-custom" id="country" require>
                                                        <option disabled >請選擇上架狀態</option>
                                                        <option <?=$row["available"]==0?"selected":"";?> value="0">已下架</option>
                                                        <option <?=$row["available"]==1?"selected":"";?> value="1">上架中</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">標籤</td>
                                                <td>
                                                    <input name="label" class="form-control form-control-custom" type="text" value="<?=$row["label"]?>">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="dontNextLine fw-bold">建立時間</td>
                                                <td><?=$row["created_at"]?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                    <div class="option-area d-flex justify-content-center mt-4 ">
                                        <button class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="#" type="submit">儲存</button>
                                    </div>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <p><?=$message?></p>
            <?php endif; ?>

        </main>

    </div>

    <?php include("../js.php"); ?>
</body>

</html>