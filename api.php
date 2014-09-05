<?
require 'nanite.php';

function createPDO() {
	return new PDO('mysql:host=localhost;dbname=map', 'root', 'password');
}

get('/', function() {
	echo "[]";
});

get('/search/(.*)', function($q) {
	/*
	try {
		$con = createPDO();
		$stm = $con->prepare("insert into history(query) values(:q)");
		$stm->execute(array("q" => $q));
		echo "[]";
	}
	catch (Exception $e) {
		echo "[]";
	}
	*/
	
});

get('/history', function() {
	try {
		$con = createPDO();
		$stm = $con->prepare("select * from history order by timestamp desc");
		$stm->execute();
		echo json_encode($stm->fetchAll(PDO::FETCH_ASSOC));
	}
	catch (Exception $e) {
		echo "[]";
	}
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
