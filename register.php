<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register New Candidate</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Register New Candidate</h1>
        <form id="registerForm">
            <div class="input-group">
                <label for="candidateName">Candidate Name</label>
                <input type="text" id="candidateName" required>
            </div>
            <div class="input-group">
                <label for="candidateEmail">Candidate Email</label>
                <input type="email" id="candidateEmail" required>
            </div>
            <div class="input-group">
                <label for="candidateResume">Upload Resume</label>
                <input type="file" id="candidateResume" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>