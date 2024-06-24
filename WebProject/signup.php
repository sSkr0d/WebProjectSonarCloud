<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>Student - Sign Up</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="src/icon.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>

    <body>
        <script src="script/script.js"></script>

        <!-- popup message -->
        <div id="popup" class="popup-container">
            <div class="popup-content">
                <p id="popup_message"></p>
                <div>
                    <button class="button" onclick="location.href='index.php'">Close</button>
                </div>
            </div>
        </div>

        <div id="popup_page_stay" class="popup-container">
            <div class="popup-content">
                <p id="popup_message_stay"></p>
                <button class="button" onclick="location.href='signup.php'">Close</button>
            </div>
        </div>
        <!--                -->

        <div class="container-row">
            <div class="signup-box">
                <form action="signup.php" method="POST" enctype="multipart/form-data" id="sign-form"> 
                    <table width="100%" class="signup-table">
                        <h1>Sign Up</h1>
                        <tr>
                            <th><label for="student_name">Full Name</label></th>
                            <th>:</th>
                            <td><textarea rows="1" name="student_name" id="student_name" required></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="student_ic">Identity Card Number</label></th>
                            <th>:</th>
                            <td><textarea rows="1" name="student_ic" id="student_ic" required></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="student_id">Matrics Number</label></th>
                            <th>:</th>
                            <td><textarea rows="1" name="student_id" id="student_id" required></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="student_pwd">Password</label></th>
                            <th>:</th>
                            <td><input type="password" rows="1" name="student_pwd" id="student_pwd" required></td>
                        </tr>
                        <tr>
                            <th><label for="student_email">E-mail</label></th>
                            <th>:</th>
                            <td><textarea rows="1" name="student_email" id="student_email" required></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="student_phone">Phone Number</label></th>
                            <th>:</th>
                            <td><textarea rows="1" name="student_phone" id="student_phone" required></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="student_address">Address</label></th>
                            <th>:</th>
                            <td><textarea rows="4" name="student_address" id="student_address" required></textarea></td>
                        </tr>
                        <tr>
                            <th><label for="student_profilePic">Profile Picture</label></th>
                            <th>:</th>
                            <td>
                                <input type="file" name="student_profilePic" id="student_profilePic" accept=".jpg, .jpeg, .png" required>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <button class="button" type="button" value="" onclick="location.href='index.php'">Cancel</button>
                    <button class="button" type="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </body>
    <?php
        include ('config.php');

        include('config.php');
            $targetdir = "uploads/profile/";
            $targetfile = "";
            $uploadstat = 0;
            $imgfiletype = "";
            $uploadfilename = "";

            // create a file if the file doeesn't exist
            if (!file_exists($targetdir) && !is_dir($targetdir)) {
                mkdir($targetdir, 0777, true); 
            }

            function insert_to_table($conn, $sql){
                if (mysqli_query($conn, $sql)) {
                    return true;
                } 
                else {
                    echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
                    return false;
                }
            }

            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $student_name = trim($_POST["student_name"]);
                $student_ic = trim($_POST["student_ic"]);
                $student_id = strtoupper(trim($_POST["student_id"]));
                $student_pwd = trim($_POST["student_pwd"]);
                $student_email = trim($_POST["student_email"]);
                $student_phone = trim($_POST["student_phone"]);
                $student_address = trim($_POST["student_address"]);

                $pwd_hash = trim(password_hash($student_pwd, PASSWORD_DEFAULT));

                if(isset($_FILES["student_profilePic"]) && $_FILES["student_profilePic"]["error"] == UPLOAD_ERR_OK){
                    $uploadstat = 1;
                    $filetemp = $_FILES["student_profilePic"];
                    $uploadfilename = $filetemp["name"];
                
                    //generate a new img name using matrics number
                    $imgfiletype = strtolower(pathinfo($uploadfilename, PATHINFO_EXTENSION));
                    $targetfile = $targetdir . $student_id . "." . $imgfiletype;
                    $imgnewname = $student_id . "." . $imgfiletype;

                    //detect the account if exist using the img name
                    if (file_exists($targetfile)) {
                        echo '<script>popup_page_stay("Your account already exist")</script>';
                        $uploadstat = 0;
                    }

                    if($_FILES["student_profilePic"]["size"] > 500000){
                        echo '<script>popup_page_stay("The file is too large. Try resizing your image")</script>';
                        $uploadstat = 0;
                    }

                    if($imgfiletype != "jpg" && $imgfiletype != "png" && $imgfiletype != "jpeg"){
                        echo '<script>popup_page_stay("Only JPG, JPEG, and PNG files are accepted")</script>';
                        $uploadstat = 0;
                    }

                    if($uploadstat == 1){
                        $sql = "INSERT INTO student (student_name, student_ic ,student_id, student_pwd, student_email, student_phone, 
                                student_address, student_profilePic)
                                VALUES ('$student_name', '$student_ic', '$student_id', '$pwd_hash', '$student_email', 
                                '$student_phone', '$student_address', '$imgnewname')";
                        $status = insert_to_table($conn, $sql);

                        if($status){
                            if(move_uploaded_file($_FILES["student_profilePic"]["tmp_name"], $targetfile)){
                                echo '<script>popup_message("Your account has been successfully registered")</script>';
                            }
                            else{
                                echo '<script>popup_message("There was an error uploading your file")</script>';
                            }
                        }
                        else{
                            echo '<script>popup_page_stay("Failed to establish a connection with the database")</script>';
                        }
                    }
                }
                else{
                    echo '<script>popup_page_stay("You must upload your profile picture")</script>';
                }
            }
            $conn -> close();
    ?>
</html>