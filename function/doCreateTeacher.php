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
    $valid = $_POST['valid'];

    // 處理圖片上傳
    // if ($_FILES['img_path']['error'] == UPLOAD_ERR_OK) {
    //     $upload_dir = 'uploads/';
    //     $tmp_name = $_FILES['img_path']['tmp_name'];
    //     $img_name = basename($_FILES['img_path']['name']);
    //     $img_path = $upload_dir . $img_name;

    //     if (!file_exists($upload_dir)) {
    //         mkdir($upload_dir, 0777, true);
    //     }

    //     if (move_uploaded_file($tmp_name, $img_path)) {
            // 上傳成功
    //     } else {
    //         echo "Error: 無法上傳圖片";
    //         exit();
    //     }
    // } else {
    //     echo "Error: 無法上傳圖片";
    //     exit();
    // }

    $sql = "INSERT INTO teacher (img_path, name, expertise, education, licence, awards, experience, description, valid, activation) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $img_path, $name, $expertise, $education, $licence, $awards, $experience, $description, $valid);

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
