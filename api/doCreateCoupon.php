<?php
// 連接資料庫
require_once("../db_connect.php");

// 取得POST數據
$name = $_POST["name"];
$discount_rate = ($_POST["discount_rate"]/100);
$start_time = $_POST["start_time"];
$activation = $_POST["activation"];


// 自動判斷數據
if (isset($_POST["end_date"]) && !empty($_POST["end_date"])) {
    $end_date = $_POST["end_date"];
    $permanent = 0; // 用 0 表示 FALSE
} else {
    $end_date = NULL;
    $permanent = 1; // 用 1 表示 TRUE
}

$created_at = date("Y-m-d H:i:s");

// 準備 SQL 語句
$stmt = $conn -> prepare (
    "INSERT INTO coupon (name, discount_rate, start_time, end_date, activation, permanent, created_at)  
    VALUES (?, ?, ?, ?, ?, ?, ?)
    "
    );

// 將參數取代佔位符
$stmt -> bind_param("sdsssis",
                    $name, $discount_rate, $start_time, $end_date, $activation, $permanent, $created_at
                    );

// 寫入資料
if ($stmt->execute()) {
    $last_id = $conn->insert_id;
    $message = "優惠券新增成功！id 為 ". $last_id;
} else {
    $message = "Oops！優惠券新增失敗，錯誤: ". $stmt->error;
}

// 重新導向編輯頁並傳送訊息
// 有時間可以用中介頁面來避免使用GET
header("Location: ../page/coupon-create.php?message=" . urlencode($message));
exit();

// $stmt->close();
// $conn->close();
?>