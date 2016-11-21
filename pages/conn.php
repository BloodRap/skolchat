<?php
$server = 'localhost';
$database = 'skolchat';
$username = 'root';
$password = '';
      try{
          $bdd = new PDO("mysql:host=$server;dbname=$database", $username, $password);
      }catch(exception $e){
          die("ERROR : ".$e->getMessage());
      }


?>
