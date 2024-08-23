<?php
require_once("../db_connect.php");

if (!isset($_GET["id"])) {
    echo "請循正常管道進入此頁";
    exit;
}

$id = $_GET["id"];
$sql = "UPDATE lesson SET activation = 1 WHERE lesson_id = $id";
$conn->query($sql);

$conn->close();
header("Location: " . $_SERVER['HTTP_REFERER']);//回到上一頁