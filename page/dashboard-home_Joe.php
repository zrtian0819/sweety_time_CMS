<?php
require_once("../db_connect.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$name = $_SESSION["user"]["name"];
$SessRole = $_SESSION["user"]["role"];

$sql = "SELECT
            (SELECT COUNT(*) FROM users) AS total_users,
            (SELECT COUNT(*) FROM users WHERE activation = '1') AS active_users,
            (SELECT COUNT(*) FROM shop) AS total_shops,
            (SELECT COUNT(*) FROM shop WHERE activation = '1') AS active_shops,
            (SELECT COUNT(*) FROM product) AS total_products,
            (SELECT COUNT(*) FROM product WHERE available = '1') AS active_products,
            shop.name AS shop_name,
            SUM(orders.total_price) AS total_sales,
            DATE(orders.order_time) AS order_date
        FROM
            orders
        JOIN
            shop ON orders.shop_id = shop.shop_id
        GROUP BY
            orders.shop_id, shop.name, DATE(orders.order_time)
        ORDER BY
            total_sales DESC, order_date";

$result = $conn->query($sql);

$counts = array();
$money_data = array();
$sweety_money_data = array();

while ($row = $result->fetch_assoc()) {
    // 只在第一次迭代時設置計數
    if (empty($counts)) {
        $counts = array(
            'total_users' => $row['total_users'],
            'active_users' => $row['active_users'],
            'total_shops' => $row['total_shops'],
            'active_shops' => $row['active_shops'],
            'total_products' => $row['total_products'],
            'active_products' => $row['active_products']
        );
    }

    // 收集商店銷售數據
    if (count($money_data) < 10) {
        $money_data[] = array(
            'name' => $row['shop_name'],
            'total_sales' => $row['total_sales']
        );
    }

    // 收集每日銷售數據
    $date = $row['order_date'];
    if (!isset($sweety_money_data[$date])) {
        $sweety_money_data[$date] = 0;
    }
    $sweety_money_data[$date] += $row['total_sales'];
}
// 按日期排序數據（最新的日期在前）
krsort($sweety_money_data);

// 只保留最近7天的數據
$seven_day_data = array_slice($sweety_money_data, 0, 7, true);

// 反轉數組，使日期按升序排列
$seven_day_data = array_reverse($seven_day_data, true);

// 將處理後的數據轉換為 JSON 格式，以便在 JavaScript 中使用
$seven_day_json = json_encode($seven_day_data);


$userCount = $counts['total_users']; //會員總數
$userCountActive = $counts['active_users']; //啟用中的會員數量
$shopCount = $counts['total_shops'];//商家總數
$shopCountActive = $counts['active_shops'];//啟用中的商家數量
$productCount = $counts['total_products'];//商品總數
$productCountActive = $counts['active_products'];//上架中的商品數量

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Sweety Time</title>
    <?php include("../css/css_Joe.php"); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script> <!-- chart.js -->
    <style>
        /* 自定義卡片容器 */
        .custom-card {
            /* padding: 1rem; */
            height: 100%;
            border: var(--area-border);
            border-radius: 10px;
            box-shadow: var(--box-shadow-blue);
        }

        /* 自定義圖表區域 */
        .chart {
            background-color: blue; /* 給圖表區塊一個背景色來模擬圖表 */
            border-radius: 0.25rem;
        }

        /* 第一行卡片內容高度調整 */
        .row .custom-card h4 {
            text-wrap: nowrap;
            font-size: 1rem;
        }
        .row .custom-card p {
            font-size: 1.6rem;
            font-weight: 700;
        }
        #canvas-holder {
            width: 100%;
            max-width: 100%;
            height: 300px; 
        }
        #salesChart {
            height: 250px !important;
        }
        #chart-area{
            height: 400px !important;
        }
        .chart-container-sales {
            position: relative;
            height: 300px;
            width: 100%;
        }
        .chart-container-area {
            position: relative;
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic ps-4 pe-2 py-5">

            <h2 class="fw-bolder mb-5"><?= $name?>, 您好！</h2>
                <?php if($SessRole=="admin"):?>
                    <div class="container-fluid">
                        <div class="row ms-2 me-0">
                            <div class="col-md-6">
                                <div class="row mb-2 mx-0">
                                    <div class="col-md-4">
                                        <div class="custom-card text-center d-flex flex-column justify-content-between">
                                            <div class="d-flex ms-2 mt-2">
                                                <h4>會員數量 <i class="fa-solid fa-user"></i></h4>
                                            </div>
                                                <p>
                                                    <?= $userCountActive;?>/<?= $userCount;?>
                                                </p>
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-card text-center d-flex flex-column justify-content-between">
                                            <div class="d-flex ms-2 mt-2">
                                                <h4>店家數量 <i class="fa-solid fa-shop"></i></h4>
                                            </div>
                                            <p>
                                                <?= $shopCountActive;?>/<?= $shopCount;?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="custom-card text-center d-flex flex-column justify-content-between">
                                            <div class="d-flex ms-2 mt-2">
                                                <h4>商品數量 <i class="fa-solid fa-bag-shopping"></i>  </h4>
                                            </div>
                                            <p>
                                                <?= $productCountActive;?>/<?= $productCount;?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mx-0">
                                    <div class="col-md-12">
                                        <div class="custom-card">
                                            <div class="d-flex ms-2 mt-2">    
                                                <h4>全站銷售量</h4>
                                            </div>
                                            <div class="chart-container-sales">
                                                <canvas id="salesChart"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 ms-3">
                                <div class="custom-card">
                                    <div class="d-flex ms-2 mt-2">
                                        <h4>熱銷名店</h4>
                                    </div>
                                    <div class="chart-container-area d-flex justify-cont-center">
                                        <canvas id="chart-area"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php elseif($SessRole=="shop"): ?>
                    請使用側邊導覽列以管理您的商店資料。
                <?php else:?>
                    您沒有任何後台權限。
                <?php endif;?>
        </div>
    </div>

    <?php include("../js.php"); ?>
    <!-- 將moneyData轉成JSON格式 -->
    <script>var moneyData = <?php echo json_encode($money_data); ?>;</script>
    
    <!-- 熱銷名店的chart控制 -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('chart-area').getContext('2d');

            // 從 moneyData 中提取標籤和數據，並截斷標籤
            var labels = moneyData.map(function(item) {
                return item.name.length > 5 ? item.name.substring(0, 5) + '...' : item.name;
            });
            var data = moneyData.map(function(item) {
                return item.total_sales;
            });

            // 設定統一的顏色
            var barColor = 'rgb(244, 162, 147)';

            var myChart = new Chart(ctx, {
                type: 'horizontalBar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '銷售額',
                        data: data,
                        backgroundColor: barColor,
                        borderColor: barColor,
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,// 保持圖表比例
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: '銷售額'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: '店鋪名稱'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: '熱銷名店銷售額排行'
                        },
                        tooltip: {
                            callbacks: {
                                title: function(tooltipItems) {
                                    // 在工具提示中顯示完整的店鋪名稱
                                    return moneyData[tooltipItems[0].dataIndex].name;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    <!-- 銷售總額的chart控制 -->
    <script>
        // 使用 PHP 處理後的數據
        var sevenDayData = <?php echo $seven_day_json; ?>;

        // 準備數據
        var dates = Object.keys(sevenDayData);
        var sales = Object.values(sevenDayData);

        // 創建圖表
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: '每日銷售總額',
                    data: sales,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,// 保持圖表比例
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'day',
                            displayFormats: {
                                day: 'MM-DD'
                            }
                        },
                        title: {
                            display: true,
                            text: '日期'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '銷售總額'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: '近七天每日銷售總額趨勢'
                    }
                }
            }
        });
        </script>
</body>

</html>