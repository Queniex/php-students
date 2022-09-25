<?php

session_start();

if( !isset($_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}

require 'functions.php';

// get data from the url
$id = $_GET["id"];

// get data query using id as a parameter
$mhs = query("SELECT * FROM mahasiswa WHERE id = $id")[0]; //because the id is integer we dont need any (" ").
//var_dump($mhs);

// checking is submit button has been press yet.
if ( isset($_POST["submit"]) ){
    
    if( edit($_POST) > 0 ){
       echo "
            <script>
                alert('data berhasil diubah!')
                document.location.href = 'index.php'
            </script>
       "; 
    } else {
        echo "
            <script>
                alert('data gagal diubah!')
                document.location.href = 'index.php'
            </script>
       "; 
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <style>
        ul li{
            list-style-type: none;
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <h1>Edit Data Mahasiswa</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <ul>
            <li>
            <input type="hidden" name="gambar" value="<?= $mhs["Gambar"]; ?>">
                <input type="hidden" name="id" value="<?= $mhs["Id"]; ?>">
            </li>
            <li>
                <label for="Nim">Nim : </label>
                <input type="text" id="Nim" name="Nim" required value="<?= $mhs["Nim"]; ?>">
            </li>
            <li>
                <label for="Nama">Nama : </label>
                <input type="text" id="Nama" name="Nama" required value="<?= $mhs["Nama"]; ?>">
            </li>
            <li>
                <label for="Gambar">Gambar : </label> <br>
                <img src="img/<?= $mhs["Gambar"]; ?>" width="60"> <br>
                <input type="file" id="Gambar" name="Gambar">
            </li>
            <li>
                <button type="submit" name="submit">Update Data!</button>
            </li>
        </ul>
    </form>

</body>
</html>