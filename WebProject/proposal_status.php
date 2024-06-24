<?php
    include('config.php');
	session_start();

    if(!isset($_SESSION['admin_id'])){
		header("location: index.php");
		exit();
	}

    // Function to insert data into the database table using prepared statements
    function update_table($conn, $sql){
        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
            return false;
        }
    }

    // This block is called when the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve'])) {
        $event_id = $_POST['event_id'];

        $sql = "UPDATE event SET event_status = 'C', event_adminRemark = '', admin_id = '{$_SESSION["admin_id"]}' WHERE event_id = '$event_id'";
        $status = update_table($conn, $sql);

        if ($status) {
            // Display a popup message
            echo "<script>alert('Proposal Approved Successfully!');</script>";
            // Redirect to the profile page after successful update
            echo "<script>window.location.href='proposal_admin_manage.php?id=$event_id';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to Approve Proposal!');</script>";
            // Redirect to the profile page after unsuccessful update
            echo "<script>window.location.href='proposal_admin_manage.php?id=$event_id';</script>";
        }
    }
?>
