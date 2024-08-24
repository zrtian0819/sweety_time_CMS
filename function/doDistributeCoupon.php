<?php
// 連接資料庫
require_once("../db_connect.php");

// 取得POST數據
$userIdList = $_POST["userIdList"];
$coupon_id = $_POST["coupon_id"];
$length = count($userIdList);

// 取得時間
$now = date("Y-m-d H:i:s");

// 準備 SQL 語法
$sql = "INSERT INTO users_coupon (user_id, coupon_id, order_id, enabled, recieved_time, used_time, used_status) VALUES ";

$values = [];
foreach ($userIdList as $user_id) {
    $values[] = "($user_id, $coupon_id, 0, 1, '$now', '0000-00-00 00:00:00', 'FALSE')";
}

// 將所有 values 合併成一個完整的 SQL 語法
$sql .= implode(", ", $values);

// 執行 SQL 語法
if (mysqli_query($conn, $sql)) {
    echo "推送優惠券成功";
    $last_id = $conn->insert_id;
    $message = "推送優惠券成功！已對". $length. "人推送". $coupon_id."號優惠券";
    header("Location: ../page/coupon-distribute.php?coupon_id=".$coupon_id."&message=" . urlencode($message));
    exit();
} else {
    echo "推送優惠券失敗: " . mysqli_error($conn);
    $message = "Oops！編輯優惠券失敗，錯誤: ". $stmt->error;
    header("Location: ../page/coupon-distribute.php?coupon_id=".$coupon_id."&message=" . urlencode($message));
    exit();
}
?>