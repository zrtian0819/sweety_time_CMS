<?php
require_once("../db_connect.php");

if (!isset($_POST['teacher_id'])) {
    echo "請循正常管道進入此頁";
    exit;
}

$teacher_id = $_POST['teacher_id'];
$name = $_POST['name'];
$expertise = $_POST['expertise'];
$education = $_POST['education'];
$licence = $_POST['licence'];
$awards = $_POST['awards'];
$experience = $_POST['experience'];
$description = $_POST['description'];
$valid = $_POST['valid'];

// 處理圖片上傳
if ($_FILES['img_path']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $tmp_name = $_FILES['img_path']['tmp_name'];
    $img_name = basename($_FILES['img_path']['name']);
    $img_path = $upload_dir . $img_name;

    // 確保上傳目錄存在
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // 移動上傳檔案到指定目錄
    if (move_uploaded_file($tmp_name, $img_path)) {
        // 上傳成功
    } else {
        echo "Error: 無法上傳圖片";
        exit();
    }
} else {
    // 如果沒有上傳新圖片，保持原有圖片路徑
    $img_path = $_POST['current_img_path']; // 這應該是原有圖片的路徑
}

// 更新教師資料
$updateSql = "UPDATE teacher SET name = ?, expertise = ?, img_path = ?, education = ?, licence = ?, awards = ?, experience = ?, description = ?, valid = ? WHERE teacher_id = ?";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->bind_param("ssssssssii", $name, $expertise, $img_path, $education, $licence, $awards, $experience, $description, $valid, $teacher_id);

if ($updateStmt->execute()) {
    header("Location: teacher.php");
    exit();
} else {
    echo "Error: " . $updateStmt->error;
}

$conn->close();
?>
