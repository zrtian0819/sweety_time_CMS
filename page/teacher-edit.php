<?php
include '../db_connect.php';

// 獲取教師 ID
if (isset($_GET['id'])) {
    $teacher_id = $_GET['id'];
    $sql = "SELECT * FROM teacher WHERE teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // 處理 POST 請求，更新教師資料
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'];
        $expertise = $_POST['expertise'];
        $education = $_POST['education'];
        $licence = $_POST['licence'];
        $awards = $_POST['awards'];
        $experience = $_POST['experience'];
        $description = $_POST['description'];
        $valid = isset($_POST['valid']) ? 1 : 0;

        // 處理圖片上傳
        if ($_FILES['img_path']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = '../images/teachers/';
            $fileName = basename($_FILES['img_path']['name']);
            $targetFilePath = $uploadDir . $fileName;

            // 確保文件移動成功
            if (move_uploaded_file($_FILES['img_path']['tmp_name'], $targetFilePath)) {
                $img_path = $targetFilePath;
            } else {
                echo "Error: 無法移動上傳的文件至 " . $targetFilePath;
                exit();
            }
        } elseif ($_FILES['img_path']['error'] != UPLOAD_ERR_NO_FILE) {
            echo "Error: 上傳文件時出現錯誤，錯誤代碼: " . $_FILES['img_path']['error'];
            exit();
        } else {
            // 如果沒有上傳新圖片，保持原有圖片路徑
            $img_path = $row['img_path'];
        }

        $updateSql = "UPDATE teacher SET name = ?, expertise = ?, img_path = ?, education = ?, licence = ?, awards = ?, experience = ?, description = ?, valid = ? WHERE teacher_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("ssssssssii", $name, $expertise, $img_path, $education, $licence, $awards, $experience, $description, $valid, $teacher_id);
        if ($updateStmt->execute()) {
            header("Location: teacher.php");
            exit();
        } else {
            echo "Error: " . $updateStmt->error;
        }
    }

    // 處理刪除請求
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $updateSql = "UPDATE teacher SET valid = 0, activation = 0 WHERE teacher_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("i", $teacher_id);
        if ($updateStmt->execute()) {
            header("Location: teacher.php?status=off"); // 更新後重定向到已下架頁面
            exit();
        } else {
            echo "Error: " . $updateStmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>編輯教師</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .thumbnail {
            width: 150px;
            height: auto;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <h2>編輯教師</h2>
            <br>
            <div class="py-2">
                <a class="btn btn-custom" href="teacher.php" title="回老師清單"><i class="fa-solid fa-circle-left"></i></a>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($row['teacher_id']); ?>">

                <!-- 顯示當前圖片 -->
                <div class="mb-3">
                    <label class="form-label">Current Image:</label>
                    <?php if ($row['img_path']): ?>
                        <img src="<?php echo htmlspecialchars($row['img_path']); ?>" class="thumbnail" alt="Current Image">
                    <?php else: ?>
                        <p>No image available.</p>
                    <?php endif; ?>
                </div>

                <!-- 上傳新圖片 -->
                <div class="mb-3">
                    <label class="form-label">Upload New Image:</label>
                    <input type="file" class="form-control" name="img_path">
                </div>

                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Expertise:</label>
                    <input type="text" class="form-control" name="expertise" value="<?php echo htmlspecialchars($row['expertise']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Education:</label>
                    <input type="text" class="form-control" name="education" value="<?php echo htmlspecialchars($row['education']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Licence:</label>
                    <input type="text" class="form-control" name="licence" value="<?php echo htmlspecialchars($row['licence']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Awards:</label>
                    <input type="text" class="form-control" name="awards" value="<?php echo htmlspecialchars($row['awards']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Experience:</label>
                    <input type="text" class="form-control" name="experience" value="<?php echo htmlspecialchars($row['experience']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description:</label>
                    <textarea class="form-control" name="description" rows="3"><?php echo htmlspecialchars($row['description']); ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="valid" id="statusSwitch" <?= $row['valid'] == 1 ? 'checked' : '' ?>>
                        <label class="form-check-label" for="statusSwitch">啟用中</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-custom">Save Changes</button>
                <a class="btn btn-danger" href="teacher-edit.php?id=<?php echo htmlspecialchars($row['teacher_id']); ?>&action=delete" onclick="return confirm('確定要將這位教師刪除嗎？');" title="刪除教師"><i class="fa-solid fa-trash-can"></i></a>
            </form>
        </div>
    </div>

    <?php include("../js.php"); ?>
</body>

</html>

<?php $conn->close(); ?>