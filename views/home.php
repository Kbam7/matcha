<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
    ?>
                <div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <h4>Hey there <?php echo $user['firstname'] ?>,</h4>
                    <p>Welcome to Matcha! Here you will find everything your heart desires.<br />But first,
                    your profile is not complete. Complete your profile to stand a better chance at finding someone you will like.</p>
                    <a class="btn btn-default" href="/matcha/views/profile_settings.php">Profile Settings</a>
                </div>
    <?php

        } ?>


    <div id="alert-messages"></div>
<!--
    <section class="jumbo-intro">
        <div class="jumbotron">
            <div class="container">
                <h2>Latest News!</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi at nisl euismod nisi facilisis bibendum.
                    Vivamus ultricies quam id nunc ullamcorper, id suscipit purus volutpat.
                    Donec porttitor massa vitae metus pharetra, vel viverra justo lobortis.</p>
                <p><a class="btn btn-primary btn-lg" href="#" role="button">Learn more &raquo;</a></p>
            </div>
        </div>
    </section> -->  <!-- /.jumbo-intro -->

    <section class="container">
        <div class="row">

            <div id="user_profiles">

            </div>

<!--            <div id="profile_source_images">

            </div>
-->
        </div> <!-- /.row -->
    </section> <!-- /.container -->

<?php include '../include/footer.php'; ?>

<script type="text/javascript" src="/matcha/assets/js/dashboard.js"></script>


<script type="text/javascript">
    // Event listeners for buttons
    var like_btn = document.querySelector('.like_btn');
    var block_btn = document.querySelector('.block_btn');

    if (like_btn) {
        like_btn.addEventListener('click', function() {
            debugger;
            var btn = this;
            var data = 'like=' + btn.id;
            ajax_post('/matcha/php/dashboard_utils.php', data, function(httpRequest) {
                var response = JSON.parse(httpRequest.responseText);
                displayAlertMessage(response.statusMsg);
                if (response.status === true) {
                    btn.innerText = btn.innerText === "Like" ? "Unlike" : "Like";
                }
            });
        });
    }
</script>


</body>

</html>

<?php

} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}
?>
