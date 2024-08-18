<?php

require_once("../db_connect.php");


$sql = "SELECT * FROM lesson WHERE activation = 1";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// print_r($row);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Lesson</title>
    <?php include("../css/css.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header.php"); ?>
    <?php include("../modules/dashboard-sidebar.php"); ?>

    <!-- Content -->
    <div class="col-lg-9 col-md-9"> <!-- 這一層佈局不要動 -->
        <div class="content neumorphic">
            <?php foreach ($rows as $row): ?>
                <h1><?= $row["name"] ?></h1>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>