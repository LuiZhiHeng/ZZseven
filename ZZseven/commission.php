<?php //commission.php
    session_start();
    if(!isset($_SESSION['user']) || $_SESSION['user'] != "admin") header('Location:login.php');
    require_once('db_connect.php');
    $conn = db_connect();
    require_once('output_f.php');
    html_header('Manage Commission');
    menu3();
    show_login_user($_SESSION['user']);
    if(isset($_GET['id']) && isset($_GET['value'])){
        $id = $_GET['id'];
        $value = $_GET['value'];
        $result = $conn->query("UPDATE inventorytype SET commission = '$value' WHERE id = '$id'");
    }

    echo '<table id="vp" border="1" cellspacing="0"><tr><th>Type</th><th>Commission</th></tr>';
    $result = $conn->query("SELECT * FROM inventorytype");
    for ($i = 0; $i < $result->num_rows; $i++){
        $row = $result->fetch_array(MYSQLI_NUM);
        echo '<tr>';
        for ($j = 1; $j < count($row); $j++){
            if ($j == 2) echo "<td><input min='0' type='number' name='value' size='5' value='" . $row[2] . "' onchange='update($row[0], this.value)'></td>";
            else echo '<td>' . $row[$j] . '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    html_footer();
?>
<script>    
function update(id, value){
    if (id!='' && value!='') {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if(this.readyState == 4 && this.status == 200) alert("Commission value for has changed to " + value);
        }
        xmlhttp.open('GET', 'commission.php?id=' + id + '&value=' + value, true);
        xmlhttp.send();
    }
}
</script>