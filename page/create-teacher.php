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
    // if (isset($_FILES['img_path']) && $_FILES['img_path']['error'] == UPLOAD_ERR_OK) {
    //     $upload_dir = '../images/teachers/'; // 設定上傳目錄
    //     $tmp_name = $_FILES['img_path']['tmp_name'];
    //     $img_name = basename($_FILES['img_path']['name']);
    //     $img_path = $upload_dir . $img_name;

        // 確保上傳目錄存在
        // if (!file_exists($upload_dir)) {
        //     mkdir($upload_dir, 0777, true);
        // }

        // 移動檔案到上傳目錄
        // if (move_uploaded_file($tmp_name, $img_path)) {
            // 上傳成功
    //     } else {
    //         echo "Error: 無法上傳圖片";
    //         exit();
    //     }
    // } else {
    //     echo "Error: 無法上傳圖片";
    //     exit();
    // }

    // 插入資料到資料庫
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Teacher</title>
    <?php include("../css/css_Joe.php"); ?>
    <?php include ("../function/doCreateTeacher.php");  ?>
</head>
<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
        <!-- <form action="../function/doCreateTeacher.php" method="POST" enctype="multipart/form-data"> -->
            <form action="create-teacher.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="expertise" class="form-label">Expertise</label>
                    <input type="text" class="form-control" id="expertise" name="expertise" required>
                </div>
                <div class="mb-3">
                    <label for="education" class="form-label">Education</label>
                    <input type="text" class="form-control" id="education" name="education" >
                </div>
                <div class="mb-3">
                    <label for="licence" class="form-label">Licence</label>
                    <input type="text" class="form-control" id="licence" name="licence" >
                </div>
                <div class="mb-3">
                    <label for="awards" class="form-label">Awards</label>
                    <input type="text" class="form-control" id="awards" name="awards" >
                </div>
                <div class="mb-3">
                    <label for="experience" class="form-label">Experience</label>
                    <input type="text" class="form-control" id="experience" name="experience" >
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" ></textarea>
                </div>
                <!-- <div class="mb-3">
                    <label for="img_path" class="form-label">Image</label>
                    <input type="file" class="form-control" id="img_path" name="img_path">
                </div> -->
                <div class="mb-3">
                    <label for="valid" class="form-label">Status</label>
                    <select class="form-select" id="valid" name="valid">
                        <option value="1">開課中</option>
                        <option value="0">已下架</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Teacher</button>
            </form>
        </div>
    </div>
    <?php include("../js.php"); ?>
</body>
</html>
