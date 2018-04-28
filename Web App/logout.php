<?

session_start();
session_unset();
session_destroy();

header("Location: index.php");
        exit();



?>

<center>
<a href="login.php"> Click here if you didn't get redirected automatically</a></center>