<?php

require_once("../db_connect.php");

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }


$status = isset($_GET["status"]) ? $_GET["status"] : "all";

if ($status === "on") {
    $sql = "SELECT * FROM articles WHERE activation = 1";
} elseif ($status === "off") {
    $sql = "SELECT * FROM articles WHERE activation = 0";
} else {
    $sql = "SELECT * FROM articles";
}


if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sql = "SELECT * FROM articles WHERE title, content LIKE '%$search%' AND activation=1";
    echo $sql;
} else {
    $sql = "SELECT * FROM articles";
}

$result = $conn->query($sql);
$articleCount = $result->num_rows;
$rows = $result->fetch_all(MYSQLI_ASSOC);




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link rel="stylesheet" href="../css/style_Joe.css">
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <h2 class="mb-3">文章管理</h2>
            <div class="py-2">
                <?php if (isset($_GET["search"])): ?>
                    <a class="btn btn-neumorphic" href="articles.php" title="回文章列表"><i class="fa-solid fa-left-long"></i></a>
                <?php endif; ?>

            </div>

            <div class="row d-flex">
                <div class="input-group mb-3">
                    <input type="search" class="form-control" name="search" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>" placeholder="輸入文字以搜尋文章">
                    <div class="input-group-append">
                        <button class="btn btn-outline-warning m-0 " type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>

                <?php if ($articleCount > 0): $rows = $result->fetch_all(MYSQLI_ASSOC); ?>
                    <h3>共有<?= $articleCount ?>篇文章</h3>

                    <ul class="nav nav-tabs-custom">
                        <li class="nav-item">
                            <a class="main-nav nav-link <?= $status === 'all' ? 'active' : '' ?>" href="status=all">全部</a>
                        </li>
                        <li class="nav-item">
                            <a class="main-nav nav-link <?= $status === 'on' ? 'active' : '' ?>" href="status=on">上架中</a>
                        </li>
                        <li class="nav-item">
                            <a class="main-nav nav-link <?= $status === 'off' ? 'active' : '' ?>" href="status=off">已下架</a>
                        </li>
                    </ul>

                    <!-- 欄位 -->

                    <table class="table table-hover">
                        <thead class="text-content">
                            <tr>
                                <th>文章編號</th>
                                <th>主題</th>
                                <th>內容</th>
                                <th>文章分類</th>
                                <th>作者</th>
                                <th>建立時間</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr class="text-center m-auto">
                                <td><?= $id ?></td>
                                <?php echo ($row["activation"] == 1) ? "<td>" . "上架中" : "<td class='text-danger'>" . "已下架"; ?></td>
                                <td><?= $ ?></td>
                                <td><?= $ ?></td>
                                <td><?= $ ?></td>
                                <td><?= $ ?></td>
                                <td><?= $ ?></td>
                                <td>
                                    <?php
                                    $sqlArticles = "SELECT * FROM articles WHERE article_id = $id";
                                    $resultArticles = $conn->query($sqlArticles);
                                    $count = $resultArticles->num_rows;
                                    $rowArticles = $resultArticles->fetch_assoc();
                                    ?>
                                    <?= $count ?></td>
                                <td><a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-eye"></i></i></a>
                                    <a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-pen"></i></a>
                                    <?php if ($status === "off"): ?>
                                        <a href="../function/doReloadArticle.php?id=<?= $id ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>
                                    <?php else: ?>
                                        <?php if ($row["activation"] == 1): ?>
                                            <a href="../function/doDeleteArticle.php?id=<?= $id ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                        <?php else: ?>
                                            <a href="../function/doReloadArticle.php?id=<?= $id ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                <?php else: ?>
                    目前沒有文章
                <?php endif; ?>


            </div>
        </div>
    </div>


    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>