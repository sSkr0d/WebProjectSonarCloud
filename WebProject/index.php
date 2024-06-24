<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,  initial-scale=1.0">
        <title>User Selection</title>
        <link rel="icon" type="image/png" href="src/icon.png">
	    <link rel="stylesheet" href="css/style.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@300&display=swap">
    </head>

    <body>
        <div class="container-row">
            <img src="src/icon.png" alt="Logo">
            <h2>FKI Event Management</h2>
            <div class="signin-box">
                <h1>Select User</h1>
                <table class="signin-table">
                    <tr>
                        <td><button onclick="location.href='signin_student.php'">Student</button></td>
                    </tr>
                    <tr>
                        <td><button onclick="location.href='signin_pmfki.php'">PMFKI</button></td>
                    </tr>
                    <tr>
                        <td><button onclick="location.href='signin_admin.php'">Admin</button></td>
                    </tr>
                </table>
            </div>
        </div>
    </body>
</html>