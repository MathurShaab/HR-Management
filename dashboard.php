<?php
    session_start();
    if(!isset($_SESSION['name'])) {
        header('Location: index.php');
    }

    if (isset($_GET['logout'])) {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit();
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Management - Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Dashboard</h1>
        <div class="user-info">
            <p>Welcome, <?php echo $_SESSION['name']; ?></p>
            <a href="?logout=true">Logout</a>
        <div class="cards-container">
            <div class="card" onclick="navigateTo('interview.php')">
                <h2>Interview Schedule</h2>
                <p>Click to view Candidates List</p>
            </div>
            <div class="card" onclick="navigateTo('manage.php')">
                <h2>Manage Candidate</h2>
                <p>Click to manage candidates.</p>
            </div>
            <div class="card" onclick="navigateTo('register.php')">
                <h2>Register New</h2>
                <p>Click to register new candidates.</p>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
