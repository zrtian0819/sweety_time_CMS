<?php


session_start();

if(!isset($_POST["account"])){ // 檢查是否通過 POST 方法提交了 "account" 資料
    header("location: sign-in.php"); // 如果 "account" 未設置，重定向到登入頁面 sign-in.php
    exit; // 停止腳本繼續執行
}

require_once("../db_connect.php");

$account = $_POST["account"];
$password = $_POST["password"];

// 帳號為空的處理方式
if (empty($account)) {
    $data = [
        "status" => 0,
        "message" => "請輸入帳號"
    ];
    echo json_encode($data);

    exit;
}

// 密碼為空的處理方式
if (empty($password)) {
    $data = [
        "status" => 0,
        "message" => "請輸入密碼"
    ];
    echo json_encode($data);

    exit;
}

//密碼加密
$password = md5($password);

$sql = "SELECT * FROM users WHERE account='$account' AND password='$password'";

$result = $conn->query($sql);
$userCount = $result->num_rows;

//判定有沒有此使用者的方式
if ($userCount > 0) {

    unset($_SESSION["error"]);
    $user = $result->fetch_assoc();

    // 僅將部分資訊存入session的方法
    $_SESSION["user"] = [
        "user_id" => $user["user_id"],
        "account" => $user["account"],
        "password" => $user["password"],
        "role" => $user["role"],
        "name" => $user["name"]
    ];

    $userId_api = $user["user_id"];

    $sql_shop="SELECT * FROM shop WHERE user_id= $userId_api";
    $result_shop = $conn->query($sql_shop);
    $row_shop = $result_shop -> fetch_assoc();

    $shopId_api = $row_shop["shop_id"];

    $_SESSION["shop"] = [
        "shop_id" => $shopId_api
    ];

    $data = [
        "status" => 1,
        "message" => "登入成功",
    ];
    echo json_encode($data);
} else {
    // $_SESSION["error"]["message"]="帳號或密碼錯誤";

    if (!isset($_SESSION["error"]["times"])) {
        $_SESSION["error"]["times"] = 1;
    } else {
        $_SESSION["error"]["times"]++;
    }
    $errorTimes = $_SESSION["error"]["times"];
    $acceptErrorTimes = 6;
    $remainErrorTimes = $acceptErrorTimes - $errorTimes;

    // $_SESSION["error"]["message"] .= "還有 $remainErrorTimes 次機會" ;

    $data = [
        "status" => 2,
        "message" => "還有 $remainErrorTimes 次機會",
        "remains" => $remainErrorTimes
    ];
    echo json_encode($data);
}


$conn->close();
