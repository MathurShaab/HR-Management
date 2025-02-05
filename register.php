<?php
session_start();
include 'db_conn.php';   // Database connection

// Check if HR is logged in
if (!isset($_SESSION['name'])) {
    header('Location: index.php');
    exit();
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidateName = mysqli_real_escape_string($conn, $_POST['candidateName']);
    $candidateEmail = mysqli_real_escape_string($conn, $_POST['candidateEmail']);
    $candidateMobile = mysqli_real_escape_string($conn, $_POST['candidateMobile']);
    $candidatePosition = mysqli_real_escape_string($conn, $_POST['candidatePosition']);
    $candidateQualification = mysqli_real_escape_string($conn, $_POST['candidateQualification']);
    $candidateExperience = mysqli_real_escape_string($conn, $_POST['candidateExperience']);
    $HR_reference = $_SESSION['name'];  // HR reference from session

    // Check if the email already exists in the database
    $emailCheckQuery = "SELECT * FROM candidates WHERE email = '$candidateEmail'";
    $emailCheckResult = mysqli_query($conn, $emailCheckQuery);
    if (mysqli_num_rows($emailCheckResult) > 0) {
        echo "<script>alert('This email is already registered. Please enter a different email.');</script>";
    } else {
        // Handle Resume Upload
        $resumeName = $_FILES['candidateResume']['name'];
        $resumeTmpName = $_FILES['candidateResume']['tmp_name'];
        $resumePath = 'uploads/' . basename($resumeName);
        move_uploaded_file($resumeTmpName, $resumePath);

        // Insert Candidate Data
        $insertQuery = "INSERT INTO candidates 
            (name, email, mobile, position, qualification, experience, resume, HR_reference) 
            VALUES 
            ('$candidateName', '$candidateEmail', '$candidateMobile', '$candidatePosition', '$candidateQualification', '$candidateExperience', '$resumePath', '$HR_reference')";

        if (mysqli_query($conn, $insertQuery)) {
            // Send email to candidate
            $subject = "Your Job Application is in Process - AJ Info Tech";
            $message = "Hello $candidateName,\n\nYour job application is in process with AJ Info Tech. 
 Your interview will be scheduled within a week with our manager.\n\nYour HR is $HR_reference.\n\nThank you!";
 $headers = "From: hr@AJinfotech.in";  // Replace with your HR email address

            if (mail($candidateEmail, $subject, $message, $headers)) {
                echo "<script>alert('Candidate Registered Successfully! Confirmation email sent to the candidate.'); window.location.href='dashboard.php';</script>";
            } else {
                echo "<script>alert('Registration successful, but failed to send email.'); window.location.href='dashboard.php';</script>";
            }
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Candidate</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="Register_body">
    <div class="container">
        <h1>Register New Candidate</h1>
        <form id="registerForm" action="" method="POST" enctype="multipart/form-data">
            <!-- Candidate Name -->
            <div class="input-group">
                <label for="candidateName">Candidate Name</label>
                <input type="text" id="candidateName" name="candidateName" required>
            </div>

            <!-- Candidate Email -->
            <div class="input-group">
                <label for="candidateEmail">Candidate Email</label>
                <input type="email" id="candidateEmail" name="candidateEmail" required>
            </div>

            <!-- Mobile Number -->
            <div class="input-group">
                <label for="candidateMobile">Mobile Number</label>
                <input type="tel" id="candidateMobile" name="candidateMobile" pattern="[0-9]{10}" placeholder="Enter 10-digit number" required>
            </div>

            <!-- Position Selection -->
            <div class="input-group">
                <label for="candidatePosition">Select Position</label>
                <select id="candidatePosition" name="candidatePosition" required>
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
            </div>

            <!-- Qualification -->
            <div class="input-group">
                <label for="candidateQualification">Qualification</label>
                <select id="candidateQualification" name="candidateQualification" required>
                    <option value="" disabled selected>Select Qualification</option>
                    <option value="B.Tech">B.Tech</option>
                    <option value="MCA">MCA</option>
                    <option value="BCA">BCA</option>
                    <option value="MBA">MBA</option>
                    <option value="B.Sc">B.Sc</option>
                    <option value="M.Sc">M.Sc</option>
                    <option value="Diploma">Diploma</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <!-- Experience -->
            <div class="input-group">
                <label for="candidateExperience">Experience (in Years)</label>
                <select id="candidateExperience" name="candidateExperience" required>
                    <option value="" disabled selected>Select Experience</option>
                    <option value="0">Fresher</option>
                    <option value="1">1 Year</option>
                    <option value="2">2 Years</option>
                    <option value="3">3 Years</option>
                    <option value="4">4 Years</option>
                    <option value="5">5+ Years</option>
                </select>
            </div>

            <!-- Resume Upload -->
            <div class="input-group">
                <label for="candidateResume">Upload Resume</label>
                <input type="file" id="candidateResume" name="candidateResume" accept=".pdf,.doc,.docx" required>
            </div>

            <!-- Submit Button -->
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>
