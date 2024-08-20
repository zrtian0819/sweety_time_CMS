<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {

    $roleMessage = "非登入狀態";
} else {
    $role = $_SESSION["user"]["role"];
    $name = $_SESSION["user"]["name"];
    $HRS = date('H');

    if ($HRS <= 4) {
        $welcome = ", 請早點睡!";
    } elseif ($HRS <= 10) {
        $welcome = ", 早安!";
    } elseif ($HRS <= 16) {
        $welcome = ", 午安!";
    } elseif ($HRS > 16) {
        $welcome = ", 晚安!";
    }

    $roleMessage = $name . $welcome;
    // echo $roleMessage;
}


?>
<!-- Navbar -->
<div class="container-fluid">
    <div class="row m-3 mb-0">
        <nav class="dashboard-navbar navbar  navbar-light neumorphic">
            <div class="container-fluid d-flex justify-content-between align-items-center">

                <div class="logo">
                    <img class="logo-img" src="../images/sweet_time_logo1.png" alt="">
                </div>


                <ul class="fs-5 list-unstyled d-flex flex-row m-0 align-items-center justify-content-center">

                    <?php if (isset($_SESSION["user"])): ?>
                        <li class="me-4 d-none d-md-block">
                            <h6 style="color:#EC9E90;" class="d-inline-block"><?= $roleMessage ?></h6>
                        </li>
                        <li class="rounded d-flex align-items-center justify-content-center p-2 me-3" style="background-color:#EC9E90;">
                            <h6 class="text-white d-inline-block text-center m-0"><?= $_SESSION["user"]["role"]; ?></h6>
                        </li>
                    <?php else: ?>
                        <li class="me-4">
                            <h6 style="color:#EC9E90" class="d-inline-block"><?= $roleMessage ?></h6>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item px-2">
                        <a class="nav-link" href="dashboard-home_Joe.php"><i class="fa-solid fa-house fa-fw"></i></a>
                    </li>
                    <li class="nav-item px-2">
                        <a class="nav-link" href="../function/doLogout.php">
                            <!-- <i class="fa-solid fa-user fa-fw"></i> -->
                            <i class="fa-solid fa-right-from-bracket fa-fw"></i>
                        </a>
                    </li>
                    <!-- <li class="nav-item px-2">
                        <a class="nav-link" href="#"><i class="fa-solid fa-bell fa-fw"></i></a>
                    </li> -->
                    <li class="nav-item px-2" id="sideBarController">
                        <a class="nav-link" href="#"><i class="fa-solid fa-bars fa-fw"></i></a>
                    </li>
                </ul>

            </div>
        </nav>
    </div>
</div>