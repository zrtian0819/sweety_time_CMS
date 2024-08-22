<?php
// 連接資料庫
require_once("../db_connect.php");

// 取得 POST 數據
$id = $_POST['id'];
$newEnabledStatus = $_POST['newEnabledStatus'];

// 更新enabled
$updateSql = "UPDATE users_coupon SET enabled = ? WHERE users_coupon_id = ?";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param('ii', $newEnabledStatus, $id);

if ($updateStmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}

$conn->close();
?>