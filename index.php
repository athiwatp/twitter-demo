<!DOCTYPE html>
<html>
<head>
	<title>Twitter Demo</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

	<link rel="stylesheet" href="/css/bootstrap.min.css" />
	<style type="text/css">
		html { height: 100% }
		body { height: 100%; margin: 0; padding: 0 }
		#map-canvas { height: 100% }

		body.wait, body.wait *{
			cursor: wait !important;
		}

	</style>
	<script type="text/javascript"
		src="https://maps.googleapis.com/maps/api/js">
	</script>
</head>
<body>

<div class="container" style="z-index: 10000; position: absolute; bottom:40px;">
	<form role="form" action="javascript:search()">
		<div class="row">
			<div class="col-xs-8">
				<input type="text" class="form-control" name="search" />
			</div>
			<div class="col-xs-4">
				<input type="submit" class="btn btn-primary" value="Search" />
			</div>
		</div>
	</form>
</div>

<div id="map-canvas"></div>

<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>

var map = null;

function search() {
	console.log('Searching ...');
	$("body").toggleClass("wait");
	
	var query = $("[name=search]").val();
	$.get('/api.php/search/' + query)
	.success(function(result) {
		// clear all marker here
		$("body").toggleClass("wait");
		console.log(result);

		try {
			var json = JSON.parse(result);
			console.log(json);
			for (var i = 0; i < json.statuses.length; i++) {
				if (json.statuses[i].geo != null) {
					var lat = json.statuses[i].geo.coordinates[0];
					var lng = json.statuses[i].geo.coordinates[1];
					console.log(lat + ' ' + lng);
					addMarker({lat: lat, lng: lng,
						icon: json.statuses[i].user.profile_image_url_https,
						text: json.statuses[i].text,
						user: json.statuses[i].user.screen_name
					});
					// console.log(json.statuses[i].user.screen_name);
					// console.log(json.statuses[i].text);
				}
			}
		}
		catch (e) {
			console.log(e);
		}
	});
}

function history() {
	$.get("/api.php/history")
	.success(function(result) {
		console.log(result);
	});
}

function addMarker(data) {
	var pos = new google.maps.LatLng(data.lat, data.lng);
	var marker = new google.maps.Marker({
		position: pos,
		map: map,
		icon: data.icon,
		title: data.text
	});
}

google.maps.event.addDomListener(window, 'load', initialize);
function initialize() {
	var options = {
		center: new google.maps.LatLng(0, 0),
		zoom: 2
	};
	map = new google.maps.Map(document.getElementById("map-canvas"),
		options);
}
</script>

</body>
</html>
