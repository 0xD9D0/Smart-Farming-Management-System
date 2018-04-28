<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>View Farm Entity Records</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content = "width = device-width, initial-scale = 1, user-scalable = no" />
	    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/backend.css"/>
</head>
<body>
<center>
<font color="black">
<h2>Entity Records</h1>
<div class="container">

    <div class="table-responsive">

<?php
// connect to the database
include('connect-db.php');

// get the records from the database
if ($result = $mysqli->query("SELECT * FROM farmentity ORDER BY id"))
{
// display records if there are records to display
if ($result->num_rows > 0)
{
// display records in a table
echo '<table class="main-table text-center table table-bordered" style="width:400px">';

// set table headers
echo '<tr><th align="center">ID</th><th align="center">Entity type</th><th align="center">Amount</th><th></th><th ></th></tr>';

while ($row = $result->fetch_object())
{
// set up a row for each record
echo "<tr>";
echo "<td>" . $row->id . "</td>";
echo "<td>" . $row->Entitytype . "</td>";
echo "<td>" . $row->Amount . "</td>";
echo "<td><a href='records.php?id=" . $row->id . "'>Edit</a></td>";
echo "<td><a href='delete.php?id=" . $row->id . "'>Delete</a></td>";
echo "</tr>";
}

echo "</table>";
}
// if there are no records in the database, display an alert message
else
{
echo "No results to display!";
}
}
// show an error if there is an issue with the database query
else
{
echo "Error: " . $mysqli->error;
}

// close database connection
$mysqli->close();

?>
</div>
</div>
<a href="records.php" class="btn btn-link">Add New Entity</a>
<br>
<a href="../index.php" class="btn btn-link">Go back to main menu</a>

</body>
</html>