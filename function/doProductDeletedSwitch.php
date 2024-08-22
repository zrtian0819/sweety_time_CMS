<?php

require_once("../db_connect.php");

if (isset($_GET["productId"])) {
    $product_id = $_GET["productId"];

    //取得product的上下架狀態
    $sql = "SELECT deleted from product WHERE product_id = $product_id";

    $result = $conn->query($sql);
    $count = $result->num_rows;
    $row = $result->fetch_assoc();

    $deleted = $row["deleted"];

    //切換成另一個狀態
    if ($deleted == 1) {
        $sqlUpdate = "UPDATE product SET deleted ='0' WHERE product_id = $product_id";
    } elseif ($deleted == 0) {
        $sqlUpdate = "UPDATE product SET deleted ='1' WHERE product_id = $product_id";
    }

    //執行切換
    $resultUpdate = $conn->query($sqlUpdate);

    //導頁
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);    //回到前一頁
        exit;
    } else {
        header("location ../page/product-list.php");
        exit;
    }
} else {
    echo "切換刪除狀態失敗";
    sleep(2);
    header("location ../page/product-list.php");
}

$conn->close();
