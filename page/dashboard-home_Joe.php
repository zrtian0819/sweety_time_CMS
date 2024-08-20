<?php

require_once("../db_connect.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$name = $_SESSION["user"]["name"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard-home_Joe</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">

            <h2><?= $name?>, 您好！</h2>
            <p>請使用側邊導覽列以管理您的商家。</p>
            

    </div>

    <?php include("../js.php"); ?>
</body>

</html>