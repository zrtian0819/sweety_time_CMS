<?php
//登出按鈕做的事

session_start();
unset($_SESSION);

header("location: ../page/login.php");

exit;
