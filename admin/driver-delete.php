<?php require_once('header.php'); ?>

<?php
if (!isset($_GET['driverID']) || $_GET['driverID'] == '') {
    header("Location: driver.php");
}

$driverID = $_GET['driverID'];

// Check if the supplier exists in the database
$statement = $pdo->prepare("SELECT * FROM tbl_driver WHERE driver_id=?");
$statement->execute([$driverID]);
$total = $statement->rowCount();

if ($total == 0) {
    header("Location: driver.php");
}

// Delete the supplier from the database
$statement = $pdo->prepare("DELETE FROM tbl_driver WHERE driver_id=?");
$statement->execute([$driverID]);

header("Location: driver.php");
?>

<?php require_once('footer.php'); ?>
