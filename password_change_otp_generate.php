<?php 
    session_start();
    include "constants.php";
    if(ENABLE_LOGIN_OTP==1){
        $random_no = random_int(100000, 999999);
        $_SESSION['otp'] = $random_no;
    }else{
        $_SESSION['otp'] = OTP;
    }
    // if($_POST['mobile_no']==null  || empty($_POST['mobile_no'])){
    //     return json_encode(
    //         array(
    //                 "responseType"=> 1,
    //                 "code"=> "446",
    //                 "msg"=> "Undefined error",
    //                 "mgsId"=> "MsgID not found!"
    //             )
    //         );
    // }
    //echo json_encode($_SESSION['otp']);
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => SMS_API,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{
        "key":"mobileno_verify",
        "variables":"'.$_SESSION['otp'].'",
        "mobilenos":"'.$_POST['mobile_no'].'" 
    }',
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    echo $response;

?>