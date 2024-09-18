<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "create-user";  // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $user = $_POST['username'];
    $phone = $_POST['phone'];
    $pass = $_POST['password'];

    // Hash the password for security
    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

    // Prepare and bind the statement with the hashed password
    $stmt = $conn->prepare("INSERT INTO users (username, phone, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $phone, $hashedPass);

    // Execute the statement
    if ($stmt->execute()) {
        $message = "New user created successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Create User Account</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create User Account</h2>
        
        <!-- Display success/error message -->
        <?php if (!empty($message)) { ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <!-- User creation form -->
        <form id="createAccountForm" method="POST" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>
    </div>
</body>
</html>
