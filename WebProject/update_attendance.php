<?php
    include("config.php");

    session_start();

    if (!isset($_SESSION['student_id'])) {
        header("Location: index.php");
        exit();
    }

    $studentId = $_SESSION['student_id'];

    if (isset($_GET['event_id']) && is_numeric($_GET['event_id']) && isset($_GET['event_pwd'])) {
        $eventId = $_GET['event_id'];
        $eventPwd = $_GET['event_pwd'];

        $stmt = mysqli_prepare($conn, "SELECT event_pwd FROM event WHERE event_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $eventId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            if ($eventPwd == $row['event_pwd']) {
                $updateAttendance = mysqli_prepare($conn, "UPDATE attendee SET attendance_status = 'A' WHERE event_id = ? AND student_id = ?");
                mysqli_stmt_bind_param($updateAttendance, "is", $eventId, $studentId);

                mysqli_stmt_execute($updateAttendance);

                mysqli_stmt_close($updateAttendance);

                $_SESSION['registration_status'] = 'attendance_success';
            } else {
                $_SESSION['registration_status'] = 'wrong_password';
            }
        } else {
            die('Error in SQL query: ' . mysqli_error($conn));
        }

        mysqli_stmt_close($stmt);
    }

    header("Location: joined_event.php");
    exit();
?>
