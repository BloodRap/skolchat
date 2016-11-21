<?php
    session_start();
    include "classes.php";
    $user = new user();
    $user->getOnlineUsers();
?>
