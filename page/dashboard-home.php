<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dashboard-home</title>
    <?php include("../css/css.php");?>
</head>
<body>
    <?php include ("../modules/dashboard-header.php");?>
    <?php include ("../modules/dashboard-sidebar.php");?>
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
</body>
</html>