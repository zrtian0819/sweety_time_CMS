<?php
require_once("../db_connect.php");

if (!isset($_GET['id'])) {
    echo "請循正常管道進入此頁";
    exit;
}

$id = $_GET["id"];

$sql = "UPDATE teacher SET valid = 0 WHERE teacher_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: teacher.php?status=off");
    exit();
} else {
    echo "刪除資料錯誤: " . $stmt->error;
}

$stmt->close();
$conn->close();
