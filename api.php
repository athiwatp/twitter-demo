<?
require 'nanite.php';

function createPDO() {
	return new PDO('mysql:host=localhost;dbname=map', 'root', 'password');
}

get('/', function() {
	echo "[]";
});

get('/geo/(.*)', function($name) {
	$uri = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
	$result = file_get_contents($uri . $name);
	$json = json_decode($result);
	$location = json_encode($json->results[0]->geometry->location);
	$point = json_decode($location);
	echo $point->lat . ',' . $point->lng;
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
				)
			)
		)
	);

	$json = json_decode($result);
	$token = $json->{"access_token"};
	// echo $token;

	// $uri = "https://api.twitter.com/1.1/search/tweets.json?q=" . $q;
	$uri = "https://api.twitter.com/1.1/search/tweets.json" .
		"?q=&result_type=recent&" .
		"geocode=13.00,100.00,50km&count=10";

 	$result = file_get_contents($uri, false,
		stream_context_create(
			array(
				"http" => array(
					"method" => "GET",
					"header" => "Authorization: Bearer " . $token . "\n"
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
