<?php

require_once("../db_connect.php");

if (isset($_GET["productId"])) {
    $product_id = $_GET["productId"];

    //取得product的上下架狀態
    $sql = "SELECT available from product WHERE product_id = $product_id";

    $result = $conn->query($sql);
    $count = $result->num_rows;
    $row = $result->fetch_assoc();

    $valid = $row["available"];

    //切換成另一個狀態
    if ($valid == 1) {
        $sqlUpdate = "UPDATE product SET available ='0' WHERE product_id = $product_id";

        $data = [
            "status" => 1,
            "message" => "將 product_id = $product_id數據 下架"
        ];
        echo json_encode($data);
    } elseif ($valid == 0) {
        $sqlUpdate = "UPDATE product SET available ='1' WHERE product_id = $product_id";

        $data = [
            "status" => 1,
            "message" => "將 product_id = $product_id數據 上架"
        ];
        echo json_encode($data);
    }

    //執行切換
    $resultUpdate = $conn->query($sqlUpdate);
} else {
    // echo "切換失敗";
    // sleep(2);
    // header("location ../page/product-list.php");
    $data = [
        "status" => 0,
        "message" => "沒有傳遞正確的數據"
    ];
    echo json_encode($data);
}

$conn->close();
