<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>Admin - Sign In</title>
        <link rel="stylesheet" href="css/style.css">
        <link rel="icon" type="image/png" href="src/icon.png">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>

    <body>
        <script src="script/script.js"></script>

        <div id="popup_page_stay" class="popup-container">
            <div class="popup-content">
                <p id="popup_message_stay"></p>
                <button class="button" onclick="location.href='signin_admin.php'">Close</button>
            </div>
        </div>

        <div class="container-row">
            <img src="src/icon.png" alt="Logo">
            <h2>FKI Event Management</h2>
            <div class="signin-box">
                <h1>Sign In</h1>
                <p>< Admin ></p>
                <form action="signin_admin.php" method="POST">
                    <table class="signin-table">
                        <tr>
                            <th><label for="admin_id">Admin ID</label></th>
                        </tr>
                        <tr>
                            <td><input type="text" name="admin_id" required></td>
                        </tr>
                        <tr>
                            <th><label for="pwd">Password</label></th>
                        </tr>
                        <tr>
                            <td><input type="password" name="pwd" required></td>
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
            $admin_id = strtoupper(trim($_POST['admin_id']));
            $pwd = trim($_POST['pwd']);
            
            $sql = "SELECT * FROM fki_admin WHERE admin_id='$admin_id' AND pwd='$pwd'";
            $result = mysqli_query($conn, $sql);
            
            if(mysqli_num_rows($result) > 0){
                session_start();
                $_SESSION['admin_id'] = $admin_id;
                header("location: proposal_admin.php");
                exit();
            }
            else{
                echo '<script>popup_page_stay("Username or Password is incorrect")</script>';
            } 
        }
        $conn -> close();
    ?>
</html>
