<?php

require_once("../db_connect.php");


$sql = "SELECT * FROM lesson";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


//teacher
$sqlTeacher = "SELECT * FROM teacher WHERE valid = 1 ORDER BY teacher_id";
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
        input[type="file"] {
            display: none;
        }

        .photo {
            position: relative;
        }

        .photo img {
            width: 100dvh;
        }

        .uploadStyle {
            cursor: pointer;
            font-size: 1.5rem;
            padding: 5px;
            text-align: center;
            position: absolute;
            top: 50%;
            left: 50%;
            translate: -50% -50%;
        }

        .uploadArea {
            border: 5px solid black;
            width: 100%;
            height: 40dvh;
            align-content: center;
            margin-bottom: 5px;
        }

        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
                <div class="col-lg-8">
                    <h1 class="m-2">
                        <input type="text" class="form-control form-control-custom fs-1" placeholder="課程名稱" name="name" required>
                    </h1>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 m-2">
                        <div class="upload">
                            <div class="photo ">
                                <img class="w-100 h-100 object-fit-cover" id="output">
                                <label for="picUpload" class="uploadStyle btn-custom">新增照片</label>
                                <input type="file" name="pic" id="picUpload" onchange="loadFile(event)" required>
                            </div>
                        </div>
                        <table class="table mt-2 table-hover align-middle">
                            <tbody>
                                <tr>
                                    <th>
                                        <h5>分類</h5>
                                    </th>
                                    <td><select name="class" id="class" class="form-select form-control-custom" required>
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
                                        <select name="teacher" id="teacher" class="form-select form-control-custom" required>
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
                                    <td class="text-danger"><input type="number" class="form-control form-control-custom" value="" name="price" required></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>時間</h5>
                                    </th>
                                    <td><input type="datetime-local" class="form-control form-control-custom" name="createTime" required></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>課程人數</h5>
                                    </th>
                                    <td><input type="number" class="form-control form-control-custom" value="" name="quota" required></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>地點</h5>
                                    </th>
                                    <td><input type="text" class="form-control form-control-custom" value="" name="classroom_name" required></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>地址</h5>
                                    </th>
                                    <td><input type="text" class="form-control form-control-custom" value="" name="location" required></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn-custom w-100">確認新增</button>
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
        //預覽
        let loadFile = function(event) {
            let output = document.getElementById("output");
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src); // free memory
            };
        };
        //所見即所得編輯器
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