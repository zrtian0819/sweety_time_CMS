<?php
require_once("../db_connect.php");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章</title>
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
                <h1 class="d-flex justify-content-center">title</h1>
                <div class="row-col-3 d-flex justify-content-center">
                    建立時間
                </div>
                <div class="row-col-3 d-flex justify-content-center ">
                    圖片
                </div>

                <div class="row-col-3 d-flex justify-content-center">
                    內文
                </div>
            </div>
        </div>

    </div>


    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>