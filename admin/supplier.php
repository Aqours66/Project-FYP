<?php require_once('header.php'); ?>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php'; // Assuming this file contains PHPMailer configuration 

// Function to send email
function sendEmail($pdo, $supplierID, $subject, $message)
{
    // Retrieve supplier information from the database based on the supplier ID
    $statement = $pdo->prepare("SELECT * FROM tbl_supplier WHERE supplier_id = :supplierID");
    $statement->bindParam(':supplierID', $supplierID, PDO::PARAM_INT);
    $statement->execute();
    $supplier = $statement->fetch(PDO::FETCH_ASSOC);

    // Create a new instance of PHPMailer
    $mailer = new PHPMailer(true);

    try {
        // Server settings
        $mailer->SMTPDebug = SMTP::DEBUG_OFF; // Enable verbose debug output. Set to SMTP::DEBUG_SERVER for detailed debug output.
        $mailer->isSMTP(); // Send using SMTP
        $mailer->Host = 'smtp.gmail.com'; // SMTP server address
        $mailer->SMTPAuth = true; // Enable SMTP authentication
        $mailer->Username = 'ongweian1234@gmail.com'; // SMTP username
        $mailer->Password = 'shkclnxfqzhnxsgg'; // SMTP password
        $mailer->SMTPSecure = 'tls'; // Enable TLS encryption
        $mailer->Port = 587; // TCP port to connect to

        // Set email content
        $mailer->setFrom('ongweian1234@gmail.com', 'Lensify');
        $mailer->addAddress($supplier['Email'], $supplier['ContactPerson']);
        $mailer->Subject = $subject;
        $mailer->Body = $message;

        // Send the email
        if ($mailer->send()) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email: " . $mailer->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    // Retrieve the supplier ID and email details from the form
    $supplierID = $_POST['supplier_id'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Call the sendEmail function
    sendEmail($pdo, $supplierID, $subject, $message);
}

?>

<section class="content-header">
    <div class="content-header-left">
        <h1>View Suppliers</h1>
    </div>
    <div class="content-header-right">
        <a href="supplier-add.php" class="btn btn-primary btn-sm">Add Supplier</a>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th width="10">#</th>
                                <th>Supplier Name</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Products/Services</th>
                                <th width="80">Action</th>
                                <th width="80">Send Email</th> <!-- New column for sending email -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $statement = $pdo->prepare("SELECT * FROM tbl_supplier");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $row['supplier_name']; ?></td>
                                    <td><?php echo $row['ContactPerson']; ?></td>
                                    <td><?php echo $row['Email']; ?></td>
                                    <td><?php echo $row['Phone']; ?></td>
                                    <td><?php echo $row['Address']; ?></td>
                                    <td><?php echo $row['ProductsServices']; ?></td>
                                    <td>
                                        <a href="supplier-edit.php?supplier_id=<?php echo $row['supplier_id']; ?>" class="btn btn-primary btn-xs">Edit</a>
                                        <a href="#" class="btn btn-danger btn-xs" data-href="supplier-delete.php?supplierID=<?php echo $row['supplier_id']; ?>" data-toggle="modal" data-target="#confirm-delete">Delete</a>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#emailModal<?php echo $row['supplier_id']; ?>">Send Email</button>
                                    </td>
                                </tr>
                                <!-- Email Modal -->
                                <div class="modal fade" id="emailModal<?php echo $row['supplier_id']; ?>" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel<?php echo $row['supplier_id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="emailModalLabel<?php echo $row['supplier_id']; ?>">Send Email</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST">
                                                    <input type="hidden" name="supplier_id" value="<?php echo $row['supplier_id']; ?>">
                                                    <div class="form-group">
                                                        <label for="subject">Subject:</label>
                                                        <input type="text" class="form-control" id="subject" name="subject" required>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="message">Message:</label>
                                                        <textarea class="form-control" id="message" name="message" required></textarea>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <button type="submit" name="send_email" class="btn btn-primary">Send</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this supplier?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>