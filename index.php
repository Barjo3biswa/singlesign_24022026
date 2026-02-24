<?php
    session_start();
    include "constants.php";
    $_SESSION['salt'] = '%%12345@@@6789%%';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>ILRMS | Government of Assam</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="assets/img/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/jquery.bxslider.css">
    <!-- Script -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.bxslider.min.js"></script>
    <link rel="stylesheet" href="assets/css/sweetalert2.min.css">
    <script src="assets/js/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="assets/js/bcrypt.js"></script>
    <script src="assets/js/sha512.min.js"></script>
    <script src="assets/js/blockUI.js"></script>
    <script type="text/javascript">
        $(function() {
            $(".font-button").bind("click", function() {
                var size = parseInt($('body').css("font-size"));
                if ($(this).hasClass("plus")) {
                    size = size + 2;
                } else {
                    size = size - 2;
                    if (size <= 10) {
                        size = 10;
                    }
                }
                $('#content').css("font-size", size);
            });
        });
    </script>
    <!-- End Script -->
<!-- End Script -->
       </head>
	<style type='text/css'>
		#content p
        {
            font-size: 14px !imporatant;
        }
		.font-button
        {
            height: 25px;
            width: 25px;
            display: inline-block;
            color: #fff;
            text-align: center;
            line-height: 22px;
            font-size: 15px;
            cursor: pointer;
			border: 1px solid #ffbd5f;
			border-radius: 1px;
        }
		a{text-decoration: none;}
		.languege-area .select-languege {
		float: left;
		color: #f79d1d;
		padding: 0px;
		min-width: 100px;
		font-size: 13px;
		height: 23px;
		font-weight: 800;
		letter-spacing: 1.2px;
		}
		.red{
			color:red;
			font-size:17px !important;
			font-family:italic;
		}
        
</style>
<body >
    <!-- Start Top Nav -->
    <nav class="navbar navbar-expand-lg bg-dark navbar-light d-none d-lg-block" id="ilrms_nav_top">
        <div class="container text-light">
            <div class="w-100 d-flex justify-content-between">
                <div>
                    <a><img src="assets/img/flag.png" alt="Flag" style="color:#fff;margin-right: 5px;">GOVERNMENT OF ASSAM</a>
                    <a><img src="assets/img/vertical-line.png" alt="verticalline" style="color:#fff;margin-right: 5px;">Revenue and Disaster Management Department </a>
                </div>
                <div>
                    <a class="font-button plus">A+</a> <a class="font-button minus">A-</a>
                    <img src="assets/img/vertical-line.png" alt="verticalline" style="color:#fff;margin-right: 5px;">
                    <label class="screen-reader-text" for="lang_choice_polylang-4">Choose a language</label>
                    <select name="lang_choice" id="lang_choice_polylang-4" class="select-languege">
                        <option value="en">English</option>
                        <option value="hi">Hindi</option>
                        <option value="asm" selected="selected">Assamese</option>
                        <option value="bn">Bengali</option>
                        <option value="bn">Server 2</option>
                    </select>
                </div>
            </div>
        </div>
    </nav>
    <!-- Close Top Nav -->


    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light shadow">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="col-md-6 ilrms-logo">
                <p><a class="tophead" title="Government of Assam" href="#">ILRMS</a></p>
                <p title="" class="mainhead" style="font-family: Roboto,sans-serif;">Integrated Land Records Management System</p>
            </div>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#ilrms_main_nav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="align-self-center collapse navbar-collapse flex-fill  d-lg-flex justify-content-lg-between" id="ilrms_main_nav">
                <div class="flex-fill">
                    <ul class="nav navbar-nav d-flex justify-content-between mx-lg-auto">
                        <li class="nav-item red" style='background:red; '>
                            <a class="nav-link red" style='color:#FFF' href="https://revenueassam.nic.in/">Main</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="iindex.html">
                                <center><img src='assets/img/home.png' width='40%'></center>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="https://landrevenue.assam.gov.in/contact-us">Contact</a>
                        </li>

                    </ul>

                </div>

            </div>

        </div>
    </nav>
    <!-- Close Header -->
    <div class="ilrms_banner">
        <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="10000">
                    <img src="assets/img/01.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/img/02.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item" data-bs-interval="2000">
                    <img src="assets/img/03.png" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="assets/img/04.png" class="d-block w-100" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleInterval" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleInterval" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </div>
    <!---------------------->
    <div class="ilrms_belowbanner">
        <div class="container">
            <!--  <marquee><h4 class="text-danger">
        This is to inform you that ILRMS services will not be available from 10 PM of 21/05/2022 to 10 AM of 23/05/2022 due to migration of Dharitree databases to Cloud server. Inconvenience regretted.

    </h4></marquee>  -->
            <!--  <marquee><h4 class="text-success">
        This is to inform you that the ILRMS service (Dharitree, NOC, Bhunaksha, revenueassam for jamabandi, epanjeeyan, basundhara etc.) will not be available , there will be a Maintenance
           Related Shutdown from 7 PM (Saturday) to 10 AM (Sunday). Inconveniences
           is highly regretted.
    </h4></marquee> -->
            <!-- <marquee><h4 class="text-danger">
       This is to inform you that the ILRMS service (Dharitree, NOC, Bhunaksha) will not be available from 10:30PM to 8AM from tomorrow ( 24th Feb,2022) due to server migration. Inconvenience regretted.

    </h4></marquee> -->
            <!-- <marquee>
    <h4 class="hide text-danger">
       ILRMS service will be down for Kamrup district from 10pm tonight due to migration of 20 villages.
    </h4>
    It will be resumed after completion of the task, hopefully by Saturday evening , 19th Feb,2022.
    </marquee> -->

            <!-- <div class="col-lg-12 alert alert-danger" style="margin-top:10px">
        Due to some urgent maintenance works as revenue year is changing from 1st of July 2023, 
        Access to Dharitree related service(s) won't be available from 12:00 AM to 12:00 PM on 1st July, 2023 .
        </div> -->
            <div class="col-lg-12">
                <div class="row">
                    <div class='col-lg-8'>
                        <div class="border_login">
                            <h4 class="underline">
                                About <strong>ILRMS</strong></h4>
                            <p id="content">
                                The efficient management of the country's land resources for long term economic development and social, cultural harmony and harmony is universally acknowledged to be of utmost importance in the context of modern technology and industrialization. The issue of full utilization of these land resources has gained a new dimension in rapid urbanization. <br> Proper management of land resources in the context of human suffering and overall has always been recognized as a matter of urgency as there is a need for equitable and equitable use of land resources based on increasing competitive demand. <br> In the words of Villa Kathar - land resources are for future generations. According to Thomas Jefferson, no generation can reduce or reduce the amount of debt it has to repay in the course of its existence. This is not the first time that I've seen this kind of thing happen, but it's the first time I've seen this kind of thing happen, and it's the first time I've seen this kind of thing happen. We have inherited it from our children and we have to keep in mind the benefits of using it for our own benefit as well as the benefit of future generations. Again, Theodore Roosevelt says - for the people.This is not the first time that I've heard of such a thing, but it's the first time that I've heard of such a thing, and it's the first time that I've heard of such a thing, and it's the first time that I've heard of such a thing. In the words of Aldo Leopold - We are abusing land resources because we know the role as our cattle property. But when we come to know the whole land resource as a part of our community or society, then maybe we will start to respect it. Overall, everyone is saying that it is important to use valuable resources like land very carefully, sensitively.</p>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class='border_login'>
                            <h4 class="underline"> <i class="fa fa-fw fa-question-circle"></i>
                                Login <strong> In</strong>
                            </h4>
                            <div id="displayBox" style="display: none;"><img src="assets/img/process.gif"></div>
                            <form class="border_login status_form" id='login_form' method="POST">
                                <label for="service_type">Select District Name : </label>
                                <select name="district" class="form-control" id='district1'>
                                    <option value="">--- Select District ---</option>
                                    <?php if (IS_PRODUCTION == 0) { ?>
                                        <option value="<?= UAT_DB_NAME ?>" <?php if (isset($_GET['district']) && $_GET['district'] == '<?=UAT_DB_NAME?>') {
                                                                                echo 'selected';
                                                                            } ?>> TEST_DB (<?= UAT_DB_NAME ?>) </option>
                                    <?php } else { ?>
                                        <option value="bajali" <?php if (isset($_GET['district']) && $_GET['district'] == 'bajali') {
                                                                    echo 'selected';
                                                                } ?>> Bajali </option>

                                        <option value="barpeta" <?php if (isset($_GET['district']) && $_GET['district'] == 'barpeta') {
                                                                    echo 'selected';
                                                                } ?>> Barpeta </option>
                                        <option value="biswanath" <?php if (isset($_GET['district']) && $_GET['district'] == 'biswanath') {
                                                                        echo 'selected';
                                                                    } ?>> Biswanath </option>
                                        <option value="bongaigaon" <?php if (isset($_GET['district']) && $_GET['district'] == 'bongaigaon') {
                                                                        echo 'selected';
                                                                    } ?>> Bongaigaon </option>
                                        <option value="charaideo" <?php if (isset($_GET['district']) && $_GET['district'] == 'charaideo') {
                                                                        echo 'selected';
                                                                    } ?>> Charaideo </option>
                                        <option value="cachar" <?php if (isset($_GET['district']) && $_GET['district'] == 'cachar') {
                                                                    echo 'selected';
                                                                } ?>> Cachar </option>

                                        <option value="darrang" <?php if (isset($_GET['district']) && $_GET['district'] == 'darrang') {
                                                                    echo 'selected';
                                                                } ?>> Darrang </option>
                                        <option value="dhemaji" <?php if (isset($_GET['district']) && $_GET['district'] == 'dhemaji') {
                                                                    echo 'selected';
                                                                } ?>> Dhemaji </option>
                                        <option value="dhubri" <?php if (isset($_GET['district']) && $_GET['district'] == 'dhubri') {
                                                                    echo 'selected';
                                                                } ?>> Dhubri </option>
                                        <option value="dibrugarh" <?php if (isset($_GET['district']) && $_GET['district'] == 'dibrugarh') {
                                                                        echo 'selected';
                                                                    } ?>> Dibrugarh </option>
                                        <option value="goalpara" <?php if (isset($_GET['district']) && $_GET['district'] == 'goalpara') {
                                                                        echo 'selected';
                                                                    } ?>> Goalpara </option>
                                        <option value="golaghat" <?php if (isset($_GET['district']) && $_GET['district'] == 'golaghat') {
                                                                        echo 'selected';
                                                                    } ?>>Golaghat </option>
                                        <option value="hailakandi" <?php if (isset($_GET['district']) && $_GET['district'] == 'hailakandi') {
                                                                        echo 'selected';
                                                                    } ?>> Hailakandi </option>
                                        <option value="hojai" <?php if (isset($_GET['district']) && $_GET['district'] == 'hojai') {
                                                                    echo 'selected';
                                                                } ?>> Hojai </option>
                                        <option value="jorhat" <?php if (isset($_GET['district']) && $_GET['district'] == 'jorhat') {
                                                                    echo 'selected';
                                                                } ?>> Jorhat </option>
                                        <option value="kamrup" <?php if (isset($_GET['district']) && $_GET['district'] == 'kamrup') {
                                                                    echo 'selected';
                                                                } ?>> Kamrup </option>
                                        <option value="kamrupM" <?php if (isset($_GET['district']) && $_GET['district'] == 'kamrupM') {
                                                                    echo 'selected';
                                                                } ?>> KamrupMetro </option>
                                        <option value="karimganj" <?php if (isset($_GET['district']) && $_GET['district'] == 'karimganj') {
                                                                        echo 'selected';
                                                                    } ?>> Karimganj </option>
                                        <option value="lakhimpur" <?php if (isset($_GET['district']) && $_GET['district'] == 'lakhimpur') {
                                                                        echo 'selected';
                                                                    } ?>> Lakhimpur </option>
                                        <option value="majuli" <?php if (isset($_GET['district']) && $_GET['district'] == 'majuli') {
                                                                    echo 'selected';
                                                                } ?>> Majuli </option>
                                        <option value="morigaon" <?php if (isset($_GET['district']) && $_GET['district'] == 'morigaon') {
                                                                        echo 'selected';
                                                                    } ?>> Morigaon </option>
                                        <option value="nagaon" <?php if (isset($_GET['district']) && $_GET['district'] == 'nagaon') {
                                                                    echo 'selected';
                                                                } ?>> Nagaon </option>
                                        <option value="nalbari" <?php if (isset($_GET['district']) && $_GET['district'] == 'nalbari') {
                                                                    echo 'selected';
                                                                } ?>> Nalbari </option>
                                        <option value="sibsagar" <?php if (isset($_GET['district']) && $_GET['district'] == 'sibsagar') {
                                                                        echo 'selected';
                                                                    } ?>> Sibsagar </option>
                                        <option value="sonitpur" <?php if (isset($_GET['district']) && $_GET['district'] == 'sonitpur') {
                                                                        echo 'selected';
                                                                    } ?>> Sonitpur </option>
                                        <option value="ssalmara" <?php if (isset($_GET['district']) && $_GET['district'] == 'ssalmara') {
                                                                        echo 'selected';
                                                                    } ?>> SouthSalmara </option>
                                        <option value="tinsukia" <?php if (isset($_GET['district']) && $_GET['district'] == 'tinsukia') {
                                                                        echo 'selected';
                                                                    } ?>> Tinsukia </option>
                                    <?php } ?>
                                </select>
                                <span id='district'></span>
                                <input type="text" id="appnum" style='width:93%; inline:center' name="uname" placeholder="User Name..">
                                <span id='username'></span>
                                <input type="password" id="appnump" style='width:93%;inline:center' name="password" placeholder="Password..">
                                <span id='password'></span>
                                <div class="row text-right">
                                    <a href="#" style="color:red" data-toggle="tooltip" data-placement="right" title="Password should be at least 8 characters in length and should include at least one upper case letter, one number, and one special character"><i class="fa fa-info-circle"></i> Password Policy</a>
                                </div>
                                <span id='db'></span>
                                <span id='con'></span>
                                <img src=captcha-image.php id="capt" width="35%"> <i class="fa fa-refresh" id='refreshCaptcha'></i>
                                <input type="text" style='width:50%;inline:center' id="captchaInput" name="captcha" placeholder="Type captcha..">

                                <span id='captcha'></span>
                                <span id="btnImageloading"></span>
                                <input class="btn btn-primary btn-block" type="submit" value="Submit">
                                <button class="btn btn-sm btn-info text-white" onclick="forgotPWD()">
                                    FORGOT-PASSWORD
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <div class="ilrms_hometext">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                </div>
            </div>
        </div>
    </div>
    <!---Logo Slider-->
    <div class="ilrms_logo_slider">
        <div class="container">
            <div class="bxslider logosImgs">
                <div><img src="assets/img/mygovassam.png" alt="ClientName" title="ClientName6"></div>
                <div><img src="assets/img/nic.png" alt="National Informatics Centre" title="National Informatics Centre"></div>
                <div><img src="assets/img/mygovassam.png" alt="ClientName" title="ClientName6"></div>
                <div><img src="assets/img/mygovassam.png" alt="My Gov Assam" title="My Gov Assam"></div>
                <div><img src="assets/img/mygovassam.png" alt="ClientName" title="ClientName6"></div>
                <div><img src="assets/img/nic.png" alt="National Informatics Centre" title="National Informatics Centre"></div>
            </div>
        </div>
    </div>
    <!--Logo Slider end-->
    <!-- Start Footer -->
    <footer class="footer-section spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h2 class="fw-title">ILRMS</h2>
                        <a href="">About ILRMS</a>
                        <a href="">FAQs</a>
                        <a href="">Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget">
                        <h2 class="fw-title">Website Links</h2>
                        <a href="https://landrevenue.assam.gov.in/" target="_blank">Revenue &amp; Disaster Management</a>
                        <a href="https://dlrs.assam.gov.in/" target="_blank">Directorate of Land Records</a>
                        <a href="https://igr.assam.gov.in/" target="_blank">Inspector General of Registration</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer-widget">
                        <h2 class="fw-title">Important Links</h2>
                        <a href="https://cm.assam.gov.in/" target="_blank">Assam CM Portal</a>
                        <a href="https://assam.gov.in/" target="_blank">Assam State Portal</a>
                        <a href="https://covid19.assam.gov.in/" target="_blank">Assam Covid-19 Portal</a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-7">
                    <div class="footer-widget">

                        <a class="fw-title"><img src="assets/img/nic_newlogo.png" alt="NIC Logo"> </a>
                        <p class="text-light">
                            Designed, Developed &amp; Hosted by <a style="text-decoration: none;color:#4bcfec" rel="sponsored" href="https://assam.nic.in/" target="_blank"> National Informatics Centre, Assam</a>
                        </p>
                        <p class="text-light">
                            Copyright &copy; 2021 Government of Assam
                        </p>
                    </div>
                </div>
            </div>

        </div>
        <div class="bg-black">
            <div class="container">
                <div class="col-12">
                    <div class="row addpad">
                        <div class="col-6">
                            <p class="fw-title visitor_count">Visitors: <span>200991</span></p>
                        </div>
                        <div class="col-6">
                            <p class="text-light" style="text-decoration: none; float:right;">
                                Content Maintained &amp; Managed by: <a style="text-decoration: none; float:right;" rel="sponsored" href="https://landrevenue.assam.gov.in/" target="_blank"> &nbsp;Revenue &amp; Disater Management Department</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer -->
    <!------Modal Start---------->
    <div class="modal fade" id="myModal12" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="panel" style="margin-top:0px">
                        <div class="panel-body">
                            <div class="ilrms_belowbanner">
                                <div class="container">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!------Modal End---------->
    <?php if (ENABLE_LOGIN_OTP == 1) {
        include "login_sms_otp.php";
    } ?>
    <script>
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

        function check_credentials() {
            // alert("in the check credential method");
            var uname = $('#appnum').val();
            var district = $('#district1').val();
            $.ajax({
                async: false,
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: 'check_credentials.php', // the url where we want to POST
                data: {
                    "uname": uname,
                    "dist_code": district
                }, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
            }).done(function(data) {
                // console.log(data);
                if (data.result) {
                    // alert("in the check credential method data type " + data.type);
                    if (data.type == 'both-mismatch-in-update') {
                        alert(data.msg);
                        location.reload();
                    } else if (data.type == 'password_not_changed') {
                        old_login();
                    } else if (data.type == 'password_changed') {
                        var salt = '<?php echo $_SESSION['salt']; ?>';
                        var bcrypt = dcodeIO.bcrypt;
                        var pass = $('#appnump').val();
                        var salt1 = bcrypt.genSaltSync(13);
                        var cred1 = bcrypt.hashSync(pass, data.msg); //dp
                        var prod = '<?= IS_PRODUCTION ?>';
                        if (prod == 0) {
                            cred1 = pass;
                        }
                        var hash = sha512(cred1 + salt);
                        //$('#hp').val(hash);
                        $('#appnump').val(hash);
                        // alert("hash is - "+ hash);
                        // alert("ok");
                        login();
                    }
                } else {
                    $('#password').addClass('has-error'); //add the error class to show red center input
                    $('#password').append('<div class="help-block red center">' + data.msg + '</div>'); // add the actual error message under our input                
                    return;
                }
            }).fail(function(xhr, err) {
                showWarningMessage('#ERROR-001 : Internal Server Issue... Kindly contact administrator');
            });
        }

        function old_login() {
            // alert("in the old login method");
            var enable_password_change = <?php echo ENABLE_PASSWORD_CHANGE; ?>;
            var formData = $('#login_form').serialize();
            $.ajax({
                async: false,
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: 'old_login.php', // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
            }).done(function(data) {
                // alert("in the old login method response data type : " +data.type);
                if (data.result) {
                    //old login success
                    if (data.type == 'require_user_map') {
                        //user is not mapped, old login success
                        userMap();
                    } else {
                        if (enable_password_change == 1) {
                            //password change
                            login();
                        } else {
                            var dataURL = 'getContent.php';
                            $('.modal-body').load(dataURL, function() {
                                $('#myModal12').modal('show');
                            });
                        }
                        //user mapped, old login success
                        // login();
                    }
                } else {
                    $('#password').addClass('has-error'); //add the error class to show red center input
                    $('#password').append('<div class="help-block red center">' + data.msg + '</div>'); // add the actual error message under our input                
                    return;
                }
            }).fail(function(xhr, err) {
                console.log(xhr);
                showWarningMessage('#ERROR-002 : Internal Server Issue... Kindly contact administrator');
            });
        }

        function userMap() {
            //alert('hai');
            var dataURL = 'getContent.php';
            $('.modal-body').load(dataURL, function() {
                $('#myModal12').modal('show');
            });
        }

        $('#login_form').on('submit', function(event) {
            event.preventDefault();
            $.blockUI({
                message: $('#displayBox'),
                css: {
                    border: 'none',
                    backgroundColor: 'transparent'
                }
            });
            //frontend validations      
            $('.help-block').hide();
            var district = $('#district1').val();
            if (district == "") {
                $("#capt").attr("src", "captcha-image.php?r=" + Math.random());
                var msg = "Please Select District";
                $('#district').addClass('has-error'); // add the error class to show red input
                $('#district').append('<div class="help-block  red center">' + msg + '</div>'); // add the actual error message under our input	
                $.unblockUI();
                return;
            }
            var uname = $('#appnum').val();
            if (uname == "") {
                $("#capt").attr("src", "captcha-image.php?r=" + Math.random());
                var msg = "Please Enter User-Name";
                $('#username').addClass('has-error'); //add the error class to show red center input
                $('#username').append('<div class="help-block red center">' + msg + '</div>'); // add the actual error message under our input
                $.unblockUI();
                return;
            }
            var appnump = $('#appnump').val();
            if (appnump == "") {
                $("#capt").attr("src", "captcha-image.php?r=" + Math.random());
                var msg = "Please Enter Password";
                $('#password').addClass('has-error'); //add the error class to show red center input
                $('#password').append('<div class="help-block red center">' + msg + '</div>'); // add the actual error message under our input
                $.unblockUI();
                return;
            }
            var captcha = $("#captchaInput").val();
            if (captcha == "") {
                $("#capt").attr("src", "captcha-image.php?r=" + Math.random());
                var msg = "Please Enter Captcha";
                $('#captcha').addClass('has-error'); //add the error class to show red center input
                $('#captcha').append('<div class="help-block red center">' + msg + '</div>'); // add the actual error message under our input
                $.unblockUI();
                return;
            }
            $.unblockUI();
            // checking whether password changed or not 
            // if changed getting the salt, and new login(done)
            // if not login with previous password, checking user map, password changed, new login 
            check_credentials();
        });

        function login() {
            var formData = $('#login_form').serialize();
            // alert('hai');       
            var enable_otp = <?php echo ENABLE_LOGIN_OTP; ?>;
            $.ajax({
                async: false,
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: 'process.php', // the url where we want to POST
                data: formData, // our data object
                dataType: 'json', // what type of data do we expect back from the server
                encode: true,
                beforeSend: function() {
                    $("#btnImageloading").html("Please wait logging in...");
                },
            }).done(function(data) {
                // console.log(data);      
                //***********************************************/
                //pasword change modal 
                if (data.password_change_flag == true) {
                    var dataURL = 'password_change.php';
                    $('.modal-body').load(dataURL, function() {
                        $('#myModal12').modal('show');
                    });
                    $.unblockUI();
                    return;
                }
                //***********************************************/

                if (!data.success) {
                    //alert(data.errors.captcha);
                    $.unblockUI();
                    $('.help-block').hide();
                    $("#btnImageloading").hide();
                    $('#login_form')[0].reset();
                    $("#capt").attr("src", "captcha-image.php?r=" + Math.random());
                    if (data.errors.db_row) {
                        $('#district').addClass('has-error'); // add the error class to show red input
                        $('#district').append('<div class="help-block  red center">' + data.errors.district + '</div>'); // add the actual error message under our input	
                    }
                    if (data.errors.district) {
                        $('#district').addClass('has-error'); // add the error class to show red input
                        $('#district').append('<div class="help-block  red center">' + data.errors.district + '</div>'); // add the actual error message under our input	
                    }
                    if (data.errors.uname) {
                        $('#username').addClass('has-error'); //add the error class to show red center input
                        $('#username').append('<div class="help-block red center">' + data.errors.uname + '</div>'); // add the actual error message under our input
                        //$('.help-block').hide();
                    }
                    if (data.errors.password) {
                        $('#password').addClass('has-error'); //add the error class to show red center input
                        $('#password').append('<div class="help-block red center">' + data.errors.password + '</div>'); // add the actual error message under our input
                        //$('.help-block').hide();
                    }
                    if (data.errors.db) {
                        $('#db').addClass('has-error'); // add the error class to show red center input
                        $('#db').append('<div class="help-block red center">' + data.errors.db + '</div>'); // add the actual error message under our input
                        //$('.help-block').hide();
                    }
                    if (data.errors.captcha) {
                        $('#captcha').addClass('has-error'); // add the error class to show red center input
                        $('#captcha').append('<div class="help-block red center">' + data.errors.captcha + '</div>'); // add the actual error message under our input
                        //$('.help-block').hide();
                    }
                    if (data.errors.con) {
                        $('#con').addClass('has-error'); //add the error class to show red center input
                        $('#con').append('<div class="help-block red center">' + data.errors.con + '</div>'); // add the actual error message under our input
                        //$('.help-block').hide();
                    }
                    if (data.errors.con) {
                        var dataURL = 'autheticate.php';
                        $('.modal-body').load(dataURL, function() {
                            $('#myModal12').modal('show');
                            setTimeout(function() {
                                $('#myModal12').modal('hide');
                            }, 3000);
                        });
                    }
                } else {

                    if(data.password_change_alert == true){
                        alert('Your password is about to expire. Please change it.');
                    }

                    if(data.password_change_force == true){
                        event.preventDefault();
                        var dataURL = 'password_resend_otp';
                            $('.modal-body').load(dataURL, function() {
                                $('#myModal12').modal('show');
                            });
                    }
                    //***********************************************/
                    if (enable_otp == 1) //login otp modal
                    {
                        var mobile = data.mobile_no;
                        // var lastChar = mobile.slice(-4);
                        if (data.password_change_flag == false) {
                            $('#div_mobile').html(mobile);
                            $('#myModalOtp').modal('show');
                            return;
                        }
                    } else {
                        if(data.password_change_force == true){
                            event.preventDefault();
                            var dataURL = 'password_resend_otp';
                                $('.modal-body').load(dataURL, function() {
                                    $('#myModal12').modal('show');
                                });
                        }else{
                            var dataURL = 'getContent.php';
                            $('.modal-body').load(dataURL, function() {
                                $('#myModal12').modal('show');
                            });
                        }
                       
                    }
                }
            }).fail(function(xhr, err) {
                showWarningMessage('#ERROR-003 : Internal Server Issue... Kindly contact administrator');
            });
        }

        $(document).ready(function() {
            $('#refreshCaptcha').click(function() {
                $("#capt").attr("src", "captcha-image.php?r=" + Math.random());
            })
        });

        /*$('#verifyOtp').click(function(){
            event.preventDefault();
            var otp = $('#mb_otp').val();
            if(otp == ""){
                showWarningMessage("Please Enter The OTP..!");
                return
            }
            $.ajax({
                type        : 'POST',
                url         : 'login_otp_verify.php',
                data        : {otp:otp},
                dataType    : 'json',
                encode      : true,
                beforeSend: function(){
                    $("#btnImageloading").html("Please wait logging in...");
                },
            }).done(function(data) {
                if(otp == data)
                {
                    showSuccessMessage('OTP-Verification successful');
                    $('#myModalOtp').modal('hide');
                    var dataURL = 'getContent.php';
                    $('.modal-body').load(dataURL,function(){
                      $('#myModal12').modal('show');
                    });

                }else{
                    showErrorMessage('otp-verification failed..!, Kindly Try Again..!')
                }                       
            }).fail(function(xhr, err) { 
                showWarningMessage('#ERROR-004 : Internal Server Issue... Kindly contact administrator');
            });
        });*/

        $('#verifyOtp').click(function() {
            event.preventDefault();
            var exp_module = <?php echo ENABLE_EXPIRY_PASSWORD_MODULE; ?>;
            var otp = $('#mb_otp').val();
            //var formData = new FormData("login_form");
            var formData = $('#login_form').serialize();
            //formData.append("otp", otp);
            if (otp == "") {
                showWarningMessage("Please Enter The OTP..!");
                return
            }
            $.ajax({
                type: 'POST',
                url: 'login_otp_verify.php',
                data: formData,
                dataType: 'json',
                encode: true,
                beforeSend: function() {
                    $("#btnImageloading").html("Please wait logging in...");
                },
            }).done(function(data) {
                //console.log(data);
                if (otp == data.otp) {
                    if (exp_module == 1) {
                        if (data.password_expired == true) {
                            $('#myModalOtp').modal('hide');
                            var dataURL = 'password_expired.php';
                            $('.modal-body').load(dataURL, function() {
                                $('#myModal12').modal('show');
                            });
                            $.unblockUI();
                            return;
                        } else {
                            showSuccessMessage('OTP-Verification successful');
                            $('#myModalOtp').modal('hide');
                            var dataURL = 'getContent.php';
                            $('.modal-body').load(dataURL, function() {
                                $('#myModal12').modal('show');
                            });
                        }
                    } else {
                        showSuccessMessage('OTP-Verification successful');
                        $('#myModalOtp').modal('hide');
                        var dataURL = 'getContent.php';
                        $('.modal-body').load(dataURL, function() {
                            $('#myModal12').modal('show');
                        });
                    }


                } else {
                    showErrorMessage('otp-verification failed..!, Kindly Try Again..!')
                }
            }).fail(function(xhr, err) {
                showWarningMessage('#ERROR-004 : Internal Server Issue... Kindly contact administrator');
            });
        });

        function forgotPWD() {
            event.preventDefault();
            var dataURL = 'forgot_password.php';
            // var dataURL = 'password_change.php';
            // var dataURL = 'password_resend_otp';
            $('.modal-body').load(dataURL, function() {
                $('#myModal12').modal('show');
            });
        }
    </script>
</body>

</html>