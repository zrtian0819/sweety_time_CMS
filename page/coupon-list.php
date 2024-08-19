<?php

require_once("../db_connect.php");

// 設定SQL查詢語句樣板 
$sql = "SELECT * FROM coupon WHERE 1=1"; // 利用永遠為真的 `1=1` 以便被後續條件替換
$params = [];

// 搜尋條件
if (isset($_GET["search"]) && !empty($_GET["search"])) {
    $search = "%" . $_GET["search"] . "%";
    $sql .= " AND name LIKE ?";
    array_unshift($params, $search);
}

// 排序条件
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

// 準備查詢
$stmt = $conn->prepare($sql);

// 將參數化的搜尋條件取代佔位符
if (!empty($params)) {
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
// $conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>優惠券種類一覽</title>
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
                <a href="./coupon.php">優惠券管理</a>>
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
                        <th>啟用狀態<br>(actication)</th>
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
                            <td>
                                <?php if( $row['activation'] == 1) : ?>
                                    <p class="text-success">啟用中</p>
                                <?php elseif( $row['activation'] == 0) : ?>
                                    <p class="text-danger">停用中</p>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['created_at'];?></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>

    </div>

    <?php include("../js.php"); ?>
</body>

</html>