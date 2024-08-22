<?php

require_once("../db_connect.php");

$per_page = 10;
$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
$page = max(1, $page);
$start_item = ($page - 1) * $per_page;

$search = isset($_GET["search"]) ? trim($_GET["search"]) : '';

// 計算總數量
$count_sql = "SELECT COUNT(*) as count FROM users WHERE activation = 1";
if ($search !== '') {
    $count_sql .= " AND name LIKE '%$search%'";
}
$count_result = $conn->query($count_sql);
if ($count_result) {
    $userCount = $count_result->fetch_assoc();
    $totalPage = ceil($userCount['count'] / $per_page);
} else {
    die("計數查詢錯誤: " . $conn->error);
}

// 根據搜尋條件查詢使用者
$sql = "SELECT * FROM users WHERE activation = 1";
if ($search !== '') {
    $sql .= " AND name LIKE '%$search%'";
}
if (isset($_GET["order"])) {
    $order = $_GET["order"];
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
}
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
                    <?php if (isset($_GET["search"])): ?>
                        <a class="btn btn-neumorphic user-btn mt-0" href="users.php" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
                    <?php endif; ?>
                    <h2 class="mb-3">會員管理</h2>
                </div>
            </div>
            <div class="container">
                <div class="row d-flex">
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="search" class="form-control" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="請輸入欲搜尋的使用者">
                            <div class="input-group-append">
                                <button class="btn btn-outline-warning m-0" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div>
                                <a class="btn btn-neumorphic user-btn" <?php if($order==1)echo"active"?>href="users.php?p=<?= $page?>&order=1">排序
                                    <i class="fa-solid fa-arrow-up-a-z"></i>
                                </a>
                                <a class="btn btn-neumorphic user-btn" <?php if($order==2)echo"active"?>href="users.php?p=<?= $page?>&order=2">排序
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
                                <?php if (isset($_GET["search"]) && $search !== ''): ?>
                                    <?= htmlspecialchars($search) ?> 的搜尋結果: 共有<?= $userCount['count'] ?>個使用者
                                <?php elseif (isset($_GET["search"]) && $search === ''): ?>
                                    請輸入有效搜尋字
                                <?php else: ?>
                                    共有<?= $userCount['count'] ?>個使用者
                                <?php endif; ?>
                            </h3>

                            <?php if (!empty($users)): ?>
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="users.php">全部</a>
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
                                                <td><?= htmlspecialchars($user["user_id"]) ?></td>
                                                <td><?= htmlspecialchars($user["name"]) ?></td>
                                                <td><?= htmlspecialchars($user["email"]) ?></td>
                                                <td><?= htmlspecialchars($user["phone"]) ?></td>
                                                <td>
                                                    <a class="btn btn-primary" href="user.php?user_id=<?= htmlspecialchars($user["user_id"]) ?>"><i class="fa-solid fa-eye"></i></a>
                                                    <a class="btn btn-danger" href="doDeleteUser.php?user_id=<?= htmlspecialchars($user["user_id"]) ?>"><i class="fa-solid fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center">
                                    <nav aria-label="page navigation">
                                        <ul class="pagination pagination-lg">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item"><a class="page-link" href="?p=<?= $page - 1 ?><?= isset($_GET["search"]) ? '&search=' . urlencode($search) : '' ?>">Previous</a></li>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                                    <a class="page-link" href="?p=<?= $i ?><?= isset($_GET["search"]) ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($page < $totalPage): ?>
                                                <li class="page-item"><a class="page-link" href="?p=<?= $page + 1 ?><?= isset($_GET["search"]) ? '&search=' . urlencode($search) : '' ?>">Next</a></li>
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
