<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logged_on_user'])) {
    $user = $_SESSION['logged_on_user']; ?>
    <!doctype html>
    <html>
    <head>
        <title><?php echo $user['firstname'] ?>'s Account | Matcha</title>
        <?php include '../include/head.php'; ?>
    </head>
    <body>
        <header>
            <?php include '../include/header.php'; ?>
            <h1 class="account-settings-header">Matcha - <small>Manage Account</small></h1>
        </header>

        <div id="error-messages"></div>

        <section class="row account-management-page">
            <section class="col-sm-8 col-sm-offset-2">
                <form id="update_account" class="form-horizontal" method="post" enctype="application/x-www-form-urlencoded">

                    <h3 class="animated bounceIn">Password Reset</h3>
                    <div class="form-group">
                        <label for="password" class="col-sm-4 control-label">Current Password: <span class="require">*</span></label>
                        <div class="col-xs-8 col-xs-offset-2 col-sm-4 col-sm-offset-0">
                            <input type="password" class="form-control" name="curr_password" id="cpasswd" value="" placeholder="Current Password" autocomplete="true" required="true" />
                        </div>
                    </div>
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

                    <hr class="clearfix" />

                    <h3>Email Address</h3>
                    <div class="form-group">
                        <label for="email" class="col-sm-4 control-label">Email Address: <span class="require">*</span></label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" name="email" id="email" value="<?php echo $user['email'] ?>" placeholder="Email Address" maxlength="64" autocomplete="true" required="true" />
                        </div>
                    </div>
                    <div class="form-group submit_field">
                        <input type="submit" class="btn btn-success btn-lg" name="submit" value="Save" />
                    </div>

                </form>
            </section>
        </section>

    <?php include '../include/footer.php'; ?>

    <script type="text/javascript" src="/matcha/assets/js/account_settings.js"></script>

    </body>

    </html>

    <?php

} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}
    ?>
