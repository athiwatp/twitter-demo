<?
require 'nanite.php';

function createPDO() {
	return new PDO('mysql:host=localhost;dbname=map', 'root', 'password');
}

get('/', function() {
	echo "[]";
});

get('/history', function() {
	echo "[]";
});

get('/test', function() {
	try {
		$con = createPDO();
		$stm = $con->prepare('select * from documents');
		$stm->execute();
		echo json_encode($stm->fetchAll(PDO::FETCH_ASSOC));
		// while($row = $stm->fetch()) print_r($row);
	}
	catch(Exception $err) {
		echo '[]';
		// echo 'ERROR: ' . $err->getMessage();
	}
});

--
