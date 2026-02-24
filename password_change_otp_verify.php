<?php    
    session_start();
    //echo $_SESSION['otp'];
    if($_SESSION['otp'] == $_POST['otp']){ 
      echo json_encode('true');
    }else{
      echo json_encode('false');
    }


    //echo $_SESSION['otp'];
    //return $_SESSION['otp'];
?>
