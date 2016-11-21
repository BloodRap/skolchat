<?php
    // Inialize session
    session_start();
        include "conn.php";
        $bdd->query("UPDATE users SET online = '0' WHERE UserName = '".$_SESSION['UserName']."' LIMIT 1");
        unset($_SESSION['UserId']);
        unset($_SESSION['UserName']);
        unset($_SESSION['UserMail']);
        // Delete certain session

        // Delete all session variables
        session_destroy();

        // Jump to login page
        header("Location: ../");
 ?>
