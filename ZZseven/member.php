<?php //member.php
session_start();
require_once('db_connect.php');
require_once('user_auth_functions.php');
require_once('output_f.php');
$conn = db_connect();

if (isset($_POST['name']) && isset($_POST['password'])) {
    $name = addslashes($_POST['name']);
    $password = $_POST['password'];

    $password = sha1($password);
    $query = "SELECT * FROM user WHERE name='$name' AND password ='$password'";
    $result = $conn->query($query);
    
    if($result->num_rows>0){
        $_SESSION['user'] = $name;
        html_header('Home');
        $rs = $conn->query("SELECT type FROM user WHERE name = '$name'");
        $rowtem = $rs->fetch_array(MYSQLI_NUM);
        if ($rowtem[0] == 1) menu2();
        elseif ($rowtem[0] == 2) menu3();
        else menu();
        show_login_user($_SESSION['user']);
    } else {
        echo '<script>alert("Wrong username / password");
        window.location = "login.php";</script>';
        session_unset();
    }
} else if(!isset($_SESSION['user'])) header('Location:login.php');
else {
    $un = $_SESSION['user'];
    html_header('Home');
    $rs = $conn->query("SELECT type FROM user WHERE name = '$un'");
    $rowtem = $rs->fetch_array(MYSQLI_NUM);
    if ($rowtem[0] == 1) menu2();
    elseif ($rowtem[0] == 2) menu3();
    else menu();
    show_login_user($_SESSION['user']);
}
echo '<section style="text-align: center">
<p>Our website will provide you the guitar products <br> 
with high quality and affordable price.</p>
</section>';
html_footer();
?>