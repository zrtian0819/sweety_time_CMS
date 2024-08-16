<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <?php include("./css.php") ?>
</head>
<body>      
            <!-- Content -->
            <div class="col-md-9"> <!-- 這一層佈局不要動 -->
                <div class="content neumorphic">
                    <h2>Welcome to Sweet Time Dashboard</h2>
                    <p>Here is an overview of your recent activities.</p>

                    <div class="card-neumorphic">
                        <h4>Quick Actions</h4>
                        <button class="btn btn-neumorphic">Action 1</button>
                        <button class="btn btn-neumorphic">Action 2</button>
                        <button class="btn btn-neumorphic">Action 3</button>
                    </div>

                    <div class="card-neumorphic">
                        <h4>Recent Updates</h4>
                        <p>Here you can display recent updates or notifications.</p>
                    </div>

                    <div class="card-neumorphic">
                        <h4>Statistics</h4>
                        <p>You can add charts or statistics here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>