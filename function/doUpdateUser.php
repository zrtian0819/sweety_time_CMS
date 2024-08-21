<?php
require_once("../db_connect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$shop_id = $_SESSION["shop"]["shop_id"];

if(!isset($_POST["name"])){
    echo "請從正常管道進入此頁";
    exit;
}

$name = $_POST["name"];
$phone = $_POST["phone"];
$address = $_POST["address"];
$description = $_POST["description"];


$sql="UPDATE shop SET name='$name',phone='$phone',address='$address',description='$description' WHERE shop_id='$shop_id'";

if($conn->query($sql)===TRUE){
    echo "更新成功";
}else{
    echo "更新資料錯誤：".$conn->error;
}

header("location:../page/shop-info.php?shopId=$shop_id");

$conn->close(); 
?>