<!doctype html>
<html>
<head>
	<title>Twitter Demo</title>
	<link rel="stylesheet" href="/css/bootstrap.min.css" />
</head>

<body>

<div class="container">
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

<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.min.js"></script>

<script>
	function search() {
		var query = $("[name=search]").val();
		$.get('/api.php/search/' + query)
		.success(function(result) {
			console.log(result);
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
