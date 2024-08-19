<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard-home_Joe</title>
    <?php include("../css/css_Joe.php"); ?>
</head>

<body>
    <?php include("../modules/dashboard-header_Joe.php"); ?>

    <div class="container-fluid d-flex flex-row px-4">

        <?php include("../modules/dashboard-sidebar_Joe.php"); ?>

        <div class="main col neumorphic p-5">
            <div class="container-fluid">
                <h2 class="mb-4">樣式表</h2>
                <hr>
                <h4 class="mb-3">輸入框</h4>
                <div class="mb-3">
                    <label for="name" class="form-label">姓名</label>
                    <input type="text" class="form-control form-control-custom" id="name" placeholder="請輸入姓名">
                </div>
                <hr>
                <h4 class="mb-4">輸入框和提交按鈕示例</h4>

                <div class="input-group mb-3">
                    <input type="text" class="form-control form-control-custom" placeholder="請輸入..." aria-label="輸入文字" aria-describedby="button-addon1">
                    <button class="btn btn-enter" type="button" id="button-addon1">提交</button>
                </div>
                <hr>
                <h4 class="mb-3">文本區域</h4>
                <div class="mb-3">
                    <label for="message" class="form-label">留言</label>
                    <textarea class="form-control textarea-custom" id="message" rows="3" placeholder="請輸入留言"></textarea>
                </div>
                <hr>
                <div class="row">
                    <h3 class="mb-3">下拉選單</h3>
                    <div class="mb-3">
                        <label for="country" class="form-label">國家</label>
                        <select class="form-select form-select-custom" id="country">
                            <option selected>請選擇國家</option>
                            <option value="1">台灣</option>
                            <option value="2">日本</option>
                            <option value="3">韓國</option>
                        </select>
                    </div>
                    <hr>
                    <h3 class="mb-3">複選框</h3>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="check1">
                        <label class="form-check-label" for="check1">選項 1</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="check2">
                        <label class="form-check-label" for="check2">選項 2</label>
                    </div>
                    <hr>
                    <h3 class="mb-3">單選框</h3>
                    <div class="mb-3 form-check">
                        <input type="radio" class="form-check-input" id="radio1" name="radioGroup">
                        <label class="form-check-label" for="radio1">選項 A</label>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="radio" class="form-check-input" id="radio2" name="radioGroup">
                        <label class="form-check-label" for="radio2">選項 B</label>
                    </div>
                    <hr>
                    <h3 class="mb-3">按鈕</h3>
                    <button class="btn btn-custom">點擊我</button>
                    <br>
                    <hr>
                    <h3 class="mb-3">link</h3>
                    <a class="link-custom" href="#" class="text-decoration-none text-dark">這是一個link</a>
                    <hr>
                    <h3 class="mb-3">彈出框</h3>
                    <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        打開彈出框
                    </button>
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">彈出框標題</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    這是彈出框的內容
                                </div>
                                <hr>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                                    <button type="button" class="btn btn-custom">保存更改</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <h4 class="mb-3">標籤頁</h4>
                    <ul class="nav nav-tabs nav-tabs-custom" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Home</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Profile</button>
                        </li>
                    </ul>
                    <br>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">這是 Home 標籤的內容</div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">這是 Profile 標籤的內容</div>
                    </div>
                    <!-- <h4 class="mb-3">警告框</h4>
                    <div class="alert alert-success-custom" role="alert">
                        這是一個成功警告框
                    </div>
                    <div class="alert alert-danger-custom" role="alert">
                        這是一個錯誤警告框
                    </div>
                </div> -->                
                <hr>
                <h4 class="mb-3">上傳圖片</h4>
                <form>
                    <div class="mb-3">
                        <label for="fileUpload" class="custom-file-upload">
                            選擇圖片
                        </label>
                        <input type="file" id="fileUpload" class="file-input" accept="image/*">
                    </div>
                </form>
                <!-- <h4 class="mb-3">表格</h4> -->
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
            <script>
                // 使用 JavaScript 觸發檔案選擇對話框
                document.querySelector('.custom-file-upload').addEventListener('click', function() {
                    document.getElementById('fileUpload').click();
                });
            </script>

        </div>

    </div>

    <?php include("../js.php"); ?>
</body>

</html>