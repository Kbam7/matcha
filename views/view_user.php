<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../vendor/autoload.php';
use GraphAware\Neo4j\Client\ClientBuilder;

include '../php/profile_utils.php';

if (isset($_SESSION['logged_on_user'])) {
        if (isset($_GET['view_user']) && !empty($_GET['view_user'])){
            $user = $_GET['view_user'];

            $client = ClientBuilder::create()->addConnection('default', 'http://neo4j:123456@localhost:7474')->build();

            $results = $client->run('MATCH (u:User {username:{uname}}) RETURN u AS user;',
                                ['uname' => $user]);
            $user = $results->getRecord()->get('user')->values();
        }


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Matcha | View User</title>
    <?php include '../include/head.php'; ?>
    <link rel="stylesheet" href="/matcha/assets/css/profile.css" />
  </head>
  <body>
    <header>
        <?php include '../include/header.php'; ?>
    </header>

    <div id="alert-messages"></div>

    <section class="container">
        <div class="row">

            <div class="col-lg-8 col-md-offset-1 col-sm-10 col-sm-offset-1 col-xs-offset-0">
                <div class="card hovercard">
                    <div class="card-background">
                        <img src="<?php getProfilePictureSrc($user); ?>" alt="<?php echo $user['username'] ?>'s Profile Picture" />
                        <!-- http://lorempixel.com/850/280/people/9/ -->
                    </div>
                    <div class="useravatar">
                        <img src="<?php getProfilePictureSrc($user); ?>" alt="<?php echo $user['username'] ?>'s Profile Picture" />
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
                        <button type="button" id="favorites" class="btn btn-primary" href="#bio_view_tab" data-toggle="tab"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                            <div class="hidden-xs">Bio</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="stars" class="btn btn-default" href="#details_view_tab" data-toggle="tab"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                            <div class="hidden-xs">Details</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-default" href="#pics_view_tab" data-toggle="tab"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <div class="hidden-xs">Pictures</div>
                        </button>
                    </div>
                </div>

                <div class="well">
                  <div class="tab-content">
                    <div class="tab-pane fade in active" id="bio_view_tab">

                    <h3>Bio</h3>

                    <dl class="dl-horizontal">
                        <dt>Tags</dt>
                        <dd id="tags_list"><?php getUsersTags($user); ?></dd>
                        <hr class="col-sm-12 clearfix" />
                        <dt>About me</dt>
                        <dd><?php echo $user['bio'] ?></dd>
                    </dl>

                    </div>
                    <div class="tab-pane fade in" id="details_view_tab">
                        <h3>Details</h3>

                        <dl class="dl-horizontal">
                            <dt>First Name</dt>
                            <dd><?php echo $user['firstname'] ?></dd>
                            <dt>Last Name</dt>
                            <dd><?php echo $user['lastname'] ?></dd>
                            <dt>Userame</dt>
                            <dd><?php echo $user['username'] ?></dd>

                            <hr class="col-sm-12 clearfix" />

                            <dt>Gender</dt>
                            <dd><?php echo $user['gender'] ?></dd>
                            <dt>Interested in</dt>
                            <dd><?php echo $user['sex_pref'] ?></dd>

                            <hr class="col-sm-12 clearfix" />

                            <dt><u>Location</u></dt>
                            <dd> </dd>
                            <dt><small>Latitude : </small></dt>
                            <dd><small><?php echo $user['latitude'] ?></small></dd>
                            <dt><small>Longitude : </small></dt>
                            <dd><small><?php echo $user['longitude'] ?></small></dd>
                        </dl>

                    </div>
                    <div class="tab-pane fade in" id="pics_view_tab">
                        <a href="/matcha/views/camera_guru.php" class="btn btn-default btn-md pull-right">
                            <span class="fa fa-plus-square-o fa-2x"></span>
                            <span class="hidden-xs"><small> New Image</small></span>
                        </a>
                        <h3><?php echo $user['firstname'] ?>'s Photos</h3>
                        <aside id="profile_gallery" class="col-md-12">
                            <h3>Your Uploads</h3>
                    <!-- include gallery -->
                    <?php include '../php/displayUserGallery.php';
    displayUserGallery($user['username']); ?>
                            <div class="clearfix"></div>
                        </aside>
                    </div>
                  </div>
                </div>

            </div> <!-- /.col-lg-6 col-sm-6 -->

        </div> <!-- /.row -->
    </section> <!-- /.container -->

    <?php include '../include/footer.php'; ?>

    <script type="text/javascript" src="/matcha/assets/js/avatar-blur.js"></script>

    <script type="text/javascript">

    // Button effect for profile page
    $(document).ready(function() {
        $(".btn-pref .btn").click(function () {
            $(".btn-pref .btn").removeClass("btn-primary").addClass("btn-default");
            // $(".tab").addClass("active"); // instead of this do the below
            $(this).removeClass("btn-default").addClass("btn-primary");
        });

        // Cant remove tag on click
        $('#tags_list').on('click', 'span', function() {
            displayAlertMessage('<p class="alert alert-warning">Cannot delete tags on this page.<br />Go to <a href="/matcha/views/profile_settings.php">Profile Settings</a> to make changes.</p>')
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
