<?php

if (!isset($_GET["id"])) {
    header("location:lesson.php");
    exit;
}

require_once("../db_connect.php");



$id = $_GET["id"];

$sql = "SELECT * FROM lesson WHERE lesson_id = $id";
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


//student
$sqlStudent = "SELECT * FROM student WHERE lesson_id = $id";
$resultStu = $conn->query($sqlStudent);
$count = $resultStu->num_rows;
$rowStu = $resultStu->fetch_assoc();

//分類
$productClass = $row["product_class_id"];
$sqlProductClass = "SELECT * FROM product_class WHERE product_class_id = $productClass";
$resultProduct = $conn->query($sqlProductClass);
$rowPro = $resultProduct->fetch_assoc();


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
    <title><?= $row["name"] ?></title>
    <?php include("../css/css_Joe.php"); ?>
    <script src="https://cdn.tiny.cloud/1/cfug9ervjy63v3sj0voqw9d94ojiglomezxkdd4s5jr9owvu/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        input[type="file"] {
            display: none;
        }

        .photo {
            position: relative;
            overflow: hidden;

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

        .cover {
            background: gray;
            opacity: .5;
            top: 0;
            left: 0;
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
            <form action="../function/doUpdateLesson.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
                <h1 class="m-2"><input type="text" class="textarea-custom" value="<?= $row["name"] ?>" name="name"></h1>
                <div class="row justify-content-center">
                    <div class="col-lg-3 m-2">
                        <div class="upload">
                            <!-- ratio ratio-4x3 -->
                            <div class="photo ">
                                <img src="../images/lesson/<?= $row["img_path"] ?>" alt="<?= $row["name"] ?>" class="w-100 h-100 object-fit-cover">
                                <div class="cover position-absolute w-100 h-100"></div>
                                <label for="picUpload" class="uploadStyle btn-custom">更新照片</label>
                                <input type="file" name="pic" id="picUpload">
                            </div>
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
                                    <td class="text-danger"><input type="text" class="textarea-custom" value=<?= ($row["price"]) ?> name="price"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>時間</h5>
                                    </th>
                                    <td><input type="datetime-local" class="textarea-custom" name="updateTime"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>課程人數</h5>
                                    </th>
                                    <td><input type="text" class="textarea-custom" value=<?= $row["quota"] ?> name="quota"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>報名人數</h5>
                                    </th>
                                    <td><?= $count ?></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>地點</h5>
                                    </th>
                                    <td><input type="text" class="textarea-custom" value="<?= $row["classroom_name"] ?>" name="classroom_name"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>地址</h5>
                                    </th>
                                    <td><input type="text" class="textarea-custom" value=<?= $row["location"] ?> name="location"></td>
                                </tr>
                                <tr>
                                    <th>
                                        <h5>狀態</h5>
                                    </th>
                                    <?php echo ($row["activation"] == 1) ? "<td>" . "上架中" : "<td class='text-danger'>" . "已下架"; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn-custom">確認修改</button>
                    </div>
                    <div class="col-lg-8 ms-2">
                        <h3 class="p-2">課程介紹</h3>
                        <p class="p-2 lh-lg">
                        <div>
                            <textarea id="tiny" name="description" value=""><?= $row["description"] ?></textarea>
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