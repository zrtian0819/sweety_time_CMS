<?php
require_once("../db_connect.php");

// 獲取狀態和搜尋字串，並設置默認值
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// 設置搜尋條件
$search_condition = "";
if ($search) {
    $search_condition = "AND (name LIKE ? OR expertise LIKE ? OR description LIKE ?)";
}

switch ($status) {
    case 'on':
        $status_condition = "WHERE valid = 1 $search_condition";
        break;
    case 'off':
        $status_condition = "WHERE valid = 0 $search_condition";
        break;
    default:
        $status_condition = "WHERE 1=1 $search_condition";
        break;
}

// 計算符合條件的總數量，用來計算頁數
$sqlCount = "SELECT COUNT(*) as count FROM teacher $status_condition";
$stmtCount = $conn->prepare($sqlCount);
if ($search) {
    $search_param = "%$search%";
    $stmtCount->bind_param("sss", $search_param, $search_param, $search_param);
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$row = $resultCount->fetch_assoc();
$teacherCount = $row['count'];

$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
$per_page = 10;
$start_item = ($page - 1) * $per_page;
$total_page = ceil($teacherCount / $per_page);

$sql = "SELECT * FROM teacher $status_condition LIMIT $start_item, $per_page";
$stmt = $conn->prepare($sql);
if ($search) {
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
}
$stmt->execute();
$result = $stmt->get_result();
$rows = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>
    <div class="container-fluid d-flex flex-row px-4">
        <?php include("../modules/dashboard-sidebar_Su.php"); ?>
        <div class="main col neumorphic p-5">

        <form method="GET" action="teacher.php" class="d-flex">
                <div class="input-group d-flex justify-content-start align-items-center mb-4">
                    <input type="search" class="form-control" placeholder="搜尋教師..." name="search" style="max-width:200px" value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-custom" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>

                <a class="h-100 btn-animation btn btn-custom d-flex flex-row align-items-center" href="create-teacher.php">
                <i class="fa-solid fa-user-plus"></i><span class="btn-animation-innerSpan d-inline-block">新增教師</span>
                </a>
            </form>


            <ul class="nav nav-tabs nav-tabs-custom d-flex justify-content-between">
                <div class="d-flex">
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'all' ? 'active' : '' ?>" href="?status=all">全部</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'on' ? 'active' : '' ?>" href="?status=on">聘僱中</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $status === 'off' ? 'active' : '' ?>" href="?status=off">已下架</a>
                    </li>
                </div>
            </ul>

            <?php if ($teacherCount > 0) : ?>
                <table class="table table-hover">
                    <thead class="text-center table-pink">
                        <tr>
                            <th>Image</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Expertise</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $teacher) : ?>
                            <tr class="text-center m-auto">
                                <td class="align-middle"><a class="teacher-profile" href=""> <img src="../images/teachers/<?= htmlspecialchars($teacher["img_path"]) ?>" alt="<?= htmlspecialchars($teacher["name"]) ?>" class="w-100 h-100 object-fit-cover"></a></td>
                                <td class="align-middle"><?= htmlspecialchars($teacher["teacher_id"]) ?></td>
                                <td class="align-middle"><?= htmlspecialchars($teacher["name"]) ?></td>
                                <td class="align-middle">
                                    <?php
                                    $expertise_text = htmlspecialchars($teacher["expertise"]);
                                    // 限制字數為 30 個字元
                                    if (mb_strlen($expertise_text) > 30) {
                                        $expertise_text = mb_substr($expertise_text, 0, 30) . '...';
                                    }
                                    echo $expertise_text;
                                    ?>
                                </td>

                                <td class="align-middle">
                                    <!-- 查看按鈕 -->
                                    <button type="button" class="btn btn-custom view-details" data-id="<?= htmlspecialchars($teacher["teacher_id"]) ?>" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                    <a class="btn btn-custom" href="teacher-edit.php?id=<?= htmlspecialchars($teacher["teacher_id"]) ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form method="POST" action="doDeleteTeacher.php" style="display:inline-block;">
                                        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher["teacher_id"]) ?>">
                                        <input type="hidden" name="valid" value="<?= htmlspecialchars($teacher["valid"]) ?>">
                                        <button class="btn btn-custom btn-onoff" type="submit">
                                            <?php if ($teacher['valid'] == 1): ?>
                                                <i class="fa-solid fa-toggle-on"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-toggle-off"></i>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $total_page; $i++) : ?>
                            <li class="page-item mx-1 <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link btn-custom" href="teacher.php?status=<?= htmlspecialchars($status) ?>&search=<?= htmlspecialchars($search) ?>&p=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php else : ?>
                <p>No teachers found.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- 詳細資訊彈跳視窗 -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">詳細資訊</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="teacherDetails"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn btn-custom" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>

    <?php include("../js.php"); ?>

    <script>
        // 當點擊查看按鈕時載入教師詳細資料
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                let teacherId = this.getAttribute('data-id');

                // 使用 fetch 從 PHP 獲取教師詳細資料
                fetch(`teacher-view.php?id=${teacherId}`)
                    .then(response => response.json())
                    .then(data => {
                        // 動態填充彈跳視窗中的內容
                        document.getElementById('teacherDetails').innerHTML = `
                            <img src="../images/teachers/${data.img_path}" alt="${data.name}" class="img-fluid">
                            <p><strong>ID:</strong> ${data.teacher_id}</p>
                            <p><strong>Name:</strong> ${data.name}</p>
                            <p><strong>Expertise:</strong> ${data.expertise}</p>
                            <p><strong>Experience:</strong> ${data.experience}</p>
                            <p><strong>Education:</strong> ${data.education}</p>
                            <p><strong>Licence:</strong> ${data.licence}</p>
                            <p><strong>Awards:</strong> ${data.awards}</p>
                            <p><strong>Description:</strong> ${data.description}</p>
                            <p><strong>Status:</strong> ${data.valid ? '開課中' : '已下架'}</p>

                        `;
                    });
            });
        });
    </script>
</body>

</html>