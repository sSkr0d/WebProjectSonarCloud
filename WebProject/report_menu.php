<?php
    include('config.php');
	session_start();

    $sql = "SELECT * FROM event WHERE event_status = 'F'";
    $result = $conn->query($sql);

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
        <title>PMFKI - Report List</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="src/icon.png">
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
        <h1>Events Report</h1>
        <div class="eventboard-row">

        <?php
            if (mysqli_num_rows($result) > 0) {
                // Output data of each row
                while ($row = mysqli_fetch_assoc($result)) {
                    // Format date
                    $startDateFormat = date("d/m/Y", strtotime($row["event_startDate"]));
                    $endDateFormat = date("d/m/Y", strtotime($row["event_endDate"]));

                    // Format time
                    $startTime12Hour = date("h:i A", strtotime($row["event_startTime"]));
                    $endTime12Hour = date("h:i A", strtotime($row["event_endTime"]));

                    echo '<div class="card">';
                    echo '<div class="image">';
                    echo '<img src="uploads/poster/' . $row["event_poster"] . '" alt="Event Poster" class="image-content">';
                    echo '</div>';
                    echo '<div class="details">';
                    echo '<div class="rowtitle">' . $row["event_name"] . '</div>';
                    echo '<div class="row scrollable">' . $row["event_posterDesc"] . '</div>';
                    echo '<div class="row">';
                    echo '<div class="column"><strong>Date</strong></div>';
                    echo '<div class="column">:</div>';
                    echo '<div class="column">' . $startDateFormat . ' - ' . $endDateFormat . '</div>';
                    echo '</div>';
                    
                    echo '<div class="row">';
                    echo '<div class="column"><strong>Time</strong></div>';
                    echo '<div class="column">:</div>';
                    echo '<div class="column">' . $startTime12Hour . ' - ' . $endTime12Hour . '</div>';
                    echo '</div>';
                    
                    echo '<div class="row">';
                    echo '<div class="column"><strong>Venue</strong></div>';
                    echo '<div class="column">:</div>';
                    echo '<div class="column">' . $row["event_venue"] . '</div>';
                    echo '</div>';
                    echo '<div class="row">';
                    echo '<td>';
                    echo '<button class="normal-btn-report" onclick="location.href=\'report_view.php?id='.$row["event_id"].'\'">View More</button>';
                    echo '</td>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                    } else {
                        echo '<p class="no-event">There Are No Available Event Report For Now</p>';
                    }
                
                    mysqli_close($conn);
        ?>
            </div>
        </div>
    </body>
</html>