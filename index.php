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
	</style>
	<script type="text/javascript"
		src="https://maps.googleapis.com/maps/api/js">
	</script>
	<script type="text/javascript">
		function initialize() {
			var mapOptions = {
				center: new google.maps.LatLng(0, 0),
				zoom: 2
			};
			var map = new google.maps.Map(document.getElementById("map-canvas"),
				mapOptions);
		}
		google.maps.event.addDomListener(window, 'load', initialize);
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
	function search() {
		var query = $("[name=search]").val();
		$.get('/api.php/search/' + query)
		.success(function(result) {
			var json = JSON.parse(result);
			console.log(json);
			for (var i = 0; i < json.statuses.length; i++) {
				if (json.statuses[i].geo != null) {
					var lat = json.statuses[i].geo.coordinates[0];
					var lng = json.statuses[i].geo.coordinates[1];
					console.log(lat + ' ' + lng);
				}
			}
		});
	}

	function history() {
		$.get("/api.php/history")
		.success(function(result) {
			console.log(result);
		});
	}
</script>

</body>
</html>
