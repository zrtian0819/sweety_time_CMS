<?php

require_once("../db_connect.php");

// 抓取現在時間以做判斷和篩選
$now = date("Y-m-d H:i:s");

// 設定SQL查詢語句樣板 
$sql = "SELECT * FROM coupon WHERE 1=1"; // 利用永遠為真的 `1=1` 以利加後續條件
$params = []; // 用來裝條件
$types = ""; // 用來紀錄條件型別

// 搜尋條件
if (isset($_GET["search"]) && !empty($_GET["search"])) {
    $search = "%" . $_GET["search"] . "%";
    $sql .= " AND name LIKE ?";
    array_push($params, $search);
    $types .= "s";
}

// 啟用狀態條件
if (isset($_GET["act_status"]) && $_GET["act_status"] !== "") {
    $act_status = $_GET["act_status"];
    $sql .= " AND activation = ?";
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
            $sql .= " AND start_time > ?";
            array_push($params, $now);
            $types .= "s";
            break;
        case "expr_canUse":
            $sql .= " AND start_time <= ? AND end_date >= ?";
            $params = array_merge($params, [$now, $now]);
            $types .= str_repeat("s", 2);
            break;
        case "expr_notStart":
            $sql .= " AND end_date < ?";
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
            $sql .= " ORDER BY coupon_id ASC";
            break;
        case "discount_asc":
            $sql .= " ORDER BY discount_rate ASC";
            break;
        case "discount_desc":
            $sql .= " ORDER BY discount_rate DESC";
            break;
        case "start_asc":
            $sql .= " ORDER BY start_time ASC";
            break;
        case "start_desc":
            $sql .= " ORDER BY start_time DESC";
            break;
        case "end_asc":
            $sql .= " ORDER BY end_date ASC";
            break;
        case "end_desc":
            $sql .= " ORDER BY end_date DESC";
            break;
        case "created_asc":
            $sql .= " ORDER BY created_at ASC";
            break;
        case "created_desc":
            $sql .= " ORDER BY created_at DESC";
            break;
    }
}

// 準備撈資料
$stmt = $conn->prepare($sql);

// 將參數化的搜尋條件取代佔位符
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

// 執行撈資料 & 取得結果
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

// $stmt->close();
// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>優惠券種類列表</title>
    <?php include("../css/css_Joe.php"); ?>
    <style>

    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <div class="">
                <a href="./coupon-home.php">優惠券管理</a>>
                <a href="./coupon-list.php">優惠券種類一覽</a>
            </div>
            <hr>
            <div class="py-2">
                <form action="">
                    <div class="input-group">
                        <input type="search" class="form-control" name="search" value="<?php echo isset($_GET["search"]) ? $_GET["search"] : "" ?>" placeholder="搜尋優惠券名稱">
                        <select class="form-select" aria-label="Default select example" name="sort">
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
                        <select class="form-select" aria-label="Default select example" name="act_status">
                            <option <?php echo is_null($act_status) ? "selected" : ""; ?> value=""><span class="text-secondery">不限啟用狀態</span></option>
                            <option <?php echo $act_status == "1" ? "selected" : ""; ?> value="1"><span class="text-success">啟用中</span></option>
                            <option <?php echo $act_status == "0" ? "selected" : ""; ?> value="0"><span class="text-danger">停用中</span></option>
                        </select>
                        <select class="form-select" aria-label="Default select example" name="expr_status">
                            <option <?php echo $expr_status == "expr_all" ? "selected" : ""; ?> value="expr_all"><span class="text-secondery">不限效期狀態</span></option>
                            <option <?php echo $expr_status == "expr_notStart" ? "selected" : ""; ?> value="expr_notStart"><span class="text-success">尚未開始</span></option>
                            <option <?php echo $expr_status == "expr_canUse" ? "selected" : ""; ?> value="expr_canUse"><span class="text-success">效期內</span></option>
                            <option <?php echo $expr_status == "expr_exprd" ? "selected" : ""; ?> value="expr_exprd"><span class="text-danger">已過期</span></option>
                        </select>
                        <button class="btn neumorphic" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
            <hr>
            <div class="">
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>優惠券種類編號<br>(coupon_id)</th>
                        <th>優惠券名稱<br>(name)</th>
                        <th>折扣率<br>(discount_rate)</th>
                        <th>啟用日期<br>(start_time)</th>
                        <th>到期日<br>(end_date)</th>
                        <th>啟用狀態<br>(activation)</th>
                        <th>創建日期<br>(created_at)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <td><?= $row['coupon_id'];?></td>
                            <td><?= $row['name'];?></td>
                            <td><?= $row['discount_rate'];?></td>
                            <td><?= $row['start_time'];?></td>
                            <td><?= $row['end_date'];?></td>
                            <td class="d-flex">
                                <p class="activ_status-text <?= $row['activation'] == 1 ? 'text-success' : 'text-danger'; ?>" data-id="<?= $row['coupon_id']; ?>">
                                    <?= $row['activation'] == 1 ? '啟用中' : '停用中'; ?>
                                </p>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input activ_switch" type="checkbox" data-id="<?= $row['coupon_id']; ?>" <?php echo $row['activation'] == 1 ? "checked" : ""; ?>>
                                </div>
                                <?php if ($row['start_time'] > $now) : ?>
                                    <p class="text-secondary">尚未開始</p>
                                <?php elseif ($row['start_time'] <= $now && $now <= $row['end_date']) : ?>
                                    <p class="text-success">效期內</p>
                                <?php elseif ($row['end_date'] < $now) : ?>
                                    <p class="text-danger">已過期</p>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['created_at'];?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Javascript 寫這裡 -->
    <?php include("../js.php"); ?>
    <script>
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
                        console.log("狀態已切換，Coupon ID: " + couponId);

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
    </script>
</body>

</html>