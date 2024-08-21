<?php
if (!isset($_GET["user_id"])) {
    echo "請正確帶入 get user_id 變數";
    exit;
}

require_once("../db_connect.php");

$user_id = $_GET["user_id"];

// 先檢查是否有 POST 請求來處理圖片上傳
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    // 檢查是否有文件上傳
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $targetDir = "/Applications/XAMPP/xamppfiles/htdocs/projext/uploads/";
        $fileName = basename($_FILES['profile_image']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // 確認檔案格式
        if (in_array(strtolower($fileType), $allowTypes)) {
            // 上傳檔案
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
                // 獲取舊的圖片名稱
                $sql = "SELECT portrait_path FROM users WHERE user_id = $user_id";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $oldImage = $row['portrait_path'];

                // 刪除舊的圖片
                if (!empty($oldImage) && file_exists($targetDir . $oldImage)) {
                    unlink($targetDir . $oldImage);
                }

                // 更新資料庫中的圖片名稱
                $sql = "UPDATE users SET portrait_path='$fileName' WHERE user_id = $user_id";
                if ($conn->query($sql)) {
                    echo "圖片更新成功";
                } else {
                    echo "資料更新失敗: " . $conn->error;
                }
            } else {
                echo "檔案上傳失敗";
            }
        } else {
            echo "不支援的檔案格式";
        }
    }

    // 更新使用者資訊
    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $sql = "UPDATE users SET name = '$name', password = '$password', email = '$email' WHERE user_id = $user_id";
    if ($conn->query($sql)) {
        echo "資料更新成功";
    } else {
        echo "資料更新失敗: " . $conn->error;
    }
}

// 確保頁面沒有錯誤
$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$userCount = $result->num_rows;
$row = $result->fetch_assoc();

if ($userCount > 0) {
    $title = $row["name"];
    $oldImage = $row["portrait_path"];
    $defaultImage = 'https://images.unsplash.com/photo-1472396961693-142e6e269027?q=80&w=2152&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
    $imagePath = !empty($oldImage) ? 'uploads/' . $oldImage : $defaultImage;
} else {
    $title = "使用者不存在";
    $imagePath = $defaultImage;
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <title><?= $title ?></title>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css/css_Joe.php"); ?>
    <style>
        .user-btn {
            width: 100px;
        }

        .user-search {
            width: 200px;
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
                    <a class="btn btn-neumorphic user-btn" href="user.php?user_id=<?= $row["user_id"] ?>" title="回使用者"><i class="fa-solid fa-left-long"></i></a>
                </div>
                <h2 class="mb-3">修改資料</h2>
                <div class="container">
                    <div class="row">
                        <?php if ($userCount > 0): ?>
                            <form action="user-edit.php?user_id=<?= $user_id ?>" method="post" enctype="multipart/form-data">
                                <div class="col d-flex justify-content-center align-items-center">
                                    <div class="mb-3">
                                        <label for="profile_image">選擇圖片:</label><br>
                                        <img src="<?= $imagePath ?>" alt="Profile Image" style="width:300px; height:300px;"><br>
                                        <input type="file" name="profile_image" id="profile_image">
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Name</th>
                                        <td>
                                            <input type="text" value="<?= $row["name"] ?>" class="form-control" name="name">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Password</th>
                                        <td>
                                            <input type="password" value="<?= $row["password"] ?>" class="form-control" name="password">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>
                                            <input type="text" value="<?= $row["email"] ?>" class="form-control" name="email">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Birthday</th>
                                        <td><?= $row["birthday"] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Sign up time</th>
                                        <td><?= $row["sign_up_time"] ?></td>
                                    </tr>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-neumorphic user-btn">儲存</button>
                                </div>
                            </form>
                        <?php else: ?>
                            使用者不存在
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>