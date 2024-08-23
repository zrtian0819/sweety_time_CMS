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

// 文字資訊傳入資料庫
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
    // echo "更新成功";
} else {
    // echo "更新資料錯誤: " . $conn->error;
}

//新增照片檔
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
                    $insert_pic_sql = "INSERT INTO product_photo (product_id,file_name,is_valid) VALUES ('$product_id','$newFileName','1')";
                    $conn->query($insert_pic_sql);
                }
            }
        }
    }
} else {
    echo "沒有傳入檔案";
}




if(isset($_POST["delFiles"]) && count($_POST["delFiles"])>0){
    print_r($_POST["delFiles"]);

    // 要刪除的檔案數量
    $delFileCount = count($_POST["delFiles"]);

    $delPhotos = $_POST["delFiles"];

    // 迭代每張要刪除的圖片
    for ($i = 0; $i < $delFileCount; $i++) {
        $DelPhoto_sql = "UPDATE product_photo SET is_valid = '0' WHERE product_photo_id = $delPhotos[$i]";
        $conn->query($DelPhoto_sql);
    }
}

//導頁
sleep(1);
header("location: ../page/product.php?productId=$product_id");

// 關閉資料庫連結
$conn->close();
