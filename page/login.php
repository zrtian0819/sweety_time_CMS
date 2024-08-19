

<!doctype html>
<html lang="en">
    <head>
        <title>甜覓食光Login</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1, shrink-to-fit=no"
        />
        <?php include("../css/login-style.php");?>
        <?php include("../css/css_Joe.php");?>
    </head>

    <body>
        <?php include ("../modules/login-header.php");?>
        <div class="container">
            <div class="login-card">
                <h1>Log In</h1>
                <input class="form-control form-control-custom" type="text" placeholder="Account">
                <input class="form-control form-control-custom mt-3" type="password" placeholder="password">
                <button class="log-in-button">Log In</button>
            </div>
        </div>
        <div class="container">
            <div class="w-50 d-flex justify-content-between mx-auto py-3">
                    <p>
                        <a class="sign-in" href="../page/create-shop.php">註冊帳號</a>
                    </p>
                    <p>
                        <a class="sign-in" href="../page/password/reset" class="forgot-pw">忘記密碼?</a>
                    </p>
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
    </body>
</html>
