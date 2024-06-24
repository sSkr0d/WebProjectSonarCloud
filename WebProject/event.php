<?php
    include('config.php');
    session_start();

    if(!isset($_SESSION['pmfki_id'])){
        header("location: index.php");
        exit();
    }

    $sql = "SELECT * FROM event WHERE event_status=? OR event_status=? OR event_status=?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        $event_status_A = 'A';
        $event_status_C = 'C';
        $event_status_F = 'F';
        mysqli_stmt_bind_param($stmt, "sss", $event_status_A, $event_status_C,$event_status_F);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        mysqli_stmt_close($stmt);
    } else {
        echo "Error in preparing statement: " . mysqli_error($conn);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>PMFKI - Event List</title>
        <link rel="icon" type="image/png" href="src/icon.png">
    	<link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>

    <body>
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

        <div class="table-list">
            <h1>Event List</h1>
            <table border="1" width="100%" class="event-list-table">
                <tr>
                    <th colspan="13">LIST OF EVENTS</th>
                </tr>
                <tr>
                    <td width="2%">No</td>
                    <td width="20%">Event Name</td>
                    <td width="12%">Date</td>
                    <td width="10%">Time</td>
                    <td width="12%">Venue</td>
                    <td width="10%">Status</td>
                    <td width="10%">Action</td>
                </tr>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    // output data of each row
                    $numrow = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $numrow . "</td><td>" . $row["event_name"] . "</td><td>";
                        $startDateFormat = date("d/m/Y", strtotime($row["event_startDate"]));
                        $endDateFormat = date("d/m/Y", strtotime($row["event_endDate"]));
                        if ($startDateFormat == $endDateFormat) {
                            echo $startDateFormat;
                        } else {
                            echo $startDateFormat . " - " . $endDateFormat;
                        }
                        echo "</td><td>" . date("h:i A", strtotime($row["event_startTime"])) . " - " . date("h:i A", strtotime($row["event_endTime"])) . "</td><td>" . $row["event_venue"] . "</td>";
                        echo '<td class="';
                    
                        if ($row["event_status"] == 'A') {
                            echo 'status-active">ACTIVE';
                        } elseif ($row["event_status"] == 'C') {
                            echo 'status-closed">CLOSED';
                        } elseif ($row["event_status"] == 'F') {
                            echo 'status-closed">FINISHED';
                        }
                        echo "</td>";              
                        echo '<td> <button class="normal-btn" onclick="location.href=\'event_view.php?id=' . $row["event_id"] . '\'">View Details</button></td>';
                        echo "</tr>" . "\n\t\t";
                        $numrow++;
                    }
                } else {
                    echo '<tr><td colspan="7">0 results</td></tr>';
                }
                mysqli_close($conn);
                ?>
            </table>
        </div>    
    </body>
</html>
