<?php 
session_start();
include "header.php";
if(isset($_SESSION['user'])){
            header('location: index.php');// redirect to dashboard page
}

// check if user coming from HTTP post request
if($_SERVER['REQUEST_METHOD']== 'POST'){
    
    $user= $_POST['user'];
    $pass= $_POST['pass'];
     
  
        //  if count > 0  => the user is in the database 
    if($user=='admin' && $pass=='admin'){
               $_SESSION['user']=$user; // Register session name 

         header('location: index.php');
        exit();
    }else{
echo '<div class="alert alert-danger">Wrong Credentials :/ </div>';        
    }


}

?>
<head>
	<meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="viewport" content = "width = device-width, initial-scale = 1, user-scalable = no" />
</head>
<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" required="required"/>
<input class="form-control"  type="password" name="pass" placeholder="Password" autocomplete="new-password"required="required"/>
    <input  class="btn btn-primary btn-block"type="submit" value="Login"/>



</form>



