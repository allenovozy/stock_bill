<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $customer_name = sanitizeInput($_POST['customer_name']);
    $customer_email = sanitizeInput($_POST['customer_email']);
    $customer_phone = sanitizeInput($_POST['customer_phone']);
    $stock_item = sanitizeInput($_POST['stock_item']);
    $quantity = intval($_POST['quantity']);
    $price_per_unit = floatval($_POST['price_per_unit']);
    $payment_method = sanitizeInput($_POST['payment_method']);
    
    // Calculate amounts
    $subtotal = $quantity * $price_per_unit;
    $tax_rate = 0.10; // 10% tax
    $tax_amount = $subtotal * $tax_rate;
    $final_amount = $subtotal + $tax_amount;
    
    // Generate unique bill number
    $bill_number = generateBillNumber();
    
    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO bills (bill_number, customer_name, customer_email, customer_phone, stock_item, quantity, price_per_unit, total_price, tax_amount, final_amount, payment_method, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
    
    $stmt->bind_param("sssssidddds", $bill_number, $customer_name, $customer_email, $customer_phone, $stock_item, $quantity, $price_per_unit, $subtotal, $tax_amount, $final_amount, $payment_method);
    
    if ($stmt->execute()) {
        // Redirect to bill display page
        header("Location: view_bill.php?bill_number=" . $bill_number);
        exit();
    } else {
        $error_message = "Error creating bill: " . $conn->error;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header("Location: input.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Fizzy Stock</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
        }
        .error-message {
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        .back-btn {
            background: #667eea;
            color: white;
            padding: 0.5rem 1rem;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <?php if (isset($error_message)): ?>
    <div class="error-container">
        <h2>Error</h2>
        <p class="error-message"><?php echo $error_message; ?></p>
        <a href="input.html" class="back-btn">Try Again</a>
    </div>
    <?php endif; ?>
</body>
</html>