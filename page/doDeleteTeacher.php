<?php
require_once("../db_connect.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $teacher_id = $_POST['teacher_id'];
    $valid = $_POST['valid'];

    // 切換 valid 狀態
    $new_valid = ($valid == 1) ? 0 : 1;

    $sql = "UPDATE teacher SET valid = ? WHERE teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $new_valid, $teacher_id);
    
    if ($stmt->execute()) {
        header("Location: teacher.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
    
    $stmt->close();
}
$conn->close();
?>

 teacher-edit.php <?php
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    echo "請正確帶入 get teacher_id 變數";
    exit;
}

require_once("../db_connect.php");

$teacher_id = intval($_GET["id"]); // 確保變數為整數

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $expertise = $_POST['expertise'];
    $experience = $_POST['experience'];
    $education = $_POST['education'];
    $licence = $_POST['licence'];
    $awards = $_POST['awards'];
    $valid = isset($_POST['valid']) ? 1 : 0;

    // 檢查檔案上傳
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $targetDir = '../images/teachers/';
        $fileType = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // 檢查檔案格式
        if (in_array(strtolower($fileType), $allowTypes)) {
            // 使用原檔名加上唯一的時間戳來生成檔名
            $originalFileName = pathinfo($_FILES['profile_image']['name'], PATHINFO_FILENAME);
            $newFileName = $originalFileName . '_' . time() . '.' . $fileType;
            $targetFilePath = $targetDir . $newFileName;

            // 確保上傳的檔案成功
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
                // 獲取舊的圖片名稱
                $sql = "SELECT img_path FROM teacher WHERE teacher_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $teacher_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $oldImage = $row['img_path'];

                // 刪除舊的圖片
                if (!empty($oldImage) && file_exists($targetDir . $oldImage)) {
                    unlink($targetDir . $oldImage);
                }

                // 更新資料庫中的圖片名稱
                $sql = "UPDATE teacher SET img_path=? WHERE teacher_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $newFileName, $teacher_id);
                if ($stmt->execute()) {
                    echo "<script>alert('圖片更新成功');</script>";
                } else {
                    echo "<script>alert('資料更新失敗: " . $stmt->error . "');</script>";
                }
            } else {
                echo "<script>alert('檔案上傳失敗');</script>";
                handleUploadError($_FILES['profile_image']['error']);
            }
        } else {
            echo "<script>alert('不支援的檔案格式');</script>";
        }
    } else {
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] != UPLOAD_ERR_OK) {
            handleUploadError($_FILES['profile_image']['error']);
        }
    }

    // 更新教師其他資料
    $sql = "UPDATE teacher SET 
                name = ?, 
                description = ?,
                expertise = ?,
                experience = ?,
                education = ?,
                licence = ?,
                awards = ?,
                valid = ?
            WHERE teacher_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssii", $name, $description, $expertise, $experience, $education, $licence, $awards, $valid, $teacher_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('資料更新成功');</script>";
        header("Location: teacher-edit.php?id=$teacher_id");
        exit;
    } else {
        echo "<script>alert('資料更新失敗: " . $stmt->error . "');</script>";
    }
}

// 獲取教師資料
$sql = "SELECT * FROM teacher WHERE teacher_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacherCount = $result->num_rows;
$row = $result->fetch_assoc();

if ($teacherCount > 0) {
    $title = $row["name"];
    // 如果有就顯示圖片，沒有就顯示預設圖
    $defaultImage = 'https://via.placeholder.com/300'; // 可以替換為你的預設圖片 URL
    $imagePath = !empty($row['img_path']) ? '../images/teachers/' . $row['img_path'] : $defaultImage;
} else {
    $title = "教師不存在";
    $imagePath = $defaultImage;
}

$conn->close();

function handleUploadError($error) {
    switch ($error) {
        case UPLOAD_ERR_INI_SIZE:
            echo "<script>alert('檔案大小超過伺服器限制');</script>";
            break;
        case UPLOAD_ERR_FORM_SIZE:
            echo "<script>alert('檔案大小超過表單限制');</script>";
            break;
        case UPLOAD_ERR_PARTIAL:
            echo "<script>alert('檔案只上傳了部分');</script>";
            break;
        case UPLOAD_ERR_NO_FILE:
            echo "<script>alert('沒有檔案被上傳');</script>";
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            echo "<script>alert('缺少臨時檔案夾');</script>";
            break;
        case UPLOAD_ERR_CANT_WRITE:
            echo "<script>alert('檔案寫入失敗');</script>";
            break;
        case UPLOAD_ERR_EXTENSION:
            echo "<script>alert('檔案上傳被擴展阻止');</script>";
            break;
        default:
            echo "<script>alert('未知錯誤');</script>";
            break;
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title><?= htmlspecialchars($title) ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .teacher-btn {
            width: 100px;
        }
        .teacher-image {
            width: 300px;
            height: 300px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="container-fluid d-flex flex-row px-4">
            <div class="main col neumorphic p-5">
                <div class="py-2">
                    <a class="btn btn-neumorphic teacher-btn" href="teacher.php" title="回教師列表"><i class="fa-solid fa-left-long"></i></a>
                </div>
                <h2 class="mb-3">修改資料</h2>
                <div class="container">
                    <div class="row">
                        <?php if ($teacherCount > 0): ?>
                            <form action="teacher-edit.php?id=<?= htmlspecialchars($teacher_id) ?>" method="post" enctype="multipart/form-data">
                                <div class="col d-flex justify-content-center align-items-center">
                                    <div class="mb-3">
                                        <label for="profile_image">上傳或更改圖片:</label><br>
                                        <img src="<?= htmlspecialchars($imagePath) ?>" alt="Profile Image" class="teacher-image" id="profileImagePreview">
                                        <input type="file" name="profile_image" id="profile_image">
                                    </div>
                                </div>
                                <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Name</th>
                                        <td>
                                            <input type="text" value="<?= htmlspecialchars($row["name"]) ?>" class="form-control" name="name">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>
                                            <textarea class="form-control" name="description"><?= htmlspecialchars($row["description"]) ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Expertise</th>
                                        <td>
                                            <textarea class="form-control" name="expertise"><?= htmlspecialchars($row["expertise"]) ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Experience</th>
                                        <td>
                                            <textarea class="form-control" name="experience"><?= htmlspecialchars($row["experience"]) ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Education</th>
                                        <td>
                                            <textarea class="form-control" name="education"><?= htmlspecialchars($row["education"]) ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Licence</th>
                                        <td>
                                            <textarea class="form-control" name="licence"><?= htmlspecialchars($row["licence"]) ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Awards</th>
                                        <td>
                                            <textarea class="form-control" name="awards"><?= htmlspecialchars($row["awards"]) ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" name="valid" <?= $row["valid"] ? 'checked' : '' ?>>
                                                <label class="form-check-label">開課中</label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <button type="submit" class="btn btn-primary">保存</button>
                            </form>
                        <?php else: ?>
                            <p>該教師資料不存在。</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // 圖片預覽
        document.getElementById('profile_image').addEventListener('change', function(event) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImagePreview').src = e.target.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
</body>

</html>