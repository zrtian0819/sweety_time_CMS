<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
include("../function/rebuildURL.php");

$user_id = $_GET["user_id"];
$now = date("Y-m-d");

// 設定SQL查詢語句樣板，使用 JOIN 來整合查詢
$sql_userCoupon = "
    SELECT uc.*, c.name AS coupon_name, c.start_time, c.end_date, c.discount_rate, c.permanent, c.activation
    FROM users_coupon uc
    JOIN coupon c ON uc.coupon_id = c.coupon_id
    WHERE uc.user_id = ?
";
$params = [$user_id];
$types = "i";

// 搜尋條件
if (isset($_GET["search"]) && !empty($_GET["search"])) {
    $search = "%" . $_GET["search"] . "%";
    $sql_userCoupon .= " AND c.name LIKE ?";
    array_push($params, $search);
    $types .= "s";
}

// 優惠活動啟用狀態條件
if (isset($_GET["act_status"]) && $_GET["act_status"] !== "") {
    $act_status = $_GET["act_status"];
    $sql_userCoupon .= " AND c.activation = ?";
    array_push($params, $act_status);
    $types .= "i";
} else {
    $act_status = "";
}

// 效期條件
if (!isset($_GET["expr_status"])) {
    $expr_status = "expr_all";
} else {
    $expr_status = $_GET["expr_status"];
    switch ($expr_status) {
        case "expr_all":
            break;
        case "expr_notStart":
            $sql_userCoupon .= " AND c.start_time > ?";
            array_push($params, $now);
            $types .= "s";
            break;
        case "expr_canUse":
            $sql_userCoupon .= " AND c.start_time <= ? AND (c.end_date >= ? OR c.permanent = 1)";
            $params = array_merge($params, [$now, $now]);
            $types .= str_repeat("s", 2);
            break;
        case "expr_exprd":
            $sql_userCoupon .= " AND c.end_date < ? AND c.permanent = 0";
            array_push($params, $now);
            $types .= "s";
            break;
    }
}

// 是否已用條件
if (!isset($_GET["used_status"])) {
    $used_status = "used_all";
} else {
    $used_status = $_GET["used_status"];
    switch ($used_status) {
        case "used_all":
            break;
        case "used_notUsed":
            $sql_userCoupon .= ' AND uc.used_status = "FALSE"';
            break;
        case "used_used":
            $sql_userCoupon .= ' AND uc.used_status = "TRUE"';
            break;
    }
}

// 單張優惠券可用狀態條件
if (isset($_GET["enabled_status"]) && $_GET["enabled_status"] !== "") {
    $enabled_status = $_GET["enabled_status"];
    $sql_userCoupon .= " AND uc.enabled = ?";
    array_push($params, $enabled_status);
    $types .= "i";
} else {
    $enabled_status = "";
}

// 排序條件
if (!isset($_GET["sort"])) {
    $sort = "uc.users_coupon_id";
} else {
    $sort = $_GET["sort"];
    switch ($sort) {
        case "id_asc":
            $sql_userCoupon .= " ORDER BY uc.users_coupon_id ASC";
            break;
        case "discount_asc":
            $sql_userCoupon .= " ORDER BY c.discount_rate ASC";
            break;
        case "discount_desc":
            $sql_userCoupon .= " ORDER BY c.discount_rate DESC";
            break;
        case "start_asc":
            $sql_userCoupon .= " ORDER BY c.start_time ASC";
            break;
        case "start_desc":
            $sql_userCoupon .= " ORDER BY c.start_time DESC";
            break;
        case "end_asc":
            $sql_userCoupon .= " ORDER BY c.end_date ASC";
            break;
        case "end_desc":
            $sql_userCoupon .= " ORDER BY c.end_date DESC";
            break;
        case "received_asc":
            $sql_userCoupon .= " ORDER BY uc.received_time ASC";
            break;
        case "received_desc":
            $sql_userCoupon .= " ORDER BY uc.received_time DESC";
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
$total_stmt = $conn->prepare($sql_userCoupon);
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
$sql_userCoupon .= " LIMIT ?, ?";
array_push($params, $start_item, $per_page);
$types .= "ii";

// 執行第二次查詢資料
$userCoupon_stmt = $conn->prepare($sql_userCoupon);
if (!empty($params)) {
    $userCoupon_stmt->bind_param($types, ...$params);
}
$userCoupon_stmt->execute();
$userCoupon_result = $userCoupon_stmt->get_result();
$userCoupon_rows = $userCoupon_result->fetch_all(MYSQLI_ASSOC);

// 額外查詢users資料表中的資料
$user_sql = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_row = $user_result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $user_row['name'] ?>的優惠券</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>
        .coupon-input-bar{
            width: 50px;
        }
        p{
            margin-bottom: auto;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <h2 class="d-flex justify-content-center mb-5">
                <a href="./user.php?user_id=<?= $user_id ?>" class="text-decoration-none" title="查看使用者資料">
                    <?php echo $user_row['name'] ?>
                </a>
                的優惠券
            </h2>

            <!-- 篩選器 -->
            <div class="py-2 myFilter mb-3">
                <form action="">
                    <div class="input-group">
                        <input type="hidden" class="form-control" name="user_id" value="<?= $user_id ?>">
                        <input type="search" class="form-control" name="search" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>" placeholder="搜尋優惠券名稱">
                        <select class="form-select" aria-label="Default select example" name="sort">
                            <option <?php echo $sort == "id_asc" ? "selected" : ""; ?> value="id_asc">依id排序(預設)</option>
                            <option <?php echo $sort == "received_asc" ? "selected" : ""; ?> value="received_asc">發券日由先至後</option>
                            <option <?php echo $sort == "received_desc" ? "selected" : ""; ?> value="received_desc">發券日由後至先</option>
                            <option <?php echo $sort == "discount_desc" ? "selected" : ""; ?> value="discount_desc">折扣由低至高</option>
                            <option <?php echo $sort == "discount_asc" ? "selected" : ""; ?> value="discount_asc">折扣由高至低</option>
                            <option <?php echo $sort == "start_asc" ? "selected" : ""; ?> value="start_asc">啟用日由先至後</option>
                            <option <?php echo $sort == "start_desc" ? "selected" : ""; ?> value="start_desc">啟用日由後至先</option>
                            <option <?php echo $sort == "end_asc" ? "selected" : ""; ?> value="end_asc">到期日由先至後</option>
                            <option <?php echo $sort == "end_desc" ? "selected" : ""; ?> value="end_desc">到期日由後至先</option>
                        </select>
                        <select class="form-select" aria-label="Default select example" name="act_status">
                            <option <?php echo is_null($act_status) ? "selected" : ""; ?> value=""><span class="text-secondary">不限優惠活動狀態</span></option>
                            <option <?php echo $act_status == "1" ? "selected" : ""; ?> value="1"><span class="text-success">啟用中</span></option>
                            <option <?php echo $act_status == "0" ? "selected" : ""; ?> value="0"><span class="text-danger">停用中</span></option>
                        </select>
                        <select class="form-select" aria-label="Default select example" name="expr_status">
                            <option <?php echo $expr_status == "expr_all" ? "selected" : ""; ?> value="expr_all"><span class="text-secondary">不限效期狀態</span></option>
                            <option <?php echo $expr_status == "expr_notStart" ? "selected" : ""; ?> value="expr_notStart"><span class="text-success">尚未開始</span></option>
                            <option <?php echo $expr_status == "expr_canUse" ? "selected" : ""; ?> value="expr_canUse"><span class="text-success">效期內</span></option>
                            <option <?php echo $expr_status == "expr_exprd" ? "selected" : ""; ?> value="expr_exprd"><span class="text-danger">已過期</span></option>
                        </select>
                        <select class="form-select" aria-label="Default select example" name="used_status">
                            <option <?php echo $used_status == "used_all" ? "selected" : ""; ?> value="used_all"><span class="text-secondary">不限使用狀態</span></option>
                            <option <?php echo $used_status == "used_notUsed" ? "selected" : ""; ?> value="used_notUsed"><span class="text-success">未使用</span></option>
                            <option <?php echo $used_status == "used_used" ? "selected" : ""; ?> value="used_used"><span class="text-danger">已使用</span></option>
                        </select>
                        <select class="form-select" aria-label="Default select example" name="enabled_status">
                            <option <?php echo is_null($enabled_status) ? "selected" : ""; ?> value=""><span class="text-secondary">不限可用狀態</span></option>
                            <option <?php echo $enabled_status == "1" ? "selected" : ""; ?> value="1"><span class="text-success">可使用</span></option>
                            <option <?php echo $enabled_status == "0" ? "selected" : ""; ?> value="0"><span class="text-danger">被禁用</span></option>
                        </select>
                        <button class="btn btn-custom" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>

            <!-- 設定一頁幾筆資料 -->
            <div class="my-2 d-flex justify-content-start">
                <form class="d-flex align-items-center">
                    <span>共有<?= $total_rows ?>筆資料，</span>
                    <span>每頁</span>
                    <input type="text" class="form-control coupon-input-bar" name="per_page" id="perPageInput" value="<?= $per_page ?>" placeholder="">
                    <span>筆</span>
                    <a class="btn mx-1 fs-6 fw-bold" id="perPageBtn">GO</a>
                </form>
            </div>

            <!-- 顯示資料的表格 -->
            <table class="table">
                <thead class="table-pink">
                    <tr>
                        <th class="text-center">優惠券編號</th>
                        <th class="text-center">優惠券id</th>
                        <th class="text-center">優惠券名稱</th>
                        <th class="text-center">折扣率</th>
                        <th class="text-center">使用期限</th>
                        <th></th>
                        <th class="text-center">發券日期</th>
                        <th class="text-center">使用日期</th>
                        <th class="text-center">可用狀態</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userCoupon_rows as $userCoupon_row) : ?>
                        <tr>
                            <td class="text-center align-middle">
                                <?php echo $userCoupon_row['users_coupon_id'];?>
                            </td>
                            <td class="text-center align-middle">
                                <?php echo $userCoupon_row['coupon_id'];?>
                            </td>
                            <td class="text-center align-middle">
                                <?php 
                                echo $userCoupon_row['coupon_name'];
                                echo '<br>';
                                echo $userCoupon_row['activation'] == 0 ?'<span class="text-danger">（本優惠活動已暫停）</span>' : '';
                                ?>
                            </td>
                            <td class="text-center align-middle">
                                <?php echo ($userCoupon_row['discount_rate'])*100;?>
                                %
                            </td>
                            <td class="text-center align-middle">
                                <?php echo $userCoupon_row['start_time'];?>
                                ~
                                <?php 
                                if($userCoupon_row['end_date'] == NULL){
                                    echo '<span class="text-secondary">無限制</span>';
                                }else{
                                    echo $userCoupon_row['end_date'];
                                }
                                ?>
                            </td>
                            <td class="text-center align-middle">
                                <?php
                                    if($userCoupon_row['start_time'] > $now){
                                        echo '<p class="text-secondary">尚未開始</p>';
                                    }elseif($userCoupon_row['start_time'] <= $now && ($now <= $userCoupon_row['end_date'] || $userCoupon_row['permanent'] == 1)){
                                        echo '<p class="text-success">效期內</p>';
                                    }elseif($userCoupon_row['end_date'] <= $now && $userCoupon_row['permanent'] == 0){
                                        echo '<p class="text-danger">已過期</p>';
                                    }
                                ?>
                            </td>
                            <td class="text-center align-middle">
                                <?php 
                                echo $userCoupon_row['recieved_time'];
                                ?>
                            </td>
                            <td class="text-center align-middle">
                                <?php
                                if($userCoupon_row['used_status'] == "TRUE"){
                                    echo 
                                    "<span class='text-danger'>已使用</span>, 使用時間為: ".
                                    "<br>".
                                    $userCoupon_row['used_time'].
                                    "<br>".
                                    "<a href='./order-details.php?order_id=".
                                    $userCoupon_row['order_id'].
                                    "'>查看訂單資訊</a>";
                                }else{
                                    echo "<span class='text-success'>未使用</span>";
                                }
                                ?>
                            </td>
                            <td class="text-center align-middle">
                                <p class="enabled_status-text <?= $userCoupon_row['enabled'] == 1 ? 'text-success' : 'text-danger'; ?>" data-id="<?= $userCoupon_row['users_coupon_id']; ?>">
                                    <?= $userCoupon_row['enabled'] == 1 ? '可使用' : '被禁用'; ?>
                                </p>
                                
                            </td>
                            <td>
                                <button class="btn enabled_switch <?= $userCoupon_row['enabled'] == 1 ? 'btn-danger' : 'btn-success'; ?>" data-id="<?= $userCoupon_row['users_coupon_id']; ?>" data-is_enabled="<?= $userCoupon_row['enabled'] ?>">
                                    <?= $userCoupon_row['enabled'] == 1? '禁用此張券' : '解禁此張券' ?>
                                </button>
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
                            <a class="page-link btn-custom" href="<?= rebuild_url(['page' => $i]) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>

    </div>

    <!-- 錯誤失敗訊息 -->
    <!-- 有時間可以用中介頁面來避免使用GET -->
    <!-- 有時間可以用別的設計取代alert -->
    <?php
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    ?>

    <!-- Javascript 寫這裡 -->
    <?php include("../js.php"); ?>
    <script>
        
        // 用AJAX動態更改單張優惠券的可用狀態
        const enabled_switches = document.querySelectorAll('.enabled_switch');

        enabled_switches.forEach(function(enabled_switch) {
            enabled_switch.addEventListener('click', function() {
            let userCouponId = this.getAttribute('data-id');
            let newEnabledStatus = this.getAttribute('data-is_enabled') == 0 ? 1 : 0;
                    
                $.ajax({
                    method: "POST",
                    url: "../api/doUsersCouponEnabledSwitch.php",
                    dataType: "json",
                    data: {
                        id: userCouponId,
                        newEnabledStatus: newEnabledStatus
                    }
                })
                .done(function(response) {
                    if (response.success) {
                        console.log("單張優惠券可用狀態更改成功，user-coupon ID: " + userCouponId);

                        let statusTextElement = document.querySelector(`.enabled_status-text[data-id='${userCouponId}']`);
                        let thisSwitchBtn = document.querySelector(`.enabled_switch[data-id='${userCouponId}']`);
                        if (statusTextElement) {
                            if (newEnabledStatus === 1) {
                                statusTextElement.textContent = '可使用';
                                statusTextElement.classList.remove('text-danger');
                                statusTextElement.classList.add('text-success');
                            } else {
                                statusTextElement.textContent = '被禁用';
                                statusTextElement.classList.remove('text-success');
                                statusTextElement.classList.add('text-danger');
                            }
                        }
                        if (thisSwitchBtn) {
                            if (newEnabledStatus === 1) {
                                thisSwitchBtn.textContent = '禁用此張券';
                                thisSwitchBtn.classList.remove('btn-success');
                                thisSwitchBtn.classList.add('btn-danger');
                                thisSwitchBtn.setAttribute('data-is_enabled', 1);
                            } else {
                                thisSwitchBtn.textContent = '解禁此張券';
                                thisSwitchBtn.classList.remove('btn-danger');
                                thisSwitchBtn.classList.add('btn-success');
                                thisSwitchBtn.setAttribute('data-is_enabled', 0);
                            }
                        }
                    } else {
                        console.log("單張優惠券啟用狀態切換失敗，user-coupon ID: " + userCouponId);
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
            let baseUrl = '<?= rebuild_url(["per_page" => null]) ?>';

            // 再把 per_page 加到網址中
            let newUrl = baseUrl + '&per_page=' + perPageValue;

            // 跳轉到新網址
            window.location.href = newUrl;
        });
    </script>
</body>

</html>
