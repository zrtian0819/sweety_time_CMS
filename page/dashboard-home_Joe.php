<?php

require_once("../db_connect.php");
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$name = $_SESSION["user"]["name"];
$SessRole = $_SESSION["user"]["role"];

$sql = "SELECT 
    COUNT(*) AS total_users,
    SUM(CASE WHEN activation = '1' THEN 1 ELSE 0 END) AS active_users
FROM users";

$result = $conn->query($sql);
$counts = $result->fetch_assoc();

$userCount = $counts['total_users'];
$userCountActive = $counts['active_users'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Sweety Time</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        /* 自定義卡片容器 */
        .custom-card {
            padding: 1rem;
            height: 100%;
            border: var(--area-border);
            border-radius: 10px;
            /* background: black; */
            box-shadow: var(--box-shadow-blue);
        }

        /* 自定義圖表區域 */
        .chart {
            height: 100px; /* 調整圖表高度 */
            background-color: blue; /* 給圖表區塊一個背景色來模擬圖表 */
            border-radius: 0.25rem;
        }

        /* 第一行卡片內容高度調整 */
        .row .custom-card h4 {
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        .row .custom-card p {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">

            <h2 class="fw-bolder"><?= $name?>, 您好！</h2>
            <p>
                <?php if($SessRole=="admin"):?>
                    <!-- 請使用側邊導覽列以管理您的平台資料。 -->
                    <div class="container my-4">
                        <!-- 第一行 -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="custom-card m-2">
                                    <!-- 這裡放入長方形圖表 -->
                                    <h4>年齡段/收入分佈</h4>
                                    <div class="chart">圖表內容</div>
                                </div>
                            </div>
                        </div>

                        <!-- 第二行 -->
                        <div class="row mb-3">
                            <div class="col-md-2">
                                <div class="custom-card text-center d-flex flex-column justify-content-between m-2">
                                    <div class="d-flex">
                                        <h4>會員數量 <i class="fa-solid fa-user"></i></h4>
                                    </div>
                                        <p>
                                            <?= $userCountActive;?>/<?= $userCount;?>
                                        </p>
                                    
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="custom-card text-center d-flex flex-column justify-content-between m-2">
                                <div class="d-flex">
                                        <h4>店家數量 <i class="fa-solid fa-shop"></i></h4>
                                    </div>
                                    <p>73,949</p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="custom-card text-center d-flex flex-column justify-content-between m-2">
                                <div class="d-flex">
                                        <h4>商品數量 <i class="fa-solid fa-bag-shopping"></i>  </h4>
                                    </div>
                                    <p>10.53</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="custom-card m-2 ">
                                    <!-- 這裡放入第一個圓形圖表 -->
                                    <h4>性別分佈</h4>
                                    <div class="chart">圓形圖表</div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif($SessRole=="shop"): ?>
                    請使用側邊導覽列以管理您的商店資料。
                <?php else:?>
                    您沒有任何後台權限。
                <?php endif;?>

            </p>
            

    </div>

    <?php include("../js.php"); ?>
</body>

</html>