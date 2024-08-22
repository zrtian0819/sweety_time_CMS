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

    </head>

    <body>
    <?php include("../modules/login-header.php");?>
    <div class="container d-flex flex-row px-4">
        <div class="main col neumorphic p-5">
            <div class=" neumorphic ">
                <h2>註冊為店家</h2>
                <input class="login-input" type="text" placeholder="店家名稱">
                <input class="login-input" type="text" placeholder="聯絡電話">
                <input class="login-input" type="text" placeholder="店家地址">
                <input class="login-input" type="text" placeholder="營業時間">
                <input class="login-input" type="text" placeholder="官方網站">
                <input class="login-input" type="text" placeholder="電子信箱">
                <hr class="logo-hr">
                <div class="d-flex justify-content-center align-items-center">
                    <h4>上傳店家Logo</h4>
                    <label for="file-upload" class="custom-file-upload ms-3">
                        上傳檔案
                    </label>
                    <input id="file-upload" type="file"/>
                </div>
                <div class="d-flex w-100">
                    <button class="log-in-button">確認</button>
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
    </body>
</html>
