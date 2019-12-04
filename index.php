<?php
require_once('config.php');
$dbconn = connect();
?>

<!DOCTYPE html>
<html>
<head>
	<title>simCharts</title>
	<meta name="description" content="Quick and easy access FAA terminal procedure charts for flight simulation use.">
	<meta name="keywords" content="p3d, fsx, xplane, vatsim, ivao, pilotedge, atc, air, traffic, control">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" 
		integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=0.7, user-scalable=1.0, minimum-scale=0.5, maximum-scale=1.0">
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-133348363-1"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-133348363-1');
	</script>
</head>
<body>
	<?php
	if(isset($_GET['direct']))
		require('html/results.php');
	elseif (isset($_GET['help']))
		require('html/help.html');
	elseif (isset($_GET['search']) && !isset($_GET['direct']))
		require('html/results.html');
	else
		require('html/frontpage.html');
	?>
</body>
</html>