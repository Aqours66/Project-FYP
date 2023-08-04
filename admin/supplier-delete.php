<?php require_once('header.php'); ?>

<?php
if (!isset($_GET['supplierID']) || $_GET['supplierID'] == '') {
    header("Location: supplier.php");
}

$supplierID = $_GET['supplierID'];

// Check if the supplier exists in the database
$statement = $pdo->prepare("SELECT * FROM tbl_supplier WHERE SupplierID=?");
$statement->execute([$supplierID]);
$total = $statement->rowCount();

if ($total == 0) {
    header("Location: supplier.php");
}

// Delete the supplier from the database
$statement = $pdo->prepare("DELETE FROM tbl_supplier WHERE SupplierID=?");
$statement->execute([$supplierID]);

header("Location: supplier.php");
?>

<?php require_once('footer.php'); ?>
