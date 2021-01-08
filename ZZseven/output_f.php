<?php //output_f.php
    if(isset($_SESSION['page'])){
        $pg = $_SESSION['page'];
        unset($_SESSION['page']);
        header('Location:'.$pg);
    }

    function html_header($title) { //print HTML header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title; ?> | zzseven</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1 style="text-align: center;">ZZSeven Online Guitar Shop</h1>
    <h4 style="text-align: center;">- <?= $title; ?> -</h4>
    <hr />
<?php
    }

    function menu(){//normal
?>
    <nav style="text-align: center;">
        |&nbsp;&nbsp;<a href="member.php">Home</a> &nbsp;|&nbsp;
        <a href="product.php">Product</a> &nbsp;|&nbsp;
        <a href="promo.php">Promo Code</a> &nbsp;|&nbsp;
        <a href="cart.php">Cart</a> &nbsp;|&nbsp;
        <a href="rate.php">Rate</a> &nbsp;|&nbsp;
        <a href="chat.php">Chat</a> &nbsp;|&nbsp;
        <a href="change_password.php">Change Password</a> &nbsp;|&nbsp;
        <a href="logout.php">Logout</a> &nbsp;|&nbsp;
        <hr>
    </nav>
<?php
    }

    function menu0(){//guest
?>
    <nav style="text-align: center;">
        |&nbsp;&nbsp;<a href="index.php">Home</a> &nbsp;|&nbsp;
        <a href="product.php">Product</a> &nbsp;|&nbsp;
        <a href="promo.php">Promo Code</a> &nbsp;|&nbsp;
        <a href="login.php">Login</a> &nbsp;|&nbsp;
        <hr>
    </nav>
<?php
    }    

    function menu2(){//seller
?>
    <nav style="text-align: center;">
        |&nbsp;&nbsp;<a href="member.php">Home</a> &nbsp;|&nbsp;
        <a href="viewProduct.php">Manage Product</a> &nbsp;|&nbsp;
        <a href="promo.php">Manage Promo Code</a> &nbsp;|&nbsp;
        <a href="CSV.php">CSV Report</a> &nbsp;|&nbsp;
        <a href="chat.php">Chat</a> &nbsp;|&nbsp;
        <a href="change_password.php">Change Password</a> &nbsp;|&nbsp;
        <a href="logout.php">Logout</a> &nbsp;|&nbsp;
        <hr>
    </nav>
<?php
    }

    function menu3(){//admin
?>
    <nav style="text-align: center;">
        |&nbsp;&nbsp;<a href="member.php">Home</a> &nbsp;|&nbsp;
        <a href="viewProduct.php">View Product</a> &nbsp;|&nbsp;
        <a href="viewUser.php">View User</a> &nbsp;|&nbsp;
        <a href="promo.php">View Promo Code</a> &nbsp;|&nbsp;
        <a href="chat.php">Chat</a> &nbsp;|&nbsp;
        <a href="change_password.php">Change Password</a> &nbsp;|&nbsp;
        <a href="commission.php">Edit Commission</a> &nbsp;|&nbsp;
        <a href="logout.php">Logout</a> &nbsp;|&nbsp;
        <hr>
    </nav>
<?php
    }

    function show_login_user($username){
        require_once('db_connect.php');
        $conn = db_connect();
        $rs = $conn->query("SELECT usertype.name FROM user JOIN usertype ON usertype.id = user.type WHERE user.name = '$username'");
        $row = $rs->fetch_assoc();
?>
    <h5 style="text-align: center;"><small>Logged in as:<br></small><?= $username; ?> (<small><?= ucfirst($row['name']); ?></small>)</h5>
    <hr>
<?php
    }

    function html_footer(){ //print HTML footer
?>
    <br><br><br>
    <footer>
        <p>ZZ Seven &copy; 2020</p>
    </footer>
</body>
</html>
<?php
    }
?>
