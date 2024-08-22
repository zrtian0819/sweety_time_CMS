<?php
require_once("../db_connect.php");
include("../function/login_status_inspect.php");

$role = $_SESSION["user"]["role"];


if ($role != "admin") {
    header("location: dashboard-home_Joe.php");
}

$delete_shop_id = $_SESSION["shop"]["shop_id"];
$user_shop_id = "SELECT suers.user_id"
// $delete_shop_id = $_POST["shop_id"];

$sql1 = "UPDATE shop SET activation = 0 WHERE shop_id = $delete_shop_id";
$sql2 = "UPDATE users SET role = 'user' WHERE user_id = $delete_shop_id;";

if ($conn->query($sql1) === TRUE) {
    echo "Shop 狀態已更新。";
} else {
    echo "更新 shop 資料錯誤: " . $conn->error;
}

if ($conn->query($sql2) === TRUE) {
    echo "User 角色已更新。";
} else {
    echo "更新 user 資料錯誤: " . $conn->error;
}

$conn->close();
header("location: ../page/shop-info-admin.php");
?>
