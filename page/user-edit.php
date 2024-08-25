<?php
if (!isset($_GET["user_id"])) {
    echo "請正確帶入 get user_id 變數";
    exit;
}

require_once("../db_connect.php");


$user_id = $_GET["user_id"];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // 檢查檔案上傳
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $targetDir = '../images/users/';
        $fileType = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        // 檢查檔案格式
        if (in_array(strtolower($fileType), $allowTypes)) {
            // 使用原檔名加上唯一的時間戳來生成檔名
            $originalFileName = pathinfo($_FILES['profile_image']['name'], PATHINFO_FILENAME);
            $newFileName = $originalFileName . '_' . time() . '.' . $fileType;
            $targetFilePath = $targetDir . $newFileName;

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
                $sql = "UPDATE users SET portrait_path='$newFileName' WHERE user_id = $user_id";
                if ($conn->query($sql)) {
                    echo "圖片更新成功";
                    header("Location: user.php?user_id=$user_id");
                    exit;
                } else {
                    echo "資料更新失敗: " . $conn->error;
                }
            } else {
                echo "檔案上傳失敗";
                handleUploadError($_FILES['profile_image']['error']);
            }
        } else {
            echo "不支援的檔案格式";
        }
    }

    $sql = "UPDATE users SET name = '$name', password = '$password', email = '$email' WHERE user_id = $user_id";
    if ($conn->query($sql)) {
        // echo "資料更新成功";
        header("Location: user.php?user_id=$user_id");
    } else {
        echo "資料更新失敗: " . $conn->error;
    }
}

$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$userCount = $result->num_rows;
$row = $result->fetch_assoc();

if ($userCount > 0) {
    $title = $row["name"];
    // 如果有就顯示圖片，沒有就顯示預設圖
    $defaultImage = 'https://images.unsplash.com/photo-1472396961693-142e6e269027?q=80&w=2152&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
    $imagePath = !empty($row['portrait_path']) ? '../images/users/' . $row['portrait_path'] : $defaultImage;
} else {
    $title = "使用者不存在";
    $imagePath = $defaultImage;
}

$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <title><?= ($title) ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .user-btn {
            width: 100px;
        }

        .user-search {
            width: 200px;
        }

        .user-img {
            width: 300px;
            height: 300px;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <div class="d-flex">
                <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3 mx-3" href="users.php">
                    <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回</span>
                </a>
                <h2 class="mb-3">修改資料</h2>
            </div>
            <div class="container-fluid d-flex justify-content-center">
                <div class="row col-10">
                    <?php if ($userCount > 0): ?>
                        <form action="user-edit.php?user_id=<?= ($user_id) ?>" method="post" enctype="multipart/form-data">
                            <div class="mb-3 d-flex justify-content-center align-items-center flex-column">
                                <label for="profile_image">
                                    <input type="file" name="profile_image" class="my-3 ms-5 ps-5" data-target="preview_img">
                                </label>
                                <div>
                                    <img src="<?= ($imagePath) ?>" alt="Profile Image" class="object-fit-fill" id="preview_img">
                                </div>
                            </div>
                            <input type="hidden" name="user_id" value="<?= ($user_id) ?>">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="text-center">Name</th>
                                    <td>
                                        <input type="text" value="<?= ($row["name"]) ?>" class="form-control" name="name">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">Password</th>
                                    <td>
                                        <input type="password" value="<?= ($row["password"]) ?>" class="form-control" name="password">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">Email</th>
                                    <td>
                                        <input type="text" value="<?= ($row["email"]) ?>" class="form-control" name="email">
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-center">Birthday</th>
                                    <td><?= ($row["birthday"]) ?></td>
                                </tr>
                                <tr>
                                    <th class="text-center">Sign up time</th>
                                    <td><?= ($row["sign_up_time"]) ?></td>
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
    <?php include("../js.php"); ?>
    <script>
        let input = document.querySelector('input[name=profile_image]')
        input.addEventListener('change', function(e) {
            readURL(e.target);
        })

        function readURL(input) {
            if (input.files && input.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let imgId = input.getAttribute('data-target')
                    let img = document.querySelector('#' + imgId)
                    img.setAttribute('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>