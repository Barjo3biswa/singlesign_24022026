<?php    
    session_start();
    include "activity_logger.php";
    include "constants.php";
    //echo $_SESSION['otp'];
    if($_SESSION['otp'] == $_POST['otp']){ 
      echo json_encode('true');
      log_request_activity(
            $_SESSION['credentials']['dist_code'], 
            'session',  
            null,
            $_SESSION['credentials']['username']  ,
            'otp-verify' 
        );
    }else{
      echo json_encode('false');
    }


    //echo $_SESSION['otp'];
    //return $_SESSION['otp'];
?>
