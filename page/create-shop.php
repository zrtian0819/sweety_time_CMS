<?php
require_once("../db_connect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION["user"])) {
    $role = $_SESSION["user"]["role"];
    $userId = $_SESSION["user"]["user_id"];

    if ($role == "admin") {

        $shopId = "admin";
    } elseif ($role == "shop") {
        // $storeSql = "SELECT * from shop WHERE user_id = $userId";
        // $storeResult = $conn->query($storeSql);
        // $storeCount = $storeResult->num_rows;
        // $storeRow = $storeResult->fetch_assoc();

        $shopId = $_SESSION["shop"]["shop_id"];
    }
} else {
    $role = "";
    $userId = "";
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>註冊申請</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include("../css/login-style.php"); ?>
    <?php include("../css/css_Joe.php"); ?>
    <style>

    </style>
</head>

<body>
    <?php include("../modules/login-header.php"); ?>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="d-flex justify-content-center">
                    <h3 class="mt-4 text-white fw-bolder" style="color: var( --text-color);">
                    <?php if ($role == "admin"): ?>
                        會員&店家註冊申請
                    <?php else: ?>
                        會員註冊
                    <?php endif; ?>
                    </h3>
                </div>
                <div class="create-shop-card mx-auto mb-5">
                    <div class="w-75">
                        <form id="loginForm" action="../function/doCreateShop.php" method="POST" enctype="multipart/form-data">
                            <h3 class="mb-5">創立使用者帳號</h3>
                            <div class="form-group">
                                <input class="form-control form-control-custom mb-3" type="text" placeholder="" id="username" name="username" required />
                                <label for="username" class="form-label"><span class="text-danger">* </span>使用者姓名</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom" type="text" placeholder="" id="account" name="account" required />
                                <label for="account" class="form-label"><span class="text-danger">* </span>帳號</label>
                                <div id="accountFeedback" class="invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input class="form-control form-control-custom" type="password" placeholder="" id="password" name="password" required />
                                    <label for="password" class="form-label" id="passWord-input"><span class="text-danger">* </span>密碼</label>
                                    <button class="btn btn-toggle-password" type="button" id="togglePassword">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom" type="date" placeholder="" id="birthday" name="birthday" required aria-label="生日" />
                                <label for="birthday" class="form-label"></label>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom" type="text" placeholder="" id="email" name="email" required />
                                <label for="email" class="form-label">E-mail</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom" type="text" placeholder="" id="phone" name="phone" required />
                                <label for="phone" class="form-label">電話</label>
                            </div>

                            <?php if ($role == "admin"): ?>
                            <hr>
                            <h3 class="mb-5">創立店家</h3>
                            <div class="form-group">
                                <input class="form-control form-control-custom" type="text" placeholder="" id="shopName" name="shopName" required />
                                <label for="shopName" class="form-label"><span class="text-danger">* </span>店名</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom" type="text" placeholder="" id="shopAddress" name="shopAddress" required />
                                <label for="shopAddress" class="form-label">店家地址</label>
                            </div>
                            <div class="form-group">
                                <input class="form-control form-control-custom" type="text" placeholder="" id="shopPhone" name="shopPhone" required />
                                <label for="shopPhone" class="form-label">店家電話</label>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control form-control-custom" placeholder="" id="shopDescription" name="shopDescription" rows="3" required></textarea>
                                <label for="shopDescription" class="form-label">店家描述</label>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <input type="file" class="form-control form-control-custom" placeholder="" id="shopImage" name="shopImage" accept="image/*">
                                    <button type="button" class="btn btn-neumorphic" id="uploadButton">
                                        上傳照片
                                    </button>
                                </div>
                                <div class="d-flex justify-content-center overflow-hidden">
                                    <img id="imagePreview" class="rounded" src="#" alt="圖片預覽" />
                                </div>

                                <hr>
                            <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-neumorphic  fw-bolder">送出</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
    <!-- Bootstrap JavaScript Libraries -->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <!-- 切換顯示密碼 -->
    <script>
        document.getElementById('togglePassword').addEventListener('click', function(e) {
            // 切換密碼可見性
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // 切換眼睛圖標
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>

    <!-- 按鈕觸發上傳檔案 -->
    <script src="../javascript/create-shop.js"></script>


</body>

</html>