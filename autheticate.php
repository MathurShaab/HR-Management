<?php
session_start();
include 'db_conn.php'; // Database connection

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        die("Username and Password are required.");
    }

    // Query to check credentials
    $sql = "SELECT * FROM hr_employees WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password (assuming passwords are stored using SHA2 in MySQL)
        $hashed_password = hash('sha256', $password);

        if ($user['password'] === $hashed_password) {
            // Authentication successful
            $_SESSION['hr_id'] = $user['hr_id'];
            $_SESSION['name'] = $user['name'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found with this email!";
    }
} else {
    echo "Invalid request!";
}
?>
