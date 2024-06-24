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
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>FKI Event Management</title>
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
                        <?php include ('navigation_admin.php')?>
                    </tr>
                </table>
            </div>
        </div>

        <div id="popup" class="popup-container">
            <div class="popup-content">
                <p id="popup_message"></p>
                <button class="button" onclick="location.href='pmfki.php'">Close</button>
            </div>
        </div>

        <!-- Form Popup -->
        <div id="popup_form" class="popup-form">
            <div class="popup-content">
                <form action="pmfki_edit.php" method="POST">
                <input type="text" id="pmfki_id" name="pmfki_id" value="<?= $_GET['id'] ?>" hidden>
                    <?php
                        if(isset($_GET['id'])){
                            $pmfkiId = $_GET['id'];
                            $sql = "SELECT * FROM pmfki WHERE pmfki_id = '$pmfkiId'";
                            $result = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $ret_pmfki_name = $row["pmfki_name"];
                                $ret_pmfki_ic = $row["pmfki_ic"];
                                $ret_pmfki_id = $row["pmfki_id"];
                                $ret_pmfki_pwd = $row["pmfki_pwd"];
                                $ret_pmfki_phone = $row["pmfki_phone"];
                    
                            }
                        }
                    ?>
                    <h2>Update PMFKI Account</h2>
                    <table>
                        <tr>
                            <th>Name</th>
                            <td class="fill">:</td>
                            <td><input type="text" name="pmfki_name" value='<?php echo"$ret_pmfki_name"?>'></td>
                        </tr>
                        <tr>
                            <th>Identity Card Number</th>
                            <td class="fill">:</td>
                            <td><input type="text" name="pmfki_ic" value='<?php echo"$ret_pmfki_ic"?>'></td>
                        </tr>
                        <tr>
                            <th>Matrics Number</th>
                            <td class="fill">:</td>
                            <td class="id"><?php echo"$ret_pmfki_id"?></td>
                        </tr>
                        <tr>
                            <th>New Password</th>
                            <td class="fill">:</td>
                            <td><input type="password" name="pmfki_pwd"></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td class="fill">:</td>
                            <td><input type="text" name="pmfki_phone" value='<?php echo"$ret_pmfki_phone"?>'></td>
                        </tr>
                    </table>
                    <br>
                    <button class="normal-btn" type="button" value"" onclick="location.href='pmfki.php'">Cancel</button>  
                    <button class="normal-btn" type="submit" name="confirm">Confirm</button>  
                </form>
            </div>
        </div>

        <div class="table-list">
            <h1>PMFKI</h1>
            <div class=middle-button>
                <button class="normal-btn" onclick="popup_form()">Add New PMKFI Account</button>
            </div>
            <table border="1" width="100%" class="event-list-table"> <!-- id="event-list-table" --> 
                <tr>
                    <th colspan="13">LIST OF PMFKI</th>
                </tr>
                <tbody>
                <tr>
                    <td width="2%">No</td>
                    <td width="30%">Name</td>
                    <td width="10%">Matrics Number</td>
                    <td width="10%">Identity Card Number</td>
                    <td width="10%">Phone Number</td>
                    <td width="10%">Action</td>
                </tr>
                <?php
                    $sql = "SELECT * FROM pmfki";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        $numrow=1;
                        while($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $numrow . "</td>";
                            echo "<td>". $row["pmfki_name"] . "</td>";
                            echo '<td>' . $row["pmfki_id"] . '</td>';
                            echo '<td>' . $row["pmfki_ic"] . '</td>';
                            echo "<td>" . $row["pmfki_phone"] . "</td>";
                            echo '<td><button class="normal-btn" onclick="location.href=\'pmfki_edit.php?id=' . $row["pmfki_id"] . '\'">Edit</button>';
                            echo '<button class="decline-btn" onclick="location.href=\'pmfki_delete.php?id=' . $row["pmfki_id"] . '\'">Delete</button></td>';
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
        function update_table($conn, $sql){
			if (mysqli_query($conn, $sql)) {
				return true;
			} else {
				echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
				return false;
			}
		}

        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $pmfki_id = $_POST['pmfki_id'];
            $pmfki_name = strtoupper(trim($_POST["pmfki_name"]));
            $pmfki_ic = trim($_POST["pmfki_ic"]);
            $pmfki_phone = trim($_POST["pmfki_phone"]);
            $pmfki_pwd = trim($_POST["pmfki_pwd"]);
            
            if(isset($_POST["confirm"])){
                if ($pmfki_pwd == "") {
                    $sql = "UPDATE pmfki SET pmfki_name = '$pmfki_name', pmfki_ic = '$pmfki_ic', pmfki_phone = '$pmfki_phone' WHERE pmfki_id = '$pmfki_id'";
                    $status = update_table($conn, $sql);
                } else {
                    $pwd_hash = trim(password_hash($_POST["pmfki_pwd"], PASSWORD_DEFAULT));
                    $sql = "UPDATE pmfki SET pmfki_name = '$pmfki_name', pmfki_ic = '$pmfki_ic', pmfki_phone = '$pmfki_phone', pmfki_pwd = '$pwd_hash' WHERE pmfki_id = '$pmfki_id'";
                    $status = update_table($conn, $sql);
                }
            }
            if($status){
                echo '<script>auto_popup_message("PMFKI account with matrics number ' . $pmfki_id . ' has been updated");</script>';
            }
            else{
                echo '<script>auto_popup_message("There was an error updating ' . $pmfki_id . ' account");</script>';
            }
        }
    ?>
</html>
