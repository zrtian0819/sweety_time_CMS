<?php
if (!isset($_GET["user_id"])) {
    echo "請正確帶入 get user_id 變數";
    exit;
}
$user_id = $_GET["user_id"];

require_once("../db_connect.php");

$sql = "SELECT * FROM users WHERE user_id = '$user_id' AND activation=1";
$result = $conn->query($sql);
$userCount = $result->num_rows;
$row = $result->fetch_assoc();

$sql_img = "SELECT portrait_path FROM users WHERE user_id = $user_id";
$result_img = $conn->query($sql_img);
$row_img = $result_img->$num_rows;
$user_img = $result_img->fetch_assoc();


if ($userCount > 0) {
    $title = $row["name"];
    $defaultImage = 'https://images.unsplash.com/photo-1472396961693-142e6e269027?q=80&w=2152&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D';
    $imagePath = !empty($row['portrait_path']) ? '../images/users/' . $row['portrait_path'] : $defaultImage;
} else {
    $title = "使用者不存在";
    $imagePath = $defaultImage;
}
?>
<!doctype html>
<html lang="en">

<head>
    <title><?= $title ?></title>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php include("../css/css_Joe.php"); ?>
    <style>
        .user-btn {
            width: 100px;
        }

        .user-search {
            width: 200px;
        }
        .user-img {
            width: 300px;
            height: 300px;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="container-fluid d-flex flex-row px-4">
            <div class="main col neumorphic p-5">
                <div class="py-2">
                    <a class="btn btn-neumorphic user-btn" href="users.php" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
                </div>
                <h2 class="mb-3"><?= $title ?> 基本資料</h2>
                <div class="container">
                    <div class="row">
                        <?php if ($userCount > 0): ?>
                            
                            <div class="mb-3 d-flex justify-content-center align-items-center flex-column">
                                <img src="<?= htmlspecialchars($imagePath) ?>" alt="Profile Image" class="object-fit-fill user-img">
                            </div>
                            <table class="table table-bordered">
                                <tr>
                                    <th>User ID</th>
                                    <td><?= $row["user_id"] ?></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?= $row["name"] ?></td>
                                </tr>
                                <tr>
                                    <th>Password</th>
                                    <td><?= $row["password"] ?></td>
                                </tr>
                                <tr>
                                    <th>Birthday</th>
                                    <td><?= $row["birthday"] ?></td>
                                </tr>
                                <tr>
                                    <th>Sign up time</th>
                                    <td><?= $row["sign_up_time"] ?></td>
                                </tr>
                            </table>
                            <div class="d-flex justify-content-end">
                                <a href="user-edit.php?user_id=<?=$row["user_id"]?>" class="btn btn-neumorphic user-btn"><i class="fa-solid fa-user-pen"></i></a>
                            </div>
                        <?php else: ?>
                            使用者不存在
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include("../js.php"); ?>
    <?php $conn->close() ?>
</body>

</html>
