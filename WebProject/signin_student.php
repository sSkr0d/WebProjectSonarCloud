<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>Student - SIgn In</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="src/icon.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>

    <body>
        <script src="script/script.js"></script>

        <div id="popup_page_stay" class="popup-container">
            <div class="popup-content">
                <p id="popup_message_stay"></p>
                <button class="button" onclick="location.href='signin_student.php'">Close</button>
            </div>
        </div>

        <div class="container-row">
            <img src="src/icon.png" alt="Logo">
            <h2>FKI Event Management</h2>
            <div class="signin-box">
                <h1>Sign In</h1>
                <p>< Student ></p>
                <form action="signin_student.php" method="POST">
                    <table class="signin-table">
                        <tr>
                            <th><label for="student_id">Student ID</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" name="student_id" required></td>
                        </tr>
                        <tr>
                            <th><label for="student_pwd">Password</label></th>
                        </tr>
                        <tr>
                            <td><input type="password" name="student_pwd" required></td>
                        </tr>
                    </table>
                    <div>
                        <button class="button" type="submit"> Sign In</button>
                    </div>
                </form>
                <div class="signup">
                    <span>
                        <p>Don't have an account?</p> 
                        <a href="signup.php">Register Here</a>
                    </span>
                </div>
            </div>
        </div>
    </body>
    <?php
        include ('config.php');
        session_start();

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $student_id = strtoupper(trim($_POST['student_id']));
            $student_pwd = trim($_POST['student_pwd']);

            $sql = "SELECT * FROM student WHERE student_id='$student_id'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                if(password_verify($student_pwd, $row['student_pwd'])){
                    $_SESSION['student_id'] = $student_id;
                    header("location: eventboard.php");
                    exit();
                }
                else{
                    echo '<script>popup_page_stay("Username or Password is incorrect")</script>';
                } 
            }
            else{
                echo '<script>popup_page_stay("Username or Password is incorrect")</script>';
            } 
        }
        $conn -> close();
    ?>
</html>
