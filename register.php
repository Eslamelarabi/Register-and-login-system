<?php

    $con = new mysqli('localhost', 'root', '', 'loginSYS');
    session_start();
    //$_SESSION['resend_attempts']
    if (!isset($_SESSION['login_attempts'])){
        $_SESSION['login_attempts']=0;
    }
    if (!isset($_SESSION['resend_attempts'])){
        $_SESSION['resend_attempts']=0;
    }
    if (!isset($_SESSION['fail_login'])){
        $_SESSION['fail_login']=0;
    }

    if (!isset($_SESSION['login_attempts_pass'])){
        $_SESSION['login_attempts_pass']=0;
    }
   
    $errors = array();
    $errors_login = array();
    if (isset($_POST['reg'])) {

        $name   = $con->real_escape_string($_POST['name']);
        $mobile = $con->real_escape_string($_POST['mobile']);
        $email  = $con->real_escape_string($_POST['email']);
        $national  = $con->real_escape_string($_POST['national_id']);
        $password  = $con->real_escape_string($_POST['password']);
        $confirm  = $con->real_escape_string($_POST['confirm']);
        $otp    = mt_rand(111111, 999999);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // validations

        $sql = $con->query("SELECT id FROM users WHERE email='$email'");
        if ($sql->num_rows > 0) {
            $errors['email'] = "Email already exists in the database!";
        }

        if($password !== $confirm){
            $errors['password_confirm'] = "Passwords Don't Match!";
        
        }
        if(strlen($password) < 8 || !preg_match("#[A-Z]+#", $password) || !preg_match("#[a-z]+#", $password) 
            || !preg_match("/[\'^£$%&*()}{@#~?><>,|=_+!-]/", $password) || !preg_match("#[0-9]+#", $password)){
            $errors['password'] = "Passwords shouldn't be less than 8 characters and contains upper and lowercase characters and also special character !";
        }
        if (count($errors) === 0){
            $query  = "INSERT INTO users (name,email,mobile,national_id,password,otp) VALUES ('$name','$email','$mobile','$national','$hashedPassword','$otp')";
            $result = $con->query($query);
            $_SESSION['EMAIL'] = $email;
            $_SESSION['otp'] = $otp;
            sendMail($email, $otp);
            //echo "yes";
            header("Location: verify.php");
            exit();
        }else{
        //  echo "no";
        }
      

    }




    // THIS IS FOR LOGIN PART 

    if (isset($_POST['login'])) {

       // $_SESSION['login_attempts']=1;
       // $_SESSION['blocked_times'];
        $email_login  = $con->real_escape_string($_POST['email']);     
        $password_login  = $con->real_escape_string($_POST['password']);
      
        // validations
    
        $sqll = $con->query("SELECT id, password, name, verified, blocked FROM users WHERE email='$email_login'");
        if ($sqll->num_rows <= 0) {
            $errors_login['email_login'] = "Email doesn't exists in the database!";
            //if(isset($_SESSION['login_attempts'])){
                $_SESSION['login_attempts'] += 1;
            //}   
        }else{
            
            $data = $sqll->fetch_array();
           
           //print_r($_SESSION['fail_login']);die('ds');
            if (!empty($data) && ($data['verified'] == 0) ){
                $errors_login['not_verified'] = "Please Verify Your Email Before Logging in!";
            }

            if (!empty($data) && $data['blocked'] == 1 ){
                $errors_login['blocked'] = "Your Account Has Been Blocked For Many Wrong Passwords!";
            }

            if (!empty($data) && ($_SESSION['fail_login'] >= 3 )){
                $con->query("UPDATE users SET blocked = 1 WHERE email = '$email_login'");
                unset($_SESSION['fail_login']);
                $errors_login['blocked'] = "Your Account Has Been Blocked For Many Wrong Passwords!";
            }

            if (!empty($data) && password_verify($password_login, $data['password'])){
               // $errors_login['password_login'] = "hello world";   
            }else{
                $errors_login['password_login'] = "The Entered password doesn't match the email!";
                $_SESSION['login_attempts_pass'] =  $_SESSION['login_attempts_pass'] + 1;
                //locked_time_pass
                
            }
        }
    
        if (count($errors_login) === 0){
            $_SESSION['name'] = $data['name'];
            $_SESSION['loggedin'] = true;
            header("Location: dashboard.php");
            exit();
        }else{
          //  echo "no";
        }
        
    }



    // THIS IS FOR VERIFY OTP FUNCTION

    if (isset($_POST['otp'])) {
       // $con = new mysqli('localhost', 'root', '', 'loginSYS'); 	
        $postOtp = $_POST['otp'];
        $email  = $_SESSION['EMAIL'];
        $query  = "SELECT * FROM users WHERE otp = '$postOtp' AND email = '$email'";
           
        $result = $con->query($query);
        if ($result->num_rows > 0) {
           	$con->query("UPDATE users SET verified = 1 WHERE email = '$email'");
            $_SESSION['IS_LOGIN'] = $email;
            echo "yes"; 
           // header("Location: login.php");
           // exit();         
        }else{
            echo "no";
        } 
                     
    }



    // RESEND OTP Logic

    if (isset($_GET['resend']) && $_GET['resend'] == 1) {
       // $email  = $_SESSION['EMAIL'];
       if (isset($_SESSION['resend_attempts'])){
        $_SESSION['resend_attempts']+=1;
       }  
        sendMail( $_SESSION['EMAIL'],  $_SESSION['otp']);
        header("Location: verify.php");
        exit();
    }



    function sendMail($to, $msg){

        require 'PHPMailer/PHPMailerAutoload.php';
    
        $mail = new PHPMailer;
        
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
    
        $mail->isSMTP();                                      
        $mail->Host = 'smtp.gmail.com';                       
        $mail->SMTPAuth = true;                               
        $mail->Username = 'eslamelarabi87@gmail.com';        // SMTP username ADD THE EMAIL HERE AS SAID IN README FILE
        $mail->Password = 'Serialkiller79';                  // SMTP password AND HERE IS THE PASSWORD
        $mail->SMTPSecure = 'tls';                            
        $mail->Port = 587;                                    
        $mail->setFrom('FromEmail', 'OTP Verification');
        $mail->addAddress($to, 'OTP Verification');           
       
        $mail->isHTML(true);                                  
    
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );
    
        $mail->Subject = 'OTP Verification';
        $mail->Body    = 'Your verification OTP Code is <b>'.$msg.'</b>';


  
        if($mail->send()) {
            return true;
        } else {
            return false;
        }
        
      }

?>