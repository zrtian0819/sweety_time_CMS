<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop info</title>
    <!-- 店家基本資料頁+店家註冊頁 -->
    <?php include("../css/css.php");?>
</head>
<body>
    <?php include ("../modules/dashboard-header.php");?>
    <?php include ("../modules/dashboard-sidebar.php");?>
    <!-- Content -->
    <div class="col-md-9"> <!-- 這一層佈局不要動 -->
        <div class="content neumorphic">
            <h2>我的商店</h2> <!-- 這編寫變數 -->
            <div class="card-neumorphic d-flex align-content-center">
                <a href="">
                    <img class="shop-info-logo" src="../images/shop_logo/beardpapas_logo.png" alt="">
                </a>
                <div class="shop-info-text">
                    <h4>beard papa's</h4> <!-- 店家名稱 -->
                    <p> <!-- 店家簡介 -->
                        日本No.1泡芙品牌 | beard papa's日式泡芙專賣店
                        美味「泡芙甜點」下午茶首選 !

                        1999年創立於日本福岡，並於2002年正式進軍海外市場，目前已於全世界設立超過400家分店。

                        門市遍及台灣、韓國、新加坡、泰國、美國、加拿大等國家，為全球性規模品牌。

                        beard papa’s深耕台灣20年以上，設立超過20間門市，未來也將持續展店，

                        致力提供新鮮現做、安心享用的優質泡芙，希望讓每位粉絲都能擁有饗芙・享福的美味體驗!</p>
                </div>
            </div>
            <div class="card-neumorphic">
                <h4>店家基本資料</h4>
                <p>Here you can display recent updates or notifications.</p>
            </div>
        </div>
    </div>
</body>
</html>