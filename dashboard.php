<?php

  require('register.php');
  if (!isset($_SESSION['loggedin']) && $_SESSION['loggedin'] != true){
    header("Location: login.php");
    exit();
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <title>Dashboard</title>
</head>
<body>



<div class="container">
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <div class="alert alert-success">
            You are now logged in!
        </div>
        <h3>Welcome <?= ucwords($_SESSION['name']); ?></h3>
        <a href="logout.php"> Logout </a>
    </div>
  </div>
</div>





    <!-- <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-success">
                    You are now logged in!
                </div>
                <h3>Welcome <?= ucwords($_SESSION['name']); ?></h3>
                <a href="logout.php"> Logout </a>
            </div>
        </div>
    </div> -->
</body>
</html>