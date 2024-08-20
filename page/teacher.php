<?php
require_once("../db_connect.php");

$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

switch ($status) {
    case 'on':
        $status_condition = "WHERE valid = 1";
        break;
    case 'off':
        $status_condition = "WHERE valid = 0";
        break;
    default:
        $status_condition = ""; // 顯示全部資料
        break;
}

// 搜尋條件
if (!empty($search)) {
    $search_condition = " AND (name LIKE ? OR expertise LIKE ?)";
} else {
    $search_condition = "";
}

// 計算總數量來確定頁數
$sqlCount = "SELECT COUNT(*) as count FROM teacher $status_condition $search_condition";
$stmtCount = $conn->prepare($sqlCount);
if (!empty($search)) {
    $search_param = "%$search%";
    $stmtCount->bind_param("ss", $search_param, $search_param);
} else {
    $stmtCount->execute(); // 如果沒有搜尋條件，直接執行
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$row = $resultCount->fetch_assoc();
$teacherCount = $row['count'];

$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
$per_page = 10;
$start_item = ($page - 1) * $per_page;
$total_page = ceil($teacherCount / $per_page);

$sql = "SELECT * FROM teacher $status_condition $search_condition LIMIT $start_item, $per_page";
$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $stmt->bind_param("ss", $search_param, $search_param);
}
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>教師列表</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <div class="py-2">
                <form action="teacher.php" method="GET">
                    <div class="input-group">
                        <input type="search" class="form-control" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="搜尋老師">
                        <div class="input-group-append">
                            <button class="btn btn-custom" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <ul class="nav nav-tabs nav-tabs-custom d-flex justify-content-between">
                <div class="d-flex">
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'all' ? 'active' : '' ?>" href="?status=all&search=<?= htmlspecialchars($search) ?>">全部</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on&search=<?= htmlspecialchars($search) ?>">開課中</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off&search=<?= htmlspecialchars($search) ?>">已下架</a>
                    </li>
                </div>
                <li><a class="btn btn-custom" href="teacher-create.php"><i class="fa-solid fa-user-plus"></i></a></li>
            </ul>

            <?php if ($teacherCount > 0) : ?>
                <table class="table table-hover">
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
                                <td class="align-middle"><a class="teacher-profile" href=""><img class="teacher-profile-img" src="<?= htmlspecialchars($teacher["img_path"]) ?>" alt=""></a></td>
                                <td class="align-middle"><?= htmlspecialchars($teacher["teacher_id"]) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($teacher["name"]) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($teacher["expertise"]) ?></td>
                                <td class="align-middle">
                                    <a class="btn btn-custom" href="teacher.php?id=<?= htmlspecialchars($teacher["teacher_id"]) ?>"><i class="fa-regular fa-eye"></i></a>
                                    <a class="btn btn-custom" href="teacher-edit.php?id=<?= htmlspecialchars($teacher["teacher_id"]) ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <a class="btn btn-custom" href="teacher-delete.php?id=<?= htmlspecialchars($teacher["teacher_id"]) ?>"><i class="fa-solid fa-trash-can"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="btn-neumorphic" href="teacher.php?status=<?= $status ?>&p=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php else : ?>
                <p>No teachers found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
<?php $conn->close(); ?>

</html>
