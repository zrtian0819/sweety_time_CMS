<?php

require_once("../db_connect.php");


//篩選
$status = isset($_GET["status"]) ? $_GET["status"] : "all";
$search = isset($_GET["search"]) ? $_GET["search"] : "";
$sort = isset($_GET["sort"]) ? $_GET["sort"] : "";
$class = isset($_GET["class"]) ? $_GET["class"] : "";

//預設sql
$sql = "SELECT * FROM lesson WHERE 1=1";

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
        $sql .= " ORDER BY lesson_id ASC";
        break;
    case "count":
        $sql .= " ORDER BY $count ASC";
        break;
    case "date":
        $sql .= " ORDER BY start_date ASC";
        break;
    case "":
        $sql;
}


$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);


//teacher
$sqlTeacher = "SELECT * FROM teacher ORDER BY teacher_id";
$resultTea = $conn->query($sqlTeacher);
$rowsTea = $resultTea->fetch_all(MYSQLI_ASSOC);

//關聯式陣列
$teacherArr = [];
foreach ($rowsTea as $teacher) {
    $teacherArr[$teacher["teacher_id"]] = $teacher["name"];
}

//分類
$sqlProductClass = "SELECT * FROM product_class ORDER BY product_class_id";
$resultPro = $conn->query($sqlProductClass);
$rowsPro = $resultPro->fetch_all(MYSQLI_ASSOC);

//關聯式陣列
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
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5 pt-3">
            <div class="py-3">
                <form action="">
                    <div class="input-group">
                        <input type="search" class="form-control" placeholder="搜尋課程" name="search">
                        <select class="form-select" aria-label="Default select example" name="class">
                            <option value="all">分類</option>
                            <option value="1">蛋糕</option>
                            <option value="2">餅乾</option>
                            <option value="3">塔 / 派</option>
                            <option value="4">泡芙</option>
                            <option value="5">冰淇淋</option>
                            <option value="6">其他</option>
                        </select>
                        <select class="form-select" aria-label="Default select example" name="sort">
                            <option value="id">依課程編號排序(預設)</option>
                            <!-- <option value="count">依報名人數排序</option> -->
                            <option value="date">依時間排序</option>
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
                    <a class="main-nav nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on">上架中</a>
                </li>
                <li class="nav-item">
                    <a class="main-nav nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off">已下架</a>
                </li>
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
                    $id = $row["lesson_id"];
                    $date = $row["start_date"];
                    $dateStr = new DateTime($date);
                    $formartDate = $dateStr->format("Y-m-d H:i") ?>
                    <tbody>
                        <tr class="text-center m-auto">
                            <td><?= $id ?></td>
                            <?php echo ($row["activation"] == 1) ? "<td>" . "上架中" : "<td class='text-danger'>" . "已下架"; ?></td>
                            <td><?= $row["name"] ?></td>
                            <td><?= $productClassArr[$row["product_class_id"]] ?></td>
                            <td><?= $teacherArr[$row["teacher_id"]] ?></td>
                            <td><?= $formartDate ?></td>
                            <td><?= $row["quota"] ?></td>
                            <td>
                                <?php
                                $sqlStudent = "SELECT * FROM student WHERE lesson_id = $id";
                                $resultStu = $conn->query($sqlStudent);
                                $count = $resultStu->num_rows;
                                $rowStu = $resultStu->fetch_assoc();
                                ?>
                                <?= $count ?></td>
                            <td><a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-eye"></i></i></a>
                                <a href="lesson-details.php?id=<?= $id ?>" class="btn btn-custom"><i class="fa-solid fa-pen"></i></a>
                                <?php if ($status === "off"): ?>
                                    <a href="../function/doReloadLesson.php?id=<?= $id ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>
                                <?php else: ?>
                                    <?php if ($row["activation"] == 1): ?>
                                        <a href="../function/doDeleteLesson.php?id=<?= $id ?>" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                                    <?php else: ?>
                                        <a href="../function/doReloadLesson.php?id=<?= $id ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php include("../js.php") ?>
    <?php $conn->close() ?>
</body>

</html>