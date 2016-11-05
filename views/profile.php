<?php
session_start();
if (isset($_SESSION['logged_on_user'])) {
    $user = $_SESSION['logged_on_user']; ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Matcha | Profile</title>
    <?php include '../include/head.php'; ?>
    <link rel="stylesheet" href="/matcha/assets/css/profile.css" />
  </head>
  <body>
    <header>
        <?php include '../include/header.php'; ?>
    </header>

    <div id="error-messages"></div>

    <section class="container">
        <div class="row">

            <div class="col-lg-8 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-offset-0">
                <div class="card hovercard">
                    <div class="card-background">
                        <img class="card-bkimg" alt="" src="http://lorempixel.com/100/100/people/9/">
                        <!-- http://lorempixel.com/850/280/people/9/ -->
                    </div>
                    <div class="useravatar">
                        <img alt="" src="http://lorempixel.com/100/100/people/9/">
                    </div>
                    <div class="card-info">
                        <span class="card-title"><?php echo $user['firstname'].' '.$user['lastname']; ?></span>
                        <dl class="dl-horizontal">
                            <dt>Fame</dt>
                            <dd><?php echo $user['fame'] ?></dd>
                        </dl>
                    </div>
                </div>
                <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
                    <div class="btn-group" role="group">
                        <button type="button" id="favorites" class="btn btn-primary" href="#tab1" data-toggle="tab"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                            <div class="hidden-xs">Bio</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="stars" class="btn btn-default" href="#tab2" data-toggle="tab"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                            <div class="hidden-xs">Details</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-default" href="#tab3" data-toggle="tab"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <div class="hidden-xs">Pictures</div>
                        </button>
                    </div>
                </div>

                <div class="well">
                  <div class="tab-content">
                    <div class="tab-pane fade in active" id="tab1">

                        <h3>Bio</h3>

                        <dl class="dl-horizontal">
                            <dt>Tags</dt>
                            <dd><?php echo $user['tags'] ?></dd>
                          <dt>About me</dt>
                          <dd><?php echo $user['bio'] ?><?php print_r($user); ?></dd>
                        </dl>

                    </div>
                    <div class="tab-pane fade in" id="tab2">
                        <h3>Details</h3>

                        <dl class="dl-horizontal">
                            <dt>First Name</dt>
                            <dd><?php echo $user['firstname'] ?></dd>
                            <dt>Last Name</dt>
                            <dd><?php echo $user['lastname'] ?></dd>
                            <dt>Gender</dt>
                            <dd><?php echo $user['gender'] ?></dd>
                            <dt>Interested in</dt>
                            <dd><?php echo $user['sex_pref'] ?></dd>
                            <hr />
                            <dt>Location : </dt>
                            <dd><?php echo $user['GPS_pos'] ?></dd>
                            <p><button onclick="geoFindMe()">Show my location</button></p>
                            <div id="out"></div>
                        </dl>

                    </div>
                    <div class="tab-pane fade in" id="tab3">
                      <h3><?php echo $user['firstname'] ?>'s Photos</h3>
                      <?php echo 'get pics string from db, split it to get paths then, using each path, display each of the images here.' ?>
                    </div>
                  </div>
                </div>

            </div> <!-- /.col-lg-6 col-sm-6 -->

        </div> <!-- /.row -->
    </section> <!-- /.container -->

    <?php include '../include/footer.php'; ?>

    <script type="text/javascript">

    // Button effect for profile page
    $(document).ready(function() {
        $(".btn-pref .btn").click(function () {
            $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
            // $(".tab").addClass("active"); // instead of this do the below
            $(this).removeClass("btn-default").addClass("btn-primary");
        });
    });

    </script>


</body>

</html>

<?php

} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}
?>
