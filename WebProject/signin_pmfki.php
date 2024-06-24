<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>PMFKI - Sign In</title>
        <link rel="icon" type="image/png" href="/WebProject/src/icon.png">
	    <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>

    <body>
        <script src="script/script.js"></script>

        <div id="popup_page_stay" class="popup-container">
            <div class="popup-content">
                <p id="popup_message_stay"></p>
                <button class="button" onclick="location.href='signin_pmfki.php'">Close</button>
            </div>
        </div>

        <div class="container-row">
            <img src="src/icon.png" alt="Logo">
            <h2>FKI Event Management</h2>
            <div class="signin-box">
                <h1>Sign In</h1>
                <p>< PMFKI ></p>
                <form action="signin_pmfki.php" method="POST">
                    <table class="signin-table">
                        <tr>
                            <th><label for="pmfki_id">PMFKI ID</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" name="pmfki_id" required></td>
                        </tr>
                        <tr>
                            <th><label for="pmfki_pwd">Password</label></th>
                        </tr>
                        <tr>
                            <td><input type="password" name="pmfki_pwd" required></td>
                        </tr>
                    </table>
                    <div class="signin-button">
                        <button class="button" type="submit"> Sign In</button>
                    </div>
                </form>
            </div>
        </div>
    </body>

    <?php
        include ('config.php');

        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $pmfki_id = strtoupper(trim($_POST['pmfki_id']));
            $pmfki_pwd = trim($_POST['pmfki_pwd']);

            $sql = "SELECT * FROM pmfki WHERE pmfki_id='$pmfki_id'";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);
                if(password_verify($pmfki_pwd, $row['pmfki_pwd'])){
                    session_start();
                    $_SESSION['pmfki_id'] = $pmfki_id;
                    header("location: proposal_pmfki.php");
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
