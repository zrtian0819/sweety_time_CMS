<?php
require_once("../db_connect.php");

if (!isset($_GET["id"])) {
    echo "無法識別的用戶 ID";
    exit;
}

$id = $_GET["id"];
$sql = "UPDATE users SET activation = 0 WHERE user_id = $id";

if ($conn->query($sql) === TRUE) {
    header("location: ../page/users.php?status=on");
} else {
    echo "軟刪除失敗: " . $conn->error;
}

$conn->close();
exit;
?>
