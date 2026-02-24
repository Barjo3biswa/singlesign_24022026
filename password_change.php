<?php
session_start();
include "constants.php";
require_once 'CentralAuthPasswordManager.php';
$pdo = new PDO("pgsql:host=" . VERIFY_USER_DB_HOST . ";port=" . VERIFY_USER_DB_PORT . ";dbname=" . CENTRAL_AUTH, "postgres", "postgres");
$policyManager = new CentralAuthPasswordManager($pdo);
$policy = $policyManager->getPolicy();
?>
<script src="assets/js/bcrypt.js"></script>
<style>
.password-rules {
    background: #f9f9fb;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 10px 15px;
    margin-top: 8px;
    font-size: 13px;
    color: #444;
}
.password-rules li.valid { color: #28a745; }
.password-rules li.invalid { color: #dc3545; }
.password-rules li span { width: 16px; display: inline-block; }
</style>

<div class="col-lg-12">
    <div class="card-header bg-info text-white text-center">
        PASSWORD CHANGE AND MOBILE NO UPDATION
    </div>
    <div class="card-header bg-dark text-danger text-center h6" style="font-weight:bold">
        <span style="color: red">Please note that the entered MOBILE NO will be used for future purpose</span>
    </div>
    <div id="displayBox" style="display: none; z-index:99999999"><img src="assets/img/process.gif"></div>
    <div class="card-body" style="margin-top:-35px;">
        <form class="border_login status_form" id='password_change_form' method="POST">
            <div class="row">
                <div class="col-lg-4" style="text-align: right;">
                    <label>New Password</label>
                </div>
                <div class="col-lg-6">
                    <input name="new_password" class="form-control" id='new_password' type="password" placeholder="NEW-PASSWORD" onkeyup="checkPasswordRules()"/>
                    <div style="margin-top:10px;">
                        <?php echo $policyManager->getPasswordRulesHtml(); ?>
                    </div>
                    <div id='password-msg'></div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4" style="text-align: right;">
                    <label>Confirm New Password</label>
                </div>
                <div class="col-lg-6">
                    <input name='confirm_new_password' class="form-control" id='confirm_new_password' type="password" placeholder="CONFIRM-NEW-PASSWORD"/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4" style="text-align: right;">
                    <label>Mobile No</label>
                </div>
                <div class="col-lg-6">
                    <input name="mobile_no" class="form-control" id='mobile_no' placeholder="MOBILE-NO"/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4" style="text-align: right;"></div>
                <div class="col-lg-6">
                    <button class="btn btn-sm btn-danger" onclick="getOTP()" id="genereteOTPButton">
                        CLICK HERE TO GENERATE OTP
                    </button>
                </div>
            </div>
            <div id="otp_div" style="display:none;">
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
                        <input name="otp" class="form-control" id='otp' placeholder="OTP"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4" style="text-align: right;"></div>
                    <div class="col-lg-6" style="margin-top: -15px;">
                        <button class="btn btn-sm btn-warning" onclick="verifyOTP()">VERIFY OTP</button>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-4" style="text-align: right;"></div>
                <div class="col-lg-6">
                    <button class="btn btn-primary" type="button" disabled style="display:none" id="loader_button">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Please Wait...
                    </button>
                    <input class="btn btn-success" type="submit" value="SUBMIT" style="display:none" id="pass_change_submit" onclick="passChangeHandle()">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function checkPasswordRules() {
    const p = document.getElementById("new_password").value;
    const rules = {
        length: p.length >= <?= $policy['min_length'] ?>,
        upper: <?= $policy['require_uppercase'] ? 'true' : 'false' ?> ? /[A-Z]/.test(p) : true,
        lower: <?= $policy['require_lowercase'] ? 'true' : 'false' ?> ? /[a-z]/.test(p) : true,
        number: <?= $policy['require_number'] ? 'true' : 'false' ?> ? /[0-9]/.test(p) : true,
        special: <?= $policy['require_special'] ? 'true' : 'false' ?> ? new RegExp('[' + "<?= addslashes($policy['allowed_specials']) ?>".replace(/[-[\]/{}()*+?.\\^$|]/g, '\\$&') + ']').test(p) : true
    };

    for (const [k, ok] of Object.entries(rules)) {
        const el = document.getElementById('rule-' + k);
        if (!el) continue;
        el.className = ok ? 'valid' : 'invalid';
        el.querySelector('span').textContent = ok ? '✓' : '✗';
    }

    const all = Object.values(rules).every(Boolean);
    document.getElementById("password-msg").innerHTML = all ? 
        "<span style='color:green;'>✅ Strong password</span>" : 
        "<span style='color:#ff6600;'>⚠️ Please meet all the requirements</span>";
}

function getOTP(){
    event.preventDefault();
    $('#otp').val();
    var mobile_no = $('#mobile_no').val();
    if(mobile_no == ""){
        showWarningMessage("Please Enter The Mobile No..!");
        return;
    }

    var new_password = $('#new_password').val();
    var result = validateText(new_password);
    if(result.lower == 0 || result.upper == 0 || result.d_s == false){
        alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character....!");
        return;
    }

    var confirm_new_password = $('#confirm_new_password').val();
    if(new_password != confirm_new_password){
        alert("New Password And Confirm New Password Filed Mismatched..!, Please Enter Properly..!");
        return;
    }

    var formData = $('#password_change_form').serialize();
    $.ajax({
        type : 'POST',
        url : 'password_change_otp_generate.php',
        data : formData,
        dataType : 'json',
        encode : true,
        beforeSend: function(){
            $("#btnImageloading").html("Please wait logging in...");
        },
    }).done(function(data) {
        if(data.responseType == 1){
            if(data.code == '406'){
                showErrorMessage('Invalid Mobile No..!, Kindly Try Again..!');
                return;
            }
            showSuccessMessage('OTP sent successfully..!');
            return;
        }
    }).fail(function(xhr, err) {
        showWarningMessage('#ERROR-005 : Internal Server Issue... Kindly contact administrator');
    });
    $('#otp_div').show();
}

function verifyOTP(){
    event.preventDefault();
    var otp = $('#otp').val();
    if(otp == ""){
        showWarningMessage("Please Enter The OTP..!");
        return
    }

    $.ajax({
        type : 'POST',
        url : 'password_change_otp_verify.php',
        data : {'otp': otp},
        dataType : 'json',
        encode : true,
        beforeSend: function(){
            $("#btnImageloading").html("Please wait logging in...");
        },
    }).done(function(data) {
        if(data=='true'){
            showSuccessMessage('OTP-Verified');
            $('#pass_change_submit').show();
            $('#otp_div').hide();
            $('#genereteOTPButton').prop('disabled', true);
            $('#mobile_no').prop('readOnly', true);
        }else{
            showErrorMessage('otp-verification failed..!(#0032), Kindly Try Again..!')
        }
    }).fail(function(xhr, err) {
        showWarningMessage('#ERROR-0032 : Internal Server Issue... Kindly contact administrator');
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

function passChangeHandle(){
    event.preventDefault();
    $('#loader_button').show();

    var new_password = $('#new_password').val();
    var result = validateText(new_password);
    if(result.lower == 0 || result.upper == 0 || result.d_s == false){
        alert("Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character....!");
        $('#loader_button').hide();
        $('#pass_change_submit').show();
        return;
    }

    var confirm_new_password = $('#confirm_new_password').val();
    if(new_password != confirm_new_password){
        alert("New Password And Confirm New Password Filed Mismatched..!, Please Enter Properly..!");
        $('#loader_button').hide();
        $('#pass_change_submit').show();
        return
    }

    var formData = $('#password_change_form').serialize();
    $('#pass_change_submit').hide();
    $.ajax({
        type : 'POST',
        url : 'password_change_process.php',
        data : formData,
        dataType : 'json',
        encode : true,
        beforeSend: function(){
            $("#btnImageloading").html("Please wait logging in...");
        },
    }).done(function(data) {
        if(!data.result){
            showWarningMessage(data.msg);
            $('#pass_change_submit').show();
            return;
        }else {
            Swal.fire({
                text: data.msg,
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    actions: 'my-actions',
                    confirmButton: 'order-2',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                    return;
                }
            });
        }
    }).fail(function(xhr, err) {
        showWarningMessage('#ERROR-007 : Internal Server Issue... Kindly contact administrator');
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
</script>