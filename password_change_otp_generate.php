<?php 
    session_start();
    include "constants.php";
    if(ENABLE_LOGIN_OTP==1){
        $random_no = random_int(100000, 999999);
        $_SESSION['otp'] = $random_no;
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

    }else{
        $_SESSION['otp'] = OTP;
        $response=[
    "responseType"=> 1,
    "code"=> "402",
    "msg"=> "SMS sent successfully",
    "mgsId"=> "TEST_DLRS_ASSAM"
        ];
        echo json_encode($response);
    }

?>


