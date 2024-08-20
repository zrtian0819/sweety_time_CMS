<?php

require_once("../db_connect.php");

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

function getLeftChar($text, $num)
{
    return substr($text, 0, $num);
}

// 搜尋
if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sql = "SELECT * FROM articles WHERE title, content LIKE '%$search%' AND activation=1";
    echo $sql;
} else {
    $sql = "SELECT * FROM articles";
}

$result = $conn->query($sql);
$articleCount = $result->num_rows;


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
            <h2 class="mb-3 text-center">文章管理</h2>
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

                    <div class="d-flex justify-content-between my-3">
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-neumorphic user-btn">排序
                                <i class="fa-solid fa-arrow-up-a-z"></i>
                            </a>
                            <a href="#" class="btn btn-neumorphic user-btn">排序
                                <i class="fa-solid fa-arrow-down-a-z"></i>
                            </a>    
                        </div>
                        <div>
                            <a href="#" class="btn btn-neumorphic user-btn">
                                <i class="fa-solid fa-plus"></i>新增文章
                            </a>
                        </div>
                    </div>


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
                            <?php foreach ($rows as $articles): ?>
                                <tr class="text-center m-auto">
                                    <td><?= $articles["article_id"] ?></td>
                                    <td><?= $articles["title"] ?></td>
                                    <td><?= getLeftChar($articles["content"], 200) . "..." ?></td>
                                    <td><?= $articles["product_class_id"] ?></td>
                                    <td><?= $articles["user_id"] ?></td>
                                    <td><?= $articles["created_at"] ?></td>
                                    <td>
                                        <a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-eye"></i></a>

                                        <a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-pen"></i></a>


                                        <a href="../function/doDeleteArticle.php?id=<?= $id ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>

                                    </td>
                                </tr>
                        </tbody>
                    <?php endforeach; ?>
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