<?php
$sever = "localhost";
$username = "admin";
$password = "12345";
$db_name = "sweety_time_cms";

$conn = new mysqli($sever, $username, $password, $db_name);

if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
} else {
    // echo "連線成功";
}
