<?php

require_once("../db_connect.php");

// 分頁條件設置
$per_page = 10;
$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
$page = max(1, $page);
$start_item = ($page - 1) * $per_page;

// 搜索
$search = isset($_GET["search"]) ? trim($_GET["search"]) : '';

// 狀態
$status = isset($_GET["status"]) ? $_GET["status"] : 'on';
$sql = "SELECT * FROM users WHERE role = 'user'";

if ($status === 'on') {
    $sql .= " AND activation = 1";
} elseif ($status === 'off') {
    $sql .= " AND activation = 0";
} elseif ($status === 'all') {
}

if ($search !== '') {
    $sql .= " AND name LIKE '%$search%'";
}

// 排序
$order = isset($_GET["order"]) ? $_GET["order"] : 1;
switch ($order) {
    case 1:
        $sql .= " ORDER BY user_id ASC";
        break;
    case 2:
        $sql .= " ORDER BY user_id DESC";
        break;
    default:
        $sql .= " ORDER BY user_id ASC";
        break;
}

// 計算總數量
$count_sql = str_replace('*', 'COUNT(*) as count', $sql); //查找、替換、sql
$count_result = $conn->query($count_sql);
if ($count_result) {
    $userCount = $count_result->fetch_assoc();
    $totalPage = ceil($userCount['count'] / $per_page);
} else {
    die("計數查詢錯誤: " . $conn->error);
}

// 分頁限制
$sql .= " LIMIT $start_item, $per_page";
$result = $conn->query($sql);
if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("查詢錯誤: " . $conn->error);
}

?>

<!doctype html>
<html lang="en">

<head>
    <title>會員管理頁</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css/css_Joe.php"); ?>
    <style>
        .user-btn {
            width: 100px;
        }

        .user-search {
            width: 200px;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <div class="d-flex">
                <div class="d-flex p-0">
                    <?php if ($search !== ''): ?>
                        <a class="btn btn-neumorphic user-btn mt-0" href="users.php" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
                    <?php endif; ?>
                    <h2 class="mb-3">會員管理</h2>
                </div>
            </div>
            <div class="container">
                <div class="row d-flex">
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="search" class="form-control" name="search" value="<?= ($search) ?>" placeholder="請輸入欲搜尋的使用者">
                            <div class="input-group-append">
                                <button class="btn btn-outline-warning m-0" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <a class="btn btn-neumorphic user-btn <?= $order == 1 ? 'active' : '' ?>" href="users.php?p=<?= $page ?>&order=1&status=<?= $status ?>&search=<?= urlencode($search) ?>">排序
                                    <i class="fa-solid fa-arrow-up-a-z"></i>
                                </a>
                                <a class="btn btn-neumorphic user-btn <?= $order == 2 ? 'active' : '' ?>" href="users.php?p=<?= $page ?>&order=2&status=<?= $status ?>&search=<?= urlencode($search) ?>">排序
                                    <i class="fa-solid fa-arrow-down-a-z"></i>
                                </a>
                            </div>
                            <div>
                                <a href="user-add.php" class="btn btn-neumorphic user-btn">新增
                                    <i class="fa-solid fa-plus"></i>
                                </a>
                            </div>
                        </div>

                        <div class="main col neumorphic p-2">
                            <h3>
                                <?php if ($search !== ''): ?>
                                    <?= ($search) ?> 的搜尋結果: 共有 <?= $userCount['count'] ?> 個使用者
                                <?php else: ?>
                                    共有 <?= $userCount['count'] ?> 個使用者
                                <?php endif; ?>
                            </h3>

                            <?php if (!empty($users)): ?>
                                <ul class="nav nav-tabs-custom">
                                    <li class="nav-item">
                                        <a class="main-nav nav-link <?= $status === 'all' ? 'active' : '' ?>" href="?status=all&search=<?= urlencode($search) ?>&p=<?= $page ?>&order=<?= $order ?>">全部</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="main-nav nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on&search=<?= urlencode($search) ?>&p=<?= $page ?>&order=<?= $order ?>">正常使用者</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="main-nav nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off&search=<?= urlencode($search) ?>&p=<?= $page ?>&order=<?= $order ?>">黑名單列表</a>
                                    </li>
                                </ul>

                                <table class="table table-bordered">
                                    <thead class="user-text">
                                        <tr>
                                            <th>User ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>其他功能</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?= ($user["user_id"]) ?></td>
                                                <td><?= ($user["name"]) ?></td>
                                                <td><?= ($user["email"]) ?></td>
                                                <td><?= ($user["phone"]) ?></td>
                                                <td>
                                                    <a class="btn btn-primary" href="user.php?user_id=<?= $user["user_id"] ?>"><i class="fa-solid fa-eye"></i></a>
                                                    <?php if ($user["activation"] == 1): ?>
                                                        <a class="btn btn-danger" href="../function/doDeleteUser.php?id=<?= $user["user_id"] ?>"><i class="fa-solid fa-trash"></i></a>
                                                    <?php else: ?>
                                                        <a class="btn btn-danger" href="../function/doReloadUser.php?id=<?= $user["user_id"] ?>"><i class="fa-solid fa-plus"></i></a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center">
                                    <nav aria-label="page navigation">
                                        <ul class="pagination pagination-lg">
                                            <!-- 第一頁 -->
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?p=1&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>">第一頁</a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- 前面的 "..." -->
                                            <?php if ($page > 3): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?p=<?= $page - 3 ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>">...</a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- 中間頁碼 -->
                                            <?php for ($i = max(1, $page - 2); $i <= min($totalPage, $page + 2); $i++): ?>
                                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?p=<?= $i ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <!-- 後面的 "..." -->
                                            <?php if ($page < $totalPage - 2): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?p=<?= $page + 3 ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>">...</a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- 最後一頁 -->
                                            <?php if ($page < $totalPage): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?p=<?= $totalPage ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>">最後一頁</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>