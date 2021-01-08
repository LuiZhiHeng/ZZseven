<?php //CSV.php
    session_start();
    require_once('db_connect.php');
    $conn = db_connect();
    if(!isset($_SESSION['user'])) header("Location:login.php");
    $un = $_SESSION['user'];
    require_once('output_f.php');
    html_header('CSV report');
    menu2();
    show_login_user($_SESSION['user']);
    $query = "SELECT inventory.name, inventory.price, (inventory.quantityTotal - inventory.quantityLeft), inventory.quantityTotal, (inventory.price * (inventory.quantityTotal - inventory.quantityLeft)) FROM inventory JOIN inventorytype ON inventorytype.id = inventory.type WHERE idSeller = (SELECT id FROM user WHERE name = '$un' AND type = 1)";
    $result = $conn->query($query);
    if (!$result) die("Failed to get report");
    $totalqty = 0;
    $totalprice = 0;
    echo "<table id='vp' border='1' cellspacing='0'>
        <p style='text-align: center'><b>Seller: </b>" . $un .  "</p>
        <tr>
            <th>Name</th>
            <th>Single Price</th>
            <th>Quantity Sold</th>
            <th>Total Price</th>
        </tr>";
    for ($i=0; $i < $result->num_rows; $i++){ 
        $row = $result->fetch_array(MYSQLI_NUM);
        echo "<tr>";
        for ($j=0; $j < 5; $j++){
            if ($j == 2) {
                echo '<td>' . htmlspecialchars($row[$j]) . ' / ';
                $totalqty += ($row[$j]);
            } elseif ($j == 3) echo htmlspecialchars($row[$j]) . '</td>';
            elseif ($j == 4) echo '<td>' . htmlspecialchars($row[$j]) . '</td>';
            else echo '<td>' . htmlspecialchars($row[$j]) . '</td>';
            if($j == 4) $totalprice += $row[$j];
        }
        echo "</tr>";
    }
    echo '<tr>
        <td colspan="2" style="text-align: right"><b>TOTAL :</b></td>
        <td>' . $totalqty . '</td>
        <td>' . $totalprice . '</td>
    </tr>';
    echo '<tr><td colspan="4"><button class="btn" style="width:100%"onclick="window.print()" type="button">Download</button></td></tr></table></div>';
    html_footer();
?>
