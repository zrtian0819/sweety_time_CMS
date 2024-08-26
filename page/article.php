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

// 把文章的資料整理成關聯陣列
$sqlArticles = "SELECT * FROM articles ORDER BY article_id";
$resultArticles = $conn->query($sqlArticles);
$rowsArticles = $resultArticles->fetch_all(MYSQLI_ASSOC);

//把文章資料轉成陣列
$arrAticles = [];
foreach ($rowsArticles as $articles) {
    $arrAticles[$articles["article_id"]] = $articles["title"];
}

//分類
$productClass = $row["product_class_id"];
$sqlProductClass = "SELECT * FROM product_class WHERE product_class_id = $productClass";
$resultProduct = $conn->query($sqlProductClass);
$rowProduct = $resultProduct->fetch_assoc();

// 使用者
$sqlUsers = "SELECT * FROM users ORDER BY user_id";
$resultUsers = $conn->query($sqlUsers);
$rowsUsers = $resultUsers->fetch_all(MYSQLI_ASSOC);

//使用者關聯式陣列
$firstUser = $rowsUsers[0]; // 這裡取得第一筆使用者資料
$authorName = $firstUser["name"]; // 取得使用者名稱

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

        <?php include("../modules/dashboard-sidebar_Su.php"); ?>

        <div class="main col neumorphic p-5">
            <div class="row">
                <div class="col-3">
                    <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="articles.php">
                        <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回</span>
                    </a>
                </div>
                <!-- 文章 -->
                <h1 class="d-flex justify-content-center">
                    <?= $row["title"] ?>
                </h1>

                <!-- 建立時間/作者/分類 -->
                <div class="row-col-3 d-flex justify-content-center">
                    建立時間：<?= $row["created_at"] ?>／作者：<?= $authorName ?>／分類：<?= $rowProduct["class_name"] ?>

                </div>

                <!-- 圖片 -->
                <div class="row-col-3 d-flex justify-content-center ">
                    <div class="m-3">
                        <img style="height: auto; object-fit: cover; max-width: 100%;" class="rounded m" src="../images/articles/<?= $row["img_path"] ?>" alt="<?= $row["title"] ?>">
                    </div>
                </div>

                <!-- 內文 -->
                <div class="row-col-3 d-flex justify-content-center">
                    <br>
                    <?= $row["content"] ?>
                </div>

            </div>
            <div class="container changePage text-end">
                <div class="d-flex justify-content-end ">
                    <?php if ($id > 1) : ?>
                        <div class="mt-4 py-2">
                            <a href="article.php?id=<?= $id - 1 ?>" class="btn-custom m-2 p-2 text-decoration-none">上一篇文章</a>
                        </div>
                    <?php endif ?>
                    <?php if ($id < $rows) : ?>
                        <div class="mt-4 py-2">
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