<?php

require_once("../db_connect.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$name = $_SESSION["user"]["name"];
$SessRole = $_SESSION["user"]["role"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Sweety Time</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">

            <h2 class="fw-bolder"><?= $name?>, 您好！</h2>
            <p>
                <?php if($SessRole=="admin"):?>
                    請使用側邊導覽列以管理您的平台資料。
                <?php elseif($SessRole=="shop"): ?>
                    請使用側邊導覽列以管理您的商店資料。
                <?php else:?>
                    您沒有任何後台權限。
                <?php endif;?>

            </p>
            

    </div>

    <?php include("../js.php"); ?>
</body>

</html>