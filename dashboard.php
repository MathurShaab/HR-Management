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
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <title>HR Management - Dashboard</title>
   
      
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="dashboard_body">
    <div class="dashboard-container">
        <div class="header">
            <div class="user-info">
                <h1>Dashboard</h1>
                <p><img src="img/ram.jpg" alt="Hey" style="width: 80px; height: 80px; margin-right: 10px;">Hi, <?php echo 
                $_SESSION['name']?></p>
            </div>
            
            <button class="logout-btn" onclick="window.location.href = '?logout=true';">Logout</button>
        
        
        </div>
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
    <script>
        function navigateTo(page) {
            window.location.href = page;
        }
        function logout() {
            alert("Logging out...");
            window.location.href = "login.php"; // Change to actual logout functionality
        }
    </script>
</body>
</html>
