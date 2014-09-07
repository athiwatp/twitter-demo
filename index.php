<!DOCTYPE html>
<html>
<head>
	<title>Twitter Demo</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" href="/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/css/style.css" />
</head>
<body>

<div class="container" id="main">
	<form role="form" action="javascript:onSearch()">
		<div class="row">
			<div class="col-xs-8">
				<input type="text" class="form-control" name="search" />
			</div>
			<div class="col-xs-4">
				<input type="submit" class="btn btn-primary" value="Search" />
				<input type="button" class="btn btn-default" value="History"
					onclick="onHistory()" />
			</div>
		</div>
	</form>
</div>

<div id="map-canvas"></div>

<div class="modal fade" id="history">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title">History</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default"
					data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js"></script>

<script>

var map = null;
var markers = new Array();
var searchHistory = new Array();
google.maps.event.addDomListener(window, 'load', initialize);

function initialize() {
	var options = {
		center: new google.maps.LatLng(0, 0),
		zoom: 2
	};
	map = new google.maps.Map(document.getElementById("map-canvas"),
		options);
}

function onSearch() {
	var query = $("[name=search]").val();
	// console.log(query);
	search(query);
}

function search(query) {
	$("body").toggleClass("wait");
	$("#history").modal("hide");
	$("[name=search]").val(query);
	addHistory(query);

	$.get('/api.php/search/' + query)
	.success(function(result) {
		$("body").toggleClass("wait");
		// console.log(result);

		// remove all markers
		for (var i = markers.length; i > 0; i--) {
			markers[i - 1].setMap(null);
			markers.pop();
		}

		try {
			var json = JSON.parse(result);
			// console.log(json);

			for (var i = 0; i < json.statuses.length; i++) {
				if (json.statuses[i].geo != null) {
					var lat = json.statuses[i].geo.coordinates[0];
					var lng = json.statuses[i].geo.coordinates[1];
					// console.log(lat + ' ' + lng);
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

function addHistory(query) {
	var found = -1;
	for (var i = 0; i < searchHistory.length; i++) {
		if (query.toUpperCase() === searchHistory[i].toUpperCase()) {
			found = i;
			break;
		}
	}
	if (found === -1) {
		searchHistory.push(query);
	}
}

function onHistory() {
	$("#history .modal-body").html("");
	var template = "<p><a href='javascript:search(\"_\")'>_</a></p>";
	for (var i = 0; i < searchHistory.length; i++) {
		var html = template.replace(/_/g, searchHistory[i]);
		$("#history .modal-body").append(html);
	}
	$("#history").modal("show");
}

function addMarker(data) {
	var pos = new google.maps.LatLng(data.lat, data.lng);
	var marker = new google.maps.Marker({
		position: pos,
		map: map,
		icon: data.icon,
		title: data.text
	});
	markers.push(marker);
}

</script>

</body>
</html>
