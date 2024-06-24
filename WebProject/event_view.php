<?php
include('config.php');
session_start();

if(!isset($_SESSION['pmfki_id'])){
    header("location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>FKI Event Management</title>
        <link rel="icon" type="image/png" href="/WebProject/src/icon.png">
    	<link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>
    <body>
        <div class="header-row">
            <div class="header-main">
                <img src="/WebProject/src/icon.png" alt="Website Logo">
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
                    $event_posterDesc = $row["event_posterDesc"];
                    $event_startDate = $row["event_startDate"];
                    $event_endDate = $row["event_endDate"];
                    $event_startTime = $row["event_startTime"];
                    $event_endTime = $row["event_endTime"];
                    $event_venue = $row["event_venue"];
                    $event_poster = $row["event_poster"];
                    $event_pwd = $row["event_pwd"];
                    $event_status = $row["event_status"];
                    $admin_name = $row["name"];
                    $pmfki_name = $row["pmfki_name"];
                }
            
            }

        ?>

        <main>
            <h1 class="header_1">Event Details</h1>
            <div class="event-view-row">
                    <div class="col-left"> 
                        <img src="uploads/poster/<?php echo $event_poster; ?>" alt="Poster Image" class="view-event-poster">
                    </div>
                    <div class="col-right"> 
                        <table class="event-view-table" >
                        <tr>
                            <th>Event Name</th>
                            <th class="fill">:</b></td>
                            <td><?php echo $event_name; ?></td>
                        </tr>
                        <tr>
                            <th>Synopsis</th>
                            <th class="fill">:</b></th>
                            <td><?php echo $event_synopsis; ?></td>
                        </tr>
                        <tr>
                            <th>Objective</th>
                            <th class="fill">:</td>
                            <td><?php echo $event_objective; ?></td>
                        </tr>
                        <tr>
                            <th>Impact</th>
                            <th class="fill">:</th>
                            <td><?php echo $event_impact; ?></td>
                        </tr>
                        <tr>
                            <th>Poster Description</th>
                            <th class="fill">:</th>
                            <td><?php echo $event_posterDesc; ?></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <th class="fill">:</th>
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
                            <th class="fill">:</td>
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
                            <th class="fill">:</td>
                            <td><?php echo $event_venue; ?></td>
                        </tr>
                        <tr>
                            <th>Event Password</th>
                            <th class="fill">:</th>
                            <td><?php echo $event_pwd; ?></td>
                        </tr>
                        <tr>
                            <th>Event status</th>
                            <th class="fill">:</th>
                            <td>
                                <?php
                                // assuming $event_status contains the status (A or C or F)
                                if ($event_status == 'A') {
                                    echo "<p class='stat-active'>ACTIVATE</p>";
                                } elseif ($event_status == 'C') {
                                    echo "<p class='stat-closed'>CLOSED</p>";
                                } elseif ($event_status == 'F') {
                                    echo "<p class='stat-closed'>FINISHED</p>";
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Submitted By</th>
                            <th class="fill">:</td>
                            <td><?php echo $pmfki_name; ?></td>
                        </tr>
                        <tr>
                            <th>Approved By</th>
                            <th class="fill">:</b></td>
                            <td><?php echo $admin_name; ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <div>
                                    <button class="normal-btn" type="button" value="" onclick="location.href='event.php'">Back</button>
                                    <button class="accept-btn" type="button" onclick="window.location.href='event_update.php?id=<?php echo $event_id; ?>'">Update</button> 
                                    <button class="decline-btn" type="button" onclick="if(confirm('Delete the event?')) { window.location.href = 'event_delete.php?id=<?php echo $event_id; ?>'; }">Delete</button>
                                </div>
                            </td>
                        </tr>
                        </table>
            		</div>
                </div>
            </div>
        </main>
    </body>

</html>
