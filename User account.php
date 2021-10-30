<?php
session_start();
if (isset($_POST["logout"])){
    session_destroy();
    header("Location: Login.php");
}

$message = '';
if (empty($_SESSION['Login']))
{
    $message = '
        <p>Вы не авторизовались</p>
        <a href="Login.php">Login</a>
        <br>
        <a href="Registrations.php">Registration</a>';
}
else
{
    $login = $_SESSION['Login'];
    $filename = "visit_logs.txt";
    $lines = file($filename);
    $array = array();
    foreach ($lines as $line){
        if (preg_match('/login='.$login.';/', $line)){
            $array[] = $line;
        }
    }

    $message = '
    <p>Вы зашли как '.$login.'</p>
    <ul><li>' . implode('</li><li>', $array) . '</li></ul>
    <form action="" method="post">
        <input type="submit" value="Logout" name="logout">
    </form>';
}
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="style.css" rel="stylesheet"/>
</head>
<body>
<div class="top">
    <h1>User account</h1>
</div>
<div class="box">
    <div class="content">
        <h2>User account</h2>
        '.$message.'
    </div>
</div>';
