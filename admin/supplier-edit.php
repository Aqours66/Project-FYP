<?php
require_once('header.php');

$error_message = '';
$success_message = '';

if (!isset($_GET['supplier_id'])) {
    header('location: supplier.php'); // Redirect to the supplier list page
    exit;
} else {
    $supplierID = $_GET['supplier_id'];

    // Check if the supplier ID is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_supplier WHERE supplier_id = ?");
    $statement->execute([$supplierID]);
    $total = $statement->rowCount();

    if ($total == 0) {
        header('location: supplier.php'); // Redirect to the supplier list page
        exit;
    } else {
        if (isset($_POST['form1'])) {
            $valid = 1;

            if (empty($_POST['supplier_name'])) {
                $valid = 0;
                $error_message .= "Supplier name cannot be empty<br>";
            }

            if (empty($_POST['supplier_email'])) {
                $valid = 0;
                $error_message .= "Supplier email cannot be empty<br>";
            }

            // Add more validations as per your requirements

            if ($valid == 1) {
                // Update supplier data in the database
                $statement = $pdo->prepare("UPDATE tbl_supplier SET supplier_name=?, Email=?, ContactPerson=?, Phone=?, Address=?, ProductsServices=? WHERE supplier_id=?");
                $statement->execute(array($_POST['supplier_name'], $_POST['supplier_email'], $_POST['contact_person'], $_POST['phone'], $_POST['address'], $_POST['products_services'], $supplierID));
                $success_message = 'Supplier updated successfully.';
            }
        }

        // Fetch the supplier data for pre-filling the form
        $statement = $pdo->prepare("SELECT * FROM tbl_supplier WHERE supplier_id = ?");
        $statement->execute([$supplierID]);
        $supplier = $statement->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Edit Supplier</h1>
    </div>
    <div class="content-header-right">
        <a href="supplier.php" class="btn btn-primary btn-sm">View All</a>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if ($error_message) : ?>
                <div class="callout callout-danger">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($success_message) : ?>
                <div class="callout callout-success">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>

            <form class="form-horizontal" action="" method="post">
                <div class="box box-info">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Supplier Name <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="text" name="supplier_name" class="form-control" value="<?php echo $supplier['supplier_name']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Supplier Email <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="email" name="supplier_email" class="form-control" value="<?php echo $supplier['Email']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Contact Person</label>
                            <div class="col-sm-4">
                                <input type="text" name="contact_person" class="form-control" value="<?php echo $supplier['ContactPerson']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Phone</label>
                            <div class="col-sm-4">
                                <input type="text" name="phone" class="form-control" value="<?php echo $supplier['Phone']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Address</label>
                            <div class="col-sm-4">
                                <textarea name="address" class="form-control"><?php echo $supplier['Address']; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Products/Services</label>
                            <div class="col-sm-4">
                                <textarea name="products_services" class="form-control"><?php echo $supplier['ProductsServices']; ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-offset-3 col-sm-4">
                            <button type="submit" class="btn btn-success" name="form1">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>