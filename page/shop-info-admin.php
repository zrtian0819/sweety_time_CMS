<?php
require_once("../db_connect.php");      //避免sidebar先載入錯誤,人天先加的

include("../function/login_status_inspect.php");

$role = $_SESSION["user"]["role"]; //判斷登入角色

//假設session之中沒有shop_id則為NULL
$shop_id = $_SESSION["shop"]["shop_id"] ?? null; 

// 根据角色重定向到不同頁面
if ($role != "admin") {
    header("Location: shop-info.php");
    exit;
}

// 預設分頁值
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
$per_page = 10;
$start_item = ($page - 1) * $per_page;

// 搜索條件
$search = isset($_GET["search"]) ? $_GET["search"] : '';

// 使用 mysqli_real_escape_string 來處理搜索字串中的特殊字符
$search = $conn->real_escape_string($search);

// 分頁條件
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
//是否啟用的分頁
$whereClause = "WHERE name LIKE '%$search%'";
if ($filter == 'active') {
    $whereClause .= " AND activation = 1";
} elseif ($filter == 'inactive') {
    $whereClause .= " AND activation = 0";
}

// 計算總記錄數
$sql_total = "SELECT COUNT(*) AS total FROM shop $whereClause";
$result_total = $conn->query($sql_total);
$total_rows = $result_total->fetch_assoc()['total'];

// 計算總頁數
$total_page = ceil($total_rows / $per_page);

// 查詢當前頁面的記錄
$sql = "SELECT * FROM shop $whereClause ORDER BY shop_id ASC LIMIT $start_item, $per_page";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);
$shopCount = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All shop list</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .dontNextLine {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <form action="">
                    <div class="input-group d-flex justify-content-end align-items-center mb-2">
                        <a class="btn neumorphic" href="shop-info-admin.php"><i class="fa-solid fa-circle-left"></i></i></a>
                        <input type="search" class="form-control" placeholder="搜尋店家" name="search" style="max-width:200px">
                        <button class="btn neumorphic" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            <div class="container">
                <div class="row">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link <?php if(!isset($_GET['filter']) || $_GET['filter'] == 'all') echo 'active'; ?>" href="shop-info-admin.php?filter=all">全部商家</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if(isset($_GET['filter']) && $_GET['filter'] == 'active') echo 'active'; ?>" href="shop-info-admin.php?filter=active">已啟用商家</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php if(isset($_GET['filter']) && $_GET['filter'] == 'inactive') echo 'active'; ?>" href="shop-info-admin.php?filter=inactive">已關閉商家</a>
                        </li>
                    </ul>
                    <div class="col-12 position-relative d-flex justify-content-center mb-3 mb-md-0 mt-3 mt-mb-0">
                        <div class="table-responsive">
                        <?php if ($shopCount > 0): ?>
                        <table class="table table-bordered table-hover bdrs table-responsive align-middle" style="min-width: 1000px;">
                                <thead class="text-center table-dark">
                                    <tr>
                                    <th class="dontNextLine text-center">Shop ID</th>
                                    <th class="dontNextLine text-center">店家名稱</th>
                                    <th class="dontNextLine text-center">電話</th>
                                    <th class="dontNextLine text-center">地址</th>
                                    <th class="dontNextLine text-center">簡介</th>
                                    <th class="dontNextLine text-center">註冊時間</th>
                                    <th class="dontNextLine text-center">啟用</th>
                                    <th class="dontNextLine text-center">操作</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <?php foreach ($rows as $row): ?>
                                        <tr>
                                            <td class="text-center align-middle"><?= $row["shop_id"] ?></td>
                                            <td class="text-center align-middle"><?= $row["name"] ?></td>
                                            <td class="text-center dontNextLine align-middle"><?= $row["phone"] ?></td>
                                            <td class="dontNextLine align-middle"><?= $row["address"] ?></td>
                                            <td class="text-center align-middle">
                                                <?php
                                                $description = $row["description"];
                                                $short_description = strlen($description) > 100 ? substr($description, 0, 100) . '...' : $description;
                                                echo $short_description;
                                                ?>
                                            </td>
                                            <td class="text-center align-middle"><?= $row["sign_up_time"] ?></td>
                                            <td class="text-center align-middle">
                                                <?php
                                                $activation = $row["activation"];
                                                if ($activation == 1) {
                                                    echo '<i class="fa-solid fa-check text-success"></i>';
                                                } else {
                                                    echo '<i class="fa-solid fa-xmark text-danger"></i>';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center align-middle">
                                                <a href="javascript:void(0);" class="btn btn-custom dontNextLine btn-sm m-2" data-shop-id="<?= $row['shop_id'] ?>" onclick="saveShopId(this)">
                                                        <i class="fa-solid fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                     <?php endforeach; ?>
                                </tbody>
                            </table>
                            <!-- 分頁導航 -->
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php if($page > 1): ?>
                                        <li class="page-item px-1">
                                            <a class="page-link btn-custom"  href="?p=<?= $page - 1 ?>&search=<?= $search ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php for($i = 1; $i <= $total_page; $i++): ?>
                                        <li class="page-item px-1 <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link btn-custom" href="?p=<?= $i ?>&search=<?= $search ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <?php if($page < $total_page): ?>
                                        <li class="page-item px-1">
                                            <a class="page-link btn-custom" href="?p=<?= $page + 1 ?>&search=<?= $search ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            <?php else: ?>
                            暫無符合條件的商品
                        <?php endif; ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <?php include("../js.php"); ?>

    <!-- 使用Ajax將shop_id存入session -->
    <script>
        function saveShopId(element) {
            // 取得 shop_id
            const shopId = element.getAttribute('data-shop-id');
            
            // 使用Ajax將shop_id存入session
            $.ajax({
                url: '../api/doSaveShopId.php', // 你需要建立的PHP檔案來處理session儲存
                method: 'POST',
                data: { shop_id: shopId },
                success: function(response) {
                    // 如果成功，重定向到 shop-info-edit.php
                    window.location.href = '../page/shop-info-edit.php?shopId=' + shopId;
                },
                error: function() {
                    alert('Failed to save shop ID.');
                }
            });
        }
    </script>
</body>
</html>