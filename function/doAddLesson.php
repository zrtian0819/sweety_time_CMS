<?php

require_once("../db_connect.php");

$name = $_POST["name"];
$class = $_POST["class"];
$teacher = $_POST["teacher"];
$price = $_POST["price"];

$time = $_POST["createTime"];
$dateTime = DateTime::createFromFormat('Y-m-d\TH:i', $time);
$formattedDateTime = $dateTime->format('Y-m-d H:i:s');

$quota = $_POST["quota"];
$classroom_name = $_POST["classroom_name"];
$location = $_POST["location"];
$description = $_POST["description"];



$sql = "INSERT INTO lesson (teacher_id, product_class_id, name, price, start_date,quota, classroom_name, location, description, activation) VALUES ('$teacher', '$class', '$name', '$price', '$formattedDateTime', '$quota', '$classroom_name', '$location', '$description', 1)";


if ($conn->query($sql) === TRUE) {
    echo "更新成功";
    header("location:../page/lesson.php");
} else {
    echo "更新資料錯誤: " . $conn->error;
}
$conn->close();
