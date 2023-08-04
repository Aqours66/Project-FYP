<?php
session_start();
include "db_conn.php";

if (isset($_POST['email'])) {

    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $email = validate($_POST['email']);


    if (empty($email)) {
        header("Location: upload-invoice.php?error=Email is required");
        exit();
    } else {
        $sql = "SELECT * FROM tbl_driver WHERE Email='$email'";

        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            if ($row['Email'] === $email) {
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['driver_name'] = $row['driver_name'];
                $_SESSION['driver_id'] = $row['driver_id'];
                header("Location: upload-invoice.php?success=Login successful");
                exit();
            } else {
                header("Location: upload-invoice.php?error=Incorrect Email");
                exit();
            }
        } else {
            header("Location: upload-invoice.php?error=Incorrect Email");
            exit();
        }
    }
} else {
    header("Location: upload-invoice.php");
    exit();
}
