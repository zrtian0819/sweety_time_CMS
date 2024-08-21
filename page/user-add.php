<?php
require_once("../db_connect.php");

$user_id = $_GET["user_id"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $targetDir = '/Applications/XAMPP/xamppfiles/htdocs/project/images/users/';
        $fileType = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        if (in_array(strtolower($fileType), $allowTypes)) {
            $originalFileName = pathinfo($_FILES['profile_image']['name'], PATHINFO_FILENAME);
            $newFileName = $originalFileName . '_' . time() . '.' . $fileType;
            $targetFilePath = $targetDir . $newFileName;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
                $sql = "UPDATE users SET portrait_path='$newFileName' WHERE user_id = $user_id";
                if ($conn->query($sql)) {
                    echo "圖片更新成功";
                    header("Location: users.php?user_id=$user_id");
                    exit;
                } else {
                    echo "資料更新失敗: " . $conn->error;
                }
            } else {
                echo "檔案上傳失敗";
                $error = $_FILES['profile_image']['error'];
                switch ($error) {
                    case UPLOAD_ERR_FORM_SIZE:
                        echo "檔案大小超過限制";
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        echo "檔案只上傳了部分";
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        echo "沒有檔案被上傳";
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        echo "缺少臨時檔案夾";
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        echo "檔案寫入失敗";
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        echo "檔案上傳被擴展阻止";
                        break;
                    default:
                        echo "未知錯誤代碼: " . $error;
                        break;
                }
            }
        } else {
            echo "不支援的檔案格式";
        }
    } else {
        echo "沒有檔案上傳或上傳錯誤";
    }
}



$sql = "SELECT * FROM users WHERE activation=1";
$result = $conn->query($sql);
$userCount = $result->num_rows;
$row = $result->fetch_assoc();

if ($userCount > 0) {
    $title = $row["name"];
    $defaultImage = 'https://images.unsplash.com/photo-1472396961693-142e6e269027?q=80&w=2152&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
    $imagePath = !empty($row['portrait_path']) ? '../images/users/' . $row['portrait_path'] : $defaultImage;
} else {
    $title = "錯誤頁面，請重新登入";
    $imagePath = $defaultImage;
}

?>
<!doctype html>
<html lang="en">

<head>
    <title>新增資料</title>
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
                    <a class="btn btn-neumorphic user-btn" href="users.php" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
                </div>
                <div class="d-flex justify-content-center">
                    <h2 class="mb-5">新增資料</h2>
                </div>
                <div class="container">
                    <div class="row">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td>
                                    <input type="text" value="<?= $row["name"] ?>" class="form-control" name="name">
                                </td>
                            </tr>
                            <tr>
                                <th>Account</th>
                                <td>
                                    <input type="text" value="<?= $row["account"] ?>" class="form-control" name="account">
                                </td>
                            </tr>
                            <tr>
                                <th>Password</th>
                                <td>
                                    <input type="text" value="<?= $row["password"] ?>" class="form-control" name="password">
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    <input type="text" value="<?= $row["email"] ?>" class="form-control" name="email">
                                </td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>
                                    <input type="text" value="<?= $row["phone"] ?>" class="form-control" name="phone">
                                </td>
                            </tr>
                            <tr>
                                <th>Birthday</th>
                                <td>
                                    <div class="birthday-group">
                                        <input type="date" style="width:150px">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Sign up time</th>
                                <td><?= $row["now"] ?></td>
                            </tr>

                        </table>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-neumorphic user-btn">儲存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
    <script>

    </script>
    <?php $conn->close() ?>
</body>

</html>