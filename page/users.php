<?php

require_once("../db_connect.php");

if (isset($_GET["search"])) {
    $search = $_GET["search"];
    $sql = "SELECT * FROM users WHERE name LIKE '%$search%' AND activation=1";
    echo $sql;
} else {
    $sql = "SELECT * FROM users";
}

$result = $conn->query($sql);
$userCount = $result->num_rows;

?>
<!doctype html>
<html lang="en">

<head>
    <title>會員管理頁</title>
    <!-- Required meta tags -->
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
            <h2 class="mb-3">會員管理</h2>
            <div class="py-2">
                <?php if (isset($_GET["search"])): ?>
                    <a class="btn btn-neumorphic" href="users.php" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
                <?php endif; ?>
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
                        <div>
                            <a href="#" class="btn btn-neumorphic user-btn">新增
                                <i class="fa-solid fa-plus"></i>
                            </a>
                        </div>
                    </div>
                    <div class="main col neumorphic p-2">
                        <?php if ($userCount > 0): $rows = $result->fetch_all(MYSQLI_ASSOC); ?>
                            <h3>共有<?= $userCount ?>個使用者</h3>
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
                                        <th>其它</th>
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