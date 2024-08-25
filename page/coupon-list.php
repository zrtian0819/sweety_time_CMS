<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
include("../function/rebuildURL.php");

// 抓取現在日期以做判斷和篩選
$now = date("Y-m-d");

// 設定SQL查詢語句樣板 
$sql_all = "SELECT * FROM coupon WHERE 1=1"; // 利用永遠為真的 `1=1` 以利加後續條件
$params = []; // 用來裝條件
$types = ""; // 用來紀錄條件型別

// 搜尋條件
if (isset($_GET["search"]) && !empty($_GET["search"])) {
    $search = "%" . $_GET["search"] . "%";
    $sql_all .= " AND name LIKE ?";
    array_push($params, $search);
    $types .= "s";
}

// 啟用狀態條件
if (isset($_GET["act_status"]) && $_GET["act_status"] !== "") {
    $act_status = $_GET["act_status"];
    $sql_all .= " AND activation = ?";
    array_push($params, $act_status);
    $types .= "i";
}else{
    $act_status = "";
}

// 效期條件
if(!isset($_GET["expr_status"])) {
    $expr_status = "expr_all";
}else{
    $expr_status = $_GET["expr_status"];
    switch ($expr_status) {
        case "expr_all":
            break;
        case "expr_notStart":
            $sql_all .= " AND start_time > ?";
            array_push($params, $now);
            $types .= "s";
            break;
        case "expr_canUse":
            $sql_all .= " AND start_time <= ? AND (end_date >= ? OR permanent = 1)";
            $params = array_merge($params, [$now, $now]);
            $types .= str_repeat("s", 2);
            break;
        case "expr_exprd":
            $sql_all .= " AND end_date < ? AND permanent = 0";
            array_push($params, $now);
            $types .= "s";
            break;
    }
}

// 排序條件
if (!isset($_GET["sort"])){
    $sort = "coupon_id";
}else{
    $sort = $_GET["sort"];
    switch ($sort) {
        case "id_asc":
            $sql_all .= " ORDER BY coupon_id ASC";
            break;
        case "discount_asc":
            $sql_all .= " ORDER BY discount_rate ASC";
            break;
        case "discount_desc":
            $sql_all .= " ORDER BY discount_rate DESC";
            break;
        case "start_asc":
            $sql_all .= " ORDER BY start_time ASC";
            break;
        case "start_desc":
            $sql_all .= " ORDER BY start_time DESC";
            break;
        case "end_asc":
            $sql_all .= " ORDER BY end_date ASC";
            break;
        case "end_desc":
            $sql_all .= " ORDER BY end_date DESC";
            break;
        case "created_asc":
            $sql_all .= " ORDER BY created_at ASC";
            break;
        case "created_desc":
            $sql_all .= " ORDER BY created_at DESC";
            break;
    }
}


// 處理per_page參數
if(!isset($_GET["per_page"]) || $_GET["per_page"] == NULL || $_GET["per_page"] == 0){
    $per_page = 10;
}else{
    $per_page = $_GET["per_page"];
}

// 第一次撈資料，計算頁數
$total_stmt = $conn->prepare($sql_all);
if (!empty($params)) {
    $total_stmt->bind_param($types, ...$params);
}
$total_stmt->execute();
$total_result = $total_stmt->get_result();
$total_rows = $total_result->num_rows;
$total_pages = ceil($total_rows / $per_page);

// 分頁處理
$current_page = isset($_GET["page"]) ? $_GET["page"] : 1;
$start_item = ($current_page - 1) * $per_page;
$sql_page = $sql_all . " LIMIT ?, ?";
array_push($params, $start_item, $per_page);
$types .= "ii";

$stmt_page = $conn->prepare($sql_page);

// 綁定所有參數
$stmt_page->bind_param($types, ...$params);

// 執行第二次撈資料
$stmt_page->execute();
$result_page = $stmt_page->get_result();
$rows_page = $result_page->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>優惠券種類列表</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .coupon-input-bar{
            width: 50px;
        }
        .table{
            table-layout: auto;
        }
        .btns-box{
            width: 200px
        }
        .coupon-filter{
            width: 700px;
        }
        .coupon-filter .search-input {
            width: 40%; /* 設定固定寬度 */
        }

        .coupon-filter .order-select {
            width: 15%;
        }

        .coupon-filter .activ-select {
            width: 15%;
        }
        .btn-white{
            /* box-shadow: 0px 0px 3px #d1d9e6; */
        }
        p{
            margin-top: auto;
            margin-bottom: auto;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">


            <!-- 篩選器 -->
            <div class="py-2 d-flex justify-content-between mb-4">
                <form action="" class="d-flex justify-content-between">
                    <div class="input-group coupon-filter">
                        <input type="search" class="form-control search-input" name="search" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>" placeholder="搜尋優惠券名稱">
                        <select class="form-select order-select" aria-label="Default select example" name="sort">
                            <option <?php echo $sort == "id_asc" ? "selected" : ""; ?> value="id_asc">依id排序(預設)</option>
                            <option <?php echo $sort == "discount_desc" ? "selected" : ""; ?> value="discount_desc">折扣由低至高</option>
                            <option <?php echo $sort == "discount_asc" ? "selected" : ""; ?> value="discount_asc">折扣由高至低</option>
                            <option <?php echo $sort == "start_asc" ? "selected" : ""; ?> value="start_asc">啟用日由先至後</option>
                            <option <?php echo $sort == "start_desc" ? "selected" : ""; ?> value="start_desc">啟用日由後至先</option>
                            <option <?php echo $sort == "end_asc" ? "selected" : ""; ?> value="end_asc">到期日由先至後</option>
                            <option <?php echo $sort == "end_desc" ? "selected" : ""; ?> value="end_desc">到期日由後至先</option>
                            <option <?php echo $sort == "created_asc" ? "selected" : ""; ?> value="created_asc">創建日由舊至新</option>
                            <option <?php echo $sort == "created_desc" ? "selected" : ""; ?> value="created_desc">創建日由新至舊</option>
                            </select>
                        <select class="form-select activ-select" aria-label="Default select example" name="expr_status">
                            <option <?php echo $expr_status == "expr_all" ? "selected" : ""; ?> value="expr_all"><span class="text-secondery">不限效期狀態</span></option>
                            <option <?php echo $expr_status == "expr_notStart" ? "selected" : ""; ?> value="expr_notStart"><span class="text-success">尚未開始</span></option>
                            <option <?php echo $expr_status == "expr_canUse" ? "selected" : ""; ?> value="expr_canUse"><span class="text-success">效期內</span></option>
                            <option <?php echo $expr_status == "expr_exprd" ? "selected" : ""; ?> value="expr_exprd"><span class="text-danger">已過期</span></option>
                        </select>
                        <button class="btn btn-custom" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
                <div class="d-flex">
                    <a class="btn-animation btn btn-custom d-flex flex-row align-items-center mx-2" href="./coupon-create.php">
                        <i class="fa-solid fa-plus"></i><span class="btn-animation-innerSpan d-inline-block">新增優惠券</span>
                    </a>
                    <a class="btn btn-custom d-flex flex-row align-items-center" href="./coupon-distrbute-history.php">
                        <i class="fa-solid fa-chart-simple me-2"></i><span class="btn-animation-innerSpan d-inline-block">查看發券歷史</span>
                    </a>
                </div>
            </div>

            <!-- 顯示資料的表格 -->
            <div class="d-flex justify-content-between">
                <ul class="nav nav-tabs-custom">
                    <li class="nav-item">
                        <a class="main-nav nav-link <?php echo $act_status == "" ? "active" : ""; ?>" href="<?= rebuild_url(['act_status' => '', 'message' => '']); ?>">全部</a>
                    </li>
                    <li class="nav-item">
                        <a class="main-nav nav-link <?php echo $act_status == "1" ? "active" : ""; ?>" href="<?= rebuild_url(['act_status' => '1', 'message' => '']); ?>">啟用中</a>
                    </li>
                    <li class="nav-item">
                        <a class="main-nav nav-link <?php echo $act_status == "0" ? "active" : ""; ?>" href="<?= rebuild_url(['act_status' => '0', 'message' => '']); ?>">已停用</a>
                    </li>
                </ul>
                <!-- 設定一頁幾筆資料 -->
                <form class="d-flex align-items-center">
                    <span>共有 <?= $total_rows ?> 筆資料，</span>
                    <span>每頁</span>
                    <input type="text" class="form-control coupon-input-bar mx-1" name="per_page" id="perPageInput" value="<?= $per_page ?>" placeholder="">
                    <span>筆</span>
                    <a class="btn btn-white" id="perPageBtn"><span class="fw-bold fs-6">GO</span></a>
                </form>
            </div>
            <table class="table table-hover">
                <thead class="table-pink">
                    <tr>
                        <th class="text-center">優惠券編號</th>
                        <th class="text-center">優惠券名稱</th>
                        <th class="text-center">折扣率</th>
                        <th class="text-center">啟用日期</th>
                        <th class="text-center">到期日</th>
                        <th class="text-center">啟用狀態</th>
                        <th class="text-center">效期狀態</th>
                        <th class="text-center">創建日期</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows_page as $row) : ?>
                        <tr>
                            <td class="text-center align-middle"><?= $row['coupon_id'];?></td>
                            <td class="text-center align-middle"><?= $row['name'];?></td>
                            <td class="text-center align-middle"><?= $row['discount_rate'];?></td>
                            <td class="text-center align-middle"><?= $row['start_time'];?></td>
                            <td class="text-center align-middle">
                                <?php if(is_null($row['end_date'])) : ?>
                                    <p class="text-muted">永久有效</p>    
                                <?php else :?>
                                    <?= $row['end_date'];?>
                                <?php endif;?>
                            </td>
                            <td class="align-middle">
                                <div class="d-flex justify-content-center">
                                    <p class="activ_status-text mx-2 <?= $row['activation'] == 1 ? 'text-success' : 'text-danger'; ?>" data-id="<?= $row['coupon_id']; ?>">
                                        <?= $row['activation'] == 1 ? '啟用中' : '停用中'; ?>
                                    </p>
                                    <div class="form-check form-switch mx   -2">
                                        <input class="form-check-input activ_switch" type="checkbox" data-id="<?= $row['coupon_id']; ?>" <?php echo $row['activation'] == 1 ? "checked" : ""; ?>>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center align-middle">
                                <?php if ($row['start_time'] > $now) : ?>
                                    <p class="text-secondary">尚未開始</p>
                                <?php elseif ((is_null($row['end_date']) && $row['permanent'] == 1 ) || ($row['start_time'] <= $now && $now <= $row['end_date'])) : ?>
                                    <p class="text-success">效期內</p>
                                <?php elseif (!is_null($row['end_date']) && $row['end_date'] < $now) : ?>
                                    <p class="text-danger">已過期</p>
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle">
                                <?= $row['created_at'];?>
                            </td>
                            <td class="text-center btns-box align-middle">
                                <div class=" d-flex align-items-center justify-content-end">
                                     <a class="btn-animation btn btn-custom d-flex flex-row align-items-center mx-2" href="./coupon-edit.php?coupon_id=<?= $row['coupon_id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i><span class="btn-animation-innerSpan d-inline-block">編輯此券</span>
                                    </a>
                                    <a class="btn-animation btn btn-custom d-flex flex-row align-items-center" href="./coupon-distribute.php?coupon_id=<?= $row['coupon_id'] ?>">
                                        <i class="fa-regular fa-paper-plane"></i><span class="btn-animation-innerSpan d-inline-block">發送此券</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <!-- 換頁按鈕 -->

                <nav aria-label="Page navigation example">
                    <ul class="pagination d-flex justify-content-center">
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item mx-1 <?php if ($current_page == $i) echo "active"; ?>">
                                <a class="page-link btn-custom" href="<?= rebuild_url(['page' => $i, 'message' => '']) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>

        </div>

    </div>
    

    <!-- 顯示新增和編輯的成功or失敗訊息 -->
    <!-- 有時間可以用中介頁面來避免使用GET -->
    <!-- 有時間可以用別的設計取代alert -->
    <?php
        if (isset($_GET['message']) && $_GET['message'] !== "") {
            $message = htmlspecialchars($_GET['message']);
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    ?>


    <!-- Javascript 寫這裡 -->
    <?php include("../js.php"); ?>
    <script>

        // 用AJAX動態更改優惠券的啟用狀態
        const activ_switches = document.querySelectorAll('.activ_switch');

        activ_switches.forEach(function(activ_switch) {
            activ_switch.addEventListener('click', function() {
                let couponId = this.getAttribute('data-id');
                let isActive = this.checked ? 1 : 0;
                        
                $.ajax({
                    method: "POST",
                    url: "../api/doCouponActivationSwitch.php",
                    dataType: "json",
                    data: {
                        id: couponId,
                        activation: isActive
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        console.log("優惠券啟用狀態更改成功，Coupon ID: " + couponId);

                        let statusTextElement = document.querySelector(`.activ_status-text[data-id='${couponId}']`);
                        if (statusTextElement) {
                            if (isActive === 1) {
                                statusTextElement.textContent = '啟用中';
                                statusTextElement.classList.remove('text-danger');
                                statusTextElement.classList.add('text-success');
                            } else {
                                statusTextElement.textContent = '停用中';
                                statusTextElement.classList.remove('text-success');
                                statusTextElement.classList.add('text-danger');
                            }
                        }
                    } else {
                        console.log("狀態切換失敗，Coupon ID: " + couponId);
                        // alert("狀態切換失敗，請稍後再試。");
                    }
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.log("Request failed: " + textStatus + ", " + errorThrown);
                    // alert("請求失敗，請稍後再試。");
                });
            });
        })

        // 設定一頁幾筆資料
        document.querySelector('#perPageBtn').addEventListener('click', function() {
            let perPageValue = document.querySelector('#perPageInput').value;

            // 抓取原來的網址並將 per_page 參數清空
            let baseUrl = '<?= rebuild_url(["per_page" => null, "message" => ""]) ?>';

            // 再把 per_page 加到網址中
            let newUrl = baseUrl + '&per_page=' + perPageValue;

            // 跳轉到新網址
            window.location.href = newUrl;
        });
    </script>
</body>

</html>