<?php
$servername = "127.0.0.1";
$username = "homestead";
$password = "secret";


$conn = new mysqli($servername, $username, $password);
mysqli_select_db($conn, 'homestead');

if ($conn->connect_error) {
    die("prisijungti nepavyko: " . $conn->connect_error);
}
echo "Prijungta";
?>
<form action="" method="post">
    <table width="50%">
        <tr>
            <td>User</td>
            <td><input type="text" name="user"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="text" name="password"></td>
        </tr>
    </table>
    <input type="submit" value="OK" name="s">
</form>

<?php
if(isset($_POST['s']) && $_POST['s']){
    $user = $_POST['user'];
    $pass = $_POST['password'];
    $ress = mysqli_query($conn, "SELECT * From admi WHERE username = '$user' and pass = '$pass'");
    echo "SELECT * From admi WHERE username = '$user' and pass = '$pass'";
    if(mysqli_num_rows($ress) == 0){
        echo 'Neprisijunge';
    }else{
        echo 'Prisijunge';
    }
}
?>

