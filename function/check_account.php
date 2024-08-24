<?php
require_once("../db_connect.php");

if(isset($_POST['account'])) {
    $account = $_POST['account'];

    // 防止 SQL 注入
    $account = mysqli_real_escape_string($conn, $account);

    $sql = "SELECT * FROM users WHERE account = '$account'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        echo "exists";
    } else {
        echo "not_exists";
    }
} else {
    echo "error";
}

$conn->close();
?>