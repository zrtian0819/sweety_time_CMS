<?php
require_once("../db_connect.php");

?>

<!doctype html>
<html lang="en">
    <head>
        <title>成為店家</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <?php include("../css/login-style.php");?>
        <?php include("../css/css_Joe.php");?>
        <style>
            form label {
                font-weight: bold;
            }
            .input-group {
                display: flex;
                align-items: stretch;
            }
            .btn-check-account {
                height: 100%;
                width: 50px;
                display: flex;
                align-items: center;
                padding: 0.375rem 0.75rem;
                margin-left: 10px;
                white-space: nowrap;
            }
            #imagePreview {
                max-width: 100%;
                max-height: 200px;
                margin-top: 10px;
                display: none;
            }
            .input-group .form-control {
                border-right: none;
            }
            .btn-toggle-password {
                background-color: #f0f0f0;
                border: 1px solid #ced4da;
                border-left: none;
                color: #495057;
                padding: 0.375rem 0.75rem;
            }
            .btn-toggle-password:hover,
            .btn-toggle-password:focus {
                background-color: #e9ecef;
                color: #495057;
            }
            .input-group:focus-within .form-control,
            .input-group:focus-within .btn-toggle-password {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
            }

        </style>
    </head>

    <body>
    <?php include("../modules/login-header.php");?>
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="d-flex justify-content-center">
                        <h3 class="mt-3" style="color: var( --text-color);">會員店家註冊申請</h3>
                    </div>
                        <div class="create-shop-card mx-auto  mb-5">
                        <div class="w-75">
                            <form action="../function/doCreateShop.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Name</label>
                                    <input class="form-control form-control-custom" type="text"  id="username" name="username" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="account" class="form-label">Account</label>
                                    <div class="d-flex">
                                        <input class="form-control form-control-custom" type="text" id="account" name="account" required/>
                                        <!-- <button type="button" class="btn btn-neumorphic btn-check-account"><i class="fa-solid fa-user-check"></i></button> -->
                                    </div>
                                    <div id="accountFeedback" class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input class="form-control form-control-custom" type="password" id="password" name="password" required/>
                                        <button class="btn btn-toggle-password" type="button" id="togglePassword">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="birthday" class="form-label">生日</label>
                                    <input class="form-control form-control-custom" type="date"  id="birthday" name="birthday" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control form-control-custom" type="text"  id="email" name="email" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input class="form-control form-control-custom" type="text"  id="phone" name="phone" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="shopName" class="form-label">店名</label>
                                    <input class="form-control form-control-custom" type="text"  id="shopName" name="shopName" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="shopAddress" class="form-label">店家地址</label>
                                    <input class="form-control form-control-custom" type="text"  id="shopAddress" name="shopAddress" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="shopPhone" class="form-label">店家電話</label>
                                    <input class="form-control form-control-custom" type="text"  id="shopPhone" name="shopPhone" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="shopDescription" class="form-label">店家描述</label>
                                    <textarea class="form-control form-control-custom" id="shopDescription" name="shopDescription" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="shopImage" class="form-label">店家圖片</label>
                                    <div class="d-flex align-items-center">
                                        <input type="file" class="form-control form-control-custom" id="shopImage" name="shopImage" accept="image/*">
                                        <button type="button" class="btn btn-neumorphic ms-2" id="uploadButton">
                                            上傳照片
                                        </button>
                                    </div>
                                    <div class="d-flex">
                                        <img id="imagePreview" src="#" alt="圖片預覽" />
                                    </div>
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-neumorphic px-4 mx-3 fw-bolder">送出</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include("../js.php");?>
        <!-- Bootstrap JavaScript Libraries -->
        <script
            src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
            crossorigin="anonymous"
        ></script>

        <script
            src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
            crossorigin="anonymous"
        ></script>
        <!-- 按鈕觸發上傳檔案 -->
        <script src="../javascript/create-shop.js"></script>
        
        <!-- 切換顯示密碼 -->
        <script>
        document.getElementById('togglePassword').addEventListener('click', function (e) {
            // 切換密碼可見性
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // 切換眼睛圖標
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
        </script>
    </body>
</html>
