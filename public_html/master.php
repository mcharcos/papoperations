<?php
if (!session_id()) {
    @session_start();
}
include_once $_SERVER['DOCUMENT_ROOT'] . '/../server/user_actions.php';
//session_start(); 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title><?php echo $pagetitle ?></title>

        <!-- Bootstrap -->
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto" rel="stylesheet"> 
        <link href="/css/font-awesome.css" rel="stylesheet" />
        <link href="/css/bootstrap.css" rel="stylesheet" />
        <link href="/css/jquery.bxslider.css" rel="stylesheet" />
        <link href="/css/bootstrap-select.min.css" rel="stylesheet" />
        <link href="/css/bootstrap-datetimepicker.css" rel="stylesheet" />
        <link href="/css/rangeslider.css" rel="stylesheet" />
        <link href="/css/rating.css" rel="stylesheet" />
        <link href="/css/style.css" rel="stylesheet" />
        <link href="/css/developer.css" rel="stylesheet" />
        <link href="/css/responsive.css" rel="stylesheet" />

<!--<script src="/js/jquery-3.1.1.min.js"></script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="/js/bootstrap.min.js"></script>
        <script src="/js/moment-with-locales.js"></script>
        <script src="/js/bootstrap-datetimepicker.min.js"></script>
        <script src="/js/bootstrap-select.min.js"></script>
        <script src="/js/jquery.bxslider.min.js"></script>
        <script src="/js/responsive-tabs.js"></script>
        <script src="/js/rangeslider.min.js"></script>
        <script src="/js/rating.js"></script>
        <script src="/js/custom.js"></script> 
        <script src="/js/bootstrap-filestyle.js"></script>
        <script type="text/javascript" src="/js/crypto.js"></script>
        <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/js/additional-methods.min.js"></script>
        <script src='https://www.google.com/recaptcha/api.js'></script>
        <!--<script type="text/javascript" src="/js/search_filter_utils.js"></script>-->


        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <?php
        if (isset($pageheader)) {
            echo $pageheader;
        }
        ?>
    </head>

    <?php if ($_SERVER['REQUEST_URI'] == '/') { ?>
        <body class="home-page">
        <?php } else { ?>
        <body class="">
        <?php }
        ?>
        <?php include($_SERVER['DOCUMENT_ROOT'] . '/header.php'); ?>

        <div id="page_content" class="content">
            <?php echo $pagecontent; ?>
        </div>


        <?php include($_SERVER['DOCUMENT_ROOT'] . '/footer.php'); ?>

        <script>
            $(document).ready(function () {
                $('.bxslider').bxSlider({
                    mode: 'fade'
                });
                $('#datetimepicker1').datetimepicker({
                    sideBySide: false
                });
            });
            $(":file").filestyle({icon: false});
            var clicked;
            function validateUpdateUserFormOnSubmit(theForm) {
                console.log('here');
                if (clicked == "cancel") {
                    return false;
                }
                var reason = "";

                if (theForm.elements.namedItem("first_name").value !== "") {
                    var first_name = theForm.elements.namedItem("first_name").value;
                } else {
                    reason += "\n first name is missed in form.";
                }
                if (theForm.elements.namedItem("last_name").value !== "") {
                    var last_name = theForm.elements.namedItem("last_name").value;
                } else {
                    reason += "\n last name is missed in form.";
                }

                if (theForm.elements.namedItem("email").value !== "") {
                    var email = theForm.elements.namedItem("email").value;
                } else {
                    reason += "\n email is missed in form.";
                }

                if (theForm.elements.namedItem("address").value !== "") {
                    var address = theForm.elements.namedItem("address").value;
                } else {
                    reason += "\n address input is missed in form.";
                }

                /* var formData = new FormData($(this)[0]); */
                //var formData = new FormData(theForm); 
                var formData = 'submitted=change&first_name=' + first_name + '&last_name=' + last_name + '&email=' + email + '&address=' + address;

                // Check if the password change is requested
                var isexpanded = document.getElementById("pwd_contract_icon").getAttribute("isexpanded");

                if (isexpanded == 1) {
                    // check if repeat password matches password
                    if (theForm.elements.namedItem("newpassword").value !== "" && theForm.elements.namedItem("passwordrepeat").value !== "") {
                        var pwd = theForm.elements.namedItem("newpassword").value;
                        var pwdrepeat = theForm.elements.namedItem("passwordrepeat").value;
                        if (pwd.localeCompare(pwdrepeat)) {
                            alert("New password check is wrong. Make sure that the new password and the repeat password match");
                            return false;
                        }

                        var oldpwd = theForm.elements.namedItem("oldpassword").value;
                        var oldpwd_hash = sha1(oldpwd);

                        var sessionpwd_hash = "<?php if (isset($_SESSION['pwd'])) $_SESSION['pwd']; ?>";

                        if (oldpwd_hash.localeCompare(sessionpwd_hash)) {
                            alert("Old password is wrong");
                            return false;
                        }
                        var pwd_hash = sha1(pwd);

                        formData += "&password_hash=" + pwd_hash + "&old_password_hash=" + oldpwd_hash;
                    } else {
                        alert("Password cannot be empty");
                        return false;
                    }
                }

                if (reason !== "") {
                    alert("Some fields need correction:\n" + reason);
                } else {
                    $.ajax({
                        type: "POST",
                        url: "/user/handlers/update_user_handler.php",
                        data: formData,
                        cache: false,
                        success: function (res) {
                            alert("Request for user data update was sent successfully");
                            $('#modal-user-profile').modal('hide');
                        }
                    });
                }
                return false;
            }
        </script>

        <!-- Modal -->
        <div class="modal fade model-form" id="signin-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="logn-page">
                        <h2>sign in</h2>
                        <form method="post" id="myform">
                            <div class="msgbox"></div>
                            <div class="alert alert-danger alert-dismissable" id="al_msg" style="display: none">
                                <a class="close" href="#" data-dismiss="alert"> × </a>
                                <p></p>
                            </div>
                            <div class="model-form-div">
                                <label>Login</label>
                                <input type="text" id="uid" name="uid" placeholder="User Name" />
                            </div>
                            <div class="model-form-div">
                                <label>Password</label>
                                <input type="password" id="pwd" name="pwd"  placeholder="password" />
                            </div>
                            <div class="model-form-btn">
                                <button type="submit">LOGIN</button>
                                <a href="" data-toggle="modal" data-target="#forgot-password-modal" data-dismiss="modal" id="reset_password">Forget Password</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade model-form" id="signup-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="logn-page">
                        <h2>Sign up</h2>
                        <form method="post" id="myregisterform" class="form-validate-jquery">
                            <div class="msgbox"></div>
                            <div class="alert alert-danger alert-dismissable" id="alert_msg" style="display: none">
                                <a class="close" href="#" data-dismiss="alert"> × </a><p></p></div>
                            <div class="alert alert-success alert-dismissable" id="su_msg" style="display: none"><p></p>
                                <a class="close" href="#" data-dismiss="alert"> × </a><p></p></div>
                            <div class="model-form-div">
                                <label>User ID</label>
                                <input type="text" id="username" name="username" placeholder="User Name" required="" onkeydown="checkchar_username(event)"/>
                            </div>
                            <div class="model-form-div">
                                <label>First Name</label>
                                <input type="text" id="first_name" name="first_name" required="" placeholder="First Name" />
                            </div>
                            <div class="model-form-div">
                                <label>Last Name</label>
                                <input type="text" id="last_name" name="last_name"  placeholder="Last Name" />
                            </div>
                            <div class="model-form-div">
                                <label>Email</label>
                                <input type="text" id="email" name="email" placeholder="Email" />
                            </div>
                            <div class="model-form-div">
                                <label>Address</label>
                                <input type="text" id="address" name="address" placeholder="Address" />
                            </div>
                            <div class="model-form-div">
                                <label>Password</label>
                                <input type="password" id="password" name="password" placeholder="password" />
                            </div>
                            <div class="model-form-div">
                                <label>RE-Password</label>
                                <input type="password" id="repeat_password" name="repeat_password" placeholder="password" />
                            </div>
                            <div class="g-recaptcha" data-sitekey="6LdhxRkUAAAAADWVubEBW_lYdV6B38XIq8oL-9vd" data-callback="recaptchaCallback"></div>
                            <input type="hidden" class="hiddenRecaptcha required" name="hiddenRecaptcha" id="hiddenRecaptcha">

                            <div class="model-form-btn" style="margin-top: 10px;">
                                <button type="submit" id="user_submit" value="register">Sign Up</button>
                                <a href="" data-toggle="modal" data-target="#signin-modal" data-dismiss="modal" id="login_anchor">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade model-form" id="modal-logout" tabindex="-1" role="dialog" aria-labelledby="modal-login-label" aria-hidden="true">
            <div class="modal-dialog  login-model-dialogue logout-dialogue">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h3 class="modal-title" id="modal-login-label">Logout</h3>
                    </div>
                    <div class="modal-body">
                        <form>
                            <p>Thank you mate, I hope you did not messed much with the observatory.</p>
                            <div class="model-form-btn">
                                <a href="/" id="okay_submit" value="Okay">See you!</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade model-form" id="forgot-password-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="logn-page">
                        <h2>Reset Password</h2>
                        <form method="post" id="reset_password_form" class="login-form">
                            <div class="alert alert-danger alert-dismissable" id="error_msg" style="display: none">
                                <a class="close" href="#" data-dismiss="alert"> × </a><p></p></div>
                            <div class="alert alert-success alert-dismissable" id="success_msg" style="display: none">
                                <a class="close" href="#" data-dismiss="alert"> × </a><p></p></div>
                            <div class="model-form-div">
                                <label>Username</label>
                                <input type="text" id="userid" name="userid" placeholder="User Name"  />
                            </div>
                            <div class="model-form-div">
                                <label>Email</label>
                                <input type="text" id="useremail" name="email" placeholder="Email"  />
                            </div>
                            <div class="model-form-btn">
                                <button type="submit">Reset</button>
                                <a href="" data-toggle="modal" data-target="#signin-modal" data-dismiss="modal" id="login_reset">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <?php
        if (isset($_SESSION['uid']) || isset($_SESSION['pwd'])) {
            $dbdata = get_user_info();
        }
        ?>
        <div class="modal fade model-editprofile" id="modal-user-profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <div class="editprofile-page">
                        <h2>Update <?= $username ?><br><span style="font-size: 12px;"><font color="orangered" size="+1"><tt><b>*</b></tt></font> indicates a required field</span></h2>

                        <p></p>
                        <div class="food-register-box">
                            <form action="javascript:;" onsubmit="return validateUpdateUserFormOnSubmit(this);">
                                <div class="msgbox"></div>
                                <div class="alert alert-danger alert-dismissable" id="alert_msg" style="display: none">
                                    <a class="close" href="#" data-dismiss="alert"> × </a><p></p></div>
                                <div class="alert alert-success alert-dismissable" id="su_msg" style="display: none"><p></p>
                                    <a class="close" href="#" data-dismiss="alert"> × </a><p></p></div>
                                <div class="food-register-form">
                                    <h3 class="food-title"><big>01</big>Basic <span>Information</span></h3>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-wrapper">
                                            <label>First Name<font color="orangered" size="+1"><tt><b>*</b></tt></font></label>
                                            <input name="first_name" type="text" placeholder="First Name" maxlength="100" size="25" value="<?= $dbdata['first_name'] ?>" />
                                        </div>
                                    </div>	
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-wrapper">
                                            <label>Last Name<font color="orangered" size="+1"><tt><b>*</b></tt></font></label>
                                            <input name="last_name" type="text" maxlength="100" size="25" value="<?= $dbdata['last_name'] ?>" />
                                        </div>

                                    </div>	
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-wrapper">
                                            <label>Email Address<font color="orangered" size="+1"><tt><b>*</b></tt></font></label>
                                            <input name="email" type="text" maxlength="100" size="25" value="<?= $dbdata['email'] ?>" />
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-wrapper">
                                            <label>Address<font color="orangered" size="+1"><tt><b>*</b></tt></font></label>
                                            <input name="address" type="text" maxlength="100" size="25" value="<?= $dbdata['address'] ?>" />
                                        </div>
                                    </div>
                                </div>

                                <div class="food-register-form" >
                                    <img src="/resources/icons2/expand_arrow.png" isexpanded=0 id="pwd_contract_icon" onclick="showorhide_pwd_section_modal()" width="30px" height="30px"/>
                                    <h3 class="food-title" onclick="showorhide_pwd_section_modal()"><big>02</big>Password <span>Information</span></h3>
                                    <div id="password_section_modal" style="display:none;">
                                        <div class="full-div">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="input-wrapper">
                                                    <label>Old Password</label>
                                                    <input type="password" placeholder="Old Password" name="oldpassword" id="oldpassword"/>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="input-wrapper">
                                                    <label>Old Password</label>
                                                    <input type="password" name="newpassword" placeholder="New Password" id="newpassword">
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <div class="input-wrapper">
                                                    <label>Brand</label>
                                                    <input type="password" name="passwordrepeat" placeholder="Repeat Password" id="passwordrepeat">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="btm-btn-div">
    <!--                                        <input type="submit" class="btn green-orange" data-dismiss="modal" onclick="clicked = 'cancel'" name='submitted' value="cancel" />
                                        <input type="submit" class="btn green-orange" onclick="clicked = 'change'" name='submitted' value="change"/>-->
                                        <button type="submit" data-dismiss="modal" onclick="clicked = 'cancel'" name='submitted' value="cancel">Cancel </button>
                                        <button type="submit" onclick="clicked = 'change'" name='submitted' value="change">Change</button>
                                    </div>
                                </div>
                            </form>
                            <div class="food-register-form">         
                                <form action="/user/handlers/update_user_picture_handler.php" method="post" enctype="multipart/form-data"> 
                                    <h3 class="food-title"><big>03</big>Profile picture <span>Information</span></h3>                               
                                    <div class="full-div">                                        
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="input-wrapper">
                                                <label>File Upload<font color="orangered" size="+1"><tt><b>*</b></tt></font></label>
                                                <input type="file" name="fileToUpload" class="filestyle" data-icon="false">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btm-btn-div">
                                        <button type="submit" name='submitted' value="Upload">Submit </button>
                                    </div>        
                                </form>
                            </div>
                        </div>	

                    </div>
                </div>
            </div>
        </div>

        <div id="dialog-confirm" style="display:none;"></div>
        <link rel="stylesheet" href="/css/alertify.core.css" />
        <link rel="stylesheet" href="/css/alertify.default.css" id="toggleCSS" />
        <script src="/js/alertify.min.js"></script>

        <?php
        if (isset($_GET['confirm']) && ($_GET['confirm'] == 1)) {
            ?>
            <script>
                                                $('#modal-confirm_message').modal('show');
            </script>
        <?php }
        ?>
            
            
            <!-- Modal -->
<div class="modal fade model-form" id="signin-modal-blog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <div class="logn-page">
                <h2>sign in</h2>
                <form method="post" id="mylogin-blog">
                    <div class="msgbox"></div>
                    <div class="alert alert-danger alert-dismissable" id="al_msg" style="display: none">
                        <a class="close" href="#" data-dismiss="alert"> × </a>
                        <p></p>
                    </div>
                    <div class="model-form-div">
                        <label>Login</label>
                        <input type="text" id="uid" name="uid" placeholder="User Name" />
                    </div>
                    <div class="model-form-div">
                        <label>Password</label>
                        <input type="password" id="pwd" name="pwd"  placeholder="password" />
                    </div>
                    <div class="model-form-btn">
                        <button type="submit">LOGIN</button>
                        <a href="" data-toggle="modal" data-target="#forgot-password-modal" data-dismiss="modal" id="reset_password">Forget Password</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>    
    $("#mylogin-blog").validate({
        rules: {
            uid: "required",
            pwd: {
                required: true,
                //                        minlength: 8
            },
        },
        messages: {
            uid: "Please enter your username.",
            pwd: {
                required: "Please provide a password.",
                //                        minlength: "Your password must be at least 8 characters long"
            },
        },
        submitHandler: function (form) {
            var uid = $('#uid').val();
            var pwd = $('#pwd').val();
            var url = '/auth/check_login.php';

            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'JSON',
                data: {uid: uid, pwd: pwd}, //--> send email and password
                success: function (data) {
                    console.log(data);
                    if (data.res == 0) {
                        $('#al_msg').show();
                        $('#al_msg p').html('You have entered an invalid Username or Password.');
                        setTimeout(function () {
                            $("#al_msg").hide();
                        }, 5000);
                        return false;
                    }
                    else if (data.res == 1) {
                        window.location.reload();
//                        window.location = window.location.href;
                        tabManage("food_tab");
                    } else if (data.res == 2) {
                        $('#al_msg').show();
                        $('#al_msg p').html("Please confirm your Email First and then try to login.");
                        setTimeout(function () {
                            $("#al_msg").hide();
                        }, 5000);
                        return false;
                    }
                }
            });
        }
    });
</script>
        <script type="text/javascript">
            function recaptchaCallback() {
                $('#hiddenRecaptcha').valid();
            }
            // validate signup form on keyup and submit
            $("#myregisterform").validate({
                ignore: ".ignore",
                rules: {
                    username: "required",
                    first_name: "required",
                    last_name: "required",
                    password: {
                        required: true,
                        minlength: 8
                    },
                    repeat_password: {
                        required: true,
                        minlength: 8,
                        equalTo: "#password"
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    address: "required",
                    hiddenRecaptcha: {
                        required: function () {
                            if (grecaptcha.getResponse() == '') {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    }
                },
                messages: {
                    first_name: "Please enter your firstname.",
                    last_name: "Please enter your lastname.",
                    username: "Please enter your username.",
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 8 characters long"
                    },
                    repeat_password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 8 characters long",
                        equalTo: "Please enter the same password as above"
                    },
                    email: "Please enter a valid email address",
                    address: "Please enter your address",
                    hiddenRecaptcha: "The captcha is required and can\'t be empty"
                },
                submitHandler: function (form) {
                    var username = $('#username').val();
                    var first_name = $('#first_name').val();
                    var last_name = $('#last_name').val();
                    var email = $('#email').val();
                    var address = $('#address').val();
                    var password = $('#password').val();
                    var repeat_pwd = $('#repeat_password').val();
                    var password_hash = sha1(password);
                    var formData = 'submitted=add&username=' + username + '&first_name=' + first_name + '&last_name=' + last_name + '&email=' + email + '&address=' + address + '&password_hash=' + password_hash;
                    $.ajax({
                        type: 'POST',
                        url: "/user/handlers/add_user_handler.php",
                        dataType: 'JSON',
                        data: formData, //--> send email and password
                        success: function (response) {
                            if (response.res == 0) {
                                //                                                         document.getElementById("loading").style.display = "none";
                                $('#su_msg').show();
                                $('#su_msg p').html('Email is sent to your email ID.');
                                setTimeout(function () {
                                    $("#su_msg").hide();
                                    $("#modal-reistration").modal('hide');
                                    $('#myregisterform')[0].reset();
                                }, 4000);
                                //                                                        return false;
                            } else if (response.res == 2) {
                                $('#alert_msg').show();
                                $('#alert_msg p').html('A user already exists with your chosen userid. Please try another.');
                                setTimeout(function () {
                                    $("#alert_msg").hide();
                                    $("#modal-reistration").modal('hide');
                                }, 5000);
                            }

                        }
                    });
                }
            });
            $("#myform").validate({
                rules: {
                    uid: "required",
                    pwd: {
                        required: true,
                        //                        minlength: 8
                    },
                },
                messages: {
                    uid: "Please enter your username.",
                    pwd: {
                        required: "Please provide a password.",
                        //                        minlength: "Your password must be at least 8 characters long"
                    },
                },
                submitHandler: function (form) {
                    var uid = $('#uid').val();
                    var pwd = $('#pwd').val();
                    var url = '/auth/check_login.php';
                    
                    $.ajax({
                        type: 'POST',
                        url: url,
                        dataType: 'JSON',
                        data: {uid: uid, pwd: pwd}, //--> send email and password
                        success: function (data) {
                            console.log(data);
                            if (data.res == 0) {
                                $('#al_msg').show();
                                $('#al_msg p').html('You have entered an invalid Username or Password.');
                                setTimeout(function () {
                                    $("#al_msg").hide();
                                }, 5000);
                                return false;
                            }
                            else if (data.res == 1) {
                                window.location = "/user/";
                            } else if (data.res == 2) {
                                $('#al_msg').show();
                                $('#al_msg p').html("Please confirm your Email First and then try to login.");
                                setTimeout(function () {
                                    $("#al_msg").hide();
                                }, 5000);
                                return false;
                            }
                        }
                    });
                }
            });
            $("#reset_password_form").validate({
                rules: {
                    userid: "required",
                    email: {
                        required: true,
                        email: true
                    },
                },
                messages: {
                    userid: "Please enter your username.",
                    email: "Please enter a valid email address",
                },
                submitHandler: function (form) {
                    var userid = $('#userid').val();
                    var useremail = $('#useremail').val();
                    var url = '/auth/handlers/forgot_pwd_handler.php';
                    $.ajax({
                        type: 'POST',
                        url: url,
                        dataType: 'JSON',
                        data: {userid: userid, useremail: useremail}, //--> send email and password
                        success: function (data) {
                            if (data.res == 0) {
                                console.log(data.res);
                                $('#success_msg').show();
                                $('#success_msg p').html('Email is sent to you Account. Please check it.');
                                setTimeout(function () {
                                    $("#success_msg").hide();
                                    $("#forgot-password-modal").modal('hide');
                                    $('#reset_password_form')[0].reset();
                                }, 4000);
                                return false;
                            } else {
                                $('#error_msg').show();
                                $('#error_msg p').html(data.msg);
                                setTimeout(function () {
                                    $("#error_msg").hide();
                                    $("#forgot-password-modal").modal('hide');
                                    $('#reset_password_form')[0].reset();
                                }, 7000);
                                return false;
                            }
                        }
                    });
                }
            });

            function reset() {
                $("#toggleCSS").attr("href", "/css/alertify.default.css");
                alertify.set({
                    labels: {
                        ok: "OK",
                        cancel: "Cancel"
                    },
                    delay: 5000,
                    buttonReverse: false,
                    buttonFocus: "ok"
                });
            }

            $("#logout_btn").click(function () {
                $('#alertify').css('display', 'none');
                $('#dialog-confirm').show();
                reset();
                $('#alertify').show();
                alertify.confirm("Are you sure you want to logout?", function (e) {
                    if (e) {
                        $.ajax({
                            type: 'POST',
                            url: "/auth/logout_session.php",
                            dataType: 'JSON',
                            success: function (response) {
                                if (response.res == 0) {
                                    $('#modal-logout').modal('show');
                                }
                                else {
                                }
                            }
                        });
                    }
                });
                return false;
            });


            function showorhide_pwd_section_modal() {

                var isexpanded = document.getElementById("pwd_contract_icon_modal").getAttribute("isexpanded");
                var element = document.getElementById("password_section_modal");
                $("#password_section_modal").slideToggle();

                if (isexpanded == 1) {
                    // hide password widgets
                    document.getElementById("pwd_contract_icon_modal").setAttribute("src", "/resources/icons2/expand_arrow.png");
                    // element.setAttribute("style", "display:none;");
                    isexpanded = 0;
                } else {
                    // show password widgets
                    document.getElementById("pwd_contract_icon_modal").setAttribute("src", "/resources/icons2/contract_arrow.png");
                    // element.setAttribute("style", "");
                    isexpanded = 1;
                }
                document.getElementById("pwd_contract_icon_modal").setAttribute("isexpanded", isexpanded);

                return false;
            }
            var letterNumber = /^[0-9a-zA-Z]+$/;
            $("#reset_password").click(function () {
                $('#modal-reistration').css('overflow', 'auto');
                $('body').css('overflow', 'hidden');
            });
            $("#login_reset").click(function () {
                $('#modal-reistration').css('overflow', 'auto');
                $('body').css('overflow', 'hidden');
            });
            $("#login_anchor").click(function () {
                $('#modal-reistration').css('overflow', 'auto');
                $('body').css('overflow', 'hidden');
            });
            $('#pwd').keypress(function (e) {
                if (e.which == '13') {
                    validLogin();
                }
            });


            function resetPassword() {
                var userid = $('#userid').val();
                var useremail = $('#useremail').val();
                var url = '/auth/handlers/forgot_pwd_handler.php';
                $("#reset_password .msgbox").html("");
                var error = "";
                if ($("#userid").val() == "") {
                    error += "<p>You must enter username.</p>";
                }
                if ($("#useremail").val() == "") {
                    error += "<p>You must enter email.</p>";
                }

                if (error != "") {
                    $('#error_msg').show();
                    $('#error_msg').html(error);
                    setTimeout(function () {
                        $("#error_msg").hide();
                    }, 5000);
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        dataType: 'JSON',
                        data: {uid: userid, useremail: useremail}, //--> send email and password
                        success: function (data) {
                            if (data != '') {
                                $('#success_msg').show();
                                $('#success_msg').html('Email is sent to you Account. Please check it.');
                                setTimeout(function () {
                                    $("#success_msg").hide();
                                    $("#modal-forgot-password").modal('hide');
                                    $('#reset_password')[0].reset();
                                }, 4000);
                                return false;
                            }
                        }
                    });
                }
            }

            function checkchar_username(evt) {

                // No problem with the special keys such as return carriage, shift, caps lock
                if (evt.keyCode == 8 || evt.keyCode == 9 || evt.keyCode == 13 ||
                        evt.keyCode == 16 || evt.keyCode == 17 || evt.keyCode == 18 || evt.keyCode == 20 || evt.keyCode == 224 ||
                        (evt.keyCode >= 112 && evt.keyCode <= 121)   // FX keys
                        ) {
                    return false;
                }
                if (evt.keyCode == 32) {
                    alert("Spaces are not allowed for username.");

                    return false;
                }

                if (!String.fromCharCode(evt.keyCode).match(letterNumber)) {
                    alert("Special characters are not allowed for username.");

                    return false;
                }

                return false;

            }
        </script>
    </body>
</html>