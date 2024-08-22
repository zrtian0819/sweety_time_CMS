<?php
require_once("../db_connect.php");

// 檢查是否有上傳檔案
if (isset($_POST["submit"])) {
    $currentImage = $_POST["currentImage"];
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES["newImage"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // 檢查是否為圖片文件
    $check = getimagesize($_FILES["newImage"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // 檢查文件大小
    if ($_FILES["newImage"]["size"] > 500000) { // 限制為500KB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // 允許的文件格式
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // 檢查是否上傳成功
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // 如果上傳成功，覆蓋現有文件
        if (move_uploaded_file($_FILES["newImage"]["tmp_name"], $target_file)) {
            // 刪除舊圖片
            if (file_exists($target_dir . $currentImage)) {
                unlink($target_dir . $currentImage);
            }

            // 更新資料庫中的圖片名稱（可選）
            // 例如：
            // $sql = "UPDATE shops SET image = '".basename($_FILES["newImage"]["name"])."' WHERE ShopID = $shopID";
            // $conn->query($sql);

            echo "The file " . htmlspecialchars(basename($_FILES["newImage"]["name"])) . " has been uploaded and replaced.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>