<?php // editProduct.php
    require_once('db_connect.php');
    require_once('output_f.php');
    $conn = db_connect();
    session_start();
    if(isset($_POST['editdone'])){
        $id = $_POST['id'];
        $name = addslashes($_POST['name']);
        $price = $_POST['price'];
        $quantityTotal = $_POST['quantityTotal'];
        $querys = "UPDATE inventory SET name='$name', price='$price', quantityTotal='$quantityTotal' WHERE id='$id'";
        $results = $conn->query($querys);
        if(!$results) die("Error update");
        else header("Location:viewProduct.php");
    }
    if(!isset($_SESSION['user'])) header("Location:login.php"); 
    if(isset($_POST['edit']) && isset($_POST['idInv'])){
        $id = $_POST['idInv'];
        $query = "SELECT id, name, price, quantityTotal FROM inventory WHERE id = '$id'";
        $result = $conn->query($query);
        if(!$result) die("Error before edit");
        html_header("Edit Product");
        $un = $_SESSION['user'];
        $rs = $conn->query("SELECT type FROM user WHERE name = '$un'");
        menu2();
        show_login_user($_SESSION['user']);
?>
    <form id="form" action="editProduct.php" method="POST">
        <?php
            $row = $result->fetch_array(MYSQLI_NUM);
            for ($j=0; $j < count($row); $j++) { 
                if ($j == 0) {echo '<div class="input-group">Product Id: <input type="text" name="id" value="'?><?php echo $row[$j]; echo'" readonly></div>';}
                elseif ($j == 1) {echo '<div class="input-group">Name: <input type="text" name="name" value="'?><?php echo $row[$j]; echo'"></div>';}
                elseif ($j == 2) {echo '<div class="input-group">Price: <input type="text" name="price" value="'?><?php echo $row[$j]; echo'"></div>';}
                elseif ($j == 3) {echo '<div class="input-group">Total Quantity: <input type="text" name="quantityTotal" value="'?><?php echo $row[$j]; echo'"></div>';}
            }
        ?>
        <input class="btn" type="submit" name="editdone" value="EDIT THIS PRODUCT">
    </form>
<?php
    html_footer();
    }
?>