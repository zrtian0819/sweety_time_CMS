<?php
require_once("../db_connect.php");      //避免sidebar先載入錯誤,人天先加的

if (session_status() == PHP_SESSION_NONE) {  //啟動session
    session_start();
}

// 检查用户是否已登录以及是否有角色信息
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION["user"]["role"];
 //假設session之中沒有shop_id則為NULL
$shop_id = $_SESSION["shop"]["shop_id"] ?? null; 

// 根据角色重定向到不同頁面
if ($role != "admin") {
    header("Location: dashboard-home_Joe.php");
    exit;
} else{

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All shop list</title>
    <?php include("../css/css_Joe.php"); ?>
</head>
<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <h2 class="mb-3">店家管理清單</h2>
            <div class="container">
                <div class="row">
                    <div class="col-12 position-relative d-flex justify-content-center mb-3 mb-md-0">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Shop ID</th>
                                    <th>店家名稱</th>
                                    <th>電話</th>
                                    <th>地址</th>
                                    <th>簡介</th>
                                    <th>註冊時間</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
</div>
</div>
</div>
</div>
</body>
</html>