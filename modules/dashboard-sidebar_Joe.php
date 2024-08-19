 <!-- Sidebar -->
 <?php

    // session_start();
    if (isset($_SESSION["user"])) {
        $role = $_SESSION["user"]["role"];
        $userId = $_SESSION["user"]["user_id"];
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
                 <a class="nav-link" href="#">會員管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="#">文章管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="#">師資管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="#">課程管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="#">優惠券管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="#">總商家管理</a>
             </li>
         </ul>
     <?php endif; ?>

     <?php if ($role == "shop" || $role == "admin"): ?>
         <button id="storeBtn" class="btn btn-neumorphic toggle-btn">
             <h5 class="h5">店家管理</h5>
         </button>
         <ul id="storeList" class="nav flex-column align-items-center">
             <li class="nav-item">
                 <a class="nav-link" href="shop-info.php">店家基本資料</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="product-list.php">商品管理</a>
             </li>
             <li class="nav-item">
                 <a class="nav-link" href="#">訂單管理</a>
             </li>
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