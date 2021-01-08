<?php //viewUser.php
    session_start();
    if(!isset($_SESSION['user']) || $_SESSION['user'] != "admin") header('Location:login.php');
    require_once('db_connect.php');
    $conn = db_connect();
    require_once('output_f.php');
    html_header('View User');
    menu3();
    show_login_user($_SESSION['user']);
    echo '<table id="vp" border="1" cellspacing="0"><tr><th>UserName</th><th>Email</th><th>Role</th></tr>';
    $result = $conn->query("SELECT user.name, user.email, user.type FROM user WHERE type != 2");
    for ($i = 0; $i < $result->num_rows; $i++){
        $row = $result->fetch_array(MYSQLI_NUM);
        $rs = $conn->query("SELECT name FROM usertype WHERE id= $row[2]");
        $type = $rs->fetch_array(MYSQLI_NUM);
        echo '<tr>';
        for ($j = 0; $j < count($row)-1; $j++){
            echo '<td>' . $row[$j] . '</td>';
        }
        echo '<td>' . $type[0] . '</td></tr>';
    }
    echo '</table>';
    html_footer();
?>