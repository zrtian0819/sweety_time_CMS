<?php

require_once("../db_connect.php");


$sql = "SELECT * FROM lesson WHERE activation = 0";
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
        <div class="main col neumorphic p-5">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="lesson.php">全部</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="lesson-online.php">上架中</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="lesson-delete.php">已下架</a>
                </li>
            </ul>
            <!-- Content -->
            <table class="table">
                <thead class="text-center">
                    <th>課程編號</th>
                    <th>課程名稱</th>
                    <th>授課老師</th>
                    <th>課程人數</th>
                    <th>報名人數</th>
                    <th>詳細資訊</th>
                </thead>
                <?php foreach ($rows as $row): ?>
                    <tbody>
                        <tr class="text-center">
                            <td><?= $row["lesson_id"] ?></td>
                            <td><?= $row["name"] ?></td>
                            <td><?= $teacherArr[$row["teacher_id"]] ?></td>
                            <td><?= $row["quota"] ?></td>
                            <td>
                                <?php
                                $lessonID = $row["lesson_id"];
                                $sqlStudent = "SELECT * FROM student WHERE lesson_id = $lessonID";
                                $resultStu = $conn->query($sqlStudent);
                                $count = $resultStu->num_rows;
                                $rowStu = $resultStu->fetch_assoc();
                                ?>
                                <?= $count ?></td>
                            <td><a href="lesson-details.php?id=<?= $row["lesson_id"] ?>" class="btn"><i class="fa-regular fa-eye"></i></a>
                                <a href="lesson-details.php?id=<?= $row["lesson_id"] ?>" class="btn"><i class="fa-solid fa-pen"></i></a>
                                <a href="doReloadLesson.php?id=<?= $row["lesson_id"] ?>" class="btn btn-primary"><i class="fa-solid fa-plus"></i></a>
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