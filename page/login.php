<?php
require_once("../db_connect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 檢查 session 中是否已有使用者資料
if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    // 如果有使用者資料，直接導向到儀表板
    header("Location: ../page/dashboard-home_Joe.php");
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <title>甜覓食光Login</title>
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

    <div class="wrapper d-flex justify-content-center align-items-center flex-column">

        <div class="login-card">
            <div class="logo-box">
                <img class="h-100" src="../images/sweet_time_logo1.png" alt="">
            </div>
            <!-- <h1>log in</h1> -->
            <h1>後台系統</h1>
            <form id="loginForm" action="doLogin.php" method="POST" class="d-flex flex-column">
                <input class="form-control form-control-custom mb-4" type="text" placeholder="Account" id="account" name="account" required>
                <input class="form-control form-control-custom mb-2" type="password" placeholder="password" id="password" name="password" required>
                <div class="d-flex justify-content-center">

                    <button class="log-in-button btn-custom" type="submit" id="signIn">登入</button>
                </div>
            </form>
        </div>
        <a class="sign-up-link mt-3 text-white" type="button" onclick="window.location.href='../page/create-shop.php'">註冊帳號</a>
    </div>


    <!-- Bootstrap JavaScript Libraries -->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <?php include("../js.php"); ?>
    <!-- 提交表單 -->
    <script>
        const account = document.querySelector("#account");
        const password = document.querySelector("#password");
        const signIn = document.querySelector("#signIn");

        signIn.addEventListener("click", function(event) {
            event.preventDefault(); // 阻止表單的默認提交行為
            let accountVal = account.value;
            let passwordVal = password.value;
            $.ajax({
                    method: "POST",
                    url: "../api/doLogin.php",
                    dataType: "json",
                    data: {
                        account: accountVal,
                        password: passwordVal,
                    }
                })
                .done(function(response) {
                    console.log(response)

                    let status = response.status;
                    if (status == 0) {
                        alert(response.message);
                    } else if (status == 2) {
                        alert(response.message);
                        if (response.remains <= 0) {
                            location.reload();
                        }
                    } else if (status == 1) {
                        alert(response.message);
                        location.href = "../page/dashboard-home_Joe.php";
                    }
                })
                .fail(function(jqXHR, textStatus) {
                    console.log("Request failed: " + textStatus);
                });
        });
    </script>

</body>

</html>