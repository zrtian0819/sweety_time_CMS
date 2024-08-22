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
                    <div class="card mx-auto  mb-5">
                        <div class="card-body">
                            <h3>成為店家</h3>
                            <form action="../api/doCreateShop.php" method="POST">
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
                                    <input type="file" class="form-control" id="shopImage" name="shopImage" required/>
                                </div>
                                <button type="submit" class="btn btn-secondary">送出</button>
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
    </body>
</html>
