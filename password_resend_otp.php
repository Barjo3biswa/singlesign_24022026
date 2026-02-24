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
    <!-- HEADER -->
    <div class="card-header bg-info text-white text-center">
        PASSWORD RESET
    </div>
    <div class="card-header bg-dark text-danger text-center h6" style="font-weight:bold">
        <span style="color: red;">Password expired. Please change your password.</span>
    </div>

    <!-- BODY -->
    <div id="displayBox" style="display:none; z-index:99999999">
        <img src="assets/img/process.gif">
    </div>

    <div class="card-body" style="margin-top:-35px;">
        <form id="password_change_form" method="POST" class="border_login status_form">

            <!-- RESEND OTP BUTTON -->
           

            <input type="hidden" name="mobile_no" id="mobile_no" value="<?php echo $_SESSION['credentials']['mobile'];?>">

            <!-- NEW PASSWORD -->
            <div class="row">
                <div class="col-lg-4" style="text-align:right;">
                    <label>New Password</label>
                </div>
                <div class="col-lg-6">
                    <input name="new_password" id="new_password" type="password" class="form-control" placeholder="Enter New Password" onkeyup="checkPasswordRules()"/>
                    <div>
                        <?php echo $policyManager->getPasswordRulesHtml(); ?>
                    </div>
                    <div id="password-msg"></div>
                </div>
            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="row mt-1">
                <div class="col-lg-4" style="text-align:right;">
                    <label>Confirm Password</label>
                </div>
                <div class="col-lg-6">
                    <input name="confirm_new_password" id="confirm_password" type="password" class="form-control" placeholder="Confirm Password"/>
                </div>
            </div>

            <!-- <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-6">
                    <button type="button" class="btn btn-warning" onclick="resendOTP()">
                        RESEND OTP
                    </button>
                </div>
            </div> -->

            <div class="row">
                <div class="col-lg-4"></div>
                <div class="col-lg-6">
                    <button id="resendBtn" type="button" class="btn btn-warning" onclick="resendOTP()">
                        GET OTP
                    </button>
                    <span id="timerText" class="ms-2 text-danger fw-bold"></span>
                    <span id="btnImageloading" class="ms-2"></span>
                </div>
            </div>


            <!-- OTP -->
            <div class="row mt-1">
                <div class="col-lg-4" style="text-align:right;">
                    <label>OTP</label>
                </div>
                <div class="col-lg-6">
                    <input name="otp" id="otp" class="form-control" placeholder="Enter OTP"/>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="row">
                <div class="col-lg-4" style="text-align:right;"></div>
                <div class="col-lg-6">
                    <button type="button" class="btn btn-primary" disabled style="display:none" id="loader_button">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Please Wait...
                    </button>
                    <button type="button" class="btn btn-primary btn-block" id="reset_submit" onclick="verifyAndUpdatePassword()">SUBMIT</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let resendCount = 0;
let isWaiting = false;
let timerInterval;

function resendOTP() {
    if (isWaiting) return; // prevent clicking during countdown

    const button = document.getElementById("resendBtn");
    const timerText = document.getElementById("timerText");

    resendCount++;

    if (resendCount <= 3) {
        // var mobile_no = "9365809217";
         var mobile_no = "<?php echo trim($_SESSION['credentials']['mobile']); ?>";

        $.ajax({
            type: 'POST',
            url: 'password_change_otp_generate.php',
            data: { mobile_no: mobile_no },
            dataType: 'json',
            beforeSend: function() {
                $("#btnImageloading").html("Please wait...");
            },
            success: function(data) {
                if (data.responseType == 1) {
                    if (data.code == '406') {
                        showErrorMessage('Invalid Mobile No..! Kindly Try Again..!');
                        return;
                    }
                    showSuccessMessage('OTP sent successfully..!');
                } else {
                    showWarningMessage('Unexpected response from server.');
                }
            },
            error: function(xhr, err) {
                showWarningMessage('#ERROR-005 : Internal Server Issue... Kindly contact administrator');
            },
            complete: function() {
                $("#btnImageloading").html("");
            }
        });

        // 🔹 Disable button and start countdown
        button.disabled = true;
        isWaiting = true;
        let secondsLeft = 60;
        timerText.textContent = `Please wait ${secondsLeft}s`;

        timerInterval = setInterval(() => {
            secondsLeft--;
            timerText.textContent = `Please wait ${secondsLeft}s`;

            if (secondsLeft <= 0) {
                clearInterval(timerInterval);
                timerText.textContent = "";
                isWaiting = false;

                if (resendCount < 3) {
                    button.disabled = false;
                    button.textContent = "RESEND OTP";
                } else {
                    button.textContent = "LIMIT REACHED";
                    button.classList.remove("btn-warning");
                    button.classList.add("btn-secondary");
                }
            }
        }, 1000);

        if (resendCount === 1) {
            button.textContent = "RESEND OTP";
        }

    } else {
        button.disabled = true;
        button.textContent = "LIMIT REACHED";
        button.classList.remove("btn-warning");
        button.classList.add("btn-secondary");
        $("#btnImageloading").html("");
        timerText.textContent = "";
    }
}
</script>



<script>
function verifyAndUpdatePassword(){
    alert("ok");
    event.preventDefault();
    $('#loader_button').show();
    $('#reset_submit').hide();

    var otp = $('#otp').val();
    var new_password = $('#new_password').val();
    var confirm_password = $('#confirm_password').val();

    if(!otp || !new_password || !confirm_password){
        showWarningMessage("All fields are required!");
        $('#loader_button').hide(); $('#reset_submit').show();
        return;
    }

    if(new_password !== confirm_password){
        showErrorMessage("Passwords do not match!");
        $('#loader_button').hide(); $('#reset_submit').show();
        return;
    }

    var result = validateText(new_password);
    if(result.lower == 0 || result.upper == 0 || result.d_s == false){
        alert("Password should include at least one uppercase, one number, and one special character!");
        $('#loader_button').hide(); $('#reset_submit').show();
        return;
    }

    var formData = $('#password_change_form').serialize();
    $.ajax({
        type: 'POST',
        url: 'password_change_process.php',
        data: formData,
        dataType: 'json',
    }).done(function(data){
        $('#loader_button').hide(); $('#reset_submit').show();
        if(data.result){
            Swal.fire({
                text: data.msg,
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((res)=>{ if(res.isConfirmed) location.reload(); });
        } else {
            showErrorMessage(data.msg || 'OTP verification failed!');
        }
    }).fail(function(){
        $('#loader_button').hide(); $('#reset_submit').show();
        showErrorMessage('Internal Server Error');
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
        "<span style='color:#ff6600;'>⚠️ Please meet all requirements</span>";
}

// ✅ ALERT MESSAGES
function showSuccessMessage(text){ Swal.fire({ title:"Success!", text, icon:"success", position:"top" }); }
function showErrorMessage(text){ Swal.fire({ title:"Error!", text, icon:"error", position:"top" }); }
function showWarningMessage(text){ Swal.fire({ title:"Warning!", text, icon:"warning", position:"top" }); }
</script>
