<?php
require 'dist/crud/functions.php';
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
    <title>Students - DB</title>

    <!-- Link daisyui -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@2.24.2/dist/full.css" rel="stylesheet" type="text/css" />

    <!-- Link tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
          theme: {
            container: {
              center: true,
              padding: '16px'
            },
            extend: {
              colors: {
                primary: '#14b8a6',
              },
              screens: {
                '2xl': '1320px',
              }
            }
          }
        }
      </script>

      <style type="text/tailwindcss">
        /* import font */
        @import url('https://fonts.googleapis.com/css2?family=Comic+Neue&family=Dosis:wght@500&family=Luckiest+Guy&family=Poppins:ital,wght@0,300;0,500;0,600;0,800;0,900;1,600&display=swap');

        @layer utilities {
         .hamburger-active > span:nth-child(1){
           @apply rotate-45;
         }

         @layer base {
          *{
            font-family: 'Poppins', sans-serif;
            color: black;
          }
         }

        /* *{
          border: 1px solid blue
         } */

        }
      </style>

</head>
    <body class="items-center w-full h-[100vh] relative bg-[black]">

        <!-- Warning -->
        <!-- <?php if( isset($error) ) : ?> -->
          <div class="absolute m-5 top-5">
            <div class="alert alert-error shadow-lg p-0 lg:p-2 lg:w-full flex justify-center items-center">
              <div class="m-0 p-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 ml-1 lg:h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Password / Username is incorrect.</span>
              </div>
            </div>
          </div>
        <!-- <?php endif; ?> -->

    <div class="container h-[100vh] flex items-center justify-center">
        <div class="mt-5 h-[60%] w-[80%] flex">
            <div class="h-[100%] rounded-l-3xl flex-[1.4] w-32 bg-[#FFACC7] container flex items-center justify-center">
                <div class="h-[90%] w-[95%]">
                        <center>
                            <h1 class="mt-14 mb-3 font-bold text-[24px] text-white underline decoration-white underline-offset-8 ">USER LOGIN</h1>
                            <form action=""></form>
                            <div class="h-[20%]">
                                <input type="text" class="border-solid border-2 border-black w-[60%] h-8 mt-3" placeholder=" Username" required type="username" name="username" id="username">
                            </div>
                            <div class="h-[20%]">
                                <input type="password" class="border-solid border-2 border-black w-[60%] h-8 mt-3"  placeholder=" Password" required type="password" name="password" id="password">
                            </div>
                        
                            <div class="h-[10%] w-[60%] mt-3 container flex justify-start">
                                <div class="-ml-4">
                                    <input type="checkbox" name="remember" id="remember">
                                    <span>Remember Me</span>
                                </div>
                            </div>
                        
                            <div class="h-[15%]">
                                <button class="border-solid border-2 border-black h-10 mt-4 w-[40%] rounded-3xl bg-[#FF535C] hover:bg-[#A13339] text-white" type="submit" name="login">Sign In</button>
                            </div>
                        </center>
                </div>
                
            </div>
            <div class="h-[100%] rounded-r-3xl flex-1 w-72 bg-white">
                <div class="h-[80%] ml-5 mr-5">
                    <img style='height: 100%; width: 100%; object-fit: contain' src="images/word.png" alt="">
                </div>
                <div class="h-[20%] ml-5 mr-5 flex items-center justify-center">
                  <a class="border-solid border-2 border-black rounded-3xl bg-[#AAEEEA] hover:bg-[#4C6D6B] text-black hover:text-white p-2" href="test2.html">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>