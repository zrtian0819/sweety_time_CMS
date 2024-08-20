<?php
require_once("../db_connect.php");

if(!isset($_POST["name"])){
    echo "請從正常管道進入此頁";
    exit;
}

$id = $_POST["id"];
$name = $_POST["name"];
$password = $_POST["password"];
$email = $_POST["email"];

$sql="UPDATE users SET name='$name',password='$password',email='$email' WHERE id='$id'";

if($conn->query($sql)===TRUE){
    echo "更新成功";
}else{
    echo "更新資料錯誤：".$conn->error;
}

header("location:user-edit.php?id=$id");

$conn->close(); 
?>