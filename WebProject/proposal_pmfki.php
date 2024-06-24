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
        <title>PMFKI - Event Proposal</title>
        <link rel="icon" type="image/png" href="src/icon.png">
    	<link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>
    <body>
        <script src="script/script.js"></script>

        <div id="popup_page_stay" class="popup-container">
            <div class="popup-content">
                <p id="popup_message_stay"></p>
                <button class="button" onclick="location.href='proposal_pmfki.php'">Close</button>
            </div>
        </div>

        <div id="popup_form" class="popup-form">
            <div class="popup-content-event">
                <h2>Add Event Proposal</h2>

                <form method="POST" action="proposal_pmfki.php" enctype="multipart/form-data" id="event-form">
                    <table>
                        <tr>
                            <th>Event name</th>
                            <th class="fill">:</th>
                            <td>
                                <textarea rows="1" name="name" cols="20" required></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Synopsis</th>
                            <th class="fill">:</th>
                            <td>
                                <textarea rows="6" name="synopsis" cols="20"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Objective</th>
                            <th class="fill">:</th>
                            <td>
                                <textarea rows="6" name="objective" cols="20"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Impact</th>
                            <th class="fill">:</th>
                            <td>
                                <textarea rows="6" name="impact" cols="20"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <th class="fill">:</th>
                            <td>
                                <input type="date" name="startDate" placeholder="DD/MM/YYYY" >
                            </td>
                        </tr>
                        <tr>
                            <th>End Date</th>
                            <th class="fill">:</th>
                            <td>
                                <input type="date" name="endDate" placeholder="DD/MM/YYYY" >
                            </td>
                        </tr>
                        <tr>
                            <th>Start Time</th>
                            <th class="fill">:</th>
                            <td><input type="time" name="startTime" ></td>
                        </tr>
                        <tr>
                            <th>End Time</th>
                            <th class="fill">:</th>
                            <td><input type="time" name="endTime" ></td>
                        </tr>
                        <tr>
                            <th>Venue</th>
                            <th class="fill">:</th>
                            <td><textarea rows="1" name="venue" cols="20"></textarea></td>
                        </tr>        
                    </table>
                        <div>
                            <br>
                            <button class="normal-btn" type="button" onclick="location.href='proposal_pmfki.php'">Back</button>
                            <button class="normal-btn" type="button" onclick="reset_form('event-form')">Reset</button>
                            <button class="normal-btn" type="sumbit"name="confirm">Confirm</button>            
                        </div>
                </form>
            </div>
        </div>

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
            <h1>Event Proposal</h1>
                <div class=middle-button>
                    <button class="normal-btn" onclick="popup_form()">Create New Proposal</button>
                </div>
            <table  border="1" width="100%" class="event-list-table">
                <tr>
                    <th colspan="13">LIST OF PROPOSAL</th>
                </tr>
                <tr>
                    <td width="2%">No</td>
                    <td width="15%">Name</td>
                    <td width="5%">Date</td>
                    <td width="5%">Time</td>
                    <td width="12%">Venue</td>
                    <td width="5%">Status</td>
                    <td width="10%">Remark</td>
                    <td width="5%">Action</td>
                </tr>
                <?php
                    $sql = "SELECT * FROM event e WHERE NOT e.event_status = 'F'";
                    $stmt = mysqli_prepare($conn, $sql);

                    // Execute the statement
                    mysqli_stmt_execute($stmt);

                    // Get the result
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        // output data of each row
                        $numrow=1;
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $numrow . "</td><td>". $row["event_name"] . "</td>";
                            $startDateFormat = date("d/m/Y", strtotime($row["event_startDate"]));
                            $endDateFormat = date("d/m/Y", strtotime($row["event_endDate"]));

                            if ($startDateFormat == $endDateFormat) {
                                // If start and end dates are the same, display only start date
                                echo '<td>' . $startDateFormat . '</td>';
                            } else {
                                // If start and end dates are different, display as "startDate - endDate"
                                echo '<td>' . $startDateFormat . ' - ' . $endDateFormat . '</td>';
                            }

                            // Display time in 12-hour format
                            $startTime12Hour = date("h:i A", strtotime($row["event_startTime"]));
                            $endTime12Hour = date("h:i A", strtotime($row["event_endTime"]));

                            echo '<td>' . $startTime12Hour . ' - ' . $endTime12Hour . '</td>';
                            echo '<td>' . $row["event_venue"] . '</td>';

                            $event_status = $row["event_status"];
                            if ($event_status == 'A' || $event_status == 'C') {
                                echo "<td class='status-active'>APPROVED</td>";
                            } else if ($event_status == 'P') {
                                echo "<td class='status-pending'>PENDING</td>";
                            } else if ($event_status == 'D') {
                                echo "<td class='status-closed' >DECLINED</td>";
                            } else {
                                echo "<td>" . $row["event_status"] . "</td>";
                            }

                            echo "<td>" . $row["event_adminRemark"] . "</td>";
                            echo '<td><button class="normal-btn" onclick="location.href=\'proposal_view.php?id=' . $row["event_id"] . '\'">View Details</button></td>';                    echo "</tr>" . "\n\t\t";
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
    <?PHP
        include('config.php');
                    
        //variables
        $action="";
        $id="";
        $name =" ";
        $startDate = "";
        $endDate = "";
        $startTime =" ";
        $endTime =" ";
        $venue = "";
        $synopsis = "";
        $objective =" ";
        $impact =" ";
                    
        //this block is called when button Submit is clicked
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //values for add or edit
            $name = trim(mysqli_real_escape_string($conn,$_POST["name"]));
            $startDate = $_POST["startDate"];
            $endDate = $_POST["endDate"];
            $startTime = $_POST["startTime"];
            $endTime = $_POST["endTime"];
            $venue =  trim(mysqli_real_escape_string($conn, $_POST['venue']));
            $synopsis = trim(mysqli_real_escape_string($conn,$_POST["synopsis"]));
            $objective = trim(mysqli_real_escape_string($conn,$_POST["objective"]));
            $impact = trim(mysqli_real_escape_string($conn,$_POST["impact"]));
        
            $sql = "INSERT INTO event (event_name, event_synopsis, event_objective, event_impact,
            event_startDate, event_endDate, event_startTime, event_endTime, event_venue, event_status, pmfki_id)
            VALUES ('$name', '$synopsis', '$objective', '$impact', '$startDate',
            '$endDate', '$startTime', '$endTime', '$venue', 'P', '" . $_SESSION["pmfki_id"] . "')";
        
            $status = insertTo_DBTable($conn, $sql);
            if ($status) {
                echo "<script>popup_page_stay('Proposal Added Successfully');</script>";
            } else {
                echo "<script>popup_page_stay('Failed to Add Proposal');</script>";
            } 
        }

        //close db connection
        mysqli_close($conn);

        //Function to insert data to database table
        function insertTo_DBTable($conn, $sql){
            if (mysqli_query($conn, $sql)) {
                return true;
            } else {
                echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
                return false;
            }
        }
    ?>
</html>
