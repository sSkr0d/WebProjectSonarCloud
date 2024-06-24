<?php
    include("config.php");

    session_start();

    if (!isset($_SESSION['student_id'])) {
        header("Location: index.php");
        exit();
    }

    $studentId = $_SESSION['student_id'];

    if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
        $eventIdToJoin = $_GET['event_id'];

        // check if the student has already joined the event
        $checkJoined = mysqli_prepare($conn, "SELECT * FROM attendee WHERE event_id = ? AND student_id = ?");
        mysqli_stmt_bind_param($checkJoined, "is", $eventIdToJoin, $studentId);
        mysqli_stmt_execute($checkJoined);
        $resultJoined = mysqli_stmt_get_result($checkJoined);

        if (mysqli_num_rows($resultJoined) > 0) {
            $_SESSION['registration_status'] = 'already_joined';
        } else {
            $insertAttendee = mysqli_prepare($conn, "INSERT INTO attendee (attendance_status, event_id, student_id) VALUES ('B', ?, ?)");
            mysqli_stmt_bind_param($insertAttendee, "is", $eventIdToJoin, $studentId);

            if (mysqli_stmt_execute($insertAttendee)) {
                $_SESSION['registration_status'] = 'success';
                header("Location: eventboard.php");
                exit();
            } else {
                $_SESSION['registration_status'] = 'failure';
            }

            mysqli_stmt_close($insertAttendee);
        }

        mysqli_stmt_close($checkJoined);
    } else {
        echo "Error: Event ID is not valid.";
    }

    header("Location: eventboard.php");
    exit();
?>
