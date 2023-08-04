<?php
session_start(); // Start the session

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

$adminEmail = 'ongweian1234@gmail.com'; // Replace with the admin's email address
$driverEmail = isset($_SESSION['Email']) ? $_SESSION['Email'] : '';

$mail = new PHPMailer();

try {
    // Configure SMTP settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server address
    $mail->SMTPAuth = true;
    $mail->Username = 'ongweian1234@gmail.com'; // Replace with your SMTP username
    $mail->Password = 'shkclnxfqzhnxsgg'; // Replace with your SMTP password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Set the sender and recipient email addresses
    $mail->setFrom($driverEmail);
    $mail->addAddress($adminEmail);

    // Set email subject and body
    $mail->Subject = 'Invoice Uploaded';
    $mail->Body = "Driver $driverEmail has uploaded an invoice.";

    // Check if the form was submitted
    if (isset($_POST['submit']) && isset($_FILES['my_image']) && isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
        $img_name = $_FILES['my_image']['name'];
        $img_size = $_FILES['my_image']['size'];
        $tmp_name = $_FILES['my_image']['tmp_name'];
        $error = $_FILES['my_image']['error'];

        if ($error === 0) {
            if ($img_size > 50000000) {
                $em = "Sorry, your file is too large.";
                echo "<p class='error-message'>$em</p>";
            } else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);

                $allowed_exs = array("jpg", "jpeg", "png");

                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                    $img_upload_path = 'uploads/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    // Establish database connection
                    $dsn = 'mysql:host=localhost;dbname=ecommerceweb';
                    $username = 'root';
                    $password = '';
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ];

                    try {
                        $pdo = new PDO($dsn, $username, $password, $options);
                    } catch (PDOException $e) {
                        throw new PDOException($e->getMessage(), (int)$e->getCode());
                    }

                    // Update the record with the uploaded photo
                    $update_sql = "UPDATE tbl_order SET upload_invoice = :new_img_name WHERE id = :id";
                    $stmt = $pdo->prepare($update_sql);
                    $stmt->execute(array(':new_img_name' => $new_img_name, ':id' => $order_id));

                    echo "<p class='success-message'>Invoice uploaded successfully!</p>";

                    // Send the email notification
                    $mail->send();
                } else {
                    $em = "You can't upload files of this type";
                    echo "<p class='error-message'>$em</p>";
                }
            }
        } else {
            $em = "Unknown error occurred!";
            echo "<p class='error-message'>$em</p>";
        }
    }
} catch (Exception $e) {
    echo "<p class='error-message'>Error sending email: " . $mail->ErrorInfo . "</p>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Upload Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #333;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1);
        }

        .container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #00c1f7;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 16px;
            color: #00c1f7;
        }

        .form-group input[type="text"],
        .form-group input[type="file"],
        .form-group select {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 3px;
            background-color: #444;
            color: #fff;
            font-size: 16px;
            outline: none;
        }

        .form-group input[type="file"] {
            margin-top: 10px;
        }

        .form-group input[type="submit"],
        .form-group a {
            background-color: #00c1f7;
            color: #000;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .form-group input[type="submit"]:hover,
        .form-group a:hover {
            background-color: #0086b3;
        }

        .error-message,
        .success-message {
            margin-bottom: 10px;
            text-align: center;
            padding: 8px;
            border-radius: 3px;
            font-size: 16px;
        }

        .error-message {
            color: #FF0000;
            background-color: #FFC0C0;
        }

        .success-message {
            color: #00c1f7;
            background-color: #C0FFC0;
        }

        .product-id {
            font-weight: bold;
            margin-top: 10px;
            font-size: 16px;
        }

        .email-form {
            text-align: center;
            margin-top: 20px;
        }

        .email-form label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            color: #00c1f7;
        }

        .email-form input[type="text"] {
            width: 100%;
            max-width: 300px;
            padding: 10px;
            border: none;
            border-radius: 3px;
            font-size: 16px;
            outline: none;
            background-color: #444;
            color: #fff;
        }

        .email-form button[type="submit"] {
            background-color: #00c1f7;
            color: #000;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .email-form button[type="submit"]:hover {
            background-color: #0086b3;
        }

        .email-form a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #00c1f7;
            font-size: 14px;
            transition: color 0.3s;
        }

        .email-form a:hover {
            color: #0086b3;
        }

        .driver-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .driver-info h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #00c1f7;
        }

        .driver-info p {
            margin-bottom: 5px;
            font-size: 16px;
        }

        .driver-info .info-label {
            font-weight: bold;
        }

        @media (max-width: 600px) {
            .container {
                padding: 10px;
                width: 100%;
            }
        }
    </style>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="driver-info">
        <?php
        if (isset($_SESSION['Email'])) {
            // Retrieve the driver's email from the session
            $driverEmail = $_SESSION['Email'];

            // Establish database connection
            $dsn = 'mysql:host=localhost;dbname=ecommerceweb';
            $username = 'root';
            $password = '';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $pdo = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }

            // Fetch the driver's information from the tbl_driver table based on their email
            $stmt = $pdo->prepare("SELECT driver_id, driver_name, Email FROM tbl_driver WHERE Email = :email");
            $stmt->execute(['email' => $driverEmail]);
            $driver = $stmt->fetch();

            // Display the driver's information
            echo "<h3>Driver Information</h3>";
            echo "<p><span class='info-label'>Driver ID:</span> " . $driver['driver_id'] . "</p>";
            echo "<p><span class='info-label'>Driver Name:</span> " . $driver['driver_name'] . "</p>";
            echo "<p><span class='info-label'>Email:</span> " . $driver['Email'] . "</p>";
        } else {
            echo "<p>Driver not logged in.</p>";
        }
        ?>
    </div>

    <form action="driver-login.php" method="post" class="email-form">
        <?php if (isset($_GET['error'])) { ?>
            <p class="error-message"><?php echo $_GET['error']; ?></p>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
            <p class="success-message"><?php echo $_GET['success']; ?></p>
        <?php } ?>
        <label>Email</label>
        <input type="text" name="email" placeholder="Email@gmail.com">
        <button type="submit">Login</button>
        <a href="driver-logout.php">Logout</a>
    </form>

    <div class="form-container">
        <form action="upload-invoice.php" method="post" enctype="multipart/form-data">
            <?php
            // Check if there is an error message in the URL query parameter
            if (isset($_GET['error'])) {
                echo "<p class='error-message'>{$_GET['error']}</p>";
            }

            // Establish database connection
            $dsn = 'mysql:host=localhost;dbname=ecommerceweb';
            $username = 'root';
            $password = '';
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                $pdo = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }

            // Fetch the list of orders
            $stmt = $pdo->prepare("SELECT id, product_id FROM tbl_order");
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Check if the form was submitted

            ?>

            <div class="form-group">
                <label for="order_id">Select Order:</label>
                <select name="order_id" id="order_id">
                    <option value="" selected>Select Order</option>
                    <?php foreach ($orders as $order) { ?>
                        <option value="<?php echo $order['id']; ?>"><?php echo $order['id'] . ' - ' . $order['product_id']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <?php if (isset($_POST['submit']) && isset($_FILES['my_image']) && isset($_POST['order_id'])) {
                $selected_order_id = $_POST['order_id'];
                $stmt = $pdo->prepare("SELECT product_id FROM tbl_order WHERE id = :order_id");
                $stmt->execute(array(':order_id' => $selected_order_id));
                $product = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
                <div class="form-group">
                    <label for="product_id">Product ID:</label>
                    <p class="product-id"><?php echo $product['product_id']; ?></p>
                </div>
            <?php } ?>
            <div class="form-group">
                <input type="file" name="my_image">
            </div>
            <div class="form-group">
                <input type="submit" name="submit" value="Upload">
            </div>
        </form>
    </div>
</body>

</html>