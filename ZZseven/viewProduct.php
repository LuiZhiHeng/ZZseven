<?php //viewProduct.php
    require_once('db_connect.php');
    $conn = db_connect();
    session_start();
    if(!isset($_SESSION['user'])) header("Location:login.php");
    $un = $_SESSION['user'];
    if (isset($_POST['add'])){
        $un = $_SESSION['user'];
        $query1 = "SELECT id FROM user WHERE name = '$un' AND type = 1";
        $result1 = $conn->query($query1);
        $row = $result1->fetch_array(MYSQLI_NUM);
        $row[0];
        if (!$result1) die("Error find seller id");
        $name = addslashes($_POST['name']);
        $type = $_POST['type'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $querya = "INSERT INTO inventory VALUES(NULL, '$row[0]', '$name', '$type', '$price', '$quantity', '$quantity')";
        $resulta = $conn->query($querya);
        if(!$resulta) echo "Add failed<br><br>";
        header('Location: viewProduct.php');
    }

    if (isset($_POST['delete']) && isset($_POST['idInv'])){
        $idInv = $_POST['idInv'];
        $result2 = $conn->query("DELETE FROM inventory WHERE id = '$idInv'");
        if (!$result2) echo "Delete failed<br><br>";
        else {
            $result3 = $conn->query("DELETE FROM cart WHERE idInventory = '$idInv'");
            if (!$result3) echo "Delete failed...<br><br>";
        }
    }

    if (isset($_POST['edit']) && isset($_POST['idInv'])){
        $idInv = $_POST['idInv'];
        $result2 = $conn->query("SELECT * FROM inventory WHERE id = '$idInv'");
        if (!$result2) echo "Edit failed<br><br>";
    }
    
    $rsc = $conn->query("SELECT type FROM user WHERE name = '$un'");
    $rowtem = $rsc->fetch_array(MYSQLI_NUM);
    if ($rowtem[0] == 1) $querys = "SELECT inventory.id, inventory.name, inventorytype.name, inventory.price, inventory.quantityLeft, inventory.quantityTotal FROM inventory JOIN inventorytype ON inventorytype.id = inventory.type WHERE idSeller = (SELECT id FROM user WHERE name = '$un')";
    elseif ($rowtem[0] == 2) $querys = "SELECT inventory.id, inventory.name, inventorytype.name, inventory.price, inventory.quantityLeft, inventory.quantityTotal FROM inventory JOIN inventorytype ON inventorytype.id = inventory.type";
    $results = $conn->query($querys);
    if(!$results) die("Error when get stock");
    require_once('output_f.php');
    html_header('Product List');
    if ($rowtem[0] == 1) menu2();
    elseif ($rowtem[0] == 2) menu3();
    show_login_user($_SESSION['user']);
    echo "<table id='vp' border='1' cellspacing='0'>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Price</th>
            <th>Quantity</th>";
    if ($rowtem[0] == 1){
            echo"<th>Edit</th>
            <th>Delete</th>";
    }
        echo "</tr>";
    for ($i = 0; $i < $results->num_rows; $i++) {
        $row = $results->fetch_array(MYSQLI_NUM);
        echo "<tr>";
        for ($j = 0; $j < 6; $j++) {
            if ($j == 1) echo "<td><a style='text-decoration: none' href='productDetail.php?productName=" . $row[$j] . "'>" . htmlspecialchars($row[$j]) . "</a></td>";
            elseif ($j == 4) echo "<td style='text-align: center'>" . htmlspecialchars($row[$j]) . " / ";
            elseif ($j == 5) echo htmlspecialchars($row[$j]) . "</td>";
            else echo "<td style='text-align: center'>" . htmlspecialchars($row[$j]) . "</td>";
        }
        if ($rowtem[0] == 1){
        echo "<td><form id='nof' action='editProduct.php' method='post'>
            <input type='hidden' name='edit' value='yes'>
            <input type='hidden' name='idInv' value='$row[0]'>
            <input type='submit' name='edit' value='EDIT'>
            </form></td>";
        echo "<td><form id='nof' action='viewProduct.php' method='post'>
            <input type='hidden' name='delete' value='yes'>
            <input type='hidden' name='idInv' value='$row[0]'>
            <input type='submit' name='delete' value='DELETE'>
            </form></td>";
        }
        echo "</tr>";
    }
?>
    <tr>
        <td colspan="7">
            <input style="display: block;margin: auto;padding: 5px 10px; width: 50%;" type="button" onclick="location.href='addProduct.php'" value="ADD MORE PRODUCT">
        </td>
    </tr>
    </table>
<?php
    html_footer();
?>