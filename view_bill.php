<?php
include "config.php";

$bill_number = isset($_GET['bill_number']) ? sanitizeInput($_GET['bill_number']) : '';

if (empty($bill_number)) {
    header("Location: index.html");
    exit();
}

// Fetch bill data
$stmt = $conn->prepare("SELECT * FROM bills WHERE bill_number = ?");
$stmt->bind_param("s", $bill_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: index.html");
    exit();
}

$bill = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill <?php echo $bill['bill_number']; ?> - Fizzy Stock</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f6fa;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .bill-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 2rem;
            position: relative;
        }

        .company-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .company-logo {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .bill-title {
            font-size: 2rem;
            font-weight: bold;
        }

        .bill-number {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .bill-content {
            padding: 2rem;
        }

        .bill-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .info-section h3 {
            color: #333;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            border-bottom: 2px solid #667eea;
            padding-bottom: 0.5rem;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
        }

        .info-label {
            font-weight: 600;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 2rem 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .items-table th {
            background: #667eea;
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        .items-table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .items-table tr:last-child td {
            border-bottom: none;
        }

        .total-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 2rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            padding: 0.5rem 0;
        }

        .total-row.final {
            border-top: 2px solid #667eea;
            font-size: 1.2rem;
            font-weight: bold;
            color: #667eea;
            margin-top: 1rem;
            padding-top: 1rem;
        }

        .payment-info {
            background: #e8f4fd;
            padding: 1.5rem;
            border-radius: 10px;
            margin-top: 1.5rem;
            border-left: 4px solid #667eea;
        }

        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .bill-info {
                grid-template-columns: 1fr;
            }
            
            .company-info {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .items-table {
                font-size: 0.9rem;
            }
            
            .items-table th,
            .items-table td {
                padding: 0.5rem;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .actions {
                display: none;
            }
            
            .container {
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="bill-header">
            <div class="company-info">
                <div>
                    <div class="company-logo">FB</div>
                </div>
                <div style="text-align: right;">
                    <div class="bill-title">STOCK BILL</div>
                    <div class="bill-number"><?php echo $bill['bill_number']; ?></div>
                </div>
            </div>
            <div style="text-align: center; margin-top: 1rem;">
                <h2>Fizzy Stock Payment System</h2>
                <p>Professional Stock Management Solutions</p>
            </div>
        </div>

        <div class="bill-content">
            <div class="bill-info">
                <div class="info-section">
                    <h3>Customer Information</h3>
                    <div class="info-item">
                        <span class="info-label">Name:</span>
                        <span class="info-value"><?php echo $bill['customer_name']; ?></span>
                    </div>
                    <?php if (!empty($bill['customer_email'])): ?>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo $bill['customer_email']; ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($bill['customer_phone'])): ?>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value"><?php echo $bill['customer_phone']; ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="info-section">
                    <h3>Bill Information</h3>
                    <div class="info-item">
                        <span class="info-label">Bill Number:</span>
                        <span class="info-value"><?php echo $bill['bill_number']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Date:</span>
                        <span class="info-value"><?php echo date('F j, Y', strtotime($bill['created_at'])); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Time:</span>
                        <span class="info-value"><?php echo date('g:i A', strtotime($bill['created_at'])); ?></span>
                    </div>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?php echo $bill['stock_item']; ?></td>
                        <td><?php echo $bill['quantity']; ?></td>
                        <td>$<?php echo number_format($bill['price_per_unit'], 2); ?></td>
                        <td>$<?php echo number_format($bill['total_price'], 2); ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($bill['total_price'], 2); ?></span>
                </div>
                <div class="total-row">
                    <span>Tax (10%):</span>
                    <span>$<?php echo number_format($bill['tax_amount'], 2); ?></span>
                </div>
                <div class="total-row final">
                    <span>Total Amount:</span>
                    <span>$<?php echo number_format($bill['final_amount'], 2); ?></span>
                </div>
            </div>

            <div class="payment-info">
                <h3 style="margin-bottom: 1rem; color: #333;">Payment Information</h3>
                <div class="info-item">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value"><?php echo $bill['payment_method']; ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Payment Status:</span>
                    <span class="status-badge <?php echo $bill['payment_status'] == 'Paid' ? 'status-paid' : 'status-pending'; ?>">
                        <?php echo $bill['payment_status']; ?>
                    </span>
                </div>
            </div>

            <div class="actions">
                <button onclick="window.print()" class="btn btn-primary">Print Bill</button>
                <a href="input.html" class="btn btn-secondary">Create New Bill</a>
                <a href="index.html" class="btn btn-secondary">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>