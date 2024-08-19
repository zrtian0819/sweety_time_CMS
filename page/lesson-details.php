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
                </div>
                <div class="col-lg-8 m-2">
                    <h3 class="p-2">課程介紹</h3>
                    <p class="p-2"><?= $row["description"] ?></p>
                    <h3 class="p-2">地點：<?= $row["classroom_name"] ?></h3>
                    <p class="ps-2">地址：<?= $row["location"] ?></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>