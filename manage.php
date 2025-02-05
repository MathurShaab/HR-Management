<?php
include 'db_conn.php';

// Handle Select or Reject button click
if (isset($_POST['action'])) {
    $id = $_POST['id'];
    $action = $_POST['action']; // 'selected' or 'rejected'

    // Update the status in the database
    $updateQuery = "UPDATE interview_schedule SET status = '$action' WHERE id = $id";
    if (mysqli_query($conn, $updateQuery)) {
        // Fetch candidate's name and email
        $query = "SELECT candidate_name, candidate_email FROM interview_schedule WHERE id = $id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        $name = $row['candidate_name'];
        $email = $row['candidate_email'];

        // Send email to the candidate
        $subject = "Interview Update";
        $message = ($action == "selected")
            ? "Dear $name,\n\nCongratulations! You have been selected for the position."
            : "Dear $name,\n\nThank you for attending the interview. Unfortunately, you have not been selected.";

        $headers = "From: hr@example.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "Email sent successfully to $name!";
        } else {
            echo "Status updated, but failed to send email.";
        }
    } else {
        echo "Failed to update status.";
    }
    exit();
}

// Fetch only candidates with 'Pending' status
$candidates = mysqli_query($conn, "SELECT id, candidate_name, position FROM interview_schedule WHERE status = 'Pending'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Candidates</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 75%;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-family: 'Pacifico', cursive;
            text-align: center;
            color: #333;
            font-size: 2.5rem;
        }

        .candidate-list {
            margin-top: 30px;
        }

        .candidate-list h2 {
            color: #444;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            margin: 0;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .candidate-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2d2d2d;
        }

        .name {
            font-size: 1.5rem;
            color: #333;
        }

        .position {
            font-size: 1.2rem;
            font-weight: 700;
            color: #2d2d2d;
        }

        .button-container {
            display: flex;
            gap: 12px;
        }

        button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .select-btn {
            background-color: #28a745;
            color: white;
        }

        .reject-btn {
            background-color: #dc3545;
            color: white;
        }

        .select-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }

        .reject-btn:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(1px);
        }

        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
            }

            ul {
                flex-direction: column;
                gap: 15px;
            }
        }
    </style>
  
    <script>
        function updateStatus(id, action) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('action', action);

            fetch('', { method: 'POST', body: formData })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    document.getElementById('candidate-' + id).remove(); // Remove candidate from the list
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</head>
<body class="manage_body">
    <div class="container">
        <h1>Manage Candidates</h1>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($candidates)) { ?>
                <li id="candidate-<?= $row['id'] ?>">
                    <div>
                        <strong><?= htmlspecialchars($row['candidate_name']) ?></strong> - 
                        <em><?= htmlspecialchars($row['position']) ?></em>
                    </div>
                    <div>
                        <button class="select-btn" onclick="updateStatus(<?= $row['id'] ?>, 'selected')">Select</button>
                        <button class="reject-btn" onclick="updateStatus(<?= $row['id'] ?>, 'rejected')">Reject</button>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>
