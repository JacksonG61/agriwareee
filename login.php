<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "create-user";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login form submission
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phoneLogin = $_POST['phoneLogin'];
    $passwordLogin = $_POST['passwordLogin'];

    // Retrieve the hashed password from the database
    $stmt = $conn->prepare("SELECT password, username FROM users WHERE phone = ?");
    $stmt->bind_param("s", $phoneLogin);
    $stmt->execute();
    $stmt->bind_result($hashedPassword, $username);
    $stmt->fetch();

    if (password_verify($passwordLogin, $hashedPassword)) {
        // Set JavaScript variable to store user info in localStorage
        echo "<script>
            localStorage.setItem('loggedInUser', JSON.stringify({phone: '$phoneLogin', username: '$username'}));
            window.location.href = 'index2.html';
        </script>";
    } else {
        $message = "Invalid phone number or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
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
        <h2>User Login</h2>
        <?php if (!empty($message)) { ?>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
        <?php } ?>
        <form id="loginForm" method="POST" action="">
            <div class="form-group">
                <label for="phoneLogin">Phone Number:</label>
                <input type="tel" class="form-control" id="phoneLogin" name="phoneLogin" required>
            </div>
            <div class="form-group">
                <label for="passwordLogin">Password:</label>
                <input type="password" class="form-control" id="passwordLogin" name="passwordLogin" required>
            </div>
            <button type="submit" class="btn btn-success">Login</button>
        </form>
    </div>
</body>
</html>
