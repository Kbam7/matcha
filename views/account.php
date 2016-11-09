<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['logged_on_user'])) {
    $user = $_SESSION['logged_on_user']; ?>
    <!doctype html>
    <html>
    <head>
        <title><?php echo $user.firstname ?>'s Account | Matcha</title>
        <?php include '../include/head.php'; ?>
    </head>
    <body>
        <header>
            <?php include '../include/header.php'; ?>
            <h1>Matcha - <small>Manage Account</small></h1>
        </header>

        <div id="error-messages"></div>

        <section class="row account-management-page">
            <section class="col-12">
                <form id="manage_account" class="animate_label" method="post" enctype="application/x-www-form-urlencoded">

                    <div class="col-6 gutter-right-10 form-input">
                        <label class="input_label" for="password">Current Password: <span class="require">*</span></label>
                        <input type="password" name="password" id="passwd" value="" placeholder="Password" autocomplete="true" required="true" />
                    </div>
                    <div class="col-6 gutter-right-10 form-input">
                        <label class="input_label" for="password">New Password: <span class="require">*</span></label>
                        <input type="password" name="password" id="passwd" value="" placeholder="Password" autocomplete="true" required="true" />
                    </div>
                    <div class="col-6 gutter-left-10 form-input">
                        <label class="input_label" for="password2">Confirm Password: <span class="require">*</span></label>
                        <input type="password" name="password2" id="passwd2" value="" placeholder="Confirm Password" autocomplete="true" required="true" />
                    </div>
                    <div class="col-6 gutter-left-10 form-input">
                        <input type="submit" name="submit" value="OK" />
                    </div>

                </form>
            </section>
        </section>

    <?php include '../include/footer.php'; ?>

    <script type="text/javascript" src="/matcha/assets/js/account.js"></script>

    </body>

    </html>
