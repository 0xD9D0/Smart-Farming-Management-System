<?php
/*
Allows the user to both create new records and edit existing records
*/

// connect to the database
include("connect-db.php");

// creates the new/edit record form
// since this form is used multiple times in this file, I have made it a function that is easily reusable
function renderForm($entity = '', $amou ='', $error = '', $id = '')
{ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>
<?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?>
</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content = "width = device-width, initial-scale = 1, user-scalable = no" />
		    <link rel="stylesheet" href="../css/bootstrap.min.css"/>
    <link rel="stylesheet" href="../css/backend.css"/>
</head>
<body bgcolor="#101214">
<center>
<font color="8b8b8b">
<h1><?php if ($id != '') { echo "Edit Record"; } else { echo "New Record"; } ?></h1>
<?php if ($error != '') {
echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error
. "</div>";
} ?>

<form action="" method="post">
<div>
<?php if ($id != '') { ?>
<input type="hidden" name="id" value="<?php echo $id; ?>" />
<p>ID: <?php echo $id; ?></p>
<?php } ?>

<strong>Entity type: *</strong> <input type="text" name="Entitytype"
value="<?php echo $entity; ?>"/><br/>
<strong>Amount: *</strong> <input type="text" name="Amount"
value="<?php echo $amou; ?>"/>
<p>* required</p>
<input type="submit" name="submit" value="Submit" />
<br>
<a href="http://adaptiveanalytics.tk/FarmEntity/index.php" class="btn btn-link">Go back to entity menu</a>
</div>
</form>
</body>
</html>

<?php }



/*

EDIT RECORD

*/
// if the 'id' variable is set in the URL, we know that we need to edit a record
if (isset($_GET['id']))
{
// if the form's submit button is clicked, we need to process the form
if (isset($_POST['submit']))
{
// make sure the 'id' in the URL is valid
if (is_numeric($_POST['id']))
{
// get variables from the URL/form
$id = $_POST['id'];
$Entitytype = htmlentities($_POST['Entitytype'], ENT_QUOTES);
$Amount = htmlentities($_POST['Amount'], ENT_QUOTES);

// check that Entitytype and Amount are both not empty
if ($Entitytype == '' || $Amount == '')
{
// if they are empty, show an error message and display the form
$error = 'ERROR: Please fill in all required fields!';
renderForm($Entitytype, $Amount, $error, $id);
}
else
{
// if everything is fine, update the record in the database
if ($stmt = $mysqli->prepare("UPDATE farmentity SET Entitytype = ?, Amount = ?
WHERE id=?"))
{
$stmt->bind_param("ssi", $Entitytype, $Amount, $id);
$stmt->execute();
$stmt->close();
}
// show an error message if the query has an error
else
{
echo "ERROR: could not prepare SQL statement.";
}

// redirect the user once the form is updated
header("Location: index.php");
}
}
// if the 'id' variable is not valid, show an error message
else
{
echo "Error!";
}
}
// if the form hasn't been submitted yet, get the info from the database and show the form
else
{
// make sure the 'id' value is valid
if (is_numeric($_GET['id']) && $_GET['id'] > 0)
{
// get 'id' from URL
$id = $_GET['id'];

// get the recod from the database
if($stmt = $mysqli->prepare("SELECT * FROM farmentity WHERE id=?"))
{
$stmt->bind_param("i", $id);
$stmt->execute();

$stmt->bind_result($id, $Entitytype, $Amount);
$stmt->fetch();

// show the form
renderForm($Entitytype, $Amount, NULL, $id);

$stmt->close();
}
// show an error if the query has an error
else
{
echo "Error: could not prepare SQL statement";
}
}
// if the 'id' value is not valid, redirect the user back to the index.php page
else
{
header("Location: index.php");
}
}
}



/*

NEW RECORD

*/
// if the 'id' variable is not set in the URL, we must be creating a new record
else
{
// if the form's submit button is clicked, we need to process the form
if (isset($_POST['submit']))
{
// get the form data
$Entitytype = htmlentities($_POST['Entitytype'], ENT_QUOTES);
$Amount = htmlentities($_POST['Amount'], ENT_QUOTES);

// check that Entitytype and Amount are both not empty
if ($Entitytype == '' || $Amount == '')
{
// if they are empty, show an error message and display the form
$error = 'ERROR: Please fill in all required fields!';
renderForm($Entitytype, $Amount, $error);
}
else
{
// insert the new record into the database
if ($stmt = $mysqli->prepare("INSERT farmentity (Entitytype, Amount) VALUES (?, ?)"))
{
$stmt->bind_param("ss", $Entitytype, $Amount);
$stmt->execute();
$stmt->close();
}
// show an error if the query has an error
else
{
echo "ERROR: Could not prepare SQL statement.";
}

// redirec the user
header("Location: index.php");
}

}
// if the form hasn't been submitted yet, show the form
else
{
renderForm();
}
}

// close the mysqli connection
$mysqli->close();
?>