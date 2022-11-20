<?php
require 'functions.php';
session_start();
$a = false;

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
    global $a;
    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT username, password FROM users WHERE username = '$username'");

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

        }

            // check captcha
            if( isset($_POST["true"]) ) {
              header("Location: index.php");
              exit;
            } elseif ( isset($_POST["false"]) ){
              $error = true;
              $_SESSION["login"] =  false;
              session_unset();
              session_destroy();
            }

        // if( $a == false) {
        //   $error = true;
        //   $_SESSION["login"] =  false;
        //   session_unset();
        //   session_destroy();
        // } 
        // $_SESSION["login"] =  true;
        // header("Location: index.php");
        // exit;

    }  
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="cupcake">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MacaroonMart | Login</title>

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
                <span>Password / Username / Captcha is incorrect.</span>
              </div>
            </div>
          </div>
        <!-- <?php endif; ?> -->

    <div class="container h-[100vh] flex items-center justify-center">
        <div class="mt-5 h-[60%] w-[60%] flex">
            <div class="h-[100%] rounded-l-3xl flex-[1.4] w-32 bg-[#FFACC7] container flex items-center justify-center">
                <div class="h-[90%] w-[95%]">
                        <center>
                            <h1 class="mt-4 mb-4 font-bold text-[24px] text-white underline decoration-white underline-offset-8 ">USER LOGIN</h1>
                        <form method="POST">
                            <div class="h-[20%]">
                                <input type="text" class="border-solid border-2 border-black w-[60%] h-8" placeholder=" Username" required type="username" name="username" id="username">
                            </div>
                            <div class="h-[20%]">
                                <input type="password" class="border-solid border-2 border-black w-[60%] h-8 mt-3"  placeholder=" Password" required type="password" name="password" id="password">
                            </div>

                            <div class="h-[10%] w-[60%] mt-3 mb-2 container flex justify-start">
                                <div class="-ml-4">
                                    <input type="checkbox" name="remember" id="remember">
                                    <span>Remember Me</span>
                                </div>
                            </div>

                            <div class="h-[40%]">
                                <div class="h-[80%] mx-5 bg-white py-3 hover:drop-shadow-xl hover:drop-shadow-[0_20px_20px_rgba(0,0,0,0.25)]">
                                    <div class="h-[30%] mb-1 mx-0">
                                        <div class="flex">
                                            <div class="flex-none w-[65%] select-none">
                                              <label class="select-none pointer-events-none">
                                                <input class="capt bg-white pointer-events-none mt-2 border-hidden text-center text-[#FF8DC7]" id="capt" readonly="readonly" value="Press Refresh" disabled>
                                              </label>
                                            </div>
                                            <div class="flex-initial w-[35%] mr-3">
                                                <div class="">
                                                    <span type="button" onclick="cap()" class="group inline-flex items-center gap-2 rounded-full bg-[#FFACC7] px-3 py-1.5 font-semibold text-black hover:bg-[#FF8DC7]">
                                                      Refresh
                                                      <svg class="stroke-black stroke-2" xmlns="http://www.w3.org/2000/svg" fill="none" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
                                                        <path d="M17 9.3466L21.0154 9.34838V9.3466M4.03076 9.86481C5.21003 5.46371 9.73381 2.85191 14.1349 4.03118C15.5867 4.42017 16.8437 5.17309 17.8343 6.16547L21.0154 9.3466M21.0154 5.5V9.3466" stroke-linecap="round" stroke-linejoin="round" class="origin-center transition duration-300 group-hover:rotate-45" />
                                                  
                                                        <path d="M2.98413 18.5V14.6517M2.98413 14.6517H7M2.98413 14.6517L6.16502 17.8347C7.15555 18.827 8.41261 19.58 9.86436 19.9689C14.2654 21.1482 18.7892 18.5364 19.9685 14.1353" stroke-linecap="round" stroke-linejoin="round" class="origin-center transition duration-300 group-hover:rotate-45" />
                                                      </svg>
                                                    </span>
                                                  </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="h-[30%] mt-2 mx-5">
                                        <div class="flex">
                                            <div class="flex-none w-[65%]">
                                              <input type="text" class="placeholder:text-black text-center bg-[#FFACC7]" id="textInput" placeholder="Input Captcha..." required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="h-[15%]">
                                <button class="btn border-solid border-2 border-black h-10 mt-4 mb-2 w-[40%] rounded-3xl bg-[#FF535C] hover:bg-[#A13339] text-white" for="my-modal" id="a" onclick="validCapt()" name="login">Sign In</button>
                            </div>
                        </form>
                        </center>
                </div>
                
            </div>
                <div class="h-[100%] rounded-r-3xl flex-1 w-72 bg-white">
                    <div class="h-[72%] ml-5 mr-5">
                        <img style='height: 100%; width: 100%; object-fit: contain' src="images/gambar/word.png" alt="">
                    </div>
                    <div class="h-[27%] ml-5 mr-5 flex items-center justify-center">
                      <a class="border-solid border-2 border-black rounded-3xl bg-[#AAEEEA] hover:bg-[#4C6D6B] text-black hover:text-white p-2" href="regist.php">Sign Up</a>
                    </div>
                </div>
        </div>
    </div>

    <script type="text/javascript">
            function cap() {
            var alpha = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', '1', '2', '3', '4', '5', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'
                        , 'i'];
            var a = alpha[Math.floor(Math.random()*23)];
            var b = alpha[Math.floor(Math.random()*23)];
            var c = alpha[Math.floor(Math.random()*23)];
            var d = alpha[Math.floor(Math.random()*23)];
            var e = alpha[Math.floor(Math.random()*23)];

            var sum = a + b + c + d + e;
            console.log(sum);
            document.querySelector('.capt').value = sum;
            }

            function validCapt() {
                var string1 = document.getElementById("capt").value;
                var string2 = document.getElementById('textInput').value;
                if (string1 == string2) {
                  document.getElementById('a').value = "true";
                } else {
                  document.getElementById('a').value = "false";
                  alert("Please enter a valid captcha!");
                }
            }
    </script>
</body>
</html>