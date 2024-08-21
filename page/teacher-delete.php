<?php
// teacher-delete.php
include '../db_connect.php';

if (isset($_GET['id'])) {
    $teacher_id = $_GET['id'];
    $sql = "UPDATE teacher SET valid = 0 WHERE teacher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $teacher_id);
    if ($stmt->execute()) {
        header("Location: teacher.php?status=off"); // 重定向到已下架頁面
        exit();
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
    <title>刪除教師</title>
    <?php include("../css/css_Joe.php"); ?>
</head>
<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <h2>教師已刪除</h2>
            <p>返回 <a href="teacher.php?status=off">已下架教師</a></p>
        </div>
    </div>

    <?php include("../js.php"); ?>
</body>
</html>

<?php $conn->close(); ?>
