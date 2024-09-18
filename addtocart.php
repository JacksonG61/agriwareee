<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "create-user";  // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission for adding to cart and purchase
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'purchase') {
        $phone = $_POST['phone'];
        $username = $_POST['username'];
        $products = json_decode($_POST['products'], true);
        $totalPrice = $_POST['totalPrice'];

        // Prepare the purchase message
        $message = "🛒 New Purchase Order:\n";
        $message .= "User: $username\nPhone: $phone\n\n";
        foreach ($products as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $message .= "Product: {$item['name']}\nPrice: ៛ {$item['price']}\nQuantity: {$item['quantity']}\nSubtotal: ៛ $subtotal\n\n";
        }
        $message .= "Total Price: ៛ $totalPrice";

        // Send the purchase details to the Telegram bot
        $botToken = '6893994364:AAHYI0MYtW84KsJNoO8TcEW4CWt-IE78BaA';
        $chatId = '-1002347920083';
        $telegramUrl = "https://api.telegram.org/bot$botToken/sendMessage";

        $ch = curl_init($telegramUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'chat_id' => $chatId,
            'text' => $message
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        if ($response) {
            echo 'success';
        } else {
            echo 'error';
        }
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 20px;
        }
        .cart-item {
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .quantity {
            display: flex;
            align-items: center;
        }
        .quantity button {
            margin: 0 5px;
        }
        .btn-back {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Cart</h2>
        <div id="cartItems"></div>
        <div>
            <h3>Total Price: <span id="totalPrice">៛ 0</span></h3>
            <button id="purchaseBtn" class="btn btn-success">Proceed to Purchase</button>
        </div>
    </div>
    <script src="invoice.js"></script>
</body>
</html>
