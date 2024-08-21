<?php

require_once("../db_connect.php");

$per_page = 10;
$page=isset($_GET["p"])? (int)$_GET["p"]:1;
$page=max(1,$page);
$start_item=($page-1) * $per_page;

$sql = "SELECT * FROM users WHERE  activation =1 ";


if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sql .= " AND name LIKE '%$search%'";
}

if(isset($_GET["order"])){
    $order = $_GET["order"];
    switch($order){
        case 1:
            $sql .=" ORDER BY user_id ASC";
            break;
        case 2:
            $sql .=" ORDER BY user_id DESC";
            break;
        default:
            $sql .=" ORDER BY user_id ASC";
            break;

    }
}

$sql .= " LIMIT $start_item,$per_page";
$result = $conn->query($sql);

$count_sql = "SELECT COUNT(*) as count FROM users WHERE activation = 1 ";
$count_result = $conn->query($count_sql);
$userCount = $count_result->fetch_assoc();
$totalPage = ceil($userCount['count'] / $per_page);

?>
<!doctype html>
<html lang="en">

<head>
    <title>會員管理頁</title>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

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
                        <div class="input-group  mb-3">
                            <input type="search" class="form-control" name="search" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>" placeholder="請輸入欲搜尋的使用者">
                            <div class="input-group-append">
                                <button class="btn btn-outline-warning m-0 " type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>
                    
                    <div class="d-flex justify-content-between my-3">
                        <div>
                            <a href="#" class="btn btn-neumorphic user-btn">排序
                                <i class="fa-solid fa-arrow-up-a-z"></i>
                            </a>
                            <a href="#" class="btn btn-neumorphic user-btn">排序
                                <i class="fa-solid fa-arrow-down-a-z"></i>
                            </a>
                        </div>
                        <div class="main col neumorphic p-2">
                            <?php if ($userCount > 0): $rows = $result->fetch_all(MYSQLI_ASSOC); ?>
                                <?php if (isset($_GET["search"])): ?>
                                    <h3><?= $search ?> 的搜尋結果: 共有<?= $userCount ?>個使用者</h3>
                                <?php endif; ?>
                                <ul class="nav nav-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="users.php">全部</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">店家</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#">會員</a>
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
                                        <?php foreach ($rows as $user): ?>
                                            <tr>
                                                <td><?= $user["user_id"] ?></td>
                                                <td><?= $user["name"] ?></td>
                                                <td><?= $user["email"] ?></td>
                                                <td><?= $user["phone"] ?></td>
                                                <td>
                                                    <a class="btn btn-primary" href="user.php?user_id=<?= $user["user_id"] ?>"><i class="fa-solid fa-eye"></i></a>
                                                    <a class="btn btn-danger" href="doDeleteUser.php?user_id=<?= $user["user_id"] ?>"><i class="fa-solid fa-trash"></i></a>
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
                            <?php else: ?>
                                目前沒有使用者
                            <?php endif; ?>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>