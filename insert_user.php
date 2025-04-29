<?php
session_start();
require 'dataconnection.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $FirstName = $_POST['FirstName'];
    $LastName = $_POST['LastName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO admin (email, password, first_name,last_name,phone_number)
            VALUES ('$email', '$password','$FirstName','$LastName','$phone')";
    $result = mysqli_query($connect, $sql);
    if($result)
    {
        echo("success");
    }
    else{
        echo("fail");
    }
}
?>
