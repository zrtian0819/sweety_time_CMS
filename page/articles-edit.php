<?php

if (!isset($_GET["id"])) {
    header("location:articles.php");
    exit;
}


require_once("../db_connect.php");


$id = $_GET["id"];

// 查詢單一文章
$sql = "SELECT * FROM articles WHERE article_id = $id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (!$result) {
    die("Query failed: " . $conn->error);
}

if (!$row) {
    die("Article not found");
}


// 查詢所有文章的數量
$stmtAll = $conn->prepare("SELECT COUNT(*) AS total FROM articles");
$stmtAll->execute();
$resultAll = $stmtAll->get_result();
$totalRows = $resultAll->fetch_assoc()['total'];

if ($id > $totalRows) {
    echo "尚未有文章";
    exit;
}

// 查詢使用者資料
$user_id = $row['user_id'];
$stmtUsers = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmtUsers->bind_param("i", $user_id);
$stmtUsers->execute();
$resultUsers = $stmtUsers->get_result();

if (!$resultUsers) {
    die("Query failed: " . $conn->error);
}

$rowUsers = $resultUsers->fetch_assoc();

if (!$rowUsers) {
    die("User not found. User ID: " . $user_id);
}

// 查詢文章分類
$productClass = $row["product_class_id"];
$sqlProductClass = "SELECT * FROM product_class WHERE product_class_id = $productClass";
$resultProduct = $conn->query($sqlProductClass);
$rowPro = $resultProduct->fetch_assoc();

// 查詢所有分類
$sqlProduct = "SELECT * FROM product_class";
$resultAllProduct = $conn->query($sqlProduct);
$rowsAllPro = $resultAllProduct->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8') ?></title>
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
            transform: translate(-50%, -50%);
        }

        .cover {
            background: gray;
            opacity: .5;
            top: 0;
            left: 0;
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
            <!-- 回文章列表按鈕 -->
            <h2 class="mb-5 text-center">修改文章</h2>
            <div class="col-3">
                <a class="btn-animation btn btn-custom d-inline-flex flex-row align-items-center mb-3" href="articles.php">
                    <i class="fa-solid fa-arrow-left-long"></i><span class="btn-animation-innerSpan d-inline-block">返回文章列表</span>
                </a>
            </div>
            <!-- 表單 -->
            <form action="../function/doUpdate4Articles.php" method="post" enctype="multipart/form-data">

                <!-- 照片 -->
                <div class="row justify-content-center">
                    <div class="col-lg-3 m-2">
                        <div class="upload">
                            <div class="photo">
                                <img src="../images/articles/<?= htmlspecialchars($row["img_path"], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8') ?>" class="w-100 h-100 object-fit-cover" id="output">
                                <div class="cover position-absolute w-100 h-100"></div>
                                <label for="picUpload" class="uploadStyle btn-custom">更新照片</label>
                                <input type="file" name="pic" id="picUpload" onchange="loadFile(event)">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 表格 -->
                <div class="d-flex justify-content-center">
                    <table class="table mt-2 table-hover align-middle">
                        <tbody>
                            <tr>
                                <th>
                                    <h5>主題</h5>
                                </th>
                                <td class="text-danger"><input type="text" class="form-control form-control-custom" value="<?= htmlspecialchars($row["title"], ENT_QUOTES, 'UTF-8') ?>" name="title"></td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>分類</h5>
                                </th>
                                <td>
                                    <select name="product_class_id" id="class">
                                        <?php foreach ($rowsAllPro as $rowProduct): ?>
                                            <option value="<?= htmlspecialchars($rowProduct["product_class_id"], ENT_QUOTES, 'UTF-8') ?>" <?= $rowProduct["product_class_id"] == $row["product_class_id"] ? "selected" : ""; ?>>
                                                <?= htmlspecialchars($rowProduct["class_name"], ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <h5>作者</h5>
                                </th>
                                <td>
                                    <?= ($rowUsers["name"]) ?>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <h5>狀態</h5>
                                </th>
                                <td>
                                    <select id="status" name="status" class="form-select form-control-custom">
                                        <option value="1" <?= $row["activation"] == 1 ? "selected" : ""; ?>>上架中</option>
                                        <option value="0" <?= $row["activation"] == 0 ? "selected" : ""; ?>>下架</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <h5 class="p-2">文章內容</h5>
                                </th>
                                <td>
                                    <p class="p-2 lh-lg">
                                    <div>
                                        <textarea id="tiny" name="content"><?= $row["content"] ?></textarea>
                                    </div>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    <button type="submit" class="btn-custom w-50">確認修改</button>
                </div>
            </form>
        </div>
    </div>

    <?php include("../js.php") ?>
    <?php $conn->close() ?>

    <script>
        // 預覽
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