<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");

$sql = "SELECT * FROM users WHERE role = 'user'";

// 分頁條件設置
$per_page = 10;
$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
$page = max(1, $page);
$start_item = ($page - 1) * $per_page;

// 搜索
$search = isset($_GET["search"]) ? trim($_GET["search"]) : '';

// 狀態
$status = isset($_GET["status"]) ? $_GET["status"] : 'on';

if ($status === 'on') {
    $sql .= " AND activation = 1";
} elseif ($status === 'off') {
    $sql .= " AND activation = 0";
    // 查詢黑名單列表的人數
    $off_sql = str_replace('*', 'COUNT(*) as count', $sql);
    $off_result = $conn->query($off_sql);
    if ($off_result) {
        $off_count = $off_result->fetch_assoc()['count'];
    } else {
        die("計數查詢錯誤: " . $conn->error);
    }
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

        .user-info-container {
            text-align: center;
            margin: 20px;
        }

        .user-portrait {
            display: block;
            margin: 0 auto 30px auto;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 10px;
        }

        .user-info {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .user-info strong {
            color: #333;
            font-weight: bold;
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .modal-content {
            margin: auto;
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
                    <?php if ($status === 'off' && $off_count == 0): ?>
                        <a class="btn btn-neumorphic user-btn mt-0" href="users.php" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
                    <?php endif; ?>
                    <!-- <h2 class="mb-3">會員管理</h2> -->
                </div>
            </div>
            <div class="container-fluid">
                <div class="row d-flex">
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="search" class="form-control" name="search" value="<?= ($search) ?>" placeholder="請輸入欲搜尋的使用者">
                            <div class="input-group-append">
                                <button class="btn btn-custom" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between my-3">
                            <div class="d-flex flex-row">
                                <a class="btn btn-neumorphic user-btn nav-link <?= $order == 1 ? 'active' : '' ?>" href="users.php?p=<?= $page ?>&order=1&status=<?= $status ?>&search=<?= urlencode($search) ?>">排序
                                    <i class="fa-solid fa-arrow-up-a-z"></i>
                                </a>
                                <a class="btn btn-neumorphic user-btn nav-link <?= $order == 2 ? 'active' : '' ?>" href="users.php?p=<?= $page ?>&order=2&status=<?= $status ?>&search=<?= urlencode($search) ?>">排序
                                    <i class="fa-solid fa-arrow-down-a-z"></i>
                                </a>
                            </div>
                            <div>
                                <a class="btn-animation btn btn-custom d-flex flex-row align-items-center" href="user-add.php">
                                    <i class="fa-solid fa-plus align-middle"></i><span class="btn-animation-innerSpan d-inline-block"> 新增會員</span>
                                </a>
                            </div>
                        </div>

                        <div class="col p-2">
                            <?php if (!empty($users)): ?>
                                <div class="d-flex justify-content-between">
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
                                    <h3 class="mx-2 my-0">
                                        <?php if ($search !== ''): ?>
                                            <?= ($search) ?> 的搜尋結果: 共有 <?= $userCount['count'] ?> 個使用者
                                        <?php else: ?>
                                            共有 <?= $userCount['count'] ?> 個使用者
                                        <?php endif; ?>
                                    </h3>
                                </div>

                                <table class="table table-hover">
                                    <thead class="text-center table-pink">
                                        <tr class="text-center m-auto align-middle">
                                            <th class="text-center col-2">User ID</th>
                                            <th class="text-center col-2">Name</th>
                                            <th class="text-center col-3">Email</th>
                                            <th class="text-center col-3">Phone</th>
                                            <th class="text-center col-2">詳細資訊</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                            <tr class="text-center m-auto align-middle">
                                                <td class="text-center"><?= ($user["user_id"]) ?></td>
                                                <td class="text-center"><?= ($user["name"]) ?></td>
                                                <td class="text-center"><?= ($user["email"]) ?></td>
                                                <td class="text-center"><?= ($user["phone"]) ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <button type="button" class="btn btn-custom view-details" data-id="<?= ($user["user_id"]) ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            <i class="fa-solid fa-eye"></i>
                                                        </button>
                                                        <a href="user-edit.php?user_id=<?= $user["user_id"] ?>" class="btn btn-custom mx-2"><i class="fa-solid fa-user-pen"></i></a>
                                                        <?php if ($user["activation"] == 1): ?>
                                                            <a class="btn btn-danger" href="../function/doDeleteUser.php?id=<?= $user["user_id"] ?>"><i class="fa-solid fa-trash"></i></a>
                                                        <?php else: ?>
                                                            <a class="btn btn-primary" href="../function/doReloadUser.php?id=<?= $user["user_id"] ?>"><i class="fa-solid fa-plus"></i></a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <div class="d-flex justify-content-center">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination d-flex justify-content-center">
                                            <!-- 第一頁 -->
                                            <?php if ($page > 1): ?>
                                                <li class="page-item px-1">
                                                    <a class="page-link btn-custom" href="?p=1&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>">頁首</a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- 前面的 "..." -->
                                            <?php if ($page > 3): ?>
                                                <li class="page-item px-1">
                                                    <a class="page-link btn-custom" href="?p=<?= $page - 3 ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>">...</a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- 中間頁碼 -->
                                            <?php for ($i = max(1, $page - 2); $i <= min($totalPage, $page + 2); $i++): ?>
                                                <li class="page-item px-1 <?= ($page == $i) ? 'active' : '' ?>">
                                                    <a class="page-link btn-custom" href="?p=<?= $i ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>"><?= $i ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <!-- 後面的 "..." -->
                                            <?php if ($page < $totalPage - 2): ?>
                                                <li class="page-item px-1">
                                                    <a class="page-link btn-custom" href="?p=<?= $page + 3 ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>">...</a>
                                                </li>
                                            <?php endif; ?>

                                            <!-- 最後一頁 -->
                                            <?php if ($page < $totalPage): ?>
                                                <li class="page-item px-1">
                                                    <a class="page-link btn-custom" href="?p=<?= $totalPage ?>&status=<?= $status ?>&search=<?= urlencode($search) ?>&order=<?= $order ?>">頁尾</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php else: ?>
                                暫無符合條件的使用者
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fad" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="exampleModalLabel">詳細資訊</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="userDetails">
                        <!-- user詳細信息 -->
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div>
                        <a href="user-edit.php?user_id=<?= $user["user_id"] ?>" class="btn btn-custom">使用者資料編輯</a>
                        <a href="user-coupon-list.php?user_id=<?= $user["user_id"] ?>" class="btn btn-custom">個人優惠卷</a>
                    </div>
                    <button type="button" class="btn btn-custom bg-danger" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>


    <?php include("../js.php"); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 點擊按鈕時彈跳出會員資料
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function() {
                    let userId = this.getAttribute('data-id');

                    //從 PHP 會員詳細資料
                    fetch(`user-view.php?id=${userId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                document.getElementById('userDetails').innerHTML = `<p>${data.error}</p>`;
                            } else {
                                // 填充彈跳視窗的內容
                                let portraitPath = data.portrait_path ? `../images/users/${data.portrait_path}` : '../images/default-user.png';
                                document.getElementById('userDetails').innerHTML = `
                        <div class="user-info-container">
                            <div class="text-center">
                                <img src="${portraitPath}" alt="${data.name}" class="img-fluid user-portrait">
                            </div>
                            <table class="user-info-box m-2">
                                <p class="user-info"><strong>ID:</strong> ${data.user_id}</p>
                                <p class="user-info"><strong>Name:</strong> ${data.name}</p>
                                <p class="user-info"><strong>Account:</strong> ${data.account}</p>
                                <p class="user-info"><strong>Birthday:</strong> ${data.birthday}</p>
                                <p class="user-info"><strong>Email:</strong> ${data.email}</p>
                                <p class="user-info"><strong>Phone:</strong> ${data.phone}</p>
                                <p class="user-info"><strong>Sign up time:</strong> ${data.sign_up_time}</p>
                            </table>
                        </div>`;

                                document.querySelector('.modal-footer .btn-custom[href*="user-edit.php"]').href = `user-edit.php?user_id=${data.user_id}`;
                                document.querySelector('.modal-footer .btn-custom[href*="user-coupon-list.php"]').href = `user-coupon-list.php?user_id=${data.user_id}`;
                            }
                        })
                        .catch(error => {
                            console.error('There was a problem with the fetch operation:', error);
                            document.getElementById('userDetails').innerHTML = `<p>加載數據時發生錯誤，請稍後再試。</p>`;
                        });
                });
            });

            // 监听排序按钮点击事件，添加橘色背景类
            document.querySelectorAll('.user-btn').forEach(button => {
                button.addEventListener('click', function() {
                    // 先移除所有按钮的橘色背景类
                    document.querySelectorAll('.user-btn').forEach(btn => btn.classList.remove('btn-orange'));

                    // 给当前点击的按钮添加橘色背景类
                    this.classList.add('btn-orange');
                });
            });
        });
    </script>

</body>

</html>