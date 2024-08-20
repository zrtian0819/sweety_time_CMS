<?php

require_once("../db_connect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//避免誤點擊到此頁執行程式
if (!isset($_POST["name"])) {
    echo "非正常管道無法進入此頁";
    sleep(1);
    header("location: dashboard-home_Joe.php");
    exit;
}

?>
<pre>
    <?php echo json_encode($_POST); ?>
    
</pre>
<?php

$product_id = $_POST["id"];
$name = $_POST["name"];
$price = $_POST["price"];
$description = $_POST["description"];
$keywords = $_POST["keywords"];
$stocks = $_POST["stocks"];
$available = $_POST["available"];
$discount = $_POST["discount"];
$label = $_POST["label"];
$class = $_POST["class"];
$editor = $_SESSION["user"]["user_id"];
$editTime = date('Y/m/d H:i:s');

$sql = "UPDATE product SET
name = '$name',
price = $price,
description = '$description',
keywords = '$keywords',
stocks = '$stocks',
available = '$available',
discount = '$discount',
label = '$label',
product_class_id = '$class',
edit_user_id = '$editor',
last_edited_at = '$editTime'
WHERE product_id = '$product_id'";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
} else {
    // echo "更新資料錯誤: " . $conn->error;
}

//導頁
sleep(1);
header("location: ../page/product.php?productId=$product_id");

// 關閉資料庫連結
$conn->close();
