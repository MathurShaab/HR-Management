<?php
session_start();
include 'db_conn.php';  // Database connection

// Check if HR is logged in
if (!isset($_SESSION['name'])) {
    header('Location: index.php');
    exit();
}

// Get the logged-in HR's name
$hr_name = $_SESSION['name'];  // HR's name from the session

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $manager = mysqli_real_escape_string($conn, $_POST['Manager']);
    $candidate = mysqli_real_escape_string($conn, $_POST['Candidate']);
    $position = mysqli_real_escape_string($conn, $_POST['Position']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $timeSlot = mysqli_real_escape_string($conn, $_POST['timeSlot']);
    
    // Get candidate's email from the database
    $candidateQuery = "SELECT email FROM candidates WHERE name = '$candidate'";
    $candidateResult = mysqli_query($conn, $candidateQuery);
    if (mysqli_num_rows($candidateResult) > 0) {
        $candidateData = mysqli_fetch_assoc($candidateResult);
        $candidateEmail = $candidateData['email'];
        
        // Insert interview schedule into interview_schedule table
        $insertQuery = "INSERT INTO interview_schedule (hr_name, manager, candidate_name, position, interview_date, time_slot, candidate_email) 
                        VALUES ('$hr_name', '$manager', '$candidate', '$position', '$date', '$timeSlot', '$candidateEmail')";
        if (mysqli_query($conn, $insertQuery)) {
            // Send email to the candidate
            $subject = "Interview Scheduled";
            $message = "Dear $candidate,\n\nYour interview has been scheduled.\n\nManager: $manager\nDate: $date\nTime Slot: $timeSlot\n\nBest regards,\nHR Team";
            $headers = "From: Recuritment@AJinfotech.in"; // Change this to your HR email

            if (mail($candidateEmail, $subject, $message, $headers)) {
                echo "<script>alert('Interview scheduled and email sent to the candidate!');</script>";
            } else {
                echo "<script>alert('Failed to send email to the candidate.');</script>";
            }
        } else {
            echo "<script>alert('Error scheduling interview.');</script>";
        }
    } else {
        echo "<script>alert('Candidate not found.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Schedule</title>
    
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="Interview_body">
    <div class="container">
        <h1>Interview Schedule</h1>
        <form action="" method="POST">

            <label for="Manager"></label>
            <select id="Manager" name="Manager">
                <option value="select">Select Manager Name</option>
                <option value="John">John</option>
                <option value="Jane">Jane</option>
                <option value="Doe">Doe</option>
            </select>
            
            <select id="Candidate" name="Candidate" required>
                <option value="select">Select Candidate Name</option>
                <?php
                // Fetch candidate names from the database
                include 'db_conn.php';
                $sql = "SELECT name FROM candidates";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                }
                ?>
            </select>
            
                <select id="Position" name="Position" required>
                    <option value="" disabled selected>Select Position</option>
                    <optgroup label="IT Department">
                        <option value="Software Developer">Software Developer</option>
                        <option value="Backend Developer">Backend Developer</option>
                        <option value="Frontend Developer">Frontend Developer</option>
                        <option value="Data Analyst">Data Analyst</option>
                    </optgroup>
                    <optgroup label="Non-IT Department">
                        <option value="HR Executive">HR Executive</option>
                        <option value="Marketing Manager">Marketing Manager</option>
                        <option value="Accountant">Accountant</option>
                        <option value="Sales Executive">Sales Executive</option>
                    </optgroup>
                </select>
            

            <label for="date"></label>
            <input type="date" id="date" name="date" required>
            
        <label for="timeSlot"></label>
        <select id="timeSlot" name="timeSlot" required>
        <option value="09:00">09:00 AM - 11:00 AM</option>
        <option value="11:00">12:00 AM - 2:00 PM</option>
        <option value="12:00">3:00  PM -  5:00 PM</option>
        </select>
        <button type="submit">Submit</button>
            
        </form>
        
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                let dateInput = document.getElementById("date");
        
                // Get today's date in YYYY-MM-DD format
                let today = new Date();
                let todayDate = today.toISOString().split("T")[0];
        
                // Set the min attribute to today's date
                dateInput.min = todayDate;
        
                // Validate date selection
                dateInput.addEventListener("change", function () {
                    if (dateInput.value < todayDate) {
                        alert("You cannot select a past date. Please choose a valid date.");
                        dateInput.value = todayDate; // Reset to today's date
                    }
                });
            });
        </script>
        
           
       
</div>
        
</body>
</html>