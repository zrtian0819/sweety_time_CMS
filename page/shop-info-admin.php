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
$per_page = 5;
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
        .table {
        color: var(--text-color);
        }
        .table thead th {
            background-color: var(--primary-color);
            color: white;
        }
        .table tbody td {
            color: var(--text-color);
        }
        .form-check-label {
            color: var(--text-color);
        }
        .align-middle .teacher-profile{
            background-color:rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="shop-info-admin.php">
                <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回</span>
            </a>
        <form action="">
            <div class="input-group d-flex justify-content-end align-items-center mb-2">
                <input type="search" class="form-control" placeholder="搜尋店家" name="search" style="max-width:200px" value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn neumorphic" type="submit" style="color: var(--primary-color);"><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>
        </form>
            <div class="container">
                <div class="row">
                    <ul class="nav nav-tabs-custom">
                        <li class="nav-item">
                            <a class="main-nav nav-link <?php if(!isset($_GET['filter']) || $_GET['filter'] == 'all') echo 'active'; ?>" href="shop-info-admin.php?filter=all">全部商家</a>
                        </li>
                        <li class="nav-item">
                            <a class="main-nav nav-link <?php if(isset($_GET['filter']) && $_GET['filter'] == 'active') echo 'active'; ?>" href="shop-info-admin.php?filter=active">已啟用商家</a>
                        </li>
                        <li class="nav-item">
                            <a class="main-nav nav-link <?php if(isset($_GET['filter']) && $_GET['filter'] == 'inactive') echo 'active'; ?>" href="shop-info-admin.php?filter=inactive">已關閉商家</a>
                        </li>
                    </ul>
                    <div class="col-12 position-relative d-flex justify-content-center mb-3 mb-md-0 mt-0">
                        <div class="table-responsive">
                        <?php if ($shopCount > 0): ?>
                        <table class="table table-hover bdrs table-responsive align-middle" style="min-width: 1000px;">
                                <thead class="text-center">
                                    <tr>
                                        <th class="dontNextLine text-center">Shop_Logo</th>
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
                                            <td class="align-middle">
                                                <div class="teacher-profile d-flex align-items-center justify-content-center">
                                                    <?php if (!empty($row["logo_path"]) && file_exists("../images/shop_logo/" . $row["logo_path"])): ?>
                                                        <img src="../images/shop_logo/<?= $row["logo_path"] ?>" alt="<?= htmlspecialchars($row["name"]) ?> Logo" class="ratio ratio-4x3">
                                                    <?php else: ?>
                                                        <i class="fa-regular fa-image"></i>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
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
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input activation-switch" type="checkbox" 
                                                        id="activationSwitch<?= $row['shop_id'] ?>" 
                                                        <?= $row["activation"] == 1 ? 'checked' : '' ?>
                                                        data-shop-id="<?= $row['shop_id'] ?>"
                                                        data-current-status="<?= $row['activation'] ?>">
                                                    <label class="form-check-label text-nowrap" for="activationSwitch<?= $row['shop_id'] ?>">
                                                        <?= $row["activation"] == 1 ? '啟用' : '停用' ?>
                                                    </label>
                                                </div>
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
                            <!-- 創建一個導航區域用於分頁 -->
                            <nav aria-label="Page navigation">
                                <!-- 創建一個無序列表來容納分頁按鈕 -->
                                <ul class="pagination justify-content-center">
                                    <!-- 前往第一頁的箭頭按鈕 -->
                                    <?php if($page > 1): ?>
                                        <li class="page-item px-1">
                                            <a class="page-link btn-custom" href="?p=1&search=<?= $search ?>&filter=<?= $filter ?>" aria-label="First">
                                                <span aria-hidden="true"><i class="fa-solid fa-backward-fast"></i></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <!-- 固定顯示第一頁 -->
                                    <li class="page-item px-1 <?= ($page == 1) ? 'active' : '' ?>">
                                        <a class="page-link btn-custom" href="?p=1&search=<?= $search ?>&filter=<?= $filter ?>">1</a>
                                    </li>
                                    
                                    <?php
                                    // 計算應該顯示的頁碼範圍
                                    $start = max(2, $page - 2);
                                    $end = min($total_page - 1, $page + 2);
                                    // 如果範圍的起始不是2,顯示省略號
                                    if ($start > 2): 
                                    ?>
                                        <li class="page-item px-1 disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>

                                    <!-- 循環創建頁碼按鈕 -->
                                    <?php for($i = $start; $i <= $end; $i++): ?>
                                        <li class="page-item px-1 <?= ($i == $page) ? 'active' : '' ?>">
                                            <a class="page-link btn-custom" href="?p=<?= $i ?>&search=<?= $search ?>&filter=<?= $filter ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <!-- 如果範圍的結束不是倒數第二頁,顯示省略號 -->
                                    <?php if ($end < $total_page - 1): ?>
                                        <li class="page-item px-1 disabled">
                                            <span class="page-link">...</span>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <!-- 固定顯示最後一頁 -->
                                    <?php if($total_page > 1): ?>
                                        <li class="page-item px-1 <?= ($page == $total_page) ? 'active' : '' ?>">
                                            <a class="page-link btn-custom" href="?p=<?= $total_page ?>&search=<?= $search ?>&filter=<?= $filter ?>"><?= $total_page ?></a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <!-- 前往最後一頁的箭頭按鈕 -->
                                    <?php if($page < $total_page): ?>
                                        <li class="page-item px-1">
                                            <a class="page-link btn-custom" href="?p=<?= $total_page ?>&search=<?= $search ?>&filter=<?= $filter ?>" aria-label="Last">
                                                <span aria-hidden="true"><i class="fa-solid fa-forward-fast"></i></span>
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
    <!-- 開關切換 -->
    <script>
        $(document).ready(function() {
            $('.activation-switch').change(function() {
                var shopId = $(this).data('shop-id');  // 修正：使用 shop-id 而不是 shop_id
                var currentStatus = $(this).data('current-status');
                var newStatus = this.checked ? 1 : 0;
                var $switch = $(this);
                
                $.ajax({
                    url: '../api/update_shop_activation.php',
                    method: 'POST',
                    data: { 
                        shop_id: shopId, 
                        activation: newStatus 
                    },
                    dataType: 'json',  // 明確指定預期的數據類型
                    success: function(response) {
                        if(response.success) {
                            // 更新標籤文字
                            $switch.next('label').text(newStatus == 1 ? '啟用' : '停用');
                            // 更新 data-current-status
                            $switch.data('current-status', newStatus);
                            console.log('店鋪狀態已更新');  // 使用 console.log 而不是 alert
                        } else {
                            console.error('更新失敗: ' + (response.message || '未知錯誤'));
                            // 恢復開關狀態
                            $switch.prop('checked', currentStatus == 1);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('更新時發生錯誤:', textStatus, errorThrown);
                        // 恢復開關狀態
                        $switch.prop('checked', currentStatus == 1);
                    }
                });
            });
        });
    </script>
</body>
</html>