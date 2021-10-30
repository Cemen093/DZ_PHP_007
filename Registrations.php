<?php
session_start();

if (!empty($_SESSION["Login"])) {
    echo '<h1>Вы уже вошли, эта страница не доступна</h1>
            <a href="user account.php">User account</a>';
    return;
}


$pattern_name = '/\w{3,}/';
$pattern_password = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/';

$error_message = '';

if (isset($_POST['submit']))
{
    $login= isset($_POST['user_login']) ? $_POST['user_login'] : '';
    $password = isset($_POST['user_password']) ? $_POST['user_password'] : '';
    $filename = 'users.txt';
    if(!preg_match($pattern_name, $login)){
        $error_message = '<p class="error">login incorrect</p>';
    } else if(!preg_match($pattern_password, $password)){
        $error_message = '<p class="error">password incorrect</p>';
    } else if (file_exists($filename) and preg_match('/login='.$login.';/', file_get_contents($filename))){
        $error_message = '<p class="error">login already exists</p>';
    } else {
        file_put_contents($filename, "login=" . $login . ';password=' . $password . ';' . PHP_EOL, FILE_APPEND);
        file_put_contents("visit_logs.txt", "login=".$login.';datetime='.(new DateTime('NOW'))->format('c').
            ';'.PHP_EOL, FILE_APPEND);
        $_SESSION['Login'] = $_POST['user_login'];
        header("Location: User account.php");
    }

}

echo'
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <link href="style.css" rel="stylesheet"/>
    </head>
    <body>
    <script>
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
    </script>
    <div class="top">
        <h1>Registration</h1>
            <a href="Login.php">Login</a>
            <br/>
            <a href="User%20account.php">User account</a>
    </div>
    <div class="box">
        <div class="content">
            <h2>Registration</h2>
            <form action="Registrations.php" method="post">
                <p><label>Login:<input type="text" placeholder="Login" name="user_login"></label></p>
                <p><label>Password:<input type="text" placeholder="Password" name="user_password"></label></p>
                <div>
                    <input type="reset" value="Reset ">
                    <input type="submit" value="Registrations" name="submit">
                </div>
            </form>
            '.$error_message.'
        </div>
    </div>
';

?>