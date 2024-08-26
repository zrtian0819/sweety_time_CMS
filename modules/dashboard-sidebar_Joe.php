 <!-- Sidebar -->
 <?php
    // $require_once("../db_connect.php");

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

 <div class="sidebar neumorphic setting" id="sideBar">

     <?php if ($role == "admin"): ?>
         <button id="adminBtn" class="btn btn-neumorphic toggle-btn">
             <h5 class="h5">admin管理</h5>
         </button>
         <ul id="adminList" class="nav flex-column align-items-center">
             <li class="nav-item">
                 <a class="nav-link" href="users.php">會員管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="article.php">文章管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="teacher.php">師資管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="lesson.php">課程管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="coupon-home.php">優惠券管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="shop-info-admin.php">總商家管理</a>
             </li>
         </ul>
     <?php endif; ?>

     <?php if ($role == "shop" || $role == "admin"): ?>
         <button id="storeBtn" class="btn btn-neumorphic toggle-btn">
             <h5 class="h5">店家管理</h5>
         </button>
         <ul id="storeList" class="nav flex-column align-items-center">
         <?php if ($role == "shop"): ?>
             <li class="nav-item">
                 <a class="nav-link" href="shop-info.php?shopId=<?= $shopId ?>">店家基本資料</a>
             </li>
        <?php endif; ?>
             <li class="nav-item">
                 <a class="nav-link" href="product-list.php?shopId=<?= $shopId ?>">商品管理</a>
             </li>
        <?php if ($role == "admin"): ?>
             <li class="nav-item">
                 <a class="nav-link" href="order-list.php">訂單管理</a>
             </li>
        <?php endif; ?>

         </ul>
     <?php endif; ?>

     <!-- 店家註冊並非在dashboard頁面執行 -->
     <!--  
     <button id="registerBtn" class="btn btn-neumorphic toggle-btn">
         <h5 class="h5">店家註冊</h5>
     </button>
     <ul id="registerList" class="nav flex-column align-items-center">
         </button>
         <ul class="nav flex-column">
             <li class="nav-item">
                 <a class="nav-link" href="#">店家基本資料填寫</a>
             </li>
         </ul>
    -->
 </div>