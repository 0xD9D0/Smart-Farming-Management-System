<head>
<?php 
session_start();

if(!isset($_SESSION['user'])){
            header('location: login.php');// redirect to dashboard page
}
include "header.php";

?>
<title> Smart Farming System </title>
	<meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content = "width = device-width, initial-scale = 1, user-scalable = no" />
</head>


<body>
<center>
<img src="http://adaptiveanalytics.tk/mobile/Adaptive%20Analytics.png" style="200px; height:100px;"/>
<h2 class="text-center">
Smart Farming System</h2>
<div class="container">  
<a href="dashboard/index.html#source=farmdashboard.json"  class="btn btn-primary">
	Farm Dashboard </a>
	<br><br>
<a href="dashboard/index.html#source=alertsdashboard.json"  class="btn btn-primary">
	Alerts Dashboard </a>
	<br><br>
	<a href="dashboard/index.html#source=statdashboard.json"  class="btn btn-primary">
	Recommended Actions & Trends</a>
	<br><br>
<a href="FarmEntity"  class="btn btn-primary">
	Farm Entities </a>
	<br><br>
	<a href="logout.php"  class="btn btn-danger">
	Log-out :( </a>
        </div>
</center>
</body>