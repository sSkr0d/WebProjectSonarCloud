<?php
    include('config.php');
	session_start();

    if(!isset($_SESSION['admin_id'])){
		header("location: index.php");
		exit();
	}
?>

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

        <?php
            if(isset($_GET["id"]) && $_GET["id"] != ""){
                $sql = "SELECT e.*, p.pmfki_name, a.name
                FROM event e
                LEFT JOIN pmfki p ON e.pmfki_id = p.pmfki_id
                LEFT JOIN fki_admin a ON e.admin_id = a.admin_id
                WHERE e.event_id=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "s", $_GET["id"]);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $event_id = $row["event_id"];
                    $event_name = $row["event_name"];
                    $event_synopsis = $row["event_synopsis"];
                    $event_objective = $row["event_objective"];
                    $event_impact = $row["event_impact"];
                    $event_startDate = $row["event_startDate"];
                    $event_endDate = $row["event_endDate"];
                    $event_startTime = $row["event_startTime"];
                    $event_endTime = $row["event_endTime"];
                    $event_venue = $row["event_venue"];
                    $event_status = $row["event_status"];
                    $event_adminRemark = $row["event_adminRemark"];
                    $admin_name = $row["name"];
                    $pmfki_name = $row["pmfki_name"];
                }
            }
        ?>

        <main>
            <div class="event-row">
                <div class="proposal-details">
                    <h1>Proposal Details</h1>
                    <form action="proposal_status.php" method="post" class="event-form">
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                        <table width="100%" class="event-table" >
                            <tr>
                                <th>Event Name</th>
                                <td class="fill">:</td>
                                <td><?php echo $event_name; ?></td>
                            </tr>
                            <tr>
                                <th>Synopsis</th>
                                <td class="fill">:</td>
                                <td><?php echo $event_synopsis; ?></td>
                            </tr>
                            <tr>
                                <th>Objective</th>
                                <td class="fill">:</td>
                                <td><?php echo $event_objective; ?></td>
                            </tr>
                            <tr>
                                <th>Impact</th>
                                <td class="fill">:</td>
                                <td><?php echo $event_impact; ?></td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td class="fill">:</td>
                                <td>
                                    <?php
                                    $formattedStartDate = date("d/m/Y", strtotime($event_startDate));
                                    $formattedEndDate = date("d/m/Y", strtotime($event_endDate));

                                    if ($formattedStartDate == $formattedEndDate) {
                                        echo $formattedStartDate;
                                    } else {
                                        echo $formattedStartDate . " - " . $formattedEndDate;
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Time</th>
                                <td class="fill">:</td>
                                <td>
                                    <?php
                                        $formattedStartTime = date("h:i A", strtotime($event_startTime));
                                        $formattedEndTime = date("h:i A", strtotime($event_endTime));
                                        echo $formattedStartTime . " - " . $formattedEndTime;
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Venue</th>
                                <td class="fill">:</td>
                                <td><?php echo $event_venue; ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td class="fill">:</td>
                                <td>
                                    <?php
                                        if ($event_status == 'A' || $event_status == 'C') {
                                            echo "<p class='stat-active'>Approved </p>";
                                        } else if ($event_status == 'P') {
                                            echo "<p class='stat-pending'>Pending </p>";
                                        } else if ($event_status == 'D') {
                                            echo "<p class='stat-closed'>Declined </p>";
                                        } else {
                                            echo "";
                                        }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Remark</th>
                                <td class="fill">:</td>
                                <td><?php echo $event_adminRemark; ?></td>
                            </tr>
                            <tr>
                                <th>Submitted By</th>
                                <td class="fill">:</td>
                                <td><?php echo $pmfki_name; ?></td>
                            </tr>
                            <tr>
                                <th>Approved/ Declined By</th>
                                <td class="fill">:</td>
                                <td><?php echo $admin_name; ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <button class="accept-btn" type="submit" name="approve" onclick="window.location.href = 'proposal_status.php?id=<?php echo $event_id; ?>'">Approve</button>
                                    <button class="decline-btn" type="button" onclick="window.location.href = 'proposal_status_decline.php?id=<?php echo $event_id; ?>'">Decline</button>
                                    <button class="normal-btn" type="button" name="back" value="Back" onclick="location.href='proposal_admin.php'">Back</button>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </div>
        </main>
    </body>
</html>
