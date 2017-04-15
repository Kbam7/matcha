<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../php/auth.php';

// Neo4j
require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

// Set up DB connection
$client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && isset($_POST['email']) && !empty($_POST['email'])) {
    // Generate hash
    $uniqueHash = md5(uniqid());
    // Match user to email address provided, and set hash property
    $result = $client->run('MATCH (u:User {email:{email}}) SET u.hash = {hash} RETURN u', ['email' => $_POST['email'], 'hash' => $uniqueHash]);
    $record = $result->getRecord();
    if (!empty($record)) {
        $user = $record->get('u')->values();
        // hash updated send email, return success and info mesage
        sendPwdResetEmail($user, $uniqueHash);

        $statusMsg = '<div class="alert alert-info">Email sent to the email address provided. Check for a link in your inbox.</div>';
        $response = array('status' => true, 'statusMsg' => $statusMsg);
    } else {
        // email address does not exist
        $statusMsg = '<div class="alert alert-info">The email address provided is not registered.</div>';
        $response = array('status' => false, 'statusMsg' => $statusMsg);
    }
    // Echo response and kill page
    die(json_encode($response));
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password']) && !empty($_POST['password'])
                && isset($_POST['password2']) && !empty($_POST['password2']) && isset($_POST['emailAddress']) && !empty($_POST['emailAddress'])) {
    // Compare passwords
    if ($_POST['password'] !== $_POST['password2']) {
        $statusMsg = '<div class="alert alert-warning">User password <b>NOT</b> updated.<br />Passwords do not match.</div>';
        $response = array('status' => false, 'statusMsg' => $statusMsg);
    } else {
        // Set new password
        $result = $client->run('MATCH (u:User {email: {email}}) SET u.password = {new_pwd} RETURN u;',
                                    ['email' => $_POST['emailAddress'], 'new_pwd' => hash('whirlpool', $_POST['password'])]);
        $record = $result->getRecord();
        $user = $record->get('u')->values();

        if (auth($user['username'], $user['password'], true)) {
            $_SESSION['logged_on_user'] = $user;
            session_regenerate_id(true);
            $statusMsg = '<div class="alert alert-success">User password is updated.</div>';
            $response = array('status' => true, 'statusMsg' => $statusMsg);
        }
    }
    die(json_encode($response));
}

// send email
function sendPwdResetEmail($user, $uniqueHash)
{
    $subject = 'Notification | Changed Password';
    $message = '
        Hey '.$user['firstname'].' '.$user['lastname'].',

        We have received a password reset request.
        To set a new password, please follow the link below.

        Click this link to verify the password reset:
        http://localhost:8080/matcha/views/reset_password.php?email='.$user['email'].'&hash='.$uniqueHash.'

        Please contact site admin if you suspect you have been hacked.
    ';

    $headers = 'From:noreply@matcha.co.za'."\r\n";
    mail($user['email'], $subject, $message, $headers);
}

?>
<!doctype html>
<html>
<head>
    <title>Reset Password | Matcha</title>
    <?php include '../include/head.php'; ?>
</head>
<body>
    <header>
        <?php include '../include/header.php'; ?>
    </header>

    <div id="alert-messages"></div>

    <section class="row account-management-page">
        <section class="col-sm-8 col-sm-offset-2">
            <h3>Password Reset</h3>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
    //  User has clicked link in the email?>

    <form id="resetPassword_password" class="form-horizontal" method="post" enctype="application/x-www-form-urlencoded">
        <p>Enter and confirm your new password.</p>
        <div class="form-group">
            <label for="new_password" class="col-sm-4 control-label">New Password:</label>
            <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-0">
                <input type="password" name="password" id="passwd" value="" class="form-control" placeholder="New Password" autocomplete="true" />
            </div>
        </div>
        <div class="form-group">
            <label for="password2" class="col-sm-4 control-label">Confirm New Password:</label>
            <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-0">
                <input type="password" name="password2" id="passwd2" value="" class="form-control" placeholder="Confirm New Password" autocomplete="true" />
            </div>
        </div>
        <div class="form-group">
            <input type="hidden" name="emailAddress" id="emailAddr" value="<?php echo $_GET['email'] ?>" />
        </div>
        <hr class="clearfix" />
        <div class="form-group submit_field">
            <input type="submit" class="btn btn-success btn-lg" name="submit" value="Send" />
        </div>
    </form>

<?php

} else { // Default to form for entering email address?>

    <form id="resetPassword_email" class="form-horizontal" method="post" enctype="application/x-www-form-urlencoded">
        <p>Enter the email address you use for your account and you will get an email with a reset link.</p>
        <div class="form-group">
            <label for="email" class="col-sm-4 control-label">Email Address: <span class="require">*</span></label>
            <div class="col-sm-6">
                <input type="email" class="form-control" name="email" id="email" value="" placeholder="Email Address" maxlength="64" autocomplete="true" required="true" />
            </div>
        </div>
        <hr class="clearfix" />
        <div class="form-group submit_field">
            <input type="submit" class="btn btn-success btn-lg" name="submit" value="Send" />
        </div>
    </form>

<?php

} ?>

        </section>
    </section>

<?php include '../include/footer.php'; ?>

<script type="text/javascript">
    var errorDiv = document.getElementById("alert-messages");
    var resetForm = document.querySelector('#resetPassword_email');
    if (resetForm) {
        // Get all input elements
        var inputs = resetForm.elements;

        // Add 'blur' event listener for error messages
        for (var i = 0; i < inputs.length; ++i) {
            var item = inputs[i];
            // dont add for submit button
            if (item.type !== "submit") {
                // item is not `submit`
                item.addEventListener('blur', function(e) {
                    // remove any error messages
                    while (errorDiv.children.length) {
                        errorDiv.removeChild(errorDiv.children[0]);
                    }
                    // and then validate
                    validate_input(this, this.value, this.type);
                });
            }
        }
        // Submit event
        resetForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var email = encodeURIComponent(document.querySelector('#email').value);
            var data = "submit=1&email=" + email;

            ajax_post('/matcha/views/reset_password.php', data, function (httpRequest) {
                var response = JSON.parse(httpRequest.responseText);
                displayAlertMessage(response.statusMsg);
                if (response.status === true) {
                    resetForm.innerHTML = response.statusMsg;
                }
            });
        });
    }

    var new_pwdForm = document.querySelector('#resetPassword_password');
    if (new_pwdForm) {
        // Get all input elements
        var pwd_inputs = new_pwdForm.elements;

        // Add 'blur' event listener for error messages
        for (var i = 0; i < pwd_inputs.length; ++i) {
            var input = pwd_inputs[i];
            // dont add for submit button
            if (input.type !== "submit") {
                // is item a `password`
                if (input.type === "password") {
                    input.addEventListener('blur', function(e) {
                        // Check if passwords do not match
                        if ((this.name === "password" && this.value !== new_pwdForm.elements.namedItem("passwd2").value) ||
                            (this.name === "password2" && this.value !== new_pwdForm.elements.namedItem("passwd").value)) {
                            displayAlertMessage("<div class=\"alert alert-warning\">Your passwords do not match</div>");
                        } else {
                            // passwords match, remove any error messages
                            while (errorDiv.children.length) {
                                errorDiv.removeChild(errorDiv.children[0]);
                            }
                            // and then validate
                            validate_input(this, this.value, this.type);
                        }
                    });
                }
            }
        }
        // Submit event
        new_pwdForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var pw1 = encodeURIComponent(document.querySelector('#passwd').value);
            var pw2 = encodeURIComponent(document.querySelector('#passwd2').value);
            var email = encodeURIComponent(document.querySelector('#emailAddr').value);

            var data = "submit=1&password=" + pw1 + "&password2=" + pw2 + "&emailAddress=" + email;

            ajax_post('/matcha/views/reset_password.php', data, function (httpRequest) {
                var response = JSON.parse(httpRequest.responseText);
                displayAlertMessage(response.statusMsg);
                if (response.status === true) {
                    new_pwdForm.innerHTML = response.statusMsg;
                    // setTimeout to navigate to dashboard.php
                    setTimeout(function() {
                        window.location = "/matcha/views/dashboard.php";
                    }, 5000);
                }
            });
        });
    }


</script>

</body>

</html>
