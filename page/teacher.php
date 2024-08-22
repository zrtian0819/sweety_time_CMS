<?php
require_once("../db_connect.php");

// 獲取狀態和搜尋字串，並設置默認值
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// 設置搜尋條件
$search_condition = "";
if ($search) { // 如果有搜尋字串，則設置搜尋條件
    $search_condition = "AND (name LIKE ? OR expertise LIKE ? OR description LIKE ?)";
}

switch ($status) {
    case 'on':
        // 狀態為 "開課中"，顯示 valid 為 1 的資料
        $status_condition = "WHERE valid = 1 $search_condition";
        break;
    case 'off':
        // 狀態為 "已下架"，顯示 valid 為 0 的資料
        $status_condition = "WHERE valid = 0 $search_condition";
        break;
    default:
        // 默認顯示全部資料
        $status_condition = "WHERE 1=1 $search_condition";
        break;
}

// 計算符合條件的總數量，用來計算頁數
$sqlCount = "SELECT COUNT(*) as count FROM teacher $status_condition";
$stmtCount = $conn->prepare($sqlCount);
if ($search) { // 綁定搜尋參數
    $search_param = "%$search%";
    $stmtCount->bind_param("sss", $search_param, $search_param, $search_param);
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$row = $resultCount->fetch_assoc();
$teacherCount = $row['count']; // 總數量

$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1; // 當前頁數，默認為 1
$per_page = 10; // 每頁顯示的資料數量
$start_item = ($page - 1) * $per_page; // 當前頁開始的資料索引
$total_page = ceil($teacherCount / $per_page); // 總頁數

// 獲取符合條件的教師資料
$sql = "SELECT * FROM teacher $status_condition LIMIT $start_item, $per_page";
$stmt = $conn->prepare($sql);
if ($search) { // 綁定搜尋參數
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC); // 獲取所有符合條件的資料
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
    <?php include("../css/css_Joe.php"); ?>
    <?php include("../css/teacher"); ?>

</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <!-- 導覽標籤 -->
            <ul class="nav nav-tabs nav-tabs-custom d-flex justify-content-between">
                <div class="d-flex">
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'all' ? 'active' : '' ?>" href="?status=all">全部</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on">開課中</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off">已下架</a>
                    </li>
                </div>
                <!-- 新增教師的按鈕 -->
                <li><a class="btn btn-custom" href="create-teacher.php"><i class="fa-solid fa-user-plus"></i></a></li>
            </ul>

            <!-- 搜尋框 -->
            <div class="input-group mb-3 mt-4">
                <form method="GET" action="teacher.php" class="d-flex">
                    <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>"> <!-- 保存當前的狀態 -->
                    <input type="text" class="form-control form-control-custom" name="search" placeholder="請輸入..." aria-label="輸入文字" aria-describedby="button-addon1" value="<?= htmlspecialchars($search) ?>"> <!-- 搜尋字串 -->
                    <button class="btn btn-enter" type="submit" id="button-addon1">提交</button> <!-- 提交搜尋表單 -->
                </form>
            </div>

            <!-- 顯示教師列表 -->
            <?php if ($teacherCount > 0) : ?>
                <table class="table table-hover mt-4">
                    <thead class="text-center">
                        <tr>
                            <th>Image</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Expertise</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $teacher) : ?>
                            <tr class="text-center m-auto">
                                <td class="align-middle"><a class="teacher-profile" href=""> <img src="../images/teachers/<?= htmlspecialchars($teacher["img_path"]) ?>" alt="<?= htmlspecialchars($teacher["name"]) ?>" class="ratio ratio-4x3"></a></td> <!-- 教師圖片 -->
                                <td class="align-middle"><?= htmlspecialchars($teacher["teacher_id"]) ?></td> <!-- 教師 ID -->
                                <td class="align-middle"><?= htmlspecialchars($teacher["name"]) ?></td> <!-- 教師名稱 -->
                                <td class="align-middle"><?= htmlspecialchars($teacher["expertise"]) ?></td> <!-- 教師專長 -->
                                <td class="align-middle">
                                    <a class="btn btn-custom" href="teacher-edit.php?id=<?= htmlspecialchars($teacher["teacher_id"]) ?>"><i class="fa-solid fa-pen-to-square"></i></a> <!-- 編輯按鈕 -->
                                    <a class="btn btn-custom" href="doDeleteTeacher.php?id=<?= htmlspecialchars($teacher["teacher_id"]); ?>">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                    <!-- 刪除按鈕 -->

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- 分頁導航 -->
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="btn-neumorphic" href="teacher.php?status=<?= htmlspecialchars($status) ?>&search=<?= htmlspecialchars($search) ?>&p=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php else : ?>
                <!-- 沒有找到教師資料時的提示訊息 -->
                <p>No teachers found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
<?php $conn->close(); ?> <!-- 關閉資料庫連接 -->

</html>