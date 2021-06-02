<?php

  require('register.php');
  //$_SESSION['login_attempts']=0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Registration & Login with Email OTP verification</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>

<div class="card text-center" style="padding:20px;">
  <h3>Registration & Login with Email OTP verification</h3>
</div><br>

<div class="container">
  <div class="row">
    <div class="col-md-3"></div>

      <div class="col-md-6">
      <?php if ( count($errors) > 0 ): ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $error) : ?>
          <li><?php echo $error; ?></li>
          <?php endforeach; ?>
      </div>
      <?php endif; ?>        
        <form  method="post" action="index.php" id="submitForm">
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" name="name" placeholder="Enter Name... " required>
          </div>
          <div class="form-group">  
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" placeholder="Enter Email... " required>
          </div>
          <div class="form-group">  
            <label for="mobile">Mobile Number:</label>
            <input type="text" class="form-control" name="mobile" placeholder="Enter Mobile number... " required>
          </div>
          <div class="form-group">  
            <label for="national">National ID:</label>
            <input type="text" class="form-control" name="national_id" placeholder="Enter National ID... " required>
          </div>
          <div class="form-group">  
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" placeholder="password... " required>
          </div>
          <div class="form-group">  
            <label for="confirm">Confirm Password:</label>
            <input type="password" class="form-control" name="confirm" placeholder="confirm password... " required>
          </div>
          <div class="form-group">
            <p>Already have account ?<a href="login.php"> Login</a></p>
            <button type="submit" class="btn btn-primary" name="reg">Sign Up</button>
          </div>
        </form>
      </div>
  </div>
</div>

<!-- <script type="text/javascript">
  $(document).ready(function(){
    $("#submitForm").on("submit", function(e){
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url  : "register.php",
        type : "POST",
        cache:false,
        data : formData,
        success:function(result){
          if (result) {
            alert("Registration sucessfully Please verify");
            window.location ='verify.php';          
          }else{
            alert("Registration failed try again!");
          }          
        }
      });  
    });    
  });
</script> -->
</body>
</html>

