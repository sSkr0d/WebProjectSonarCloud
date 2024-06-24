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
                    $event_adminRemark = $row["event_adminRemark"];
                    $admin_name = $row["name"];
                    $pmfki_name = $row["pmfki_name"];
                }        
            }
        ?>
    
        <main>
            <div id="popup" class="popup-container">
                <div class="popup-content">
                    <p id="popup_message"></p>
                    <button class="button" onclick="location.href='proposal_update.php'">Close</button>
                </div>
            </div>

            <div class="event-row">
                <div class="proposal-details">
                    <h1>Update Event Proposal Details</h1>
                    <form action="proposal_update.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="event_id" name="event_id" value="<?=$_GET['id']?>">
                        <table width="100%" class="event-table">
                        <tr>
                            <td width="18%x">Event Name</td>
                            <td width="1%">:</td>
                            <td><textarea name="event_name" rows="2"><?php echo $event_name; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Synopsis</td>
                            <td>:</td>
                            <td><textarea name="event_synopsis" rows="4"><?php echo $event_synopsis; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Objective</td>
                            <td>:</td>
                            <td><textarea name="event_objective" rows="4"><?php echo $event_objective; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Impact</td>
                            <td>:</td>
                            <td><textarea name="event_impact" rows="4"><?php echo $event_impact; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <td>:</td>
                            <td>
                                <input type="date" name="event_startDate" placeholder="DD/MM/YYYY" value="<?php echo $event_startDate; ?>"></td>
                            </td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>:</td>
                            <td>
                                <input type="date" name="event_endDate" placeholder="DD/MM/YYYY" value="<?php echo $event_endDate; ?>"></td>
                            </td>
                        </tr>
                        <tr>
                            <td>Start Time</td>
                            <td>:</td>
                            <td>
                                <input type="time" name="event_startTime" value="<?php echo $event_startTime; ?>"></td>
                            </td>
                        </tr>
                        <tr>
                            <td>End Time</td>
                            <td>:</td>
                            <td>
                                <input type="time" name="event_endTime" value="<?php echo $event_endTime; ?>"></td>
                            </td>
                        </tr>
                        <tr>
                            <td>Venue</td>
                            <td>:</td>
                            <td><textarea name="event_venue" rows="2"><?php echo $event_venue; ?></textarea></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>:</td>
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
                            <td>Remark</td>
                            <td>:</td>
                            <td><?php echo $event_adminRemark; ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <br>
                                <div>
                                    <button class="accept-btn" type="submit" name="confirm">Confirm</button>
                                    <button class="normal-btn" type="button" onclick="location.href='proposal_view.php?id=<?php echo $event_id; ?>'">Back</button>
                                </div>
                            </td>
                        </tr>
                        </table>
                    </form>
                </div>
            </div>
        </main>
    </body>
    <?php
        // Function to insert data into the database table using prepared statements
        function update_table($conn, $sql){
            if (mysqli_query($conn, $sql)) {
                return true;
            } else {
                echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
                return false;
            }
        }

        //this block is called when button Submit is clicked
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //values for add or edit
            $event_id = $_POST['event_id'];
            $event_name = trim($_POST["event_name"]);
            $event_synopsis = trim(mysqli_real_escape_string($conn,$_POST["event_synopsis"]));
            $event_objective = trim(mysqli_real_escape_string($conn,$_POST["event_objective"]));
            $event_impact = trim(mysqli_real_escape_string($conn,$_POST["event_impact"]));
            $event_startDate = $_POST["event_startDate"];
            $event_endDate = $_POST["event_endDate"];
            $event_startTime = $_POST["event_startTime"];
            $event_endTime = $_POST["event_endTime"];
            $event_venue =  trim(mysqli_real_escape_string($conn,$_POST["event_venue"]));

            if(isset($_POST["confirm"])){
                $sql = "UPDATE event SET event_name = '$event_name', event_synopsis = '$event_synopsis', event_objective = '$event_objective', 
                event_impact = '$event_impact', event_startDate = '$event_startDate', event_endDate = '$event_endDate',
                event_startTime = '$event_startTime', event_endTime = '$event_endTime', event_venue = '$event_venue', event_status = 'P'
                WHERE  event_id='$event_id'";
                $status = update_table($conn, $sql);
                if ($status) {
                    // Display a popup message
                    echo "<script>alert('Proposal updated successfully!');</script>";
                    // Redirect to the profile page after successful update
                    echo "<script>window.location.href='proposal_view.php?id=$event_id';</script>";
                    exit();
                }
                else{
                     echo "<script>alert('Failed to update proposal details');</script>";
                
                     // Redirect to the profile page after successful update
                     echo "<script>window.location.href='proposal_view.php?id=$event_id';</script>";
                } 
            }
        }
    ?>
</html>
