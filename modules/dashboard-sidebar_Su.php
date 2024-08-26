<?php
// 獲取當前頁面名稱，不包括查詢字符串
function getBasePageName()
{
    return basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), ".php");
}
?>
<div class="sidebar neumorphic setting" id="sideBar">
    <?php if ($role == "admin"): ?>
        <button id="adminBtn" class="btn btn-neumorphic toggle-btn">
            <h5 class="h5">admin管理</h5>
        </button>
        <ul id="adminList" class="nav flex-column align-items-center">
            <li class="nav-item">
                <a class="nav-link <?= (getBasePageName() == 'users') ? 'active' : '' ?>" href="users.php">會員管理</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (getBasePageName() == 'articles') ? 'active' : '' ?>" href="articles.php">文章管理</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (getBasePageName() == 'teacher') ? 'active' : '' ?>" href="teacher.php">師資管理</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (getBasePageName() == 'lesson') ? 'active' : '' ?>" href="lesson.php">課程管理</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (getBasePageName() == 'coupon-list') ? 'active' : '' ?>" href="coupon-home.php">優惠券管理</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (getBasePageName() == 'shop-info-admin') ? 'active' : '' ?>" href="shop-info-admin.php">總商家管理</a>
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
                    <a class="nav-link <?= (getBasePageName() == 'shop-info') ? 'active' : '' ?>" href="shop-info.php?shopId=<?= $shopId ?>">店家基本資料</a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link <?= (getBasePageName() == 'product-list') ? 'active' : '' ?>" href="product-list.php">商品管理</a>
            </li>
            <?php if ($role == "admin"): ?>
             <li class="nav-item">
                 <a class="nav-link <?= (getBasePageName() == 'order-list') ? 'active' : '' ?>" href="order-list.php">訂單管理</a>
             </li>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
</div>