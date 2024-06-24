<?php
    include("config.php");

    session_start();

    if (!isset($_SESSION['student_id'])) {
        header("Location: index.php");
        exit();
    }

    $studentId = $_SESSION['student_id'];

    if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
        $eventIdToUnjoin = $_GET['event_id'];

        $deleteAttendee = mysqli_prepare($conn, "DELETE FROM attendee WHERE event_id = ? AND student_id = ?");
        mysqli_stmt_bind_param($deleteAttendee, "is", $eventIdToUnjoin, $studentId);

        mysqli_stmt_execute($deleteAttendee);

        mysqli_stmt_close($deleteAttendee);
    }

    if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
        $eventId = $_GET['event_id'];

        $stmt = mysqli_prepare($conn, "SELECT event_name FROM event WHERE event_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $eventId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $eventName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        echo "You've successfully withdrawn from the event '" . $eventName . "'.";
    } else {
        echo "Error: Event ID is not valid.";
    }

    header("Location: joined_event.php");
    exit();
?>
