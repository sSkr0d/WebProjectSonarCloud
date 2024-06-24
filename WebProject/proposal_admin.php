<?php
    include('config.php');
	session_start();

    if(!isset($_SESSION['admin_id'])){
		header("location: index.php");
		exit();
	}
?>

<!DOCTYPE html>
<html lang="en">

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>FKI Event Management</title>
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
                        <?php include ('navigation_admin.php')?>
                    </tr>
                </table>
            </div>
        </div>
                

        <div class="table-list">
            <h1>Event Proposal</h1>
            <table border="1" width="100%" class="event-list-table"> <!-- id="event-list-table" --> 
                <tr>
                    <th colspan="13">LIST OF PROPOSAL</th>
                </tr>
                <tr>
                    <td width="2%">No</td>
                    <td width="20%">Name</td>
                    <td width="12%">Date</td>
                    <td width="10%">Time</td>
                    <td width="12%">Venue</td>
                    <td width="5%">Status</td>
                    <td width="10%">Remark</td>
                    <td width="5%">Action</td>
                </tr>
                <?php
                    $sql = "SELECT * FROM event e WHERE NOT e.event_status = 'F'";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $numrow=1;
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $numrow . "</td><td>". $row["event_name"] . "</td>";
                            $startDateFormat = date("d/m/Y", strtotime($row["event_startDate"]));
                            $endDateFormat = date("d/m/Y", strtotime($row["event_endDate"]));

                            if ($startDateFormat == $endDateFormat) {
                                echo '<td>' . $startDateFormat . '</td>';
                            } else {
                                echo '<td>' . $startDateFormat . ' - ' . $endDateFormat . '</td>';
                            }

                            $startTime12Hour = date("h:i A", strtotime($row["event_startTime"]));
                            $endTime12Hour = date("h:i A", strtotime($row["event_endTime"]));

                            echo '<td>' . $startTime12Hour . ' - ' . $endTime12Hour . '</td>';
                            echo '<td>' . $row["event_venue"] . '</td>';

                            $event_status = $row["event_status"];
                            if ($event_status == 'A' || $event_status == 'C') {
                                echo "<td class=\"status-active\">Approved</td>";
                            } else if ($event_status == 'P') {
                                echo "<td class=\"status-pending\">Pending</td>";
                            } else if ($event_status == 'D') {
                                echo "<td class=\"status-closed\">Declined</td>";
                            } else {
                                echo "<td>" . $row["event_status"] . "</td>";
                            }

                            echo "<td>" . $row["event_adminRemark"] . "</td>";
                            echo '<td><button class="normal-btn" onclick="location.href=\'proposal_admin_manage.php?id=' . $row["event_id"] . '\'">Manage</button></td>';
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
