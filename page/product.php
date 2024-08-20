<?php
require_once("../db_connect.php");

if(!isset($_GET["productId"])){
    $message = "請依照正常管道進入此頁";
}else{
    $id = $_GET["productId"];
    $sql = "SELECT * from product WHERE product_id = $id";

    $result = $conn->query($sql);
    $count = $result->num_rows;
    $row = $result -> fetch_assoc();

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

    //撈出照片檔
    $photosql = "SELECT * FROM product_photo 
    WHERE is_valid = 1 AND product_id = $id
    ORDER BY product_id";
    
    $photorResult = $conn->query($photosql);
    $photoRows= $photorResult->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$row["name"]?></title>
    <?php include("../css/css_Joe.php"); ?>

    <style>
        main *{
            /* border: 1px solid red; */
        }

        .dontNextLine{
            white-space: nowrap;
            text-align: end;
            padding-right: 30px !important;
        }

        .img-box{
            aspect-ratio: 1;
            border-radius: 10px;
            margin-bottom: 20px;
            /* box-shadow: 0 0 15px #F4A293; */
            overflow: hidden;
            transition: 0.5s;

            &:hover{
                /* box-shadow: 0 0 40px #F4A293; */
            }
        }

        .img-small{
            aspect-ratio: 1;
            border-radius: 10px;
            overflow:hidden;
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

            <h2 class="mb-5 text-center">商品管理</h2>
            <?php if(isset($_GET["productId"])): ?>
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-11">
                            <div class="row d-flex align-items-center flex-column flex-xl-row">
                                <div class="col col-xl-5 px-2 ">
                                    <div class="img-box">
                                        <img id="majorPhoto" class="w-100 h-100 object-fit-cover" src="../images/prdoucts/<?=$photoRows[0]["file_name"]?>" alt="">
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="row w-100">
                                            <?php foreach($photoRows as $photoItem): ?>
                                            <div class="img-small col-3">
                                                <img class="smallImg w-100 h-100 object-fit-cover" src="../images/prdoucts/<?=$photoItem["file_name"]?>" alt="">
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col col-xl-7 px-2">

                                <h3 class="mt-4 text-center text-xl-start"><?=$row["name"]?></h3>
                                <table class="table table-hover">
                                    <tr>
                                        <td class="dontNextLine fw-bold">id</td>
                                        <td><?=$id?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">上架店家</td>
                                        <td><?=$shopName?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">價格</td>
                                        <td><?=$row["price"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">庫存</td>
                                        <td><?= $row["stocks"] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">商品分類</td>
                                        <td><?=$className?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">描述</td>
                                        <td><?=$row["description"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">關鍵字</td>
                                        <td><?=$row["keywords"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">折扣</td>
                                        <td><?=$row["discount"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">上架</td>
                                        <td><?= $row["available"]=1?"上架中":'<span class="text-attention">下架中</span>?>'?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">標籤</td>
                                        <td><?=$row["label"]?></td>
                                    </tr>
                                    <tr>
                                        <td class="dontNextLine fw-bold">建立時間</td>
                                        <td><?=$row["created_at"]?></td>
                                    </tr>
                                </table>
                                </div>
                                
                                <div class="option-area d-flex justify-content-center mt-4 ">
                                    <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="product-edit.php?productId=<?=$id?>">編輯</a>
                                    <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="">下架</a>
                                    <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="">刪除</a>
                                </div>
                            </div>
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