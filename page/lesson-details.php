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

$sqlAll = "SELECT lesson.* FROM lesson";
$allResult = $conn->query($sqlAll);
$rows = $allResult->num_rows;


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

//分類
$productClass = $row["product_class_id"];
$sqlProductClass = "SELECT * FROM product_class WHERE product_class_id = $productClass";
$resultProduct = $conn->query($sqlProductClass);
$rowPro = $resultProduct->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title><?= $row["name"] ?></title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        a {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5 pt-4">
            <!-- Content -->
            <a href="lesson.php" class="btn btn-custom"><i class="fa-solid fa-arrow-left"></i></a>
            <h1 class="m-2"><?= $row["name"] ?></h1>
            <div class="row justify-content-center">
                <div class="col-lg-3 m-2">
                    <img src="../images/lesson/<?= $row["img_path"] ?>" alt="<?= $row["name"] ?>" class="ratio ratio-4x3">
                    <table class="table mt-2 table-hover">
                        <tbody>
                            <tr>
                                <th>
                                    <h5>分類</h5>
                                </th>
                                <td><?= $rowPro["class_name"] ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>講師</h5>
                                </th>
                                <td><?= $teacherArr[$row["teacher_id"]] ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>價錢</h5>
                                </th>
                                <td class="text-danger"><?= number_format($row["price"]) ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>時間</h5>
                                </th>
                                <td><?= $row["start_date"] ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>課程人數</h5>
                                </th>
                                <td><?= $row["quota"] ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>報名人數</h5>
                                </th>
                                <td><?= $count ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>地點</h5>
                                </th>
                                <td><?= $row["classroom_name"] ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>地址</h5>
                                </th>
                                <td><?= $row["location"] ?></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>狀態</h5>
                                </th>
                                <?php echo ($row["activation"] == 1) ? "<td>" . "上架中" : "<td class='text-danger'>" . "已下架"; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-8 ms-2">
                    <h3 class="p-2">課程介紹</h3>
                    <div class="p-2"><?= $row["description"] ?></div>
                    <div class="changePage text-end">
                        <?php if ($id > 1) : ?>
                            <a href="lesson-details.php?id=<?= $id - 1 ?>" class="btn-custom m-2 p-2">上一個課程</a>
                        <?php endif ?>
                        <?php if ($id < $rows) : ?>
                            <a href="lesson-details.php?id=<?= $id + 1 ?>" class="btn-custom m-2 p-2">下一個課程</a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php") ?>
    <?php $conn->close() ?>
</body>

</html>