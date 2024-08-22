<?php

require_once("../db_connect.php");


$sql = "SELECT * FROM lesson";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


//teacher
$sqlTeacher = "SELECT * FROM teacher ORDER BY teacher_id";
$resultTea = $conn->query($sqlTeacher);
$rowsTea = $resultTea->fetch_all(MYSQLI_ASSOC);
// print_r($rowsTea);

//關聯式陣列
$teacherArr = [];
foreach ($rowsTea as $teacher) {
    $teacherArr[$teacher["teacher_id"]] = $teacher["name"];
}

//所有分類
$sqlProduct = "SELECT * FROM product_class";
$resultAllProduct = $conn->query($sqlProduct);
$rowsAllPro = $resultAllProduct->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=, initial-scale=1.0">
    <title>新增課程</title>
    <?php include("../css/css_Joe.php"); ?>
    <script src="https://cdn.tiny.cloud/1/cfug9ervjy63v3sj0voqw9d94ojiglomezxkdd4s5jr9owvu/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .upload {
            width: 100%;
        }
        .uploadArea{
            border: 5px solid black;
            width: 100%;
            height: 40dvh;
            align-content: center;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5 pt-4">
            <!-- Content -->
            <a href="lesson.php" class="btn btn-custom"><i class="fa-solid fa-arrow-left"></i></a>
            <form action="../function/doAddLesson.php" method="POST" enctype="multipart/form-data">
                <h1>
                    <input type="text" class="textarea-custom" value="課程名稱" name="name">
                </h1>
                <div class="row justify-content-center">
                    <div class="col-lg-3 m-2">
                        <div class="mb-2 upload text-center">
                            <div class="uploadArea">
                                <h4>上傳照片</h4>
                            </div>
                            <input type="file" class="form-control" name="pic" required>
                        </div>
                        <table class="table mt-2 table-hover">
                            <tbody>
                                <tr>
                                    <th>
                                        <h5>分類</h5>
                                    </th>
                                    <td><select name="class" id="class">
                                            <?php foreach ($rowsAllPro as $rowProduct): ?>
                                                <option value="<?= $rowProduct["product_class_id"] ?>"><?= $rowProduct["class_name"] ?></option>
                                            <?php endforeach; ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>講師</h5>
                                    </th>
                                    <td>
                                        <select name="teacher" id="teacher">
                                            <?php foreach ($rowsTea as $rowTea): ?>
                                                <option value="<?= $rowTea["teacher_id"] ?>"><?= $rowTea["name"] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>價錢</h5>
                                    </th>
                                    <td class="text-danger"><input type="text" class="textarea-custom" value="" name="price"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>時間</h5>
                                    </th>
                                    <td><input type="datetime-local" class="textarea-custom" name="createTime"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>課程人數</h5>
                                    </th>
                                    <td><input type="text" class="textarea-custom" value="" name="quota"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>地點</h5>
                                    </th>
                                    <td><input type="text" class="textarea-custom" value="" name="classroom_name"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>地址</h5>
                                    </th>
                                    <td><input type="text" class="textarea-custom" value="" name="location"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn-custom">確認新增</button>
                    </div>
                    <div class="col-lg-8 ms-2">
                        <h3 class="p-2">課程介紹</h3>
                        <p class="p-2 lh-lg">
                        <div>
                            <textarea id="tiny" name="description" value=""></textarea>
                        </div>
                        </p>
                    </div>
                </div>

            </form>
        </div>

    </div>
    <?php include("../js.php") ?>
    <?php $conn->close() ?>
    <script>
        tinymce.init({
            selector: 'textarea#tiny'
        });
        document.addEventListener('focusin', (e) => {
            if (e.target.closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root") !== null) {
                e.stopImmediatePropagation();
            }
        });
    </script>
</body>

</html>