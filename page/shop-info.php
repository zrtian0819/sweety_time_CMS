<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop info</title>
    <!-- 店家基本資料頁+店家註冊頁 -->
     <!-- 店家註冊頁→含上傳Logo照片
          店家基本資料頁
          店家編輯更新頁→含編輯logo照片、自己註銷店家 -->
    <?php include("../css/css.php");?>
</head>
<body>
    <?php include ("../modules/dashboard-header.php");?>
    <?php include ("../modules/dashboard-sidebar.php");?>
    <!-- Content -->
    <div class="col-lg-10 col-md-9"> <!-- 這一層佈局不要動 -->
        <div class="content neumorphic">
            <h2>我的商店</h2> <!-- 這編寫變數 -->
            <div class="card-neumorphic d-flex align-content-center">
                <a href="">
                    <img class="shop-info-logo" src="../images/shop_logo/深夜裡的法國手工甜點_logo.jpg" alt="">
                </a>
                <div class="shop-info-text m-2">
                    <h3 class=" fw-bold">深夜的法國甜點</h3> <!-- 店家名稱 -->
                    <h4>店家簡介</h4>
                    <p>
                    自2011年創店至今，以最招牌的塔類甜點為主，佐以豐富的蛋糕點心，累積近百種口味豐富且獨具特色的甜點。而於2019年開立的實體旗艦店，則是提供外帶與現場享用甜點、結合咖啡與茶品的複合式空間。每日可賣出150-200份甜點，長時間的經營，也累積了超過81萬網路粉絲與好口碑。<br>
                    新鮮╳誠意╳分享<br>
                    品牌名稱的「深夜」，代表「當天新鮮製作」的堅持。為了確保顧客能吃到剛出爐、風味最佳的甜點，我們每天接單現做，傍晚出爐並在深夜販售。精心挑選的高品質食材及厚實飽滿的用料，是深法甜點的靈魂。我們希望人與人之間能透過這樣的甜點牽起聯繫，無論是作為一份禮物，或是一起品嚐享用，將吃甜點的美好心情與之分享，即是我們最想傳遞的品牌精神。</p>
                </div>
            </div>
            <div class="card-neumorphic">
                <div class="shop-info-text m-2">
                    <h4>店家基本資料</h4>
                    <ul class=" list-unstyled">
                        <li>Phone：0223673067</li>
                        <li></li>
                        <li></li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>