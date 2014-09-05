<?
require 'nanite.php';

function createPDO() {
	return new PDO('mysql:host=localhost;dbname=map', 'root', 'password');
}

get('/', function() {
	echo "[]";
});

get('/search/(.*)', function($q) {

	// TODO FIX ERROR WHEN SEARCH WITH SPACE

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

	$encode = "TWRFdHBmTVBNTHRubDE0Nmg1SkVTcjJrSzpKNVk2aUlScG5TMHh6YU54SXpBT1" .
				"VwTUVNRFFPZ1BaUDUwUlBYaGhZYnBCVmQ5eGZsbQ==";
	$data = "grant_type=client_credentials";

	$result = file_get_contents('https://api.twitter.com/oauth2/token', false,
		stream_context_create(
			array(
			'http' => array(
				'method' => 'POST',
				'header' =>
					"User-Agent: My Twitter App v1.0\n" .
					"Authorization: Basic " . $encode . "\n" .
					"Content-Type: " .
						"application/x-www-form-urlencoded;charset=UTF-8\n" .
					"Content-Length: " . strlen($data) . "\n",
				'content' => $data,
				),
			)
		)
	);

	$json = json_decode($result);
	$token = $json->{"access_token"};
	// echo $token;

 	$result = file_get_contents(
		"https://api.twitter.com/1.1/search/tweets.json?q=" . $q, false,
		stream_context_create(
			array(
				"http" => array(
					"method" => "GET",
					"header" => "Authorization: Bearer " . $token . "\r\n"
				)
			)
		)
	);

	echo ($result);

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
