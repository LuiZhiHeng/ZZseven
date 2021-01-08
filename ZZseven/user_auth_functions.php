<?php //user_auth_funcrions.php
require_once('db_connect.php');
function filled_out($form_vars){ //check html form is filled
    foreach($form_vars as $key => $value){
        if((!isset($key)) || ($value == '')) return false;
    }
    return true;
}

function valid_email($addr){ //check email address
    return (preg_match('/^[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/',$addr));
}

function register($name,$password,$email,$type){
    $conn = db_connect();
    $password = sha1($password);
    $result = $conn->query("INSERT INTO user (name, password, email, type) VALUES('$name','$password','$email','$type')");        
    if (!$result) throw new Exception("Could not register your data into database. <br> Please try again!");
}

function reset_password($email){
    $new_password = sha1(get_random_word(6));
    if($new_password == false) throw new Exception("New password generate failed.");
    $conn = db_connect();
    $result = $conn->query("UPDATE user SET password='$new_password' WHERE email='$email'");
    if(!$result) throw new Exception("Password failed to reset.");  
    else return $new_password;
}

function notify_password($email, $password){
    $conn = db_connect();
    $query = "SELECT email FROM user WHERE email='$email'";
    $result = $conn->query($query);
    if(!$result) throw new Exception("Could not find your account.");  
    else if($result->num_rows == 0) throw new Exception("Could not find your account."); 
    else{
        $row = $result->fetch_object();
        $email = $row->email;
        $from = "From: b190024a@sc.edu.my \r\n";
        $message = "Your ZZseven password has been changed to " .$password. "\r\n" ."Please use it next time you log in. \r\n";
        if(mail($email,'ZZseven Online Shop Login Information',$message,$from)) return true;
        else throw new Exception("Failed to send the notifying email.");
    }
}

function get_random_word($max_length){
    $permitted_chars = '0123456789';
    $ran_str = '';
    for($i=0; $i< $max_length; $i++) $ran_str .= $permitted_chars[rand(0,strlen($permitted_chars)-1)];
    return $ran_str;
}

?>