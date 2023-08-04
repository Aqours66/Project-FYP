<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['Add_name'])) {
        $valid = 0;
        $error_message .= "Add Name can not be empty<br>";
    } else {
		// Duplicate Add checking
    	// current Add name that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_Add WHERE Add_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			$current_Add_name = $row['Add_name'];
		}

		$statement = $pdo->prepare("SELECT * FROM tbl_Add WHERE Add_name=? and Add_name!=?");
    	$statement->execute(array($_POST['Add_name'],$current_Add_name));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'Add name already exists<br>';
    	}
    }

    if($valid == 1) {    	
		// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_Add SET Add_name=? WHERE Add_id=?");
		$statement->execute(array($_POST['Add_name'],$_REQUEST['id']));

    	$success_message = 'Add is updated successfully.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_Add WHERE Add_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit Add</h1>
	</div>
	<div class="content-header-right">
		<a href="Add.php" class="btn btn-primary btn-sm">View All</a>
	</div>
</section>


<?php							
foreach ($result as $row) {
	$Add_name = $row['Add_name'];
}
?>

<section class="content">

  <div class="row">
    <div class="col-md-12">

		<?php if($error_message): ?>
		<div class="callout callout-danger">
		
		<p>
		<?php echo $error_message; ?>
		</p>
		</div>
		<?php endif; ?>

		<?php if($success_message): ?>
		<div class="callout callout-success">
		
		<p><?php echo $success_message; ?></p>
		</div>
		<?php endif; ?>

        <form class="form-horizontal" action="" method="post">

        <div class="box box-info">

            <div class="box-body">
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label">Add Name <span>*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="Add_name" value="<?php echo $Add_name; ?>">
                    </div>
                </div>
                <div class="form-group">
                	<label for="" class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                      <button type="submit" class="btn btn-success pull-left" name="form1">Update</button>
                    </div>
                </div>

            </div>

        </div>

        </form>



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
                Are you sure want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>