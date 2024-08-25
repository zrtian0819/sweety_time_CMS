<?php
require_once("../db_connect.php");
include("../function/login_status_inspect.php");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $account = $_POST["account"];
    $password = md5($_POST['password']);
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $birthday = $_POST["birthday"];
    $now = date('Y-m-d H:i:s');

    if (empty($account) || empty($password) || empty($email) || empty($phone) || empty($birthday)) {
        echo "有內容未填寫，欄位不能為空";
        exit;
    }

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $targetDir = '../images/users/';
        $fileType = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');

        if (in_array(strtolower($fileType), $allowTypes)) {
            $originalFileName = pathinfo($_FILES['profile_image']['name'], PATHINFO_FILENAME);
            $newFileName = $originalFileName . '_' . time() . '.' . $fileType;
            $targetFilePath = $targetDir . $newFileName;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFilePath)) {
                $sql = "INSERT INTO users (role,name, account, password, email, phone, birthday, sign_up_time,activation,portrait_path) 
VALUES ('user','$name', '$account', '$password', '$email', '$phone', '$birthday','$now',1,'$newFileName')";
                if ($conn->query($sql) === TRUE) {
                    header("location:users.php");
                    exit;
                } else {
                    echo "圖片路徑儲存失敗: " . $conn->error;
                }
            } else {
                echo "圖片上傳失敗";
            }
        } else {
            echo "不支援的檔案格式";
        }
    } else {
        echo "資料儲存成功，但未上傳圖片。";
    }
}

$conn->close();

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

        <div class="container-fluid d-flex flex-row px-4">
            <div class="main col neumorphic p-5">
                <div class="py-2">
                    <a class="btn btn-neumorphic user-btn" href="users.php" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
                </div>
                <div class="d-flex justify-content-center">
                    <h2 class="mb-5">新增資料</h2>
                </div>
                <div class="container-fluid col-9">
                    <div class="row">
                        <form action="user-add.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3 d-flex justify-content-center align-items-center flex-column">
                                <label for="profile_image">
                                    <input type="file" name="profile_image" class="my-3 ms-5 ps-5" data-target="preview_img">
                                </label>
                                <div>
                                    <img src="<?= ($imagePath) ?>" alt="Profile Image" class="object-fit-fill user-img" id="preview_img">
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="form-label"><span class="text-danger">* </span>Name</label>
                                <input type="text" class="form-control" name="name" require>
                            </div>
                            <div class="mb-2">
                                <label class="form-label"><span class="text-danger">* </span>account</label>
                                <input type="text" class="form-control" name="account" require>
                            </div>
                            <div class="mb-2">
                                <label class="form-label"><span class="text-danger">* </span>password</label>
                                <input type="password" class="form-control" name="password" require>
                            </div>
                            <div class="mb-2">
                                <label class="form-label"><span class="text-danger">* </span>email</label>
                                <input type="text" class="form-control" name="email" require>
                            </div>
                            <div class="mb-2">
                                <label class="form-label"><span class="text-danger">* </span>phone</label>
                                <input type="tel" class="form-control" name="phone" require>
                            </div>
                            <div class="mb-2">
                                <label class="form-label"><span class="text-danger">* </span>birthday</label>
                                <input type="date" name="birthday" class="mx-3 my-1">
                            </div>
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-neumorphic user-btn">儲存</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
    <script>
        let input =document.querySelector('input[name=profile_image]')
        input.addEventListener('change',function(e){
            readURL(e.target);
        })
        function readURL(input){
            if(input.files && input.files[0]){
                let reader = new FileReader();
                reader.onload = function(e){
                    let imgId = input.getAttribute('data-target')
                    let img =document.querySelector('#'+imgId)
                    img.setAttribute('src',e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
    </script>
</body>

</html>