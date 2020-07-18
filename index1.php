<?php
$json = file_get_contents("php://input");
$obj = json_decode($json);

$timestamp = $obj->timestamp;
$mac = $obj->mac;
$rssi = $obj->rssi;
$bcon = "A";


$servername= 'localhost';
$username= 'root';
$password= '';
$db= 'school';

$conn = mysqli_connect($servername, $username, $password, $db);

//Insertion the data sent from the gateway


$sql = "INSERT INTO test(timestamp, mac, rssi, bcon)
    VALUES('$timestamp', '$mac','$rssi', '$bcon')";
if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
$sql = "COUNT * FROM test;";
if(mysqli_query($conn, $sql)==0){

//selecting the details gateway which sends highest signal strength for a purticular beacon
$sql1= "SELECT timestamp,mac,rssi,bcon FROM test WHERE rssi=(SELECT MAX(rssi) FROM test); ";
$result = mysqli_query($conn, $sql1);
$a= mysqli_num_rows($result);
if (mysqli_num_rows($result) > 0) { 
  while($row = mysqli_fetch_assoc($result)) {  
    $corrent_gateway = $row['mac'];
    $corrent_rssi = $row['rssi'];
    $new_timestamp = $row['timestamp'];
    $beacon_name = $row['bcon'];
    
    echo "<br>";
  }
} else {
  echo "0 results";
}

//insert the details of the nearest gateway to table test1

$sql2 = "INSERT INTO test1( timestamp,mac,rssi,bcon )
    VALUES('$new_timestamp','$corrent_gateway','$corrent_rssi','$beacon_name')";
if (mysqli_query($conn, $sql2)) {
  echo "New record created successfully in test 2";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
}else if()

//finding the time spend in the range of each gateway.

$sql3 = "SELECT 
    g1.mac,
    g1.bcon,
    g1.timestamp,
    g2.timestamp AS time
FROM
    test1 g1
        INNER JOIN
    test1 g2 ON g2.id = g1.id + 1
WHERE
    g1.bcon = 'A';";

$result1 = mysqli_query($conn, $sql3);
$b= mysqli_num_rows($result1);
if (mysqli_num_rows($result1) > 0) {  
  while($row1 = mysqli_fetch_assoc($result1)) { 
    $e = $row1['mac'];
    $f = $row1['bcon'];
    $g = $row1['timestamp'];
    $h = $row1['time'];    
    echo "<br>";
  }

echo $e;
//inserting the time duration of each gateway in to table test2

$sql4 = "INSERT INTO test2( mac,time_start,time_end,bcon) VALUES ('$e','$g','$h','$f');";
if (mysqli_query($conn, $sql4)) {
  echo "New record created successfully in test 2";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
} else {
  echo "0 results";
}

mysqli_close($conn);
/*$response=array();
$response['data1'] = $obj->timestamp;
$response['data2'] = $obj->mac;
$response['data3'] = $obj->rssi;
$json_response = json_encode($response);
echo $json_response;
header("Content-type: application/json");*/

?>

<!--admin dashboard-->



<!DOCTYPE html>
<html>
<head>
<title>Table with database</title>
<style>
table {
border-collapse: collapse;
width: 100%;
color: #588c7e;
font-family: monospace;
font-size: 25px;
text-align: left;
}
th {
background-color: #588c7e;
color: white;
}
tr:nth-child(even) {background-color: #f2f2f2}
</style>
</head>
<body>
<h1>user is corrently at : <?php echo $corrent_gateway;?></h1>
<table>
<tr>
<th>Id</th>
<th>MAC ADDRESS</th>
<th>START TIME</th>
<th>END TIME</th>
</tr>
<?php
$conn = mysqli_connect("localhost", "root", "", "school");
// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
$sql5 = "SELECT id, mac, time_start,time_end FROM test2";
$result1 = mysqli_query($conn, $sql5);
$x= mysqli_num_rows($result1);
if (mysqli_num_rows($result1) > 0) {
  // output data of each row
  while($row5 = mysqli_fetch_assoc($result1)) {
echo "<tr><td>" . $row5["id"]. "</td><td>" . $row5["mac"] . "</td><td>" . $row5["time_start"]. "</td><td>" . $row5["time_end"] ."</td></tr>";
}
echo "</table>";
} else { echo "0 results"; }
$conn->close();
?>
</table>

</body>
</html>



























