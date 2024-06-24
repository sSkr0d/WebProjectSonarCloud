<?php
    include('config.php');
	session_start();

    if(!isset($_SESSION['pmfki_id'])){
		header("location: index.php");
		exit();
	}

    if (isset($_GET["id"]) && $_GET["id"] != "") {
        $id = $_GET["id"];
        $sql = "DELETE FROM event WHERE event_id = ? ";
        
        $stmt = mysqli_prepare($conn, $sql);
    
        mysqli_stmt_bind_param($stmt, "i", $id);
    
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>';
            echo 'alert("Proposal Deleted Successfully!");';
            echo 'window.location.href = "proposal_pmfki.php";';
            echo '</script>';
        } else {
            echo '<script>';
            echo 'alert("Failed to Delete the Proposal!");';
            echo 'window.location.href = "proposal_pmfki.php";';
            echo '</script>';
        }
    
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($conn);
?>
