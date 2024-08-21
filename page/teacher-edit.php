<?php
// teacher-edit.php
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
        $img_path = $_POST['img_path'];
        $valid = $_POST['valid'];

        $updateSql = "UPDATE teacher SET name = ?, expertise = ?, img_path = ?, valid = ? WHERE teacher_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sssii", $name, $expertise, $img_path, $valid, $teacher_id);
        if ($updateStmt->execute()) {
            header("Location: teacher.php");
            exit();
        } else {
            echo "Error: " . $updateStmt->error;
        }
    }

    // 處理刪除請求
    if (isset($_GET['action']) && $_GET['action'] === 'delete') {
        $updateSql = "UPDATE teacher SET valid = 0 WHERE teacher_id = ?";
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
            
            <form method="POST">
                <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($row['teacher_id']); ?>">
                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Expertise:</label>
                    <input type="text" class="form-control" name="expertise" value="<?php echo htmlspecialchars($row['expertise']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image Path:</label>
                    <input type="text" class="form-control" name="img_path" value="<?php echo htmlspecialchars($row['img_path']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <select class="form-control" name="valid" required>
                        <option value="1" <?= $row['valid'] == 1 ? 'selected' : '' ?>>開課中</option>
                        <option value="0" <?= $row['valid'] == 0 ? 'selected' : '' ?>>已下架</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-custom">Save Changes</button>
                <a class="btn btn-danger" href="teacher-edit.php?id=<?php echo htmlspecialchars($row['teacher_id']); ?>&action=delete" onclick="return confirm('確定要將這位教師設為已下架嗎？');" title="下架教師"><i class="fa-solid fa-trash-can"></i></a>
            </form>
        </div>
    </div>

    <?php include("../js.php"); ?>
</body>
</html>

<?php $conn->close(); ?>
