<?php
require_once("../db_connect.php");


// 文章不存在的時候回到列表頁
if (!isset($_GET["id"])){
    header("location: articles.php");
}


// 取得資料
$sql = "SELECT * FROM articles ";
$result = $conn->query($sql);
$count = $result->num_rows;
$row = $result->fetch_assoc();


// 檢查文章是否存在
if($count > 0){
    $title = $row["title"];
}else{
    $title = "文章不存在";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$article["title"]?></title>
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

        <div class="main col neumorphic p-5">

            <div class="row">
                <div class="d-flex justify-content-end">
                    <div>
                        <a class="btn btn-neumorphic article-btn mt-0" href="articles.php" title="回文章列表"><i class="fa-solid fa-left-long"></i>回到文章列表</a>
                    </div>
                </div>

                <h1 class="d-flex justify-content-center">
                    標題
                </h1>

                <div class="row-col-3 d-flex justify-content-center">
                    建立時間/作者/產品分類
                </div>
                <div class="row-col-3 d-flex justify-content-center ">
                    圖片
                </div>

                <div class="row-col-3 d-flex justify-content-center">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam natus corrupti, error, eaque ipsum molestias illum sit quis magnam sequi exercitationem, dignissimos excepturi. Aut iste ut, soluta voluptatibus labore quia?
                </div>
            </div>
        </div>

    </div>


    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>