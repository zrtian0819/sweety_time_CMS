<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");

//篩選
$status = isset($_GET["status"]) ? $_GET["status"] : "all";
$search = isset($_GET["search"]) ? $_GET["search"] : "";
$sort = isset($_GET["sort"]) ? $_GET["sort"] : "";
$class = isset($_GET["class"]) ? $_GET["class"] : "";
$page = isset($_GET["p"]) ? $_GET["p"] : 1;

//分頁顯示
$per_page = 10;
$startItem = ($page - 1) * $per_page;

//預設sql
$sql = "SELECT lesson.*, IFNULL(COUNT(student.lesson_id), 0) AS student_count 
        FROM lesson 
        LEFT JOIN student ON lesson.lesson_id = student.lesson_id 
        WHERE 1=1";

//狀態
if ($status == "on") {
    $sql .= " AND activation = 1";
} elseif ($status == "off") {
    $sql .= " AND activation = 0";
} else {
    $sql;
}


//課程分類
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

//搜尋課程
if ($search != "" && $class != "all") {
    $sql .= " AND name LIKE '%$search%'";
} elseif ($search != "" && $class == "all") {
    $sql .= " AND name LIKE '%$search%'";
}

//排序
switch ($sort) {
    case "id":
        $sql .= " GROUP BY lesson.lesson_id ORDER BY lesson.lesson_id ASC";
        break;
    case "people":
        $sql .= " GROUP BY lesson.lesson_id ORDER BY student_count DESC";
        break;
    case "date":
        $sql .= " GROUP BY lesson.lesson_id ORDER BY lesson.start_date ASC";
        break;
    default:
        $sql .= " GROUP BY lesson.lesson_id";
}

//符合上述條件的總數
$count_sql = $sql;
$resultCount = $conn->query($count_sql);
$allCount = $resultCount->num_rows;

//分頁
$limit = " LIMIT $startItem, $per_page";
$sql .= $limit;
$total_page = ceil($allCount / $per_page);

// echo $sql;
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

//teacher
$sqlTeacher = "SELECT * FROM teacher ORDER BY teacher_id";
$resultTea = $conn->query($sqlTeacher);
$rowsTea = $resultTea->fetch_all(MYSQLI_ASSOC);
// print_r($rowsTea);

//老師關聯式陣列
$teacherArr = [];
$teacherStatus = [];
foreach ($rowsTea as $teacher) {
    $teacherArr[$teacher["teacher_id"]] = $teacher["name"];
    $teacherStatus[$teacher["teacher_id"]] = $teacher["valid"];
    // print_r($teacherArr);
}

//分類
$sqlProductClass = "SELECT * FROM product_class ORDER BY product_class_id";
$resultPro = $conn->query($sqlProductClass);
$rowsPro = $resultPro->fetch_all(MYSQLI_ASSOC);

//分類關聯式陣列
$productClassArr = [];
foreach ($rowsPro as $productClass) {
    $productClassArr[$productClass["product_class_id"]] = $productClass["class_name"];
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Lesson</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .addClass {
            margin-left: auto;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5 pt-3">
            <h1>課程列表</h1>

            <div class="py-3">
                <form action="" method="GET">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="搜尋課程" name="search" value="<?= $search ?>">
                        <select class="form-select" aria-label="Default select example" name="class">
                            <option value="all" <?= $class == "all" ? "selected" : "" ?>>分類</option>
                            <option value="1" <?= $class == "1" ? "selected" : "" ?>>蛋糕</option>
                            <option value="2" <?= $class == "2" ? "selected" : "" ?>>餅乾</option>
                            <option value="3" <?= $class == "3" ? "selected" : "" ?>>塔 / 派</option>
                            <option value="4" <?= $class == "4" ? "selected" : "" ?>>泡芙</option>
                            <option value="5" <?= $class == "5" ? "selected" : "" ?>>冰淇淋</option>
                            <option value="6" <?= $class == "6" ? "selected" : "" ?>>其他</option>
                        </select>
                        <select class="form-select" aria-label="Default select example" name="sort">
                            <option value="id" <?= $sort == "id" ? "selected" : "" ?>>依課程編號排序(預設)</option>
                            <option value="people" <?= $sort == "people" ? "selected" : "" ?>>依報名人數排序</option>
                            <option value="date" <?= $sort == "date" ? "selected" : "" ?>>依時間排序</option>
                        </select>
                        <button class="btn neumorphic" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
            <ul class="nav nav-tabs-custom">
                <li class="nav-item">
                    <a class="main-nav nav-link <?= $status === 'all' ? 'active' : '' ?>" href="?status=all">全部</a>
                </li>
                <li class="nav-item">
                    <a class="main-nav nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on&search=<?= $search ?>&class=<?= $class ?>&sort=<?= $sort ?>&p=<?= $page ?>">上架中</a>
                </li>
                <li class="nav-item">
                    <a class="main-nav nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off&search=<?= $search ?>&class=<?= $class ?>&sort=<?= $sort ?>&p=<?= $page ?>">已下架</a>
                </li>
                <a href="addLesson.php" class="btn btn-custom addClass btn-animation d-inline-flex align-items-center">
                    <i class="fa-solid fa-plus"></i><span class="btn-animation-innerSpan">新增課程</span></a>
            </ul>

            <!-- Content -->
            <table class="table table-hover">
                <thead class="text-center">
                    <th>課程編號</th>
                    <th>課程狀態</th>
                    <th>課程名稱</th>
                    <th>課程分類</th>
                    <th>授課老師</th>
                    <th>課程時間</th>
                    <th>課程人數</th>
                    <th>報名人數</th>
                    <th>詳細資訊</th>
                </thead>
                <?php foreach ($rows as $row):
                    // print_r($row);
                    $id = $row["lesson_id"];
                    $date = $row["start_date"];
                    $dateStr = new DateTime($date);
                    $formartDate = $dateStr->format("Y-m-d H:i") ?>
                    <tbody>
                        <tr class="text-center m-auto align-middle">
                            <td><?= $id ?></td>
                            <?php if ($row["activation"] == 1 && $teacherStatus[$row["teacher_id"]] == 1) : ?>
                                <td>上架中</td>
                            <?php else: ?>
                                <td class='text-danger'>已下架</td>
                            <?php endif; ?>
                            <td><?= $row["name"] ?></td>
                            <td><?= $productClassArr[$row["product_class_id"]] ?></td>
                            <td><?= $teacherArr[$row["teacher_id"]] ?></td>
                            <td><?= $formartDate ?></td>
                            <td><?= $row["quota"] ?></td>
                            <td><?php
                                $sqlStudent = "SELECT * FROM student WHERE lesson_id = $id";
                                $resultStu = $conn->query($sqlStudent);
                                $count = $resultStu->num_rows;
                                $rowStu = $resultStu->fetch_assoc();
                                ?>
                                <?= $count ?></td>
                            </td>
                            <td>
                                <?php if ($teacherStatus[$row["teacher_id"]] != 0): ?>
                                    <a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-eye"></i></a>
                                    <a href="editLesson.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <?php if ($status === "off"): ?>
                                        <a href="../function/doReloadLesson.php?id=<?= $id ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>
                                    <?php else: ?>
                                        <?php if ($row["activation"] == 1): ?>
                                            <a href="../function/doDeleteLesson.php?id=<?= $id ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                        <?php else: ?>
                                            <a href="../function/doReloadLesson.php?id=<?= $id ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="d-flex justify-content-center align-items-center">
                                        <a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom me-2"><i class="fa-solid fa-eye"></i></a>
                                        <div class="text-danger text-center">此老師已下架</div>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
            <?php if (isset($page)) : ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                            <li class="page-item m-2 <?php if ($i == $page) echo "active" ?>"><a class="page-link btn-custom" href="lesson.php?status=<?= $status ?>&search=<?= $search ?>&class=<?= $class ?>&sort=<?= $sort ?>&p=<?= $i ?>"><?= $i ?></a></li>
                        <?php endfor; ?>
                    </ul>

                </nav>
            <?php endif; ?>

        </div>
    </div>
    <?php include("../js.php") ?>
    <?php $conn->close() ?>
</body>

</html>