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

        </style>
    </head>

    <body>
    <?php include("../modules/login-header.php");?>
        <div class="container">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="d-flex justify-content-center">
                        <h3 class="mt-3" style="color: var(--primary-color);">會員店家註冊申請</h3>
                    </div>
                        <div class="create-shop-card mx-auto  mb-5">
                        <div class="w-75">
                            <form action="../api/doCreateShop.php" method="POST">
                                <div class="mb-3">
                                    <label for="account" class="form-label">Account</label>
                                    <input class="form-control form-control-custom" type="text" class="form-control" id="account" name="account" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input class="form-control form-control-custom" type="password" class="form-control" id="password" name="password" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="repassword" class="form-label">RePassword</label>
                                    <input class="form-control form-control-custom" type="password" class="form-control" id="repassword" name="repassword" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="birthday" class="form-label">生日</label>
                                    <input class="form-control form-control-custom" type="date" class="form-control" id="birthday" name="birthday" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control form-control-custom" type="text" class="form-control" id="email" name="email" required/>
                                </div>
                                <!-- phone -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input class="form-control form-control-custom" type="text" class="form-control" id="phone" name="phone" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="shopName" class="form-label">店名</label>
                                    <input class="form-control form-control-custom" type="text" class="form-control" id="shopName" name="shopName" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="shopAddress" class="form-label">店家地址</label>
                                    <input class="form-control form-control-custom" type="text" class="form-control" id="shopAddress" name="shopAddress" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="shopPhone" class="form-label">店家電話</label>
                                    <input class="form-control form-control-custom" type="text" class="form-control" id="shopPhone" name="shopPhone" required/>
                                </div>
                                <div class="mb-3">
                                    <label for="shopDescription" class="form-label">店家描述</label>
                                    <textarea class="form-control form-control-custom" id="shopDescription" name="shopDescription" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="shopImage" class="form-label">店家圖片</label>
                                    <div class="d-inline-block">
                                        <a class="btn btn-neumorphic px-4 mx-3 fw-bolder" id="uploadButton">
                                            上傳照片
                                        </a>
                                        <input type="file" class="form-control d-none" id="shopImage" name="shopImage" required>
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
        <script>
            document.getElementById('uploadButton').addEventListener('click', function() {
                document.getElementById('shopImage').click();
            });
        </script>
    </body>
</html>
