<?php

require_once("../db_connect.php");

if (!isset($_GET["id"])) {
    echo "請循正常管道進入";
}

$id = $_GET["id"];
$name = $_POST["name"];
$class = $_POST["class"];
$teacher = $_POST["teacher"];
$price = $_POST["price"];

$updateTime = $_POST["updateTime"];
$dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $updateTime);
$formattedDateTime = $dateTime->format('Y-m-d H:i:s');

$quota = $_POST["quota"];
$classroom_name = $_POST["classroom_name"];
$location = $_POST["location"];
$description = $_POST["description"];



$sql = "UPDATE lesson SET name = '$name',product_class_id='$class',teacher_id='$teacher',price='$price',start_date='$formattedDateTime',quota='$quota',classroom_name='$classroom_name',location='$location',description='$description' WHERE lesson_id ='$id'";

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
    header("location:../page/lesson-details.php?id=$id");
} else {
    echo "更新資料錯誤: " . $conn->error;
}
$conn->close();
