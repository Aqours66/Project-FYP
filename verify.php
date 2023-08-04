<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
}
?>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to the PHPMailer autoloader

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Retrieve the user with the matching token from the database
    $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_token = :token");
    $statement->bindValue(':token', $token);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if a user was found with the provided token
    if ($user) {
        // Check if the user is already verified
        if ($user['cust_status'] == 1) {
            // Display a message if the user is already verified
            $verificationMessage = 'Your account is already verified. You can proceed to log in.';
        } else {
            // Set the user's status to active (assuming 1 represents active status)
            $statement = $pdo->prepare("UPDATE tbl_customer SET cust_status = 1 WHERE cust_token = :token");
            $statement->bindValue(':token', $token);
            $statement->execute();

            // Display a success message to the user
            $verificationMessage = 'Your account has been successfully verified. You can now log in.';
        }
    } else {
        // Display an error message if the token is not valid
        $verificationMessage = 'Invalid token. Please try again or contact support.';
    }
} else {
    // Display an error message if the token parameter is missing
    $verificationMessage = 'Invalid URL. Please provide a token.';
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
    <div class="inner">
        <h1>Customer Registration</h1>
        <div class="verification-message" style="color: white; text-align: center;">
            <?php echo $verificationMessage; ?>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>