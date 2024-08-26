<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
include("../function/function.php");

$status = isset($_GET["status"]) ? $_GET["status"] : "all";

// 初始化SQL語句
$sql = "SELECT * FROM articles WHERE 1 = 1";
$params = [];
$types = "";

if ($status == "on") {
    $sql .= " AND activation = 1";
} elseif ($status == "off") {
    $sql .= " AND activation = 0";
} else {
    $sql;
}


// 搜尋條件
if (isset($_GET["search"]) && !empty($_GET["search"])) {
    $search = "%" . $_GET["search"] . "%";
    $sql .= " AND (title LIKE ? OR content LIKE ?)";
    array_push($params, $search, $search);
    $types .= "ss";
}

//檢查排序條件
$sortArt = isset($_GET["sortArt"]) ? $_GET["sortArt"] : "article_id";
$sortDir = isset($_GET["sortDir"]) && $_GET["sortDir"] == "DESC" ? "DESC" : "ASC";


//分頁設定 每頁顯示12筆資料
$page = isset($_GET["p"]) ? intval($_GET["p"]) : 1;
$per_page = 12;
$start_item = ($page - 1) * $per_page;

$sqlCount = "SELECT COUNT(*) FROM articles WHERE 1 = 1";

if (!empty($params)) {
    $sqlCount .= " AND (title LIKE ? OR content LIKE ?)";
}

$stmt_count = $conn->prepare($sqlCount);
if ($stmt_count === false) {
    die("Prepare statement failed: " . $conn->error);
}

$stmt_count = $conn->prepare($sqlCount);
if (!empty($params)) {
    $stmt_count->bind_param($types, ...$params);
}

$stmt_count->execute();
$stmt_count->bind_result($total_items);
$stmt_count->fetch();
$stmt_count->close();

$total_pages = $total_items > 0 ? ceil($total_items / $per_page) : 1;

//添加排序和分頁
$sql .= " ORDER BY $sortArt $sortDir LIMIT ?, ?";
array_push($params, $start_item, $per_page);
$types .= "ii";

// echo ($sql);

// 準備 SQL 語句
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare statement failed: " . $conn->error);
}

// 綁定參數
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// 執行查詢
$stmt->execute();

// 獲取查詢結果
$result = $stmt->get_result();

if ($result === false) {
    die("Query failed: " . $stmt->error);
}

$articlesCount = $result->num_rows;

//分類
$sqlProductClass = "SELECT * FROM product_class ORDER BY product_class_id";
$resultProduct = $conn->query($sqlProductClass);
$rowsProduct = $resultProduct->fetch_all(MYSQLI_ASSOC);

//分類關聯式陣列
$productClassArr = [];
foreach ($rowsProduct as $productClass) {
    $productClassArr[$productClass["product_class_id"]] = $productClass["class_name"];
};


// 使用者
$sqlUsers = "SELECT * FROM users ORDER BY user_id";
$resultUsers = $conn->query($sqlUsers);
$rowsUsers = $resultUsers->fetch_all(MYSQLI_ASSOC);



//使用者關聯式陣列
$usersArr = [];
foreach ($rowsUsers as $users) {
    $usersArr[$users["user_id"]] = $users["name"];
};


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

        <?php include("../modules/dashboard-sidebar_Su.php"); ?>

        <div class="main col neumorphic p-5">

            <div class="row d-flex">

                <?php if ($articlesCount > 0): $rows = $result->fetch_all(MYSQLI_ASSOC); ?>


                    <!-- 搜尋 -->
                    <div class="container-fluid">
                        <form action="">
                            <div class=" input-group m-2">
                                <input type="search" class="form-control input-border-pink" name="search" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>" placeholder="輸入文章、主題關鍵字">

                                <div class="input-group-append">
                                    <button class="btn btn-custom" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                                <div class="ms-3">
                                    <a href="articles-create.php" class="btn-animation btn btn-custom d-flex flex-row align-items-center">
                                        <i class="fa-solid fa-plus"></i>
                                        <span class="btn-animation-innerSpan d-inline-block"> 新增文章</span>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- 排序 -->
                    <div class="d-flex justify-content-between my-3">
                        <div class="d-flex flex-row">
                            <a href="?sortArt=<?= $sortArt ?>&sortDir=ASC&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" class="btn btn-neumorphic articles-btn nav-link active">排序
                                <i class="fa-solid fa-arrow-down-a-z"></i>
                            </a>
                            <a href="?sortArt=<?= $sortArt ?>&sortDir=DESC&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" class="btn btn-neumorphic articles-btn nav-link">排序
                                <i class="fa-solid fa-arrow-up-a-z"></i>
                            </a>

                        </div>
                    </div>
            </div>

            <!-- 上架狀態 -->
            <div class="d-flex justify-content-between align-items-center ">
                <ul class="nav nav-tabs-custom">
                    <li class="nav-item">
                        <a class="main-nav nav-link <?= $status === 'all' ? 'active' : '' ?>" href="?status=all">全部</a>
                    </li>
                    <li class="nav-item">
                        <a class="main-nav nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>&sortArt=<?= $sortArt ?>&sortDir=<?= $sortDir ?>&p=<?= $page ?>">上架中</a>
                    </li>
                    <li class="nav-item">
                        <a class="main-nav nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>&sortArt=<?= $sortArt ?>&sortDir=<?= $sortDir ?>&p=<?= $page ?>">已下架</a>
                    </li>
                </ul>
                <p class="d-flex justify-content-end">共有<?= $articlesCount ?>篇文章</p>
            </div>




            <!-- 欄位 -->
            <table class="table table-hover">
                <thead class="text-content table-pink">
                    <tr class="text-center m-auto align-middle">
                        <th class="text-center col-1">文章編號</th>
                        <th class="text-center col-1">上架狀態</th>
                        <th class="text-center col-1">主題</th>
                        <th class="text-center col-1">內容</th>
                        <th class="text-center col-1">文章分類</th>
                        <th class="text-center col-1">作者</th>
                        <th class="text-center col-1">建立時間</th>
                        <th class="text-center col-1">一鍵修改</th>
                        <th class="text-center col-1">詳細資訊</th>
                    </tr>
                </thead>

                <!-- 欄位內容 -->
                <tbody>
                    <?php foreach ($rows as $articles):
                        $id = $articles["user_id"];
                        $date = $articles["created_at"];
                        $dateStr = new DateTime($date);
                        $formartDate = $dateStr->format("Y-m-d H:i");
                    ?>
                        <tr class="text-center m-auto">
                            <td><?= $articles["article_id"] ?></td>
                            <?php echo ($articles["activation"] == 1) ? "<td>" . "上架中" : "<td class='text-danger'>" . "已下架"; ?></td>
                            </td>
                            <td><?= $articles["title"] ?></td>
                            <td><?= getLeftChar($articles["content"], 70) . "..." ?></td>
                            <td><?= $productClassArr[$articles["product_class_id"]] ?></td>
                            <td><?= $usersArr[$id] ?></td>
                            <td><?= $formartDate ?></td>
                            <td>
                                <?php if ($status === "off"): ?>
                                    <a href="../function/doReload4Articles.php?id=<?= $id ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>
                                <?php else: ?>
                                    <?php if ($articles["activation"] == 1): ?>
                                        <a href="../function/doDelete4Articles.php?id=<?= $id ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    <?php else: ?>

                                        <a href="../function/doReload4Articles.php?id=<?= $id ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>

                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <!-- 右側按鈕 -->
                            <td>
                                <div class="d-flex justify-content-center ">
                                    <div class="me-1">
                                        <a href="article.php?id=<?= $articles["article_id"] ?>" id="" class="btn btn-custom"><i class="fa-solid fa-eye"></i></a>
                                    </div>

                                    <div class="me-1">
                                        <a href="articles-edit.php?id=<?= $articles["article_id"] ?>" class="btn btn-custom"><i class="fa-solid fa-pen"></i></a>
                                    </div>

                                </div>
                            </td>
                        </tr>

                </tbody>
            <?php endforeach; ?>
            </table>
            <?php if (isset($page)): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item m-2 <?php if ($i == $page) echo "active" ?>"><a class="page-link btn-custom" href="lesson.php?status=<?= $status ?>&search=<?= $search ?>&class=<?= $class ?>&sort=<?= $sort ?>&p=<?= $i ?>"><?= $i ?></a></li>
                        <?php endfor; ?>

                    </ul>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            <!-- 返回文章列表按鈕 -->
            <div class="col-3">
                <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="articles.php">
                    <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回文章列表</span>
                </a>
            </div>
            <!-- 狀態 -->
            <ul class="nav nav-tabs-custom">
                <li class="nav-item">
                    <a class="main-nav nav-link <?= $status === 'all' ? 'active' : '' ?>" href="?status=all">全部</a>
                </li>
                <li class="nav-item">
                    <a class="main-nav nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>&sortArt=<?= $sortArt ?>&sortDir=<?= $sortDir ?>&p=<?= $page ?>">上架中</a>
                </li>
                <li class="nav-item">
                    <a class="main-nav nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off&search=<?= isset($_GET['search']) ? urlencode($_GET['search']) : '' ?>&sortArt=<?= $sortArt ?>&sortDir=<?= $sortDir ?>&p=<?= $page ?>">已下架</a>
                </li>
            </ul>
            <!-- 欄位 -->
            <table class="table table-hover">

                <thead class="text-content table-pink">
                    <tr class="text-center m-auto align-middle">
                        <th class="text-center col-1">文章編號</th>
                        <th class="text-center col-1">上架狀態</th>
                        <th class="text-center col-1">主題</th>
                        <th class="text-center col-1">內容</th>
                        <th class="text-center col-1">文章分類</th>
                        <th class="text-center col-1">作者</th>
                        <th class="text-center col-1">建立時間</th>
                        <th class="text-center col-1">一鍵修改</th>
                        <th class="text-center col-1">詳細資訊</th>
                    </tr>
                </thead>

            </table>
            目前沒有文章
        <?php endif; ?>


        </div>
    </div>


    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>