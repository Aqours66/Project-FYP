<!DOCTYPE html>
<html>

<head>
    <title>Invoice</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        /* Custom styles for the invoice */
        body {
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f5ff;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h2 {
            margin-bottom: 30px;
            color: #007bff;
            text-transform: uppercase;
        }

        .invoice-details {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        table th,
        table td {
            text-align: center;
            padding: 8px;
            border: 1px solid #e9e9e9;
            color: #000;
        }

        .company-logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            height: auto;
        }

        /* Print styles */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .container {
                max-width: 100%;
            }

            h2 {
                margin: 0;
            }

            .company-logo {
                display: none;
            }

            .print-button {
                display: none;
            }

            .invoice-details {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: avoid;
            }

            .customer-signature {
                page-break-inside: avoid;
                position: fixed;
                bottom: 20px;
                left: 20px;
                width: 200px;
                margin-top: 20px;
                padding-bottom: 100px;
            }

            .signature-label {
                page-break-inside: avoid;
                font-weight: bold;
                margin-bottom: 70px;
                /* Add margin-bottom to create spacing */
            }

            .signature-line {
                page-break-inside: avoid;
                background-color: black;
                margin-top: 10px;
                /* Add margin-top to create spacing */
            }
        }

        /* Positioning for the print button */
        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-transform: uppercase;
        }

        .customer-signature {
            margin-top: 200px;
        }

        .signature-label {
            font-weight: bold;
        }

        .signature-line {
            position: absolute;
            bottom: 0px;
            left: 10px;
            width: 200px;
            height: 1px;
            background-color: black;
        }

        /* Custom colors */
        .bg-primary {
            background-color: #007bff !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .bg-secondary {
            background-color: #6c757d !important;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .bg-info {
            background-color: #17a2b8 !important;
        }

        .text-info {
            color: #17a2b8 !important;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .text-success {
            color: #28a745 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        /* Futuristic styles */
        .invoice-details {
            border-bottom: 2px dashed #007bff;
            padding-bottom: 20px;
        }

        table th,
        table td {
            background-color: #eff6ff;
        }

        .bg-primary {
            background-color: #007bff !important;
        }

        .text-primary {
            color: #007bff !important;
        }

        .print-button {
            background-color: #007bff;
        }

        .customer-signature {
            padding-bottom: 40px;
        }

        .signature-label {
            color: #007bff;
        }

        .signature-line {
            background-color: #007bff;
        }
    </style>
</head>

<body>
    <?php
    include 'inc/config.php';

    // Retrieve payment ID from URL
    if (isset($_GET['payment_id'])) {
        $paymentId = $_GET['payment_id'];

        // Retrieve customer details and total amount based on payment ID
        $statement = $pdo->prepare("SELECT tbl_payment.*, SUM(tbl_order.quantity * tbl_order.unit_price) AS total_amount, tbl_order.id AS order_id, tbl_order.product_id AS product_id FROM tbl_payment JOIN tbl_order ON tbl_payment.payment_id = tbl_order.payment_id WHERE tbl_payment.payment_id = ? GROUP BY tbl_payment.payment_id");
        $statement->bindParam(1, $paymentId);
        $statement->execute();
        $payment = $statement->fetch(PDO::FETCH_ASSOC);

        // Retrieve order details based on payment ID
        $statement = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id = ?");
        $statement->bindParam(1, $paymentId);
        $statement->execute();
        $orders = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Generate invoice HTML
        $invoiceHtml = '
            <h2 class="text-primary">Invoice</h2>
            <div class="invoice-details">
                <p><strong>Order ID:</strong> ' . $payment['order_id'] . '</p>
                <p><strong>Product ID:</strong> ' . $payment['product_id'] . '</p>
                <p><strong>Payment ID:</strong> ' . $payment['payment_id'] . '</p>
                <p><strong>Customer Name:</strong> ' . $payment['customer_name'] . '</p>
                <p><strong>Customer Email:</strong> ' . $payment['customer_email'] . '</p>
                <!-- Include any other necessary order and payment details -->
            </div>
            <img src="path_to_your_logo.png" alt="Company Logo" class="company-logo">
            <table class="table">
                <thead>
                    <tr class="bg-secondary">
                        <th class="text-black">Product Name</th>
                        <th class="text-black">Quantity</th>
                        <th class="text-black">Unit Price</th>
                        <th class="text-black">Total</th>
                    </tr>
                </thead>
                <tbody>';

        // Loop through orders to generate invoice items
        foreach ($orders as $order) {
            $productName = $order['product_name'];
            $quantity = $order['quantity'];
            $unitPrice = $order['unit_price'];
            $total = $quantity * $unitPrice;

            $invoiceHtml .= '
                    <tr>
                        <td style="color: #000;">' . $productName . '</td>
                        <td style="color: #000;">' . $quantity . '</td>
                        <td style="color: #000;">' . $unitPrice . '</td>
                        <td style="color: #000;">' . $total . '</td>
                    </tr>';
        }

        $invoiceHtml .= '
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-right"><strong style="color: #000;">Total Amount:</strong></td>
                        <td style="color: #000;">' . $payment['total_amount'] . '</td>
                    </tr>
                </tfoot>
            </table>

            <div class="customer-signature">
                <p class="signature-label text-primary" style="color: #007bff;">Customer Signature:</p>
                <!-- Add the customer signature placeholder here -->
                <hr class="signature-line">
            </div>';

        // Output the invoice HTML
        echo $invoiceHtml;
    } else {
        echo 'Payment ID not specified.';
    }
    ?>

    <button class="btn btn-primary print-button" onclick="window.print()">Print</button>
</body>

</html>