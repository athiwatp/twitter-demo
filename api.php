<?
session_start();
require 'nanite.php';

$db_server		= "mysql:host=localhost;dbname=map";
$db_user		= "map";
$db_password	= "password";

get('/search/(.*)', function($q) {
	global $db_server;
	global $db_user;
	global $db_password;

	if (isset($_SESSION["history"])) {
		$history = $_SESSION["history"];
		if (!in_array($q, $history)) {
			$history[] = $q;
			$_SESSION["history"] = $history;
		}
	}
	else {
		$history = array($q);
		$_SESSION["history"] = $history;
	}

	try {
		$con = new PDO($db_server, $db_user, $db_password);
		$stm = $con->prepare("select * from history where query=:q " .
			"and utc_timestamp() - timestamp < 60 * 60 ");
		$stm->execute(array("q" => $q));
		$res = $stm->fetchAll(PDO::FETCH_ASSOC);
		if (count($res) == 0) {
			$tweets = getTweet($q);
			$stm = $con->prepare(
				"insert into history(query, result, timestamp) " .
				"values(:q, :tweet, utc_timestamp()) " .
				"on duplicate key " .
				"update result=:tweet, timestamp=utc_timestamp()"
				);
			$stm->execute(array("q" => $q, "tweet" => $tweets));
			echo $tweets;
		}
		else {
			echo $res[0]["result"];
		}
	}
	catch (Exception $e) {
		echo "[]";
	}
});

function getTweet($name) {
	// 1. get lat,lng from google geocode
	$uri = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
	$result = file_get_contents($uri . urlencode($name));
	$json = json_decode($result);
	// $location = json_encode($json->results[0]->geometry->location);
	// $point = json_decode($location);
	// echo $point->lat . ',' . $point->lng;
	$lat = $json->results[0]->geometry->location->lat;
	$lng = $json->results[0]->geometry->location->lng;
	// echo $lat . ' ' . $lng;

	// 2. get twitter token by base64 application key + secret
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

	// 3. get tweets
	// $uri = "https://api.twitter.com/1.1/search/tweets.json?q=" . $q;
	$uri = "https://api.twitter.com/1.1/search/tweets.json" .
		"?q=&result_type=recent&" .
		"geocode=$lat,$lng,50km&count=100";

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

	return $result;
}


get('/', function() {
	echo "[]";
});

get('/geo/(.*)', function($name) {
	$uri = 'https://maps.googleapis.com/maps/api/geocode/json?address=';
	$result = file_get_contents($uri . urlencode($name));
	$json = json_decode($result);
	// $location = json_encode($json->results[0]->geometry->location);
	// $point = json_decode($location);
	// echo $point->lat . ',' . $point->lng;
	echo
		$json->results[0]->geometry->location->lat . ' ' .
		$json->results[0]->geometry->location->lng;
});

get('/history', function() {
	if (isset($_SESSION["history"])) {
		$history = $_SESSION["history"];
		echo json_encode($history);
	}
	else {
		echo '[]';
	}
});

get('/clear', function() {
	unset($_SESSION["history"]);
	echo '[]';
});

get('/test', function() {
	global $db_server;
	global $db_user;
	global $db_password;
	try {
		$con = new PDO($db_server, $db_user, $db_password);
		$stm = $con->prepare("select * from history");
		$stm->execute();
		echo json_encode($stm->fetchAll(PDO::FETCH_ASSOC));
		// while($row = $stm->fetch()) print_r($row);
	}
	catch(Exception $err) {
		echo '[]';
		echo 'ERROR: ' . $err->getMessage();
	}
});


























// note
