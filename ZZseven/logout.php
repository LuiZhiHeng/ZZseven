<?php //logout.php
require_once('output_f.php');
session_start();
html_header('Log Out');
$old_user = $_SESSION['user'];
unset($_SESSION['user']);
$result_dest = session_destroy();
if(!empty($old_user)){
    if ($result_dest) echo '<script>alert("You are logged out");window.location ="login.php";</script>';
    else echo 'Could not logged you out..<br><a href="login.php">Go to Login page</a>';
} else header('Location:login.php');