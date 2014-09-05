<?
try {
	$con = new PDO('mysql:host=localhost;dbname=openbi', 'root', 'password');
/*
	$stm = $conn->prepare('select * from users where id = :id');
	$stm->execute(array('id' => $id));
*/

	$stm = $con->prepare('select * from documents');
	$stm->execute();

	while($row = $stm->fetch()) {
		print_r($row);
		echo '<br/>';
	}
} catch(Exception $err) {
	echo 'ERROR: ' . $err->getMessage();
}
