<?php
require_once("../db_connect.php");


// 文章不存在的時候回到列表頁
if (!isset($_GET["id"])) {
    header("location: articles.php");
    exit;
}


// 取得資料
$id = $_GET["id"];
$sql = "SELECT * FROM articles WHERE article_id = $id";
$result = $conn->query($sql);

// 檢查行數
$count = $result->num_rows;

// 檢查文章是否存在
if ($count > 0) {
    $row = $result->fetch_assoc();
    if ($row) {
        $title = $row["title"];
    } else {
        $title = "文章不存在";
    }
} else {
    $title = "文章不存在";
}

// 測試用
// var_dump($row);


// 取得所有文章的資料
$sqlAll = "SELECT * FROM articles";
$allResult = $conn->query($sqlAll);
$rows = $allResult->num_rows;

//把articles的資料整理成關聯陣列
$sqlArticles = "SELECT * FROM articles ORDER BY article_id";
$resultArticles = $conn->query($sqlArticles);
$rowsArticles = $resultArticles->fetch_all(MYSQLI_ASSOC);

//把資料轉成陣列
$arrAticles = [];
foreach ($rowsArticles as $articles) {
    $arrAticles[$articles["article_id"]] = $articles["title"];
}

//取得與文章相關聯的使用者資料
$sqlAuthor = "SELECT * FROM articles WHERE user_id = $id";
$resultAuthor = $conn->query($sqlAuthor);
$rowAuthor = $resultAuthor->fetch_assoc();

//分類
$productClass = $row["product_class_id"];
$sqlProductClass = "SELECT * FROM product_class WHERE product_class_id = $productClass";
$resultProduct = $conn->query($sqlProductClass);
$rowProduct = $resultProduct->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $row["title"] ?></title>
    <link rel="stylesheet" href="../css/style_Joe.css">
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .bot-ul .page-item .page-link {
            background-color: #efbeb6;
            color: #fff;
            border: none;
        }

        .bot-ul .page-item.active .page-link {
            background-color: #efbeb6;
            color: #fff;
            border: none;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <!-- 回到文章列表按鈕 -->
        <div class="main col neumorphic p-5">
            <div class="row">
                <div class="d-flex justify-content-end">
                    <div>
                        <a class="btn btn-neumorphic article-btn mt-0" href="articles.php" title="回文章列表"><i class="fa-solid fa-left-long"></i>回到文章列表</a>
                    </div>
                </div>
                <!-- 文章 -->
                <h1 class="d-flex justify-content-center">
                    <?= $row["title"] ?>
                </h1>

                <!-- 建立時間/作者/分類 -->
                <div class="row-col-3 d-flex justify-content-start">
                    建立時間：<?= $row["created_at"] ?>／作者：<?= $row["created_at"] ?>／分類：<?= $rowProduct["class_name"] ?>

                </div>

                <!-- 圖片 -->
                <div class="row-col-3 d-flex justify-content-center ">
                    <div class="m-3">
                        <img style="height: auto; object-fit: cover; max-width: 100%;" class="rounded m" src="../images/article/<?= $row["img_path"] ?>" alt="<?= $row["title"] ?>">
                    </div>
                </div>

                <!-- 內文 -->
                <div class="row-col-3 d-flex justify-content-center">
                    <br>
                    <?= $row["content"] ?>
                </div>

            </div>
            <div class="container changePage text-end">
                <div class="row">
                    <?php if ($id > 1) : ?>
                        <div class="py-4">
                            <a href="article.php?id=<?= $id - 1 ?>" class="btn-custom m-2 p-2 text-decoration-none">上一篇文章</a>
                        </div>
                    <?php endif ?>
                    <?php if ($id < $rows) : ?>
                        <div class="">
                            <a href="article.php?id=<?= $id + 1 ?>" class="btn-custom m-2 p-2 text-decoration-none">下一篇文章</a>
                        </div>
                    <?php endif ?>
                </div>
            </div>
        </div>


    </div>


    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>