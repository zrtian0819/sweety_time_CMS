<?php

require_once("../db_connect.php");
include("../function/login_status_inspect.php");
$coupon_id = $_GET["coupon_id"];


// user資料的name搜尋條件
// user資料的account搜尋條件
// user資料的生日條件(年:XX年以前或以後 月:XX月 日:XX日)
// user資料的註冊時間條件(XX日期 以前或以後)

// 設定SQL查詢語句樣板 
$sql_users = "SELECT * FROM users WHERE activation = 1 AND role = 'user'";

// 篩選條件
$params = []; // 用來裝條件
$types = ""; // 用來紀錄條件型別

// 搜尋會員名稱
if (isset($_GET["search_n"]) && !empty($_GET["search_n"])) {
    $search_n = "%" . $_GET["search_n"] . "%";
    $sql_users .= " AND name LIKE ?";
    array_push($params, $search_n);
    $types .= "s";
}

// 搜尋會員帳號
if (isset($_GET["search_a"]) && !empty($_GET["search_a"])) {
    $search_a = "%" . $_GET["search_a"] . "%";
    $sql_users .= " AND account LIKE ?";
    array_push($params, $search_a);
    $types .= "s";
}

// 篩選會員生日月份
if (isset($_GET["pick_month"]) && !empty($_GET["pick_month"])) {
    $selected_months = $_GET["pick_month"]; // 假設這是從多選下拉式選單傳來的值的陣列

    if (is_array($selected_months) && count($selected_months) > 0) {
        $placeholders = implode(',', array_fill(0, count($selected_months), '?'));
        $sql_users .= " AND MONTH(birthday) IN ($placeholders)";
        foreach ($selected_months as $month) {
            array_push($params, $month);
            $types .= "i"; // 月份是整數型別
        }
    }
}

// 篩選註冊日期
if(!isset($_GET["signUp_compair"])){
    $signUp_compair = "before";
}else{
    $signUp_compair = $_GET["signUp_compair"];
}
if (isset($_GET["signUp_date"]) && !empty($_GET["signUp_date"]) && isset($_GET["signUp_compair"]) && !empty($_GET["signUp_compair"])) {
    $signUp_date = $_GET["signUp_date"];
    if($signUp_compair == "before"){
        $sql_users .= " AND sign_up_time < ?";
    }
    if($signUp_compair == "after"){
        $sql_users .= " AND sign_up_time > ?";
    }
    array_push($params, $signUp_date);
    $types .= "s";
} 

// 排序條件
if (!isset($_GET["sort"])){
    $sort = "id_asc";
}else{
    $sort = $_GET["sort"];
    switch ($sort) {
        case "id_asc":
            $sql_users .= " ORDER BY user_id ASC";
            break;
        case "id_desc":
            $sql_users .= " ORDER BY user_id DESC";
            break;
        case "birthday_asc":
            $sql_users .= " ORDER BY birthday ASC";
            break;
        case "birthday_desc":
            $sql_users .= " ORDER BY birthday DESC";
            break;
        case "signUp_asc":
            $sql_users .= " ORDER BY sign_up_time ASC";
            break;
        case "signUp_desc":
            $sql_users .= " ORDER BY sign_up_time DESC";
            break;
    }
}

// 撈users資料
$stmt_users = $conn->prepare($sql_users);
if (!empty($params)) {
    $stmt_users->bind_param($types, ...$params);
}
$stmt_users->execute();
$result_users = $stmt_users->get_result();
$rows_user = $result_users->fetch_all(MYSQLI_ASSOC);


// 撈coupon資料
$sql_coupons = "SELECT * FROM coupon WHERE coupon_id = ?";
$stmt_coupons = $conn->prepare($sql_coupons);
$stmt_coupons->bind_param("i", $coupon_id);
$stmt_coupons->execute();
$result_coupons = $stmt_coupons->get_result();
$row_coupon = $result_coupons->fetch_assoc();

?>
<!doctype html>
<html lang="en">

<head>
    <title>推送優惠券</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css/css_Joe.php"); ?>
    <style>
        .user-btn {
            width: 100px;
        }

        .user-search {
            width: 200px;
        }

        /* ---------------------------------------------下拉式選單相關的樣式 -----------------------------------------------*/

        /* The container div - needed to position the dropdown content */
        .dropdown {
        height: 100%;
        position: relative;
        display: inline-block;
        }

        /* Dropdown Button */
        .dropbtn{
            height: 100%; 
        }

        /* Dropdown Content (Hidden by Default) */
        /* 修改下拉選單的樣式 */
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            left: -600px; /* 保持與 selectedValues 的左邊對齊 */
            top: 100%; /* 緊貼在 selectedValues 區域下方 */
            margin-top: 5px; /* 稍微向下偏移一點 */
        }


        /* Links inside the dropdown */
        .dropdown-content label {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        }

        /* Change color of dropdown links on hover */
        .dropdown-content label:hover {background-color: #f1f1f1}

        /* Show the dropdown menu when the button is clicked */
        .show {display: block;}

        /* Added style for the selected values display area */
        #selectedValues {
        width: 600px;
        padding: 10px;
        border: 1px solid #ddd;
        height: 100%;
        font-size: 16px;
        cursor: pointer;
        color: #000;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <h2>推送「 <?= $row_coupon["name"] ?> 」(id = <?= $row_coupon["coupon_id"] ?>)<?= $row_coupon["activation"] == 0 ? "<span class='text-danger'>(注意：此優惠活動為停用狀態)</span>" : "" ; ?></h2>
            <hr>

            <!-- 篩選器 -->
            <div class="py-2">
                <form action="" class ="d-flex">
                    <div class="input-group">

                        <!-- 確保coupon_id不會跑掉 -->
                        <input type="hidden" name="coupon_id" value="<?= $coupon_id ?>">

                        <!-- 搜尋條件 -->
                        <input type="search" class="form-control" name="search_n" value="<?php echo isset($_GET["search_n"]) ? $_GET["search_n"] : "" ?>" placeholder="搜尋會員名稱">
                        <input type="search" class="form-control" name="search_a" value="<?php echo isset($_GET["search_a"]) ? $_GET["search_a"] : "" ?>" placeholder="搜尋帳號">

                        <!-- 註冊時間條件 -->
                        <div class="d-flex">
                            <input type="date" class="form-control" name="signUp_date" value="<?php echo isset($_GET["signUp_date"]) ? $_GET["signUp_date"] : "" ?>">
                            <select class="form-select" aria-label="Default select example" name="signUp_compair">
                                <option <?php echo $signUp_compair == "before" ? "selected" : ""; ?> value="before">前註冊</option>
                                <option <?php echo $signUp_compair == "after" ? "selected" : ""; ?> value="after">後註冊</option>
                            </select>
                        </div>

                        <!-- 排序條件 -->
                        <select class="form-select" aria-label="Default select example" name="sort">
                            <option <?php echo $sort == "id_asc" ? "selected" : ""; ?> value="id_asc">依id排序（少⭢多）</option>
                            <option <?php echo $sort == "id_desc" ? "selected" : ""; ?> value="id_desc">依id排序（少⭢多）</option>
                            <option <?php echo $sort == "birthday_asc" ? "selected" : ""; ?> value="birthday_asc">依生日（先⭢後）</option>
                            <option <?php echo $sort == "birthday_desc" ? "selected" : ""; ?> value="birthday_desc">依生日（後⭢先）</option>
                            <option <?php echo $sort == "signUp_asc" ? "selected" : ""; ?> value="signUp_asc">依註冊時間（先⭢後）</option>
                            <option <?php echo $sort == "signUp_desc" ? "selected" : ""; ?> value="signUp_desc">依註冊時間（後⭢先）</option>
                        </select>
                        
                        <!-- 自定義的下拉式多選選單 -->
                            <div class="d-flex align-items-center">
                                <!-- 顯示已勾選選項值的區域 -->
                                <div class="d-flex align-items-center" id="selectedValues">
                                    生日：所有月份
                                </div>
                                <!-- 下拉選單的內容 -->
                                <div class="dropdown" style="position: relative;">
                                    <div id="myDropdown" class="dropdown-content">
                                        <?php
                                            $selected_months = isset($_GET["pick_month"]) ? $_GET["pick_month"] : range(1, 12);

                                            if (!is_array($selected_months)) {
                                                $selected_months = [$selected_months];
                                            }

                                            for ($i = 1; $i <= 12; $i++) {
                                                $checked = in_array($i, $selected_months) ? "checked" : "";
                                                echo '<label><input type="checkbox" value="' . $i . '" name="pick_month[]" onchange="updateSelectedValues()" ' . $checked . '> ' . $i . '月</label>';
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <button class="btn neumorphic" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>
            </div>
            <hr>
            <div class="">
            </div>

            <!-- 顯示users資料的表格 -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>會員編號<br>(user_id)</th>
                        <th>會員名稱<br>(name)</th>
                        <th>帳號<br>(account)</th>
                        <th>生日<br>(birthday)</th>
                        <th>註冊時間<br>(sign_up_time)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows_user as $row_user) : ?>
                        <tr>
                            <td><?= $row_user['user_id'];?></td>
                            <td><?= $row_user['name'];?></td>
                            <td><?= $row_user['account'];?></td>
                            <td><?= $row_user['birthday'];?></td>
                            <td><?= $row_user['sign_up_time'];?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
    <script>
        window.onload = function() {
            updateSelectedValues(); // 只更新顯示文字，不進行預設勾選
        };

        function toggleDropdown() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        // 點擊顯示已選擇的值的區域後顯示/隱藏下拉選單
        document.querySelector('#selectedValues').addEventListener('click', function(event) {
            event.stopPropagation();
            toggleDropdown();
        });

        // 如果使用者點擊了下拉選單以外的區域，收起選單
        window.onclick = function(event) {
            if (!event.target.matches('#selectedValues') && !event.target.closest('.dropdown-content')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }

        // 更新已選擇的值
        function updateSelectedValues() {
            var checkboxes = document.querySelectorAll('#myDropdown input[type="checkbox"]:checked');
            var allCheckboxes = document.querySelectorAll('#myDropdown input[type="checkbox"]');
            
            if (checkboxes.length === allCheckboxes.length) {
                document.getElementById('selectedValues').innerText = '生日：所有月份';
            } else if (checkboxes.length > 0) {
                var selectedValues = Array.from(checkboxes).map(cb => cb.value + '月');
                document.getElementById('selectedValues').innerText = '生日： ' + selectedValues.join(', ');
            } else {
                document.getElementById('selectedValues').innerText = '生日：';
            }
        }
    </script>
</body>

</html>
