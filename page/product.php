<?php
require_once("../db_connect.php");

if (!isset($_GET["productId"])) {
    $message = "請依照正常管道進入此頁";
    header("location: product-list.php");
} else {
    $id = $_GET["productId"];
    $sql = "SELECT * from product WHERE product_id = $id";

    $result = $conn->query($sql);
    $count = $result->num_rows;
    $row = $result->fetch_assoc();

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

    //使用者類別
    $sqlUser = "SELECT * from users";
    $userResult = $conn->query($sqlUser);
    $userRows = $userResult->fetch_all(MYSQLI_ASSOC);

    //使用者類別陣列
    $userArr = [];
    foreach ($userRows as $userRow) {
        $userArr[$userRow["user_id"]] = $userRow["name"];
    }

    //撈出照片檔
    $photosql = "SELECT * FROM product_photo 
    WHERE is_valid = 1 AND product_id = $id
    ORDER BY product_id";

    $photorResult = $conn->query($photosql);
    $photoRows = $photorResult->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $row["name"] ?></title>
    <?php include("../css/css_Joe.php"); ?>

    <style>
        main * {
            /* border: 1px solid red; */
        }

        .dontNextLine {
            white-space: nowrap;
            text-align: end;
            padding-right: 30px !important;
        }

        .img-box {
            aspect-ratio: 1;
            border-radius: 10px;
            margin-bottom: 20px;
            /* box-shadow: 0 0 15px #F4A293; */
            overflow: hidden;
            transition: 0.5s;

            &:hover {
                /* box-shadow: 0 0 40px #F4A293; */
            }
        }

        .img-small {
            aspect-ratio: 1;
            border-radius: 10px;
            overflow: hidden;
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

            <h2 class="mb-5 text-center">商品管理</h2>
            <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="product-list.php">
                <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回</span>
            </a>

            <?php if (isset($_GET["productId"])): ?>
                <div class="container">

                    <div class="row d-flex justify-content-center">
                        <div class="col-12">
                            <div class="row d-flex align-items-center flex-column flex-xl-row">
                                <div class="col col-xl-4 px-2 ">
                                    <div class="img-box">
                                        <img id="majorPhoto" class="w-100 h-100 object-fit-cover" src="../images/products/<?= $photoRows[0]["file_name"] ?>" alt="">
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <div class="row w-100">
                                            <?php foreach ($photoRows as $photoItem): ?>
                                                <div class="img-small col-3">
                                                    <img class="smallImg w-100 h-100 object-fit-cover" src="../images/products/<?= $photoItem["file_name"] ?>" alt="">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                </div>
                                <div class="col col-xl-8 px-2">

                                    <h3 class="mt-4 text-center text-xl-start"><?= $row["name"] ?></h3>
                                    <table class="table table-hover">
                                        <tr>
                                            <td class="dontNextLine fw-bold">id</td>
                                            <td><?= $id ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">上架店家</td>
                                            <td><?= $shopName ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">價格</td>
                                            <td><?= $row["price"] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">庫存</td>
                                            <td><?= $row["stocks"] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">商品分類</td>
                                            <td><?= $className ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">描述</td>
                                            <td><?= $row["description"] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">關鍵字</td>
                                            <td><?= $row["keywords"] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">折扣</td>
                                            <td><?= $row["discount"] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">產品狀態</td>
                                            <td>
                                                <?php
                                                if ($row["deleted"] == 1) {
                                                    echo '<div class="btn btn-secondary">已刪除</div>';
                                                } else {
                                                    if ($row["available"] == 1) {
                                                        echo '<div class="btn btn-success">上架中</div>';
                                                    } else {
                                                        echo '<div class="btn btn-danger">已下架</div>';
                                                    }
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">標籤</td>
                                            <td><?= $row["label"] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">建立時間</td>
                                            <td><?= $row["created_at"] ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">編輯者</td>
                                            <td><?= isset($row["edit_user_id"]) ? $userArr[$row["edit_user_id"]] : "" ?></td>
                                        </tr>
                                        <tr>
                                            <td class="dontNextLine fw-bold">編輯時間</td>
                                            <td><?= $row["last_edited_at"] ?></td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="option-area d-flex justify-content-center mt-4 ">
                                    <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="product-edit.php?productId=<?= $id ?>">編輯</a>
                                    <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="../function/doProductValidSwitch.php?productId=<?= $id ?>"><?= $row["available"] == 1 ? "下架" : "上架" ?></a>
                                    <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" href="../function/doProductDeletedSwitch.php?productId=<?= $id ?>"><?= $row["deleted"] == 0 ? "刪除" : "復原產品" ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <p><?= $message ?></p>
            <?php endif; ?>

        </main>

    </div>

    <?php include("../js.php"); ?>
</body>

</html>