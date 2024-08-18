<?php

require_once("../db_connect.php");


$sql = "SELECT * FROM lesson WHERE activation = 1";
$result = $conn->query($sql);
$rows = $result->fetch_all(MYSQLI_ASSOC);

// print_r($row);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>Lesson</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>
        <div class="main col neumorphic p-5">
            <!-- Content -->
            <table class="table">
                <thead class="text-center">
                    <th>課程編號</th>
                    <th>課程名稱</th>
                    <th>授課老師</th>
                    <th>課程人數</th>
                    <th>詳細資訊</th>
                </thead>
                <?php foreach ($rows as $row): ?>
                    <tbody>
                        <tr class="text-center">
                            <td><?= $row["lesson_id"] ?></td>
                            <td><?= $row["name"] ?></td>
                            <td><?= $row["teacher_id"] ?></td>
                            <td><?= $row["quota"] ?></td>
                            <td><a href="" class="btn"><i class="fa-regular fa-eye"></i></a></td>
                        </tr>
                    </tbody>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>

</html>