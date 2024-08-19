<!-- 檢查登入狀態；-->
<?php

// 若session中沒有user則導頁回登入畫面
if(!isset($_SESSION["user"])){
    header("location: ../page/login.php");
    exit;
}

?>