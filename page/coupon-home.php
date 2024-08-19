<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>優惠券管理</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <div class="">
                <a href="./coupon.php">優惠券管理</a>
            </div>
            <hr>
            <a href="./coupon-list.php" class="btn-neumorphic text-decoration-none">優惠券種類一覽</a>
        </div>

    </div>

    <?php include("../js.php"); ?>
</body>

</html>