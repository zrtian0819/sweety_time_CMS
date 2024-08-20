<?php
// create.php
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $expertise = $_POST['expertise'];
    $img_path = $_POST['img_path'];
    $status = $_POST['status']; // 新增狀態欄位

    $sql = "INSERT INTO teacher (name, expertise, img_path, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $expertise, $img_path, $status);
    if ($stmt->execute()) {
        header("Location: all.php");
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新增教師</title>
    <?php include("../css/css_Joe.php"); ?>
</head>
<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <h2>新增教師</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Expertise:</label>
                    <input type="text" class="form-control" name="expertise" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Image Path:</label>
                    <input type="text" class="form-control" name="img_path" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status:</label>
                    <select class="form-select" name="status" required>
                        <option value="In Progress">開課中</option>
                        <option value="Completed">課程結束</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-custom">Add Teacher</button>
            </form>
        </div>
    </div>

    <?php include("../js.php"); ?>
</body>
</html>

<?php $conn->close(); ?>
