<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../php/profile_utils.php';

if (isset($_SESSION['logged_on_user'])) {
    // get user
    $user = $_SESSION['logged_on_user'];

    // get current sex_pref in order to preselect the checkbox
    $tmp = explode(',', $user['sex_pref']);

    // Sexual Preference  |  -1:none, 0:female, 1:male, 2:both
    $sp = -1;

    if (in_array('men', $tmp, true)) {
        $sp = 1;
    }
    if (in_array('women', $tmp, true)) {
        $sp = ($sp > 0 ? 2 : 0);
    } ?>
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
                        <button type="button" id="favorites" class="btn btn-primary" href="#bio_settings_tab" data-toggle="tab"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                            <div class="hidden-xs">Bio</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="stars" class="btn btn-default" href="#details_settings_tab" data-toggle="tab"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                            <div class="hidden-xs">Details</div>
                        </button>
                    </div>
                    <div class="btn-group" role="group">
                        <button type="button" id="following" class="btn btn-default" href="#pics_settings_tab" data-toggle="tab"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <div class="hidden-xs">Pictures</div>
                        </button>
                    </div>
                </div>

                <div class="well">
                  <div class="tab-content">
                    <div class="tab-pane fade in active" id="bio_settings_tab">

                        <h3>Bio</h3>

                        <form id="edit_bio_form" class="form-horizontal">
                          <div class="form-group">
                            <label for="edit_tags" class="col-sm-2 control-label">Tags :</label>
                            <div class="col-sm-10" id="tags_list">
                                <?php getUsersTags($user); ?>
                                <input type="text" class="form-control" name="tags_input" id="edit_tags" value="" placeholder="Add a tag" onkeydown="return !(event.keyCode==13)" autocomplete="on" />
                                <input type="hidden" id="tags_value" name="tags" />
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="edit_bio" class="col-sm-2 control-label">Bio : </label>
                            <div class="col-sm-10">
                              <textarea class="form-control" name="bio" id="edit_bio" rows="5" maxlength="300"><?php if (isset($user['bio'])) {
        echo $user['bio'];
    } ?></textarea>
                              <span class="label label-primary">MAX: 300 Characters</span>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                              <button type="submit" class="btn btn-success">Save</button>
                            </div>
                          </div>
                        </form>

                    </div>
                    <div class="tab-pane fade in" id="details_settings_tab">
                        <h3>Details</h3>

                        <form id="edit_details_form" class="form-horizontal">
                            <div class="form-group">
                              <label for="edit_firstname" class="col-sm-4 control-label">First Name :</label>
                              <div class="col-sm-6">
                                  <input type="text" class="form-control" name="firstname" id="edit_firstname" value="<?php echo $user['firstname'] ?>" maxlength="32" autocomplete="true" />
                              </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_lastname" class="col-sm-4 control-label">Last Name :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="lastname" id="edit_lastname" value="<?php echo $user['lastname'] ?>" maxlength="32" autocomplete="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_username" class="col-sm-4 control-label">User Name :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="username" id="edit_username" value="<?php echo $user['username'] ?>" maxlength="32" autocomplete="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="edit_age" class="col-sm-4 control-label">Age :</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="age" id="edit_age" value="<?php if (isset($user['age'])) echo $user['age'] ?>" maxlength="2" autocomplete="true" />
                                </div>
                            </div>

                            <hr  />

                            <div class="form-group">
                                <label for="edit_gender" class="col-sm-4 control-label">Gender :</label>
                                <div class="col-sm-6">
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" id="gender_male" value="male"

                                        <?php
                                            if (isset($user['gender']) && $user['gender'] === 'male') {
                                                echo 'checked="true" ';
                                            } ?>

                                         />
                                        Male
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="gender" id="gender_female" value="female"

                                            <?php
                                                if (isset($user['gender']) && $user['gender'] === 'female') {
                                                    echo 'checked="true" ';
                                                } ?>

                                         />
                                        Female
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="interested" class="col-sm-4 control-label">Interested in :</label>
                                <div class="col-sm-6">
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="male_interest" name="sex_pref_m"  value="men"
                                            <?php
                                                if ($sp > 0) {
                                                    echo 'checked="true" ';
                                                } ?>
                                         />
                                        Men
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="female_interest" name="sex_pref_f" value="women"
                                            <?php
                                                if ($sp === 0 || $sp === 2) {
                                                    echo 'checked="true" ';
                                                } ?>
                                        />
                                        Women
                                    </label>
                                    <label class="checkbox-inline">
                                        <input type="checkbox" id="no_interest" name="sex_pref_none" value="none"
                                            <?php
                                                if ($sp === -1) {
                                                    echo 'checked="true" ';
                                                } ?>
                                        />
                                        None
                                    </label>

                                </div>
                            </div>

                            <hr  />

                            <div class="form-group">
                                <label for="edit_location" class="col-sm-4 control-label">Location :</label>
                                <div class="col-sm-6">
                                    <div class="col-sm-10">
                                        <label for="edit_latitude" class="col-sm-4 control-label">Latitude</label>
                                        <input type="text" class="form-control" name="latitude" id="edit_latitude" placeholder="<?php if (isset($user['latitude'])) echo $user['latitude'] ?>" autocomplete="true" />
                                    </div>
                                    <div class="col-sm-10">
                                        <label for="edit_longitude" class="col-sm-4 control-label">Longitude</label>
                                        <input type="text" class="form-control" name="longitude" id="edit_longitude" placeholder="<?php if (isset($user['longitude'])) echo $user['longitude'] ?>" autocomplete="true" />
                                    </div>
                                </div>
                                <div class="col-sm-12" style="text-align: center; margin-top: 20px;">
                                    <button type="button" class="btn btn-info btn-lg" onclick="geoFindMe()">Get location</button>
                                    <div id="out"></div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </form>

                    </div>
                    <div class="tab-pane fade in" id="pics_settings_tab">
                        <a href="/matcha/views/camera_guru.php" class="btn btn-default btn-md pull-right">
                            <span class="fa fa-plus-square-o fa-2x"></span>
                            <span class="hidden-xs"><small> New Image</small></span>
                        </a>
                        <h3><?php echo $user['firstname'] ?>'s Photos</h3>
                        <aside id="profile_settings_gallery" class="col-md-12">
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
    <script type="text/javascript" src="/matcha/assets/js/profile_settings.js"></script>

    <script type="text/javascript">



    </script>


</body>

</html>

<?php

} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}

?>
