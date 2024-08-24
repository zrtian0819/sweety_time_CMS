<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
include("../function/rebuildURL.php");

$coupon_id = $_GET["coupon_id"];
$recieved_time = $_GET["recieved_time"];


$sql = "SELECT uc.coupon_id, c.name AS coupon_name, uc.recieved_time, GROUP_CONCAT(uc.user_id ORDER BY uc.user_id ASC) AS user_ids
        FROM users_coupon uc
        INNER JOIN coupon c ON uc.coupon_id = c.coupon_id
        WHERE c.coupon_id = ? AND uc.recieved_time = ?";

// 將 同coupon_id 且 同發送時間 視為一次發送事件
$sql .= " GROUP BY uc.coupon_id, uc.recieved_time"; //GROUP BY 要寫在 WHERE 之後

// 撈coupon 和 users-coupon 的資料
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $coupon_id, $recieved_time);

$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
// print_r($row);

// 計算 收到優惠券的人數
$user_ids_array = explode(',', $row['user_ids']);
$userAmount = count($user_ids_array);

// 撈users 的資料
$user_ids_str = $row['user_ids'];
$sql_users = "SELECT * 
            FROM users 
            WHERE user_id IN ($user_ids_str);";
            // ORDER BY FIELD(user_id, $userIdStr);"; 這段可使排序依 $userIdListStr原始的順序排序
$stmt_users = $conn->prepare($sql_users);
$stmt_users->execute();
$result_users = $stmt_users->get_result();
$rows_users = $result_users->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>訂單資訊</title>
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
            <!-- 發送事件的資訊 -->
            <h2 class="mb-4">優惠券發送歷史</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>優惠券id</th>
                        <th>優惠券名稱</th>
                        <th>發送人數</th>
                        <th>發送時間<br></th>
                    </tr>
                </thead>
                <tbody>
                        <tr>
                            <td><?php echo $row["coupon_id"];?></td>
                            <td><?php echo $row["coupon_name"];?></td>
                            <td>
                                <?php echo $userAmount;?>人
                            </td>
                            <td><?php echo $row["recieved_time"];?></td>
                        </tr>
                </tbody>
            </table>

            <!-- 發券名單的表格 -->
            <hr>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>使用者id</th>
                        <th>使用者名稱</th>
                        <th>帳號</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows_users as $row_user) : ?>
                        <tr>
                            <td>
                                <?php echo $row_user['user_id'];?>
                            </td>
                            <td>
                                <?php echo $row_user['name'];?>
                            </td>
                            <td>
                                <?php echo $row_user['account'];?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
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

    </script>
</body>

</html>