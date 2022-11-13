<?php
$conn = mysqli_connect("localhost", "root", "", "phpdasar");

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ){
        $rows[] = $row;
    }
    return $rows;
}

function add($data) {
    global $conn;
    $nim = htmlspecialchars($data["Nim"]);
    $nama = htmlspecialchars($data["Nama"]);
    // $gambar = htmlspecialchars($data["Gambar"]);

    // upload picture
    $gambar = upload();
    if( !$gambar ) {
        return false;
    }

    $query = "INSERT INTO mahasiswa 
                VALUES
               ('', '$nama', '$nim', '$gambar')"; 
    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function upload() {

    $fileName = $_FILES['Gambar']['name'];
    $fileSize = $_FILES['Gambar']['size'];
    $error = $_FILES['Gambar']['error'];
    $tmpName = $_FILES['Gambar']['tmp_name'];

    // check if there is no picture upload
    if( $error === 4 ){
        echo "
            <script>
                alert('Please upload a picture!')
            </script>
       ";
       return false; 
    }

    // checking the file extension type
    $validPicture = ['jpg', 'jpeg', 'png'];
    $pictureExtension = explode('.', $fileName); // will return an array of the picture name;
    $pictureExtension = strtolower( end( $pictureExtension ));
    if( !in_array($pictureExtension, $validPicture) ) { // this function could find if a string is part of the array; 
        echo "
            <script>
                alert('Your file type is not a picture!')
            </script>
       ";
       return false;
    }

    // checking the file size
    if( $fileSize > 1000000 ) {
        echo "
            <script>
                alert('Your picture size is too big!')
            </script>
       ";
       return false;
    }

    // picture ready to be upload
    // generate new picture name
    $newPictureName = uniqid();
    $newPictureName .= '.';
    $newPictureName .= $pictureExtension;

    move_uploaded_file($tmpName, 'img/' . $newPictureName);

    return $newPictureName;

}

function deletes($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE Id = $id");

    return mysqli_affected_rows($conn);
}

function edit($data) {
    global $conn;

    $id = $data["id"];
    $nim = htmlspecialchars($data["Nim"]);
    $nama = htmlspecialchars($data["Nama"]);
    // $gambar = htmlspecialchars($data["Gambar"]);
    $oldPicture = htmlspecialchars($data["Gambar"]);

    // check if user choosing a new picture
    if( $_FILES['Gambar']['error'] === 4 ) {
        $gambar = $oldPicture;
    } else {
        $gambar = upload();
    }

    $query = "UPDATE mahasiswa SET
               Nama = '$nama',
               Nim = '$nim',
               Gambar = '$gambar'
               WHERE Id = $id"; 

    mysqli_query($conn, $query);

    return mysqli_affected_rows($conn);
}

function find($keyword) {
    $query = "SELECT * FROM mahasiswa
                WHERE
                Nama LIKE '%$keyword%' OR
                Nim LIKE '%$keyword%'";
                
    return query($query);
}

function regist($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]); // to make user possible add some string
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);

    // does username exist
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'" );

    if( mysqli_fetch_assoc($result) ) {
        echo "
            <script>
                alert('Username already exist!')
                document.location.href = 'regist.php'
            </script>
       "; 
       return false;
    }

    // check confirm of the password
    if( $password !== $password2 ) {
        echo "
            <script>
                alert('Password do not match!')
                document.location.href = 'regist.php'
            </script>
       "; 
       return false;
    }

    // encrypt the password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // add password to db
    mysqli_query($conn, "INSERT INTO users VALUES('', '$username', '$password')");

    return mysqli_affected_rows($conn);
}
?>