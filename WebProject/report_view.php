<?php
    include('config.php');
    session_start();

    $pageurl = $_SERVER['REQUEST_URI'];
    $takeurl = parse_url($pageurl);
    parse_str($takeurl['query'], $id);

    if (isset($id['id'])) {
        $event_id = $id['id'];
        $sql = "SELECT e.*, s.student_id, s.student_name, f.rating, f.comment, att.attendance_status
        FROM event e
        LEFT JOIN feedback f ON e.event_id = f.event_id
        LEFT JOIN student s ON f.student_id = s.student_id
        LEFT JOIN attendee att ON s.student_id = att.student_id AND att.event_id = e.event_id
        WHERE e.event_id = ?
        ORDER BY att.attendance_status";
        $stmt = mysqli_prepare($conn, $sql);


        mysqli_stmt_bind_param($stmt, "i", $event_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    }

    if (!isset($_SESSION['pmfki_id'])) {
        header("location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>PMFKI - View Report</title>
        <link rel="icon" type="image/png" href="src/icon.png">
        <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>

    <body>
        <script src="script/script.js"></script>

            <div class="header-row">
                <div class="header-main">
                    <img src="src/icon.png" alt="Website Logo">
                    <h2>
                        <span>FKI</span>
                        <span>EVENT</span>
                        <span>MANAGEMENT</span>
                    </h2>
                    <table class="header-nav">
                        <tr>
                            <?php include ('navigation_pmfki.php') ?>
                        </tr>
                    </table>
                </div>
            </div>

        <?php
            echo"<div class='report-row'>";
            if (isset($result) && mysqli_num_rows($result) > 0) {
                $rows = $result->fetch_assoc(); 
                echo"<div class='report-left'>";
                echo "<img src='uploads/poster/" . ($rows["event_poster"] ?? '') . "' alt='Event Poster'>";
                echo "</div>";
                echo"<div class='report-right'>";
                echo "<h1>" . ($rows["event_name"] ?? '') . " Report</h1>";
                $formattedStartDate = date("d/m/Y", strtotime($rows["event_startDate"]));
                $formattedEndDate = date("d/m/Y", strtotime($rows["event_endDate"]));
                if ($formattedStartDate == $formattedEndDate) {
                    echo "<h2> Date: " . $formattedStartDate . "</h2>";
                } else {
                    echo "<h2> Date: " . $formattedStartDate . " - " . $formattedEndDate . "</h2>";
                }
                $formattedStartTime = date("h:i A", strtotime($rows["event_startTime"]));
                $formattedEndTime = date("h:i A", strtotime($rows["event_endTime"]));
                echo "<h2> Time: " . $formattedStartTime . " - " . $formattedEndTime . "</h2>";
                echo "<h2> Venue: " . $rows["event_venue"] . "</h2>";
                echo "</div>";
                echo "</div>";
                echo "<div class=middle-button>";
                echo "<button class='normal-btn' onclick='window.print()'>Save Report</button>";
                echo "</div>";
// registered student list
echo "<table border='1' width='100%' class='event-list-table'>";
echo "<tr>";
echo "<th colspan='13'>LIST OF REGISTERED STUDENT</th>";
echo "</tr>";
echo "<tr>";
echo "<td width='2%'>No</td>";
echo "<td width='15%'>Student Name</td>";
echo "<td width='5%'>Student Matrics Number</td>";
echo "<td width='5%'>Attendance Status</td>";
echo "</tr>";


// Fetch the list of registered students
$registeredStudentsSql = "SELECT s.student_name, s.student_id, att.attendance_status
                          FROM student s
                          LEFT JOIN attendee att ON s.student_id = att.student_id
                          WHERE att.event_id = ?";
$registeredStudentsStmt = mysqli_prepare($conn, $registeredStudentsSql);
mysqli_stmt_bind_param($registeredStudentsStmt, "i", $event_id);
mysqli_stmt_execute($registeredStudentsStmt);
$registeredStudentsResult = mysqli_stmt_get_result($registeredStudentsStmt);

$numrow = 1;
while ($registeredStudentRow = mysqli_fetch_assoc($registeredStudentsResult)) {
    echo "<tr>";

    if ($registeredStudentRow["attendance_status"] == 'A') {
        echo "<td>" . $numrow . "</td><td>" . $registeredStudentRow["student_name"] . "</td>";
        echo '<td>' . $registeredStudentRow["student_id"] . '</td>';
        echo '<td>ATTENDED</td>';
    } else if ($registeredStudentRow["attendance_status"] == 'B') {
        echo "<td>" . $numrow . "</td><td>" . $registeredStudentRow["student_name"] . "</td>";
        echo '<td>' . $registeredStudentRow["student_id"] . '</td>';
        echo '<td>ABSENT</td>';
    }

    echo "</tr>";
    $numrow++;
}

echo "</table>";

                // feedback list
                echo "<table border='1' width='100%' class='event-list-table'>";
                echo "<tr>";
                echo "<th colspan='13'>LIST OF FEEDBACK</th>";
                echo "</tr>";
                echo "<tr>";
                echo "<td width='2%'>No</td>";
                echo "<td width='15%'>Student Name</td>";
                echo "<td width='5%'>Student Matrics</td>";
                echo "<td width='3%'>Rating</td>";
                echo "<td width='12%'>Comment</td>";
                echo "</tr>";

                $numrow = 1;
                $feedbackAvailable = false;
                
                // Reset the data pointer to the beginning
                mysqli_data_seek($result, 0);
                
                while ($rows = $result->fetch_assoc()) {
                    if (isset($rows["student_name"])) {
                        echo "<tr>";
                        echo "<td>" . $numrow . "</td><td>" . $rows["student_name"] . "</td>";
                        echo '<td>' . $rows["student_id"] . '</td>';
                        echo '<td>' . $rows["rating"] . '</td>';
                        echo '<td>' . $rows["comment"] . '</td>';
                        echo "</tr>";
                        $numrow++;
                        $feedbackAvailable = true;
                    }
                }
                
                if (!$feedbackAvailable) {
                    echo "<tr><td colspan='5'>No feedback available for this event.</td></tr>";
                }
                
                echo "</table>";
            }
            ?>
        </div>
    </body>   
</html>