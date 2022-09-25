<?php
require 'functions.php';
session_start();

if( isset($_COOKIE['id']) && isset($_COOKIE['key']) ) {
    // if( $_COOKIE['login'] == 'true' ) {
    //     $_SESSION['login'] = true;
    // }

    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // get username using id
    $result = mysqli_query($conn, "SELECT username FROM users WHERE id = $id");
    $row =  mysqli_fetch_assoc($result);

    // chcking cookie dan username
    if( $key === hash('sha256', $row['username']) ) {
        $_SESSION['login'] = true;
    }     

}

if( isset($_SESSION["login"]) ) {
    header("Location: index.php");
    exit;
}

if( isset($_POST["login"]) ) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    // check username
    if( mysqli_num_rows($result) === 1 ) {

        // check password
        $row = mysqli_fetch_assoc($result);
        if( password_verify($password, $row["password"]) ) {// to check does the string same as the hash

            // set session
            $_SESSION["login"] =  true;

            // check remember me    
            if( isset($_POST['remember']) ) {
                // make cookie
                setcookie('id', $row['id'], time() + 60);
                setcookie('key', hash('sha256', $row['username']), time() + 60);
            }

            header("Location: index.php");
            exit;
        }
    }  

    $error = true;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <style>
        label.2{
            display : block;
        }
        ul li{
            list-style-type: none;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    
    <h1>Login Page</h1>

    <?php if( isset($error) ) : ?>
       <p style="color: red; font-style: italic;">Password / Username is incorrect!</p>
    <?php endif; ?>

    <form action="" method="post">

        <ul>
            <li>
                <label class="2" for="username">Username : </label>
                <input type="username" name="username" id="username">
            </li>

            <li>
                <label class="2" for="password">Password : </label>
                <input type="password" name="password" id="password">
            </li>

            <li>
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember me : </label>
            </li>
            
            <li>
                <button type="submit" name="login">login</button>
            </li>
        </ul>

    </form>

</body>
</html>