<?php
// Include the config.php file
require_once 'config.php';

// Get the uploaded file
$file = $_FILES['invoiceFile'];

// Perform server-side validation on the file (e.g., file format, size) if needed

// Move the file to a permanent location
$targetDir = "uploads/";
$fileName = basename($file['name']);
$targetPath = $targetDir . $fileName;
if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // File uploaded successfully

    // Get the order details
    $orderId = $_POST['orderId'];

    // Update the order record in the database with the invoice file name
    $sql = "UPDATE tbl_order SET invoice_file = '$fileName' WHERE id = $orderId";
    if ($conn->query($sql) === TRUE) {
        echo "Invoice uploaded successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Error uploading file.";
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Upload Invoice</title>
    <script>
        function submitInvoice() {
            var fileInput = document.getElementById('invoiceFileInput');
            var file = fileInput.files[0];

            if (file) {
                // Perform client-side validation on the file (e.g., file format, size)

                var formData = new FormData();
                formData.append('invoiceFile', file);
                formData.append('orderId', orderId); // Pass the order ID to the server

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'upload_invoice.php', true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Response from the server
                        console.log(xhr.responseText);
                        // Perform any additional actions or display messages as needed
                    }
                };
                xhr.send(formData);
            }
        }
    </script>
</head>

<body>
    <form>
        <input type="file" id="invoiceFileInput">
        <button type="button" onclick="submitInvoice()">Upload Invoice</button>
    </form>
</body>

</html>