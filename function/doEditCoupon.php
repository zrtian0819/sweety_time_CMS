<?php
// 連接資料庫
require_once("../db_connect.php");

// 取得POST數據
$coupon_id = $_POST["coupon_id"];
$name = $_POST["name"];
$discount_rate = ($_POST["discount_rate"]/100);
$start_time = $_POST["start_time"];
$activation = $_POST["activation"];

$now = date("Y-m-d H:i:s");


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
    "UPDATE coupon 
    SET name = ?, discount_rate = ?, start_time = ?, end_date = ?, activation = ?, permanent = ?, created_at = ?, last_edited_at = ?
    WHERE coupon_id = ?"
);

// 將參數取代佔位符
$stmt -> bind_param("sdsssissi",
                    $name, $discount_rate, $start_time, $end_date, $activation, $permanent, $created_at, $now, $coupon_id
                    );

// 寫入資料
if ($stmt->execute()) {
    $last_id = $conn->insert_id;
    $message = "編輯優惠券成功！你剛剛編輯的優惠券id 是 ". $coupon_id;
    header("Location: ../page/coupon-list.php?message=" . urlencode($message));
    exit();
} else {
    $message = "Oops！編輯優惠券失敗，錯誤: ". $stmt->error;
    header("Location: ../page/coupon-edit.php?coupon_id=$coupon_id&message=" . urlencode($message));
    exit();
}

// 重新導向編輯頁並傳送訊息
// 有時間可以用中介頁面來避免使用GET


// $stmt->close();
// $conn->close();
?>