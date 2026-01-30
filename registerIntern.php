<?php

    require 'dbConfig.php';
    session_start();


    if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
        header("Location: loginUser.php");
        exit();
    }   

    $confirmationMessage = "";
    if (isset($_SESSION['registration_msg'])) {
        $confirmationMessage = $_SESSION['registration_msg'];
        unset($_SESSION['registration_msg']); 
    }
?>

<!DOCTYPE html>
<html>
    <head>        
        <meta charset= "UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/registerIntern.css">
        <title>Registration Form</title>
    </head>

    <body>
        <div class="registrationContainer">
            <h2 class="registrationTitle"> Register an Account </h2>
            <?php echo $confirmationMessage; ?>
            <form action="registrationAuth.php" method="POST">
                <div class="formGroup">
                    <label for="username" class="formLabel"> Username: </label>
                    <input type="username" class="formInput" id="username" name="username" required> <br> <br>
                </div>
                <div class="formGroup">
                    <label for="password" class="formLabel"> Password: </label>  
                    <input type="password" class="formInput" id="password" name="password" required> <br> <br>
                </div>
                <div class="registerButtonContainer">
                    <input type="submit" class="registerButton" value="Register">
                </div>
            </form>
        </div>

        <?php if (strpos($confirmationMessage, 'successful') !== false): ?>
            <script>
                const redirectConfig = {
                    url: 'adminDashboard.php',
                    delay: 2000
                };
            </script>
            <script src="js/redirectBuffer.js"></script>
        <?php endif; ?>
    </body>
</html>