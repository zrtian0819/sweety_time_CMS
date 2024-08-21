<!-- 檢查登入狀態；-->
<?php

//避免大家頁面跳出暫時先停用
// exit;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 若session中沒有user則導頁回登入畫面
if (!isset($_SESSION["user"])) {
    header("location: ../page/login.php");
    exit;
}

?>