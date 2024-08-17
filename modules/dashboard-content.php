<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php include("../css/css.php") ?>
</head>

<body>
    <div class="container-fluid">
        <div class="row ms-3 me-3">
            <!-- Sidebar -->
            <div class="col-sm-12 col-md-3 col-lg-3
            col-xxl-3 ">
                <div class="sidebar neumorphic setting">
                    <button id="adminBtn" class="btn btn-neumorphic toggle-btn">
                        <h5 class="h5">admin管理</h5>
                    </button>
                    <ul id="adminList" class="nav flex-column collapse align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="#">會員管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">文章管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">師資管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">課程管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">優惠券管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">總商家管理</a>
                        </li>
                    </ul>

                    <button id="storeBtn" class="btn btn-neumorphic toggle-btn">
                        <h5 class="h5">店家管理</h5>
                    </button>
                    <ul id="storeList" class="nav flex-column collapse align-items-center">
                        <li class="nav-item">
                            <a class="nav-link" href="#">店家基本資料</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">商品管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">訂單管理</a>
                        </li>
                    </ul>

                    <button id="registerBtn" class="btn btn-neumorphic toggle-btn">
                        <h5 class="h5">店家註冊</h5>
                    </button>
                    <ul id="registerList" class="nav flex-column collapse align-items-center">
                        </button>
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link" href="#">店家基本資料填寫</a>
                            </li>
                        </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="gx-6 col-md-9 col-lg-9">
                <div class="content neumorphic">
                    <h2 class="h2">Welcome to Sweet Time Dashboard</h2>
                    <p>這是各位的放置區，預設的樣式可以參考唷，Button統一使用目前樣式，方便後續修改。</p>

                    <div class="card-neumorphic">
                        <h4 class="h4">Title</h4>
                        <button class="btn btn-neumorphic">Button</button>
                        <button class="btn btn-neumorphic">Button</button>
                        <button class="btn btn-neumorphic">Button</button>
                    </div>

                    <div class="card-neumorphic">
                        <h4>Title</h4>
                        <p>You can add content here.</p>
                    </div>

                    <div class="card-neumorphic">
                        <h4>Title</h4>
                        <p>You can add content here.</p>
                    </div>

                    <div class="card-neumorphic">
                        <h4>Title</h4>
                        <p>You can add content here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("../javascript/script.js"); ?>
</body>

</html>