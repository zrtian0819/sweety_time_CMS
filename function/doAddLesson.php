<?php

if (!isset($_POST["name"])) {
    header("location: addLesson.php");
    exit;
}

require_once("../db_connect.php");

print_r($_FILES);
exit;

$name = $_POST["name"];
$pic = $_FILES["pic"];
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

if ($_FILES["pic"]["error"] == 0) {
    $fileName = $_FILES["pic"]["name"];
    $fileInfo = pathinfo($fileName);
    $extension = $fileInfo["extension"];

    $newFileName = time() . ".$extension";
    if (move_uploaded_file($_FILES["pic"]["tmp_name"], "../images/lesson/" . $newFileName)) {
        $sql = "INSERT INTO lesson (teacher_id, product_class_id, name, img_path, price, start_date,quota, classroom_name, location, description, activation) VALUES ('$teacher', '$class', '$name', '$newFileName', '$price', '$formattedDateTime', '$quota', '$classroom_name', '$location', '$description', 1)";


        if ($conn->query($sql) === TRUE) {
            echo "新增成功";
            header("location:../page/lesson.php");
        } else {
            echo "新增失敗: " . $conn->error;
        }
    }
}
print_r($_FILES);


$conn->close();
