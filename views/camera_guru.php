<?php

session_start();
if (isset($_SESSION['logged_on_user'])) {
    $user = $_SESSION['logged_on_user']; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Matcha | Home</title>
    <?php include '../include/head.php'; ?>
  </head>
  <body>
    <header>
        <?php include '../include/header.php'; ?>
    </header>

    <?php
        if ($user['profile_complete'] === 0) {
            // incomplete profile
            // display message with link to complete profile
            echo
            '
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <p>your profile is not complete, please complete your profile to stand a better chance at finding someone.</p>
                    <a class="btn btn-default" href="/matcha/views/profile.php">Profile Settings</a>
                </div>
            ';
        } ?>

    <div id="error-messages"></div>

    <?php include '../include/camagru_interface.php'; ?>

    <?php include '../include/footer.php'; ?>

    <script type="text/javascript" src="/matcha/assets/js/camera.js"></script>
    <script type="text/javascript" src="/matcha/assets/js/image_management.js"></script>

    </body>

    </html>

<?php

} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}
?>
