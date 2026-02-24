
<?php 
    session_start();
    include "constants.php";
    //**************************************************************/
    if(empty($_POST['user_name'])){
        echo json_encode([
            "result" => false,
            "msg" => "User name not found..!"
        ]);
        exit;
    }  
    if(empty($_POST['fp_new_password'])){
        echo json_encode([
            "result" => false,
            "msg" => "Please Enter The New Password..!"
        ]);
        exit;
    }  
    if(empty($_POST['fp_confirm_new_password'])){
        echo json_encode([
            "result" => false,
            "msg" => "Please Enter The Confirm New Password..!"
        ]);
        exit;
    }  
    if(empty($_POST['fpcaptcha'])){
        echo json_encode([
            "result" => false,
            "msg" => "Please Enter The Captcha..!"
        ]);
        exit;
    }        
    if($_POST['fpcaptcha']!=$_SESSION['my_captcha']){
        $errors['captcha']=" ";
        echo json_encode([
            "result" => false,
            "msg" => "Captcha Mismatched, Please Enter The Captcha Correctly..!"
        ]);
        exit;
    }
    if($_POST['user_name'] != $_SESSION['user_name']){
        echo json_encode([
            "result" => false,
            "msg" => "User name not matched..!"
        ]);
        exit;
    }
    //**************************************************************/
    //echo json_encode(["P"=>$_POST, "S"=>$_SESSION]);
    $options = [
        'cost' => 12,
    ];
    $enc_pass = password_hash($_POST['fp_new_password'], PASSWORD_BCRYPT, $options);
    // echo "uname- : ".$_POST['user_name']."-enc_pass-".$enc_pass. 
    //      "-dist_code-".$_SESSION['credentials']['dist_code']."-mobile-".
    //      $_SESSION['credentials']['mobile']."-dhar_user-".$_SESSION["credentials"]["dharitree"].
    //     "-noc_user-".$_SESSION["credentials"]["noc"];        
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => PASS_API_UPDATION,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
            'uname'     => $_POST['user_name'],
            'cred'      => $enc_pass,
            'dist_code' => $_SESSION['credentials']['dist_code'],
            'mobile'    => $_SESSION['credentials']['mobile'],
            'dhar_user' => $_SESSION["credentials"]["dharitree"]?$_SESSION["credentials"]["dharitree"]:null,
            'noc_user'  => $_SESSION["credentials"]["noc"]?$_SESSION["credentials"]["noc"]:null
        ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    $resp = json_decode($response);
    // var_dump($resp);
    if($resp!=null && $resp[0]->responseType == 2)
    {
        echo json_encode([
            "result" => true,
            "msg" => "Password Updated Successfully..!!!"
        ]);
        exit;
    }
    else {
        echo json_encode([
            "result" => false,
            "msg" => "Some Error Occured, Please Contact Administrator..!"
        ]);
        exit;
    }
?>
