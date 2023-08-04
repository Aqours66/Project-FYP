<?php require_once('header.php'); ?>

<?php
$error_message = '';
$success_message = '';

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
        // Save driver data to the database
        $statement = $pdo->prepare("INSERT INTO tbl_driver(driver_name, Email, ContactPerson, Phone, Address) VALUES (?, ?, ?, ?, ?)");
        $statement->execute(array($_POST['driver_name'], $_POST['driver_email'], $_POST['contact_person'], $_POST['phone'], $_POST['address']));

        $success_message = 'Driver added successfully.';
    }
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add Driver</h1>
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
                                <input type="text" name="driver_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Driver Email <span>*</span></label>
                            <div class="col-sm-4">
                                <input type="email" name="driver_email" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Contact Person</label>
                            <div class="col-sm-4">
                                <input type="text" name="contact_person" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Phone</label>
                            <div class="col-sm-4">
                                <input type="text" name="phone" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Address</label>
                            <div class="col-sm-4">
                                <textarea name="address" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-sm-offset-3 col-sm-4">
                            <button type="submit" class="btn btn-success" name="form1">Save</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>