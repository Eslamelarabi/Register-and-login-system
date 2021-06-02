<?php

  require('register.php');
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    header("Location: dashboard.php");
    exit();
  }

  if (isset($_SESSION['locked_time'])){
    $diff = time() - $_SESSION['locked_time'];
    if ($diff > 120){
        unset($_SESSION['locked_time']);
        $_SESSION['login_attempts']=0;
        $_SESSION['fail_login_ip'] +=1;
    }
  }


  if (isset($_SESSION['locked_time_pass'])){
    $diff = time() - $_SESSION['locked_time_pass'];
    if ($diff > 120){
        unset($_SESSION['locked_time_pass']);
        $_SESSION['login_attempts_pass']=0;
        $_SESSION['fail_login'] +=1;
    }
  }


  if (isset($_SESSION['fail_login_ip']) && $_SESSION['fail_login_ip'] >= 3){
      $deny = array("111.111.111", "222.222.222", "333.333.333");
      if (in_array ($_SERVER['REMOTE_ADDR'], $deny)) {
        unset( $_SESSION['fail_login_ip']);
        header("location:index.php");
        exit();
    }
  } 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>

<div class="card text-center" style="padding:20px;">
  <h3>Login</h3>
</div><br>

<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
      <div class="col-md-6">
        <?php if ( count($errors_login) > 0 ): ?>
        <div class="alert alert-danger">
          <?php foreach ($errors_login as $login) : ?>
            <li><?php echo $login; ?></li>
            <?php if ($login == "Please Verify Your Email Before Logging in!"){ header( "refresh:5;url=verify.php" );}  ?>
            <?php if ($login == "Your Account Has Been Blocked For Many Wrong Passwords!"){ header( "refresh:5;url=index.php" );}  ?>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>          
        <form  method="post" action="login.php" id="submitForm">
          <div class="form-group">  
            <label for="nmail">Email:</label>
            <input type="text" class="form-control" name="email" placeholder="Enter Email... " required>
          </div>
          <div class="form-group">  
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" placeholder="Password... " required>
          </div>
          <div class="form-group">
            <p>Need a New account ?<a href="index.php"> Sign Up</a></p>
            <?php if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] > 2){
                if (!isset($_SESSION['locked_time'])){
                  $_SESSION['locked_time'] = time();
                }else{
                  $_SESSION['locked_time'] = time() - $_SESSION['locked_time'];
                }

                echo "<p>Too Many login Attempts Please Wait For 2 Mins </p>";
            }elseif(isset($_SESSION['login_attempts_pass']) && $_SESSION['login_attempts_pass'] > 2){
                if (!isset($_SESSION['locked_time_pass'])){
                  $_SESSION['locked_time_pass'] = time();
                }else{
                  $_SESSION['locked_time_pass'] = time() - $_SESSION['locked_time_pass'];
                }
                echo "<p>Too Many Wrong Passwords Attempts Please Wait For 2 Mins </p>";
            }else { ?>
              <button type="submit" class="btn btn-primary" name="login">Login</button>
            <?php } ?>
          </div>
        </form>
      </div>
  </div>
</div>

<script type="text/javascript">

</script>
</body>
</html>