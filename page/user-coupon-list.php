<?php

require_once("../db_connect.php");
$user_id = $_GET["user_id"];
$now = date("Y-m-d");

// 取得users資料表中的資料
$user_sql = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt -> get_result();
$user_row = $user_result -> fetch_assoc();

// 取得users_coupon資料表中的資料
$userCoupon_sql = "SELECT * FROM users_coupon WHERE user_id = ?";
$userCoupon_stmt = $conn->prepare($userCoupon_sql);
$userCoupon_stmt->bind_param("i", $user_id);
$userCoupon_stmt->execute();
$userCoupon_result = $userCoupon_stmt -> get_result();
$userCoupon_rows = $userCoupon_result -> fetch_all(MYSQLI_ASSOC);

// 取得coupon資料表中的資料
$coupon_sql = "SELECT * FROM coupon";
$coupon_stmt = $conn->prepare($coupon_sql);
$coupon_stmt->execute();
$coupon_result = $coupon_stmt -> get_result();
$coupon_rows = $coupon_result -> fetch_all(MYSQLI_ASSOC);

// 將需要的資料存入關聯式多維陣列
$coupon_data = [];

foreach ($coupon_rows as $coupon_row) {
    $coupon_data[$coupon_row['coupon_id']] = [
        'coupon_name' => $coupon_row['name'],
        'start_time' => $coupon_row['start_time'],
        'end_date' => $coupon_row['end_date'],
        'discount_rate' => $coupon_row['discount_rate'],

    ];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php  ?></title>
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
                <a href="./users.php">使用者管理</a>>
                <a href="./user-coupon-list.php?user_id=<?= $user_id ?>>"><?php echo $user_row['name'] ?>的優惠券</a>
            </div>
            <hr>

            <h2>
                <a href="./user.php?user_id=<?= $user_id ?>" class="text-decoration-none">
                    <?php echo $user_row['name'] ?>
                </a>
                的優惠券
            </h2>

            <!-- 顯示資料的表格 -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>優惠券編號<br>(user_coupon_id)</th>
                        <th>優惠券id<br>(coupon_id)</th>
                        <th>優惠券名稱<br>(name)</th>
                        <th>折扣率<br>(used_status)</th>
                        <th>啟用日<br>(start_time)</th>
                        <th>到期日<br>(end_date)</th>
                        <th>發券日期<br>(recieved_time)</th>
                        <th>可用狀態(enabled)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userCoupon_rows as $userCoupon_row) : ?>
                        <tr>
                            <td>
                                <?php echo $userCoupon_row['users_coupon_id'];?>
                            </td>
                            <td>
                                <?php echo $userCoupon_row['coupon_id'];?>
                            </td>
                            <td>
                                <?php echo $coupon_data[$userCoupon_row['coupon_id']]['coupon_name'];?>
                            </td>
                            <td>
                                <?php echo ($coupon_data[$userCoupon_row['coupon_id']]['discount_rate'])*100;?>
                                %
                            </td>
                            <td>
                                <?php echo $coupon_data[$userCoupon_row['coupon_id']]['start_time'];?>
                            </td>
                            <td>
                                <?php 
                                if($coupon_data[$userCoupon_row['coupon_id']]['end_date'] == NULL){
                                    echo "永久有效";
                                }else{
                                    echo $coupon_data[$userCoupon_row['coupon_id']]['end_date'];
                                }
                                
                                ?>
                            </td>
                            <td>
                                <?php 
                                echo $userCoupon_row['recieved_time'];
                                if($userCoupon_row['used_status'] == "TRUE"){
                                    echo "已使用, 使用時間為: ". $userCoupon_row['used_time'];
                                }
                                if($userCoupon_row['used_status'] == "FALSE" && $coupon_data[$userCoupon_row['coupon_id']]['end_date'] < $now){
                                    echo '<span class="text-danger">已過期</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <p class="enabled_status-text <?= $userCoupon_row['enabled'] == 1 ? 'text-success' : 'text-danger'; ?>" data-id="<?= $userCoupon_row['users_coupon_id']; ?>">
                                    <?= $userCoupon_row['enabled'] == 1 ? '可使用' : '被禁用'; ?>
                                </p>
                                <button class="btn enabled_switch <?= $userCoupon_row['enabled'] == 1 ? 'btn-danger' : 'btn-success'; ?>" data-id="<?= $userCoupon_row['users_coupon_id']; ?>" data-is_enabled="<?= $userCoupon_row['enabled'] ?>">
                                    <?= $userCoupon_row['enabled'] == 1? '禁用此張券' : '解禁此張券' ?>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>

    </div>

    <!-- Javascript 寫這裡 -->
    <?php include("../js.php"); ?>
    <script>
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
    </script>
</body>

</html>