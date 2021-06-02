<?php

  

/// ALL THIS LOGIC WAS MOVED TO REGISTER.PHP FILE 
  session_start();
  if (isset($_POST['otp'])) {
    $con = new mysqli('localhost', 'root', '', 'loginSYS'); 	
   	$postOtp = $_POST['otp'];
   	$email  = $_SESSION['EMAIL'];
 	$query  = "SELECT * FROM users WHERE otp = '$postOtp' AND email = '$email'";
   	
   	$result = $con->query($query);
   if ($result->num_rows > 0) {
      
        $_SESSION['IS_LOGIN'] = $email; 
        echo "yes";         
   }else{
        echo "no";
       } 
                 
  }

?>