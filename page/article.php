<?php
require_once("../db_connect.php");


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章管理頁</title>
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
            <h2 class="mb-3 text-center">文章</h2>
            <div class="py-2">
                <?php if (isset($_GET["search"])): ?>
                    <a class="btn btn-neumorphic" href="articles.php" title="回文章列表"><i class="fa-solid fa-left-long"></i></a>
                <?php endif; ?>

            </div>

            <div class="row d-flex">
                
            </div>
        </div>
    </div>


    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>