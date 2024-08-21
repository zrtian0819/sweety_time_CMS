<?php
require_once("../db_connect.php");

$sql = "SELECT * FROM users WHERE activation=1";
$result = $conn->query($sql);
$userCount = $result->num_rows;
$row = $result->fetch_assoc();

?>
<!doctype html>
<html lang="en">

<head>
    <title>新增資料</title>
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
                    <a class="btn btn-neumorphic user-btn" href="users.php" title="回使用者列表"><i class="fa-solid fa-left-long"></i></a>
                </div>
                <div class="d-flex justify-content-center">
                    <h2 class="mb-5">新增資料</h2>
                </div>
                <div class="container">
                    <div class="row">
                        <table class="table table-bordered">
                            <tr>
                                <th>Name</th>
                                <td>
                                    <input type="text" value="<?= $row["name"] ?>" class="form-control" name="name">
                                </td>
                            </tr>
                            <tr>
                                <th>Account</th>
                                <td>
                                    <input type="text" value="<?= $row["account"] ?>" class="form-control" name="account">
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
                                <th>Phone</th>
                                <td>
                                    <input type="text" value="<?= $row["phone"] ?>" class="form-control" name="phone">
                                </td>
                            </tr>
                            <tr>
                                <th>Birthday</th>
                                <td>
                                    <div class="birthday-group">
                                        <input type="date">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Sign up time</th>
                                <td><?= $row["now"] ?></td>
                            </tr>

                        </table>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-neumorphic user-btn">儲存</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../js.php"); ?>
    <script>

    </script>
    <?php $conn->close() ?>
</body>

</html>