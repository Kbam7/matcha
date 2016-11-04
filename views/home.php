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

    <div id="error-messages"></div>

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

    <section class="jumbo-intro">
        <div class="jumbotron">
            <div class="container">
                <h2>Latest News!</h2>
                <p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
                <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
            </div>
        </div>
    </section> <!-- /.jumbo-intro -->
    <section class="container">
        <div class="row">

            <?php include '../include/gallery.php'; ?>

        </div> <!-- /.row -->
    </section> <!-- /.container -->

<?php include '../include/footer.php'; ?>

</body>

</html>

<?php

} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}
?>
