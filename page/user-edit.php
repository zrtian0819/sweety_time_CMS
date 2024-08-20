<?php
if (!isset($_GET["user_id"])) {
    echo "請正確帶入 get user_id 變數";
    exit;
}
$user_id = $_GET["user_id"];

require_once("../db_connect.php");

$sql = "SELECT * FROM users WHERE user_id = '$user_id'";
$result = $conn->query($sql);
$userCount = $result->num_rows;
$row = $result->fetch_assoc();

if ($userCount > 0) {
    $title = $row["name"];
} else {
    $title = "使用者不存在";
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
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="container-fluid d-flex flex-row px-4">
            <div class="main col neumorphic p-5">
                <div class="py-2">
                    <a class="btn btn-neumorphic user-btn" href="user.php?user_id=<?=$row["user_id"]?>" title="回使用者"><i class="fa-solid fa-left-long"></i></a>
                </div>
                <h2 class="mb-3">修改資料</h2>
                <div class="container">
                    <div class="row">
                        <?php if ($userCount > 0): ?>
                            <form action="doUpdateUser.php" method="post">
                                <table class="table table-bordered">
                                    <input type="hidden" value="<?= $row["user_id"] ?>" class="form-control" name="user_id">
                                    <tr>
                                        <th>User ID</th>
                                        <td><?= $row["user_id"] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td>
                                            <input type="text" value="<?= $row["name"] ?>" class="form-control" name="name">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Password</th>
                                        <td> 
                                            <input type="text" value="<?= $row["password"] ?>" class="form-control" name="password">
                                        </td> 
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>
                                            <input type="text" value="<?= $row["email"] ?>" class="form-control" name="email">
                                        </td>
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
                                    <button type="submit" class="btn btn-neumorphic user-btn">儲存</button>
                                </div>
                            </form>
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
