<?php //product.php
    session_start();
    require_once('db_connect.php');
    $conn = db_connect();
    require_once('output_f.php');
    html_header('Product');
    (isset($_SESSION['user']))? menu(): menu0();
    if(isset($_SESSION['user'])) {
        $un = $_SESSION['user'];
        show_login_user($un);
    }

    // if add to cart
    if(isset($_POST['id']) && isset($_POST['addc'])){
        if(!isset($_SESSION['user'])) {
            $_SESSION['page'] = basename($_SERVER['PHP_SELF']);
            echo "<script>alert('Please login first'); window.location = 'login.php';</script>";
        }

        $un = $_SESSION['user'];
        $rsId = $conn->query("SELECT id FROM user WHERE name = '$un'");
        $id = $rsId->fetch_array(MYSQLI_NUM);
        $inv = $_POST['id'];
        $rsCheckRepeat = $conn->query("SELECT id, idInventory FROM cart WHERE idCustomer = $id[0]");
        if($rsCheckRepeat->num_rows <= 0){
            $rsAdd = $conn->query("INSERT INTO cart VALUES(NULL, '$id[0]', '$inv', 1)");
            if($rsAdd) echo '<script>alert("Successfully added to cart")</script>';
            else die("Error");
        }
        for ($i = 0; $i < $rsCheckRepeat->num_rows; $i++){
            $cart = $rsCheckRepeat->fetch_array(MYSQLI_NUM);
            if($cart[1] == $inv){ // if added before
                $rsUpdate = $conn->query("UPDATE cart SET quantity = quantity + 1 WHERE id = $cart[0]");
                if($rsUpdate) {
                    echo '<script>alert("Successfully added to cart!")</script>';
                    break;
                }
            } elseif ($i == ($rsCheckRepeat->num_rows - 1)) { // if not added before
                $rsAdd = $conn->query("INSERT INTO cart VALUES(NULL, '$id[0]', '$inv', 1)");
                if($rsAdd) echo '<script>alert("Successfully added to cart")</script>';
                else die("Error");
            }
        }
    }
?>
    <table id="vp" border="1" cellspacing="0">
        <tr>
            <form action="product.php" method="POST" >
            <td colspan="5">
                <input style="width:100%;" type="text" maxlength="40" name="key" placeholder="Search product...">
            </td>
            <td>
                <input style="width:100%;" type="submit" name="search" value="SEARCH">
            </td>
            </form>
        </tr>
        <tr>
            <th>No.</th>
            <th>Product name</th>
            <th>Type</th>
            <th>Price</th>
            <th>Qty</th>
            <th>Add To Cart</th>
        </tr>
        <?php
            $search = "";
            if(isset($_POST['search']) && isset($_POST['key'])){
                $key = $_POST['key'];
                $result = $conn->query("SELECT inventory.name, inventorytype.name, inventory.price, inventory.quantityLeft, inventory.id FROM inventory JOIN inventorytype ON inventorytype.id = inventory.type WHERE inventory.name LIKE '%$key%'");
            } else $result = $conn->query("SELECT inventory.name, inventorytype.name, inventory.price, inventory.quantityLeft, inventory.id FROM inventory JOIN inventorytype ON inventorytype.id = inventory.type");
            for($i=0;$i<$result->num_rows;$i++){
                echo '<tr><td>' . ($i+1) . '</td>';
                $row = $result -> fetch_array(MYSQLI_NUM);
                for($j=0;$j<count($row)-1;$j++){
                    if($j == 0) echo '<td><a style="text-decoration:none" href="productDetail.php?productName=' . $row[0] . '">' . $row[0] . '</a></td>';
                    else echo '<td>' . $row[$j] . '</td>';
                }
                ?>
                <td>
                    <form action="product.php" method="POST">
                        <input type="hidden" name="id" value="<?= $row[4] ?>">
                        <input class="btn" type="submit" name="addc" value="Add To Cart">
                    </form>
                </td>
                <?php
                echo '</tr>';
            }
        ?>
    </table>
<?php
    html_footer();
?>