<?php //productDetail.php
    session_start();
    require_once('db_connect.php');
    $conn = db_connect();
    require_once('output_f.php');
    html_header('Product Detail');
    (isset($_SESSION['user']))? menu(): menu0();
    if(isset($_SESSION['user'])) show_login_user($_SESSION['user']);

    if(isset($_GET['productName'])){
        $pn = addslashes($_GET['productName']);
        $rspid = $conn->query("SELECT inventory.id, inventory.name, inventorytype.name, inventory.price, inventory.quantityLeft, inventory.idSeller FROM inventory JOIN inventorytype ON inventorytype.id = inventory.type WHERE inventory.name = '$pn'");
        $pid = $rspid->fetch_array(MYSQLI_NUM);
        $rsRate = $conn->query("SELECT user.name, rate.comment, rate.rate FROM rate JOIN user ON user.id = rate.idCustomer WHERE idInventory = $pid[0]");
        $rsSeller = $conn->query("SELECT name FROM user WHERE id = $pid[5]");
        $seller = $rsSeller->fetch_assoc();
?>
    <table id="vp" border="1" cellspacing="0">
        <tr>
            <th>Seller</th>
            <th>Name</th>
            <th>Type</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Add to Cart</th>
        </tr>
        <tr style="text-align: center">
            <td><a style="text-decoration: none" href="chat.php?name=<?= $seller['name'] ?>"><?= $seller['name'] ?></a></td>
            <td><?= $pid[1] ?></td>
            <td><?= $pid[2] ?></td>
            <td><?= $pid[3] ?></td>
            <td><?= $pid[4] ?></td>
            <td>
                <form action="product.php" method="POST">
                    <input type="hidden" name="id" value="<?= $pid[0] ?>">
                    <input class="btn" type="submit" name="addc" value="Add To Cart">
                </form>
            </td>
        </tr>
    </table>
    <hr><p style="text-align: center; font-weight: bold;">Rate & Comment</p>
    <table id="vp" border="1" cellspacing="0">
        <tr>
            <th style="text-align: center">User</th>
            <th style="text-align: center">Comment</th>
            <th style="text-align: center">Rate (0-10)</th>
        </tr>
<?php
        $totalRate = 0;
        for ($i = 0; $i < $rsRate->num_rows; $i++) { 
            echo '<tr>';
            $rc = $rsRate->fetch_array(MYSQLI_NUM);
            $totalRate += $rc[2];
            for ($j=0; $j < count($rc); $j++) {
                if($j == 0) echo '<td><a style="text-decoration:none" href="chat.php?name=' . $rc[$j] . '">' . $rc[$j] . '</a></td>';
                elseif($j == 2) echo '<td style="text-align: center">' . $rc[$j] . '</td>';
                else echo '<td>' . $rc[$j] . '</td>';
            }
            echo '</tr>';
        }
        if($rsRate->num_rows > 0) echo '<tr><td style="text-align: right; font-weight: bold;" colspan="2">Average: </td><td style="text-align: center">' . sprintf("%.2f", $totalRate/($rsRate->num_rows)) . '</td></tr>';
        else echo '<tr><td colspan="3" style="text-align: center;">No comment yet</td></tr>';
?>
    </table>
<?php
    } else header('Location:product.php');
    html_footer();
?>