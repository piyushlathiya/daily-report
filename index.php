<?php
include_once('include/application_top.php');

$user_logged_in = $_core->isUserLoggedIn();
if ($user_logged_in) {
    $_core->redirect('activity.php');
    exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>cReport-Login</title>
            <link rel="icon" type="image/png" href="<?php echo $_core->getSkinUrl('images/fevicon.png') ?>" />
            <link rel="stylesheet" href="<?php echo $_core->getSkinUrl('style.css') ?>" type="text/css" media="all" />
            <script type="text/javascript" src="<?php echo $_core->getUrl('js/jQuery.min.js') ?>"></script>
            <script type="text/javascript">
                function validate(){
                    //	Validation
                    var err="";
                    if(document.login_form.email.value==''){
                        err += "Please Enter E-mail Address";
                    }
                    if(document.login_form.password.value==''){
                        if (err == "") { err += "Please Enter Password"; }
                        else { err += " and Password \n"; }
                    }

                    //	Norify Error
                    if(err){
                        err = "Please insert following fields.\n\n" + err;
                        alert(err);
                        return false;
                    }
                    return true;
                }
            </script>
    </head>
    <body class="home-login">
        <div class="wrapper emplyoee-login">
            <div class="page">
                <div class="main">
                    <div class="form">
                        <div class="starter">
                            <h1>Sign in</h1>
                            <img src="<?php echo $_core->getSkinUrl('images/log.png') ?>" alt="logo" />
                        </div>
                        <form id="login" name="login_form" method="post" action="login.php" onsubmit="return validate();">
                            <div class="field-row clear">
                                <div class="label"><label for="login_username">Email</label></div>
                                <input type="text" name="email" id="login_username" class="field required" title="Enter your email address" />
                            </div>
                            <div class="field-row">
                                <div class="label"><label for="login_password">Password</label></div>
                                <input type="password" name="password" id="login_password" class="field required" title="Enter your password" />
                            </div>
                            <div class="submit">
                                <input class="button" type="submit" name="submit" value="Sign in" />  
                            </div>
                            <p class="inst"><a href="#">Forget Password ?</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php include_once('include/application_bottom.php'); ?>