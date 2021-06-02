<?php
  require('register.php');

  if (isset($_SESSION['locked_time_otp'])){
    $diff_otp = time() - $_SESSION['locked_time_otp'];
    //print_r($_SESSION['resend_attempts']);
     //print_r($diff_otp);
    //die('hello');
    if ($diff_otp > 120){
      //die('ll');
       // $_SESSION['blocked_times'] += 1;
        unset($_SESSION['locked_time_otp']);
        $_SESSION['resend_attempts']=0;
        header("Location: verify.php");
    }
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Registration & Login with Email OTP verification using Jquery AJAX with PHP Mysql</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>

    <div class="card text-center" style="padding:20px;">
        <h3>Please Enter The Verification Code That Was Sent To Your Email</h3>
    </div><br>

    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">   

                <div class="alert alert-success alert-dismissible" style="display: none;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span class="success-message"></span>
                </div>

                <form id="otpForm">
                <div class="form-group">  
                    <label for="code">OTP:</label>
                    <input type="text" class="form-control" name="otp" placeholder="Enter Verification Code..." required="" id="otp">
                    <span class="otp-message" style="color: red;"></span>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success" id="verifyOtp">Verify OTP</button>
                </div>
                </form>
                <div class="form-group">
                      <?php if (isset($_SESSION['resend_attempts']) && $_SESSION['resend_attempts'] == 3 ){
                        if (!isset($_SESSION['locked_time_otp'])){
                          $_SESSION['locked_time_otp'] = time();
                        }else{
                          $_SESSION['locked_time_otp'] = time() - $_SESSION['locked_time_otp'];
                        }
                        echo "<p>Too Many Send OTP Attempts Please Wait For 2 Mins </p>";
                      }else { ?>
                          <a href="verify.php?resend=1" class="btn btn-primary">Resend OTP</a>
                    <?php } ?>
                </div>        
            </div>
        </div>
    </div>
    <script type="text/javascript">
  $(document).ready(function(){

  $("#verifyOtp").on("click",function(e){
      e.preventDefault();
      var otp = $("#otp").val();
      $.ajax({
        url  : "register.php",
        type : "POST",
        cache:false,
        data : {otp:otp},
        success:function(response){
          if (response == "yes") {
            window.location.href='login.php';
          }
          if (response =="no") {
            $(".otp-message").html("Please enter valid OTP");
          }        
        }
      });
    });
  });



</script>
</body>
</html>