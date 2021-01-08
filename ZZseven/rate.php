<?php //rate.php
    require_once('output_f.php');
    html_header('Rate');
    session_start();
    if(!isset($_SESSION['user'])) header('Location:login.php');
    menu();
    show_login_user($_SESSION['user']);
    if(isset($_POST['product_name']) && isset($_POST['rate']) && isset($_POST['comment'])){
        $productname=addslashes($_POST['product_name']);
        $rate=$_POST['rate'];
        $comment=addslashes($_POST['comment']);
        require_once('db_connect.php');
        $conn=db_connect();
        $user=$_SESSION['user'];
        $idCustomer = $conn->query("SELECT id FROM user WHERE name='$user'");
        $idCust = implode($idCustomer->fetch_row());
        $idInventory = $conn->query("SELECT id FROM inventory WHERE name='$productname'");
        $idInvent = $idInventory->fetch_array(MYSQLI_NUM);
        if ($idInvent[0] < 1) echo "<script>alert('Product name (" . $productname . ") is not exist');</script>"; 
        else {
            $result = $conn->query("INSERT INTO rate (idCustomer, idInventory, rate, comment) VALUES ('$idCust', $idInvent[0], '$rate', '$comment')");
            if (!$result) throw new Exception("Problem Occurs. <br> Please try again!");
            else echo "<script>alert('Successfully rated');</script>";
        }
    }
?>
    <form id="form" method="post" action="rate.php">
        <div class="input-group">
            <label>Product Name:</label>
            <input type="text" name="product_name" required/>
        </div>
        <div class="input-group">
            <label>Your Rate (Lowest:1 Highest:10)</label>
            <input type="number" name="rate" min="1" max="10" required/>
        </div>
        <div class="input-group">
            <label>Your Comment: </label>
            <textarea style="width: 96.5%;resize:none" name="comment" rows="8" cols="35" required></textarea>
        </div>
        <div class="input-group">
            <button type="submit" class="btn">Confirm</button>
        </div>
    </form>
<?php
    html_footer();
?>