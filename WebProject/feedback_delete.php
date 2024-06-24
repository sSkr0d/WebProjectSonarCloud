<?php
    include("config.php");
    session_start();

    if (isset($_GET['id'])) {
        $feedback_id = $_GET['id'];
        $sql = "SELECT f.*, e.event_name FROM feedback f 
                JOIN event e ON f.event_id = e.event_id 
                WHERE f.feedback_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $_GET["id"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $ret_event_name = $row["event_name"];
            $ret_rating = $row["rating"];
            $ret_comment = $row["comment"];
        }
    }

    if (!isset($_SESSION['student_id'])) {
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>Student - Feedback List</title>
        <link rel="icon" type="image/png" href="src/icon.png">
	    <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>
    <body onload="auto_open_popup('popup_form')">
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
                        <?php include ('navigation_student.php') ?>
                    </tr>
                </table>
            </div>
        </div>

        <div id="popup" class="popup-container">
            <div class="popup-content">
                <p id="popup_message"></p>
                <button class="button" onclick="location.href='feedback.php'">Close</button>
            </div>
        </div>

        <div id="popup_form" class="popup-form">
            <div class="popup-content">
                <p>Are you sure you want to delete this feedback?</p>
                <form action="feedback_delete.php?id=<?= isset($_GET['id']) ? $_GET['id'] : '' ?>" method="POST"> 
                    <input type="text" id="feedback_id" name="feedback_id" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>" hidden>
                    <button class="normal-btn" type="button" action="" onclick="location.href='feedback.php'">Cancel</button>
                    <button class="decline-btn" type="submit" name="confirm">Confirm</button>
                </form>
            </div>
        </div>
            
        <div class="table-list">
            <h1>My Feedback</h1>
            <table border="1" width="100%" class="event-list-table">
                <tr>
                    <th width="2%">No</th>
                    <th width="10%">Attended Event Name</th>
                    <th width="1%">Rating</th>
                    <th width="10%">Comment</th>
                    <th width="5%">Action</th>
                </tr>
                <?php
                    $sql = "SELECT f.*, e.event_name
                    FROM feedback f
                    LEFT JOIN event e ON f.event_id = e.event_id
                    WHERE f.student_id=". $_SESSION["student_id"]; //WHERE student_id=". $_SESSION["student_id"];
                    
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $numrow=1;
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $numrow . "</td><td>". $row["event_name"] . "</td>";
                            echo "<td>" . $row["rating"] . "/5</td>";
                            echo "<td>" . $row["comment"] . "</td>";
                            echo '<td>';
                            echo '<button class="accept-btn" onclick="location.href=\'feedback_edit.php?id=' . $row["feedback_id"] . '\'">Update</button>';
                            echo '<button class="decline-btn" onclick="location.href=\'feedback_delete.php?id=' . $row["feedback_id"] . '\'">Delete</button>';
                            echo '</td>';
                            echo "</tr>" . "\n\t\t";
                            $numrow++;
                        }
                    } else {
                        echo '<tr><td colspan="7">0 results</td></tr>';
                    } 
                ?>
            </table>
        </div>
    </body>
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $feedback_id = $_POST['feedback_id'];
            
            if(isset($_POST["confirm"])){
                $delsql = "DELETE FROM feedback WHERE feedback_id = '$feedback_id'";
                $result = mysqli_query($conn, $delsql);
                if (mysqli_query($conn, $sql) && $result) {
                    echo '<script>auto_popup_message("Feedback has been deleted");</script>';
                }
                else{
                    echo '<script>auto_popup_message("There was an error deleting you feedback");</script>';
                }
                
            }
        }
    ?>
</html>
