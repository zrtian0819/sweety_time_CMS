<?php
// 連接資料庫
require_once("../db_connect.php");

// 取得 POST 數據
$id = $_POST['id'];
$activation = $_POST['activation'];

// 更新activation
$updateSql = "UPDATE coupon SET activation = ? WHERE coupon_id = ?";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param('ii', $activation, $id);

if ($updateStmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>