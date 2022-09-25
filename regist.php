<?php
    require 'dist/crud/functions.php';

    // checking that user has pressed the register button;
    if( isset($_POST["register"]) ) {

       if( regist($_POST) > 0 ) {
        echo "
            <script>
                alert('user successfully added !')
                document.location.href = 'regist.php'
            </script>
       "; 
       } else {
        echo mysqli_error($conn);
       }

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
          }
         }

        /* *{
          border: 1px solid blue
         } */

        }
      </style>

</head>
<body>
    
    <h1>Registration Page</h1>

    <form action="" method="POST">

    <ul>
        <li>
            <label for="username">username : </label>
            <input type="text" name="username" id="username">
        </li>
        
        <li>
            <label for="password">password :</label>
            <input type="password" name="password" id="password">
        </li>
        
        <li>
            <label for="password2">Confirm your password : </label>
            <input type="password" name="password2" id="password2">
        </li>
        
        <li>
            <button type="submit" name="register"> Register! </button> <br> <br>
            <a href="login.php">Login</a>
        </li>
    </ul>

    </form>

</body>
</html>