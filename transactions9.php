<?php 
// Código modificado para protección de SQL Injection utilizando "Prepared Statements"
// Realizado por Jose Manuel Espinoza Reyes y Roberto Vargas Villalobos
// Tarea de Curso MSEG11 - SDA
// Fecha: 16-04-2023
// Código fuente original: https://github.com/bojanisc/AcmeSQL

include 'config.php';

if (!isset($_GET['id'])) {
	echo "Error, the account number must be set!";
	exit(1);
}

?>

<!DOCTYPE html>
<html>
<head>
<title>List of your transactions</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="index">
<b id="welcome">Welcome: <i>
</i></b>
<br><br>
Here is the list of transactions for your account <?php echo(htmlentities($_GET['id']));?>:<br><br>
<ul>
<?php 

// Connect to MySQL database
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$id = strtolower($_GET['id']);

// Aplicación de plantilla de "Prepared Statement" para utilizar la consulta

$sql = $conn->prepare("SELECT * FROM transactions WHERE source = ?");
$source = intval( $id );
$sql->bind_param("i", $id);
$sql->execute();
$result = $sql->get_result();

if ($conn->error != "") {
	echo "<br>";
}

if ($result->num_rows > 0) {

// output data of each row
	while($row = $result->fetch_assoc()) {

		echo "<li>Source: " . $row['source'] . ", Destination: " . $row['destination'] . ", Amount: " . $row['amount'] . ", Date: " . $row['datetime'] . "<br>";
	}
} else {
	echo "No transactions found.<br>";
}

$sql->close();
$conn->close();
		
?>
</ul>
<b id="logout"><a href="index.php">Go back</a></b>
</div>
</body>
</html>
