<?php

require_once("../db_connect.php");

if (!isset($_POST["id"])) {
    echo "請循正常管道進入";
    exit;
}

$id = intval($_POST["id"]);

// 檢查 POST 請求中是否包含所有必要的資料
$requiredFields = ["title", "content", "product_class_id", "status"];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        echo "缺少必要的資料: " . htmlspecialchars($field);
        exit;
    }
}

$title = $_POST["title"];
$content = $_POST["content"];
$product_class_id = intval($_POST["product_class_id"]);
$status = intval($_POST["status"]);

// 預設圖片更新為 false
$updateImage = false;
$newFileName = '';

if (isset($_FILES["pic"]) && $_FILES["pic"]["error"] === UPLOAD_ERR_OK) {
    $fileName = $_FILES["pic"]["name"];
    $fileInfo = pathinfo($fileName);
    $extension = strtolower($fileInfo["extension"]);

    // 圖片檔案類型和大小的基本檢查
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($extension, $allowedExtensions)) {
        echo "不支援的檔案格式";
        exit;
    }

    $newFileName = time() . ".$extension";
    if (!move_uploaded_file($_FILES["pic"]["tmp_name"], "../images/articles/" . $newFileName)) {
        echo "圖片上傳失敗";
        exit;
    }
    $updateImage = true;
}

// 準備 SQL 語句
$sql = "UPDATE articles SET title = ?, content = ?, product_class_id = ?, activation = ?";
if ($updateImage) {
    $sql .= ", img_path = ?";
}
$sql .= " WHERE article_id = ?";

// 使用 prepared statement 來執行 SQL
$stmt = $conn->prepare($sql);
if ($updateImage) {
    $stmt->bind_param("ssisi", $title, $content, $product_class_id, $status, $newFileName, $id);
} else {
    $stmt->bind_param("ssisi", $title, $content, $product_class_id, $status, $id);
}

if ($stmt->execute()) {
    header("Location: ../page/article.php?id=$id");
    exit; // 確保程式停止執行
} else {
    echo "更新資料錯誤: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
