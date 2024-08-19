<?php

if (!isset($_GET["id"])) {
    header("location:lesson.php");
    exit;
}

require_once("../db_connect.php");



$id = $_GET["id"];

$sql = "SELECT * FROM lesson WHERE lesson_id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


//teacher
$sqlTeacher = "SELECT * FROM teacher ORDER BY teacher_id";
$resultTea = $conn->query($sqlTeacher);
$rowsTea = $resultTea->fetch_all(MYSQLI_ASSOC);

//關聯式陣列
$teacherArr = [];
foreach ($rowsTea as $teacher) {
    $teacherArr[$teacher["teacher_id"]] = $teacher["name"];
}


//student
$sqlStudent = "SELECT * FROM student WHERE lesson_id = $id";
$resultStu = $conn->query($sqlStudent);
$count = $resultStu->num_rows;
$rowStu = $resultStu->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title><?= $row["name"] ?></title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <!-- Content -->
            <h1><?= $row["name"] ?></h1>
            <div class="row justify-content-center">
                <div class="col-lg-3 m-2">
                    <img src="../images/lesson/<?= $row["img_path"] ?>" alt="<?= $row["name"] ?>" class="ratio ratio-4x3">
                    <h3 class="pt-2">講師：<?= $teacherArr[$row["teacher_id"]] ?></h3>
                    <h4>課程時間：<?= $row["start_date"] ?></h4>
                    <h4 class="h4">價錢：<?= $row["price"] ?></h4>
                    <h5>地點：<?= $row["classroom_name"] ?></h5>
                    <p>地址：<?= $row["location"] ?></p>
                    <h5>報名人數：<?=$count?></h5>
                </div>
                <div class="col-lg-8 ms-2">
                    <h3 class="p-2">課程介紹</h3>
                    <p class="p-2"><?= $row["description"] ?></p>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php") ?>
</body>

</html>