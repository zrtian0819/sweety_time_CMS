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

$shop_id = $_POST["shop_id"];
$name = $_POST["name"];
$price = $_POST["price"];
$stocks = $_POST["stocks"];
$class = $_POST["class"];
$description = $_POST["description"];
$keywords = $_POST["keywords"];
$discount = $_POST["discount"];
$available = $_POST["available"];
$label = $_POST["label"];
$editor = $_SESSION["user"]["user_id"];
$editTime = date('Y/m/d H:i:s');

$sql = "INSERT INTO product 
(shop_id,name, price, stocks ,product_class_id,description,keywords,discount,available,label,edit_user_id,last_edited_at)
VALUES 
('$shop_id','$name', '$price', '$stocks','$class','$description', '$keywords','$discount','$available','$label','$editor','$editTime')";

echo $sql;

exit;

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