<?php

require_once("../db_connect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//避免誤點擊到此頁執行程式
if (!isset($_POST["shop_id"])) {
    echo "非正常管道無法進入此頁";
    // header("location: ../page/product-list.php");
    exit;
}


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
$createdTime = date('Y/m/d H:i:s');

//使用者若沒填寫折扣則預設傳入1(不打折)
if(empty($discount)){
    $discount = 1;
}

$sql = "INSERT INTO product 
(shop_id,name, price, stocks ,product_class_id,description,keywords,discount,available,label,edit_user_id,created_at,deleted)
VALUES 
('$shop_id','$name', '$price', '$stocks','$class','$description', '$keywords','$discount','$available','$label','$editor','$createdTime','0')";

if ($conn->query($sql) === TRUE) {
    // echo "資料更新成功";
    $last_id = $conn->insert_id;
} else {
    // echo "更新資料錯誤: " . $conn->error;
}

$file_count = count($_FILES["pic"]["name"]);

if ($file_count > 0) {
    for ($i = 0; $i < $file_count; $i++) {

        if (isset($_FILES["pic"]["name"][$i]) && $_FILES["pic"]["error"][$i] == 0) {
            //判定推入的檔案沒有錯誤的話
            $targetDir = '../images/products';
            $originalFileName = pathinfo($_FILES['pic']['name'][$i], PATHINFO_FILENAME);
            $fileType = pathinfo($_FILES['pic']['name'][$i], PATHINFO_EXTENSION);
            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

            if (in_array(strtolower($fileType), $allowTypes)) {
                //檢查副檔名是否許可
                $newFileName = $originalFileName . "_" . time() . "_" . $i . "." . $fileType;
                $targetFilePath = $targetDir . "/" . $newFileName;

                if (move_uploaded_file($_FILES['pic']['tmp_name'][$i], $targetFilePath)) {
                    //如果有成功放置檔案則執行sql語法
                    $insert_pic_sql = "INSERT INTO product_photo (product_id,file_name,is_valid) VALUES ('$last_id','$newFileName','1')";
                    $conn->query($insert_pic_sql);
                }
            }
        }
    }
} else {
    echo "沒有傳入檔案";
}


//導頁
sleep(1);
header("location: ../page/product.php?productId=".$last_id);
// 關閉資料庫連結
$conn->close();
