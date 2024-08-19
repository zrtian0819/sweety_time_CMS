<?php

require_once("../db_connect.php");



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <h2>文章管理</h2>
            <ul class="nav nav-tabs">
            <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="articles.php">全部</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="articles-online">上架中</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="articles-delete">已下架</a>
                </li>
            </ul>
            <!-- 文章分類 -->
             <table class="table table-hover">
             <thead class="text-center">
                    <th>文章編號</th>
                    <th>主題</th>
                    <th>文章分類</th>
                    <th>作者</th>
                    <th>建立時間</th>
                </thead>
             </table>

        </div>

    </div>

    <?php include("../js.php"); ?>
</body>

</html>