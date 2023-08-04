<?php
require_once('header.php');

$error_message = '';
$success_message = '';

if (!isset($_GET['driver_id'])) {
    header('location: driver.php'); // Redirect to the driver list page
    exit;
} else {
    $driverID = $_GET['driver_id'];

    // Check if the driver ID is valid or not
    $statement = $pdo->prepare("SELECT * FROM tbl_driver WHERE driver_id = ?");
    $statement->execute([$driverID]);
    $total = $statement->rowCount();

    if ($total == 0) {
        header('location: driver.php'); // Redirect to the driver list page
        exit;
    } else {
        if (isset($_POST['form1'])) {
            $valid = 1;

            if (empty($_POST['driver_name'])) {
                $valid = 0;
                $error_message .= "Driver name cannot be empty<br>";
            }

            if (empty($_POST['driver_email'])) {
                $valid = 0;
                $error_message .= "Driver email cannot be empty<br>";
            }

            // Add more validations as per your requirements

            if ($valid == 1) {
                // Update driver data in the database
                $statement = $pdo->prepare("UPDATE tbl_driver SET driver_name=?, Email=?, ContactPerson=?, Phone=?, Address=? WHERE driver_id=?");
                $statement->execute(array($_POST['driver_name'], $_POST['driver_email'], $_POST['contact_person'], $_POST['phone'], $_POST['address'], $driverID));
                $success_message = 'Driver updated successfully.';
            }
        }

        // Fetch the driver data for pre-filling the form
        $statement = $pdo->prepare("SELECT * FROM tbl_driver WHERE driver_id = ?");
        $statement->execute([$driverID]);
        $driver = $statement->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Edit Driver</h1>
    </div>
    <div class="content-header-right">
        <a href="driver.php" class="btn btn-primary btn-sm">View All</a>
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
                            <label for="" class="col-sm-3 control-label">Driver Name <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="text" name="driver_name" class="form-control" value="<?php echo $driver['driver_name']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Driver Email <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="email" name="driver_email" class="form-control" value="<?php echo $driver['Email']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Contact Person</label>
                            <div class="col-sm-4">
                                <input type="text" name="contact_person" class="form-control" value="<?php echo $driver['ContactPerson']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Phone</label>
                            <div class="col-sm-4">
                                <input type="text" name="phone" class="form-control" value="<?php echo $driver['Phone']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Address</label>
                            <div class="col-sm-4">
                                <textarea name="address" class="form-control"><?php echo $driver['Address']; ?></textarea>
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