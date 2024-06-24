<?php
    session_start();
    include('config.php');


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $event_id = $_POST["event_id_to_feedback"];
        $rating = $_POST["rating"];
        $comment = trim($_POST["comment"]);
        
        if (isset($_SESSION['student_id'])) {
            $student_id = $_SESSION['student_id'];

            $sql = "INSERT INTO feedback (rating, comment, event_id, student_id) 
                    VALUES ('$rating', '$comment', '$event_id', '$student_id')";

            $status = insertTo_DBTable($conn, $sql);
            if ($status) {

                echo "<script>alert('Feedback Added Successfully!');</script>";
            } else {
                echo "<script>alert('Failed to Add Feedback!');</script>";
            }

            echo "<script>window.location.href='joined_event.php';</script>";
            exit();
        } else {

            echo "<script>alert('Error: Student ID not set.');</script>";

        }
    }

    mysqli_close($conn);

    function insertTo_DBTable($conn, $sql) {
        if (mysqli_query($conn, $sql)) {
            return true;
        } else {
            echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
            return false;
        }
    }
?>
