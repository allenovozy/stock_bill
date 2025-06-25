-- Create database 
DATABASE NAME bills

and table structure for the stock bill payment system
CREATE DATABASE IF NOT EXISTS bill;
USE bill;

CREATE TABLE IF NOT EXISTS bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bill_number VARCHAR(20) UNIQUE NOT NULL,
    customer_name VARCHAR(100) NOT NULL,
    customer_email VARCHAR(100),
    customer_phone VARCHAR(20),
    stock_item VARCHAR(100) NOT NULL,
    quantity INT NOT NULL,
    price_per_unit DECIMAL(10,2) NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    tax_amount DECIMAL(10,2) DEFAULT 0,
    final_amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_status VARCHAR(20) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO bills (bill_number, customer_name, customer_email, customer_phone, stock_item, quantity, price_per_unit, total_price, tax_amount, final_amount, payment_method, payment_status) VALUES
('BILL-001', 'John Doe', 'john@example.com', '+1234567890', 'Premium Stock A', 100, 25.50, 2550.00, 255.00, 2805.00, 'Credit Card', 'Paid'),
('BILL-002', 'Jane Smith', 'jane@example.com', '+1234567891', 'Standard Stock B', 50, 15.75, 787.50, 78.75, 866.25, 'Bank Transfer', 'Paid'),
('BILL-003', 'Mike Johnson', 'mike@example.com', '+1234567892', 'Premium Stock C', 75, 32.00, 2400.00, 240.00, 2640.00, 'Cash', 'Pending');