<?php
    session_start();
    session_destroy();
    unset($_SESSION['EMAIL']);
    unset($_SESSION['IS_LOGIN']);
    header("Location:login.php");

?>