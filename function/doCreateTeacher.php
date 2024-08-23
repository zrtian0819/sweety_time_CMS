<?php
require_once("../db_connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $expertise = $_POST['expertise'];
    $education = $_POST['education'];
    $licence = $_POST['licence'];
    $awards = $_POST['awards'];
    $experience = $_POST['experience'];
    $description = $_POST['description'];
    $valid = isset($_POST['valid']) ? 1 : 0;

    // 初始化圖片路徑變數
    $img_path = null;

    // 處理圖片上傳
    if (isset($_FILES['img_path']) && $_FILES['img_path']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../images/teachers/';
        $tmp_name = $_FILES['img_path']['tmp_name'];
        $img_name = basename($_FILES['img_path']['name']);
        $img_path = $upload_dir . $img_name;

        // 檢查並創建目錄（如果不存在）
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // 移動上傳的檔案
        if (!move_uploaded_file($tmp_name, $img_path)) {
            echo "Error: 無法上傳圖片";
            exit();
        }
    } else {
        echo "Error: 無法上傳圖片或檔案不存在";
        exit();
    }

    // 插入數據到資料庫
    $sql = "INSERT INTO teacher (img_path, name, expertise, education, licence, awards, experience, description, valid, activation) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $activation = 1; // 如果需要，可以根據實際需求設定
    $stmt->bind_param("sssssssii", $img_path, $name, $expertise, $education, $licence, $awards, $experience, $description, $valid, $activation);

    if ($stmt->execute()) {
        header("Location: teacher.php?status=" . ($valid ? 'on' : 'off'));
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>