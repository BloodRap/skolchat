<?php
    include "classes.php";
    $user = new user();

    if( !isset($_POST['UserName']) || !isset($_POST['UserMail']) || !isset($_POST['UserPassword']) ){

        header("Location: ../index.php?error=1");
    }
    elseif( empty($_POST['UserName']) || empty($_POST['UserMail']) || empty($_POST['UserPassword']) ){
        header("Location: ../index.php?error=1");
    }
    else {
        $user->setUserName($_POST['UserName']);
        $user->setUserMail($_POST['UserMail']);
        $user->setUserPassword(sha1($_POST['UserPassword']));
        $user->InsertUser();

    }
?>
