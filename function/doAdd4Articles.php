<?php

if (!isset($_POST["title"])) {
    header("location: articles-create.php");
    exit;
}

require_once("../db_connect.php");

// 判斷開啟Session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$title = $_POST["title"] ?? ''; // 如果 POST 沒有值，使用空字串
$content = $_POST["content"] ?? ''; // 預設值為空字串
$class = $_POST["class"] ?? ''; // 預設值為空字串
$user_id = $_SESSION["user"]["user_id"] ?? 0; // 把當前使用者傳入

$time = $_POST["createTime"];
$dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $time);
if ($dateTime === false) {
    die("日期格式錯誤，請檢查輸入的時間格式");
}
$formattedDateTime = $dateTime->format('Y-m-d H:i:s');

$activation = isset($_POST["activation"]) ? $_POST["activation"] : 1; // 預設為 1（上架）
$artValid = isset($_POST["artValid"]) ? $_POST["artValid"] : 1; // 預設為 1（有效）

$pic = $_FILES["pic"] ?? null; // 預設為 null

if ($pic && $_FILES["pic"]["error"][0] == 0) {
    $fileNames = [];
    for ($i = 0; $i < count($_FILES["pic"]["name"]); $i++) {
        $fileName = $_FILES["pic"]["name"][$i];
        $fileInfo = pathinfo($fileName);
        $extension = $fileInfo["extension"];
        $newFileName = time() . "_$i.$extension";

        if (move_uploaded_file($_FILES["pic"]["tmp_name"][$i], "../images/articles/" . $newFileName)) {
            $fileNames[] = $newFileName;
        } else {
            die("圖片上傳失敗");
        }
    }

    // 將圖片名稱存入資料庫
    $imgPaths = implode(',', $fileNames);
    $sql = "INSERT INTO articles (title, content, product_class_id, user_id, created_at, img_path, activation, artValid) 
            VALUES ('$title', '$content', '$class', '$user_id', '$formattedDateTime', '$imgPaths', '$activation', '$artValid')";
    if ($conn->query($sql) === TRUE) {
        echo "新增成功";
        header("location:../page/articles.php");
    } else {
        die("新增失敗: " . $conn->error);
    }
} else {
    // 處理沒有上傳圖片的情況
    $sql = "INSERT INTO articles (title, content, product_class_id, user_id, created_at, activation, artValid) 
            VALUES ('$title', '$content', '$class', '$user_id', '$formattedDateTime', '$activation', '$artValid')";
    if ($conn->query($sql) === TRUE) {
        echo "新增成功";
        header("location:../page/articles.php");
    } else {
        die("新增失敗: " . $conn->error);
    }
}

$conn->close();

?>