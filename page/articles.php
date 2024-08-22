<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }


// 文字縮排
function getLeftChar($text, $num)
{
    return substr($text, 0, $num);
}

// 狀態
$status = isset($_GET["status"]) ? $_GET["status"] : "all";

// 初始化SQL語句
$sql = "SELECT * FROM articles WHERE 1 = 1";
$params = [];
$types = "";

// 搜尋是否有搜尋條件
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


//文章分類
$class = isset($_GET["class"]) ? $_GET["class"] : "";

switch ($class) {
    case 1:
        $sql .= " AND product_class_id =1";
        break;
    case 2:
        $sql .= " AND product_class_id =2";
        break;
    case 3:
        $sql .= " AND product_class_id =3";
        break;
    case 4:
        $sql .= " AND product_class_id =4";
        break;
    case 5:
        $sql .= " AND product_class_id =5";
        break;
    case 6:
        $sql .= " AND product_class_id =6";
        break;
    case "all":
        $sql;
        break;
}

//搜尋文章
$search = isset($_GET["search"]) ? $_GET["search"] : "";
if ($search != "" && $class != "all") {
    $sql .= " AND name LIKE '%$search%'";
} elseif ($search != "" && $class == "all") {
    $sql .= " AND name LIKE '%$search%'";
}

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

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">

            <h2 class="m-3 d-flex justify-content-center">文章列表</h2>

            <div class="row d-flex">

                <?php if ($articlesCount > 0): $rows = $result->fetch_all(MYSQLI_ASSOC); ?>
                    <h3 class="d-flex justify-content-center">共有<?= $articlesCount ?>篇文章</h3>

                    <form action="">
                        <div class=" input-group m-2 d-flex justify-content-end">
                            <div>
                                <input type="search" class="form-control" name="search" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>" placeholder="請輸入文字以搜尋文章、主題">
                            </div>

                            <select class="form-select-sm" aria-label="Default select example" name="class">
                                <option value="all">分類</option>
                                <option value="1">蛋糕</option>
                                <option value="2">餅乾</option>
                                <option value="3">塔 / 派</option>
                                <option value="4">泡芙</option>
                                <option value="5">冰淇淋</option>
                                <option value="6">其他</option>
                            </select>
                            <select class="form-select-sm" aria-label="Default select example" name="sort">
                                <option value="id">依文章編號排序(預設)</option>
                                <option value="date">依建立時間排序</option>
                            </select>

                            <div class="input-group-append">
                                <button class="btn btn-outline-warning m-0 " type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                    </form>

                    <!-- 排序 -->
                    <div class="d-flex justify-content-between my-3">
                        <div class="d-flex justify-content-between">
                            <a href="?sortArt=<?= $sortArt ?>&sortDir=ASC&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" class="btn btn-neumorphic articles-btn">排序
                                <i class="fa-solid fa-arrow-down-a-z"></i>
                            </a>
                            <a href="?sortArt=<?= $sortArt ?>&sortDir=DESC&search=<?= isset($_GET['search']) ? $_GET['search'] : '' ?>" class="btn btn-neumorphic articles-btn">排序
                                <i class="fa-solid fa-arrow-up-a-z"></i>
                            </a>
                        </div>
                        <div>
                            <a href="#" class="btn btn-neumorphic articles-btn">
                                <i class="fa-solid fa-plus"></i>新增文章
                            </a>
                        </div>
                    </div>
            </div>

            <!-- 欄位 -->
             .
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

                <!-- 欄位內容 -->
                <tbody>
                    <?php foreach ($rows as $articles):
                        $id = $articles["user_id"];
                        $date = $articles["created_at"];
                        $dateStr = new DateTime($date);
                        $formartDate = $dateStr->format("Y-m-d H:i")
                    ?>
                        <tr class="text-center m-auto">
                            <td><?= $articles["article_id"] ?></td>
                            <td><?= $articles["title"] ?></td>
                            <td><?= getLeftChar($articles["content"], 200) . "..." ?></td>
                            <td><?= $productClassArr[$articles["product_class_id"]] ?></td>
                            <td><?= $usersArr[$users["user_id"]] ?></td>
                            <td><?= $formartDate ?></td>
                            <td>
                                <div class="d-flex justify-content-center ">
                                    <div class="me-1">
                                        <a href="article.php?id=<?= $articles["article_id"] ?>" id="" class="btn btn-custom"><i class="fa-solid fa-eye"></i></a>
                                    </div>

                                    <div class="me-1">
                                        <a href="edit-article.php?id=<?= $articles["article_id"] ?>" class="btn btn-custom"><i class="fa-solid fa-pen"></i></a>
                                    </div>

                                    <div class="me-1">
                                        <a href="../function/doDeleteArticle.php?id=<?= $articles["article_id"] ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

            <?php if (isset($page)): ?>
                <nav aria-label="Page navigation">
                    <ul class="bot-ul pagination pagination-lg ">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="me-1 page-item <?php if ($page == $i) echo "active"; ?>">
                                <a class="page-link btn-custom" href="articles.php?p=<?= $i ?>&sortArt=<?= $sortArt ?>&sortDir=<?= $sortDir ?>&search=<?= isset($_GET["search"]) ? $_GET["search"] : '' ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php else: ?>
            目前沒有文章
        <?php endif; ?>


        </div>
    </div>


    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>