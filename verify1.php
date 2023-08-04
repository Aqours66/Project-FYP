<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $banner_registration = $row['banner_registration'];
}
?>

<div class="page-banner" style="background-color:#444;background-image: url(assets/uploads/<?php echo $banner_registration; ?>);">
    <div class="inner">
        <h1>Subscriber Registration</h1>
        <div class="verification-message" style="text-align: center; color: white;">
            <?php
            if (isset($_GET['token'])) {
                $token = $_GET['token'];

                // Retrieve the subscriber with the matching token from the database
                $statement = $pdo->prepare("SELECT * FROM tbl_subscriber WHERE subs_hash = :token");
                $statement->bindValue(':token', $token);
                $statement->execute();
                $subscriber = $statement->fetch(PDO::FETCH_ASSOC);

                // Check if a subscriber was found with the provided token
                if ($subscriber) {
                    // Check if the subscriber is already active
                    if ($subscriber['subs_active'] == 1) {
                        // Display a message if the subscriber is already active
                        echo 'Your email is already verified.';
                    } else {
                        // Set the subscriber's status to active (assuming 1 represents active status)
                        $statement = $pdo->prepare("UPDATE tbl_subscriber SET subs_active = 1 WHERE subs_hash = :token");
                        $statement->bindValue(':token', $token);
                        $statement->execute();

                        // Display a success message to the subscriber
                        echo 'Your email has been successfully verified.';
                    }
                } else {
                    // Display an error message if the token is not valid
                    echo 'Invalid token. Please try again or contact support.';
                }
            } else {
                // Display an error message if the token parameter is missing
                echo 'Invalid URL. Please provide a token.';
            }
            ?>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>