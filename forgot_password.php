<script src="assets/js/bcrypt.js"></script>
<div class="col-lg-12">
   <div class="card-header bg-success text-white text-center">
      FORGOT PASSWORD 
   </div>
   <div class="bg-dark text-white text-center">
      <span style="font-size:16px;">
        NOTE: FOR VERIFYING THE USER NAME, AN OTP WILL BE SENT TO THE REGISTERD MOBILE NO, AFTER VERIFICATION OF THE OTP PASSWORD CAN BE RESET.        
      </span>
   </div>
   <div class="card-body" style="margin-top:-35px;">
    <form class="border_login status_form" id='fp_form' method="POST">      
        <div class="row">
            <div class="col-lg-4" style="text-align: right;">
                <label>USER-NAME: </label>
            </div>
            <div class="col-lg-6">
                <input name="user_name" class="form-control" id="fp_username"
                type="text" placeholder="USER-NAME"/>
            </div>
        </div>                       
        <div class="row">
            <div class="col-lg-4" style="text-align: right;"></div>
            <div class="col-lg-6">                
                <button class="btn btn-primary" type="button" disabled style="display:none" id="loader_button">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Please Wait...
                </button>
                <button class="btn btn-sm btn-warning text-white text-bold" onclick="verifyUser()" id="verifyUserBtn">
                    VERIFY USER-NAME
                </button>
            </div>
        </div>   
        <div id="fp_otp_div" style="display:none;">            
            <div class="row mt-1">
                <div class="col-lg-2" style="text-align: right;">
                </div>
                <div class="col-lg-9">
                    <span class="text-success font-weight-bold text-center" style="font-weight: bold;">
                        OTP Has Been Sent To The Mobile No, Kindly Verify.<br>
                    </span>
                </div>
            </div>    
            <div class="row mt-1">
                <div class="col-lg-4" style="text-align: right;">
                </div>
                <div class="col-lg-6">                    
                    <input name="fpotp" class="form-control" id='fpotp' placeholder="OTP"/>                            
                </div>
            </div>      
            <div class="row">
                <div class="col-lg-4" style="text-align: right;"></div>
                <div class="col-lg-6" style="margin-top: -15px;">
                    <button class="btn btn-sm btn-info" onclick="verifyFpOTP()">VERIFY OTP</button>
                </div>
            </div>      
        </div>   
        <div id="fp_pass_div" style="display:none;">   
            <div class="row">
                <div class="col-lg-4" style="text-align: right;">
                    <label>New Password</label>
                </div>
                <div class="col-lg-6">
                    <input name="fp_new_password" class="form-control" id='fp_new_password' 
                    type="password" placeholder="NEW-PASSWORD"/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4" style="text-align: right;">
                    <label>Confirm New Password</label>
                </div>
                <div class="col-lg-6">
                    <input name='fp_confirm_new_password' class="form-control" id='fp_confirm_new_password' 
                    type="password" placeholder="CONFIRM-NEW-PASSWORD"/>                            
                </div>
            </div>            
            <div class="row">
                <div class="col-lg-4" style="text-align: right;"></div>
                <div class="col-lg-6">
                    <img src=captcha-image.php id="fpcapt" width="35%"> <i class="fa fa-refresh" id='fprefreshCaptcha'></i>
                    <input type="text" style='width:50%;inline:center' id="fpcaptchaInput" name="fpcaptcha" placeholder="Type captcha..">
                </div>
            </div>  
            <div class="row">
                <div class="col-lg-4" style="text-align: right;"></div>
                <div class="col-lg-6">                    
                    <button class="btn btn-primary" type="button" disabled style="display:none" id="fp_loader_button">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Please Wait...
                    </button>
                    <button class="btn btn-primary" onclick="handleFP()" id="fp_submit">
                        SUBMIT
                    </button>
                </div>
            </div>  
        </div>        
    </form>
   </div>
</div>

<script>

    function handleFP(){
        event.preventDefault();        
        var formData = $('#fp_form').serialize();
        var username= $('#fp_username').val();
        if(username == ""){
            alert("Please enter the User-Name..!");
            return;
        }
        var new_password = $('#fp_new_password').val();
        var result = validateText(new_password);
        if(result.lower == 0 || result.upper == 0 || result.d_s == false){            
            alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character....!");
            return;
        }
        var confirm_new_password = $('#fp_confirm_new_password').val();
        if(new_password != confirm_new_password){
            alert("New Password And Confirm New Password Filed Mismatched..!, Please Enter Properly..!");
            return 
        }     
        var captcha = $("#fpcaptchaInput").val();
        if(captcha == ""){
            var msg = "Please Enter Captcha";            
            alert(msg);
            return;
        } 
        $('#fp_loader_button').show();
        $('#fp_submit').hide();
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'forgot_password_process.php', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true,
            beforeSend: function(){
                $("#btnImageloading").html("Please wait logging in...");
            },
        }).done(function(data) {
            if(!data.result){
                alert(data.msg);
                $('#fp_loader_button').hide();
                $('#fp_submit').show();
                return;
            }else
            {                
                Swal.fire({
                    text: data.msg,
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        actions: 'my-actions',
                        confirmButton: 'order-2',
                    }
                }).then((result) => {
                    if (result.isConfirmed) 
                    {
                        location.reload();
                        return;
                    }
                });
            }       
        }).fail(function(xhr, err) { 
            showWarningMessage('#ERROR-FP-001 : Internal Server Issue... Kindly contact administrator');
            $('#fp_loader_button').hide();
            $('#fp_submit').show();
        });
    }

    function validateText(text) {
        var digit_special = true;
        var paswd = /^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,20}$/;
        if(!text.match(paswd)){
            digit_special = false;
        }
        return {
            lower: (text.match(/[a-z]/g) || []).length,
            upper: (text.match(/[A-Z]/g) || []).length,
            d_s : digit_special
        };
    }

    function verifyUser(){
        event.preventDefault();
        var username= $('#fp_username').val();
        if(username == ""){
            alert("Please enter the User-Name..!");
            return;
        }
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'verify_user.php', // the url where we want to POST
            data        : {'uname':username}, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true,
            beforeSend: function(){
                $("#btnImageloading").html("Please wait logging in...");
            },
        }).done(function(data) {
            if(!data.result){
                showWarningMessage(data.msg);
                return;
            }else{
                if(data.msg == "" || data.msg == null){
                    showErrorMessage('#ERRFP3:Invalid Registered Mobile No..!, Kindly Try Again..!');
                    return;
                }
                if(data.msg.responseType == 1){
                    if(data.msg.code == '406'){
                        showErrorMessage('#ERRFP1:Invalid Registered Mobile No..!, Kindly Try Again..!');
                        return;
                    }else if(data.msg.code == 'ERR'){
                        showErrorMessage('#ERRFP2:Invalid Registered Mobile No..!, Kindly Try Again..!');
                        return;
                    }
                    showSuccessMessage('OTP sent successfully..!');
                    $('#fp_otp_div').show();
                    return;
                }
            }
        }).fail(function(xhr, err) { 
            showWarningMessage('#ERROR-FP-001 : Internal Server Issue... Kindly contact administrator');
        });
    }

    function verifyFpOTP(){
        event.preventDefault();
        var otp = $('#fpotp').val();
        if(otp == ""){
            showWarningMessage("Please Enter The OTP..!");
            return
        }
        //var formData = $('#fp_form').serialize();
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'password_change_otp_verify.php', // the url where we want to POST
            data        : {'otp': otp}, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true,
            beforeSend: function(){
                $("#btnImageloading").html("Please wait logging in...");
            },
        }).done(function(data) {
            if(data == 'true'){
                showSuccessMessage('OTP-Verified');
                $('#fp_otp_div').hide();
                $('#verifyUserBtn').hide();
                $('#fp_username').prop('readOnly', true);
                $('#fp_pass_div').show();
                
            }else{
                showErrorMessage('otp-verification failed..!, Kindly Try Again..!')
            }                       
        }).fail(function(xhr, err) { 
            showWarningMessage('#ERROR-006 : Internal Server Issue... Kindly contact administrator');
        });
    }

    function showSuccessMessage(text) {
        Swal.fire({
            title: "Success!",
            text: text,
            icon: 'success',
            position: 'top',
            timer: 5000000000000,
            showCancelButton: true

        });
    }

    function showErrorMessage(text) {
        Swal.fire({
            title: "Error!",
            text: text,
            icon: 'error',
            position: 'top',
            timer: 5000000000000,
            showCancelButton: true

        });
    }

    function showWarningMessage(text) {
        Swal.fire({
            title: "Warning!",
            text: text,
            icon: 'warning',
            position: 'top',
            timer: 5000000000000,
            showCancelButton: true
        });
    }

    $(document).ready(function(){   
        $('#fprefreshCaptcha').click(function(){
            $("#fpcapt").attr("src","captcha-image.php?r="+ Math.random()); 
        })
    });

</script>
