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

// 準備撈資料
$stmt_all = $conn -> prepare($sql_all);

// 將參數化的搜尋&篩選條件取代佔位符
if (!empty($params)) {
    $stmt_all -> bind_param($types, ...$params);
}

// 執行撈資料 & 取得結果
$stmt_all->execute();
$result_all = $stmt_all -> get_result();
$rows_all = $result_all->fetch_all(MYSQLI_ASSOC);

// 計算頁數
$per_page = isset($_GET["per_page"])? $_GET["per_page"] : 10;
$total_rows = count($rows_all);
$total_pages = ceil($total_rows / $per_page);

// 依據目前頁碼來撈第二次資料
$current_page = isset($_GET["page"])? $_GET["page"] : 1;
$sql_page = $sql_all . " LIMIT ?, ?";

// 將分頁參數加入到參數陣列中
$start_item = ($current_page - 1) * $per_page;
array_push($params, $start_item, $per_page);
$types .= "ii";

$stmt_page = $conn->prepare($sql_page);

// 綁定所有參數
$stmt_page->bind_param($types, ...$params);

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

            <!-- 篩選器 -->
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

            <div class="my-2">
                <form action="" class="d-flex align-items-center">
                    <span>每頁</span>
                    <input type="text" class="form-control coupon-input-bar" name="per_page" value="<?= $per_page ?>" placeholder="">
                    <span>筆</span>
                    <button button type = "submit" class="btn neumorphic mx-2">GO</button>
                </form>
            </div>

            <!-- 顯示資料的表格 -->
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
                    <?php foreach ($rows_page as $row) : ?>
                        <tr>
                            <td><?= $row['coupon_id'];?></td>
                            <td><?= $row['name'];?></td>
                            <td><?= $row['discount_rate'];?></td>
                            <td><?= $row['start_time'];?></td>
                            <td>
                                <?php if(is_null($row['end_date'])) : ?>
                                    <p>永久有效</p>    
                                <?php else :?>
                                    <?= $row['end_date'];?>
                                <?php endif;?>
                            </td>
                            <td class="d-flex">
                                <p class="activ_status-text <?= $row['activation'] == 1 ? 'text-success' : 'text-danger'; ?>" data-id="<?= $row['coupon_id']; ?>">
                                    <?= $row['activation'] == 1 ? '啟用中' : '停用中'; ?>
                                </p>
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input activ_switch" type="checkbox" data-id="<?= $row['coupon_id']; ?>" <?php echo $row['activation'] == 1 ? "checked" : ""; ?>>
                                </div>
                                <?php if ($row['start_time'] > $now) : ?>
                                    <p class="text-secondary">尚未開始</p>
                                <?php elseif ((is_null($row['end_date']) && $row['permanent'] == 1 ) || ($row['start_time'] <= $now && $now <= $row['end_date'])) : ?>
                                    <p class="text-success">效期內</p>
                                <?php elseif (!is_null($row['end_date']) && $row['end_date'] < $now) : ?>
                                    <p class="text-danger">已過期</p>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= $row['created_at'];?>
                                <a href="./coupon-edit.php?coupon_id=<?= $row['coupon_id'] ?>">編輯</a>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
            <!-- 換頁按鈕 -->

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php if ($current_page == $i) echo "active"; ?>">
                                <a class="page-link" href="<?= rebuild_url(['page' => $i]) ?>">
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
        if (isset($_GET['message'])) {
            $message = htmlspecialchars($_GET['message']);
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    ?>


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
    </script>
</body>

</html>