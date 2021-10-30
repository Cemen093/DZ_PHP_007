<?php
//Реализовать страницу входа / авторизации с запоминанием сессии.
//При входе на странице показывать историю входов и ip устройств.
//$ip = $_SERVER['REMOTE_ADDR']; для получения ip.

session_id("ad136688-d1c1-4b44-b8a0-fbb9418fb51a");
session_start();

if (!empty($_SESSION["Login"])) {
    echo '<h1>Вы уже вошли, эта страница не доступна</h1>
            <a href="user account.php">User account</a>';
    return;
}

if (!isset($_SESSION['login_attempt']))
    $_SESSION['login_attempt'] = 0;

if (isset($_POST['submit']) and isset($_POST['user_login']) and isset($_POST['user_password'])) {
    $login = $_POST['user_login'];
    $password = $_POST['user_password'];
    $_SESSION['login_attempt']++;

    $filename = 'users.txt';
    if (file_exists($filename)){
        $file = file_get_contents($filename);
        if (preg_match('/login='.$login.';password='.$password.'/', $file)){
            $_SESSION['login_attempt'] = 0;
            $_SESSION['Login'] = $login;
            file_put_contents("visit_logs.txt",
                "login=".$login.';'.
                    'datetime='.(new DateTime('NOW'))->format('c').';'.
                    'ip='.$_SERVER['REMOTE_ADDR'].';'.PHP_EOL, FILE_APPEND);
            header("Location: User account.php");
        }
    }
}

$error_message = ($_SESSION['login_attempt'] > 0 ?
    '<p class="error">Вы неудачно пытались залогинится '.$_SESSION['login_attempt'].' раз(а),</p> 
                <p class="error">у вас осталось '.(3 - $_SESSION['login_attempt']).' попытока(ок)</p>' : '');

if ($_SESSION['login_attempt'] < 3){
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
            <h1>Login</h1>
            <a href="Registrations.php">Registration</a>
            <br/>
            <a href="User%20account.php">User account</a>
        </div>
        <div class="box">
            <div class="content">
                <h2>Login</h2>
                <form action="Login.php" method="post">
                    <p><label>Login:<input type="text" placeholder="Login" name="user_login"></label><p>
                    <p><label>Password:<input type="text" placeholder="Password" name="user_password"></label></p>
                    <div>
                        <input type="reset" value="Reset ">
                        <input type="submit" value="Sing in" name="submit">
                    </div>
                </form>
                '.$error_message.'
            </div>
        </div>
        </body>
        </html>
    ';
} else {
    $_SESSION['login_attempt'] = 0;
    header("Location: Registrations.php");
}