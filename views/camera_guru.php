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
                    <h4>Hey there '.$user['firstname'].',</h4>
                    <p>Welcome to Matcha! Here you will find everything your heart desires.<br />But first,
                    your profile is not complete, complete your profile to stand a better chance at finding someone you will like.</p>
                    <a class="btn btn-default" href="/matcha/views/profile.php">Profile Settings</a>
                </div>
            ';
        } ?>

    <div id="error-messages"></div>

    <section class="col-md-12" id="imageDisplay">
        <aside class="col-sm-5 overlays">
            <form id="overlayForm">
                <!-- ADD PHP HERE TO POPULATE OVERLAY LIST. Save overlay to DB and allow users to upload more overlays -->
                <div class="form-input">
                    <input class="success" type="submit" name="submit" title="First Select an overlay image. . ." value="Take Photo" disabled="true"/>
                </div>
                <div class="overlay_images">
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_1"><img src="/matcha/assets/img/overlays/glasses.png" alt="Glasses" /></label>
                        <input type="radio" name="overlay" id="overlay_1" value="../assets/img/overlays/glasses.png" required="true" />
                    </div>
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_2"><img src="/matcha/assets/img/overlays/whiskers.png" alt="Whiskers" /></label>
                        <input type="radio" name="overlay" id="overlay_2" value="../assets/img/overlays/whiskers.png" required="true" />
                    </div>
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_3"><img src="/matcha/assets/img/overlays/unicorn.png" alt="Unicorn" /></label>
                        <input type="radio" name="overlay" id="overlay_3" value="../assets/img/overlays/unicorn.png" required="true" />
                    </div>
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_4"><img src="/matcha/assets/img/overlays/text/kewl.png" alt="Kewl Text" /></label>
                        <input type="radio" name="overlay" id="overlay_4" value="../assets/img/overlays/text/kewl.png" required="true" />
                    </div>
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_5"><img src="/matcha/assets/img/overlays/text/uhno.png" alt="Uh No Text" /></label>
                        <input type="radio" name="overlay" id="overlay_5" value="../assets/img/overlays/text/uhno.png" required="true" />
                    </div>
                </div>

            </form> <!-- /#overlayForm -->
        </aside> <!-- /.overlays -->
        <div class="col-sm-7 imageDisplay_inner">
            <div class="col-md-12">
                <div class="user-upload-img"></div>

                <div class="overlayPreview"></div>

                <video autoplay="true" id="videoStream"></video>

                <canvas id="canvas"></canvas>
            </div>
            <hr class="clearfix" />
            <div class="col-md-12 imageUploadSection collapsed">
                <h3>Upload an Image</h3>
                <form id="imageUploadForm" method="post" enctype="multipart/form-data">
                    <progress class="during-upload" id="progress" max="100" value="0">
                    </progress>

                    <div class="image-upload-fields">
                        <p>Select image to upload:</p>
                        <div class="form-input">
                            <input type="file" name="userfile" id="file" required="true" />
                        </div>
                        <div class="form-input">
                            <label class="input_label" for="userUpload_ImgTitle">Image Title:</label>
                            <input type="text" name="imgTitle" id="userUpload_ImgTitle" placeholder="Image Title:" required="true" />
                        </div>
                        <div class="form-input">
                            <input class="btn border border-3 white rounded hover-text-blue text-22" type="submit" name="submit" value="Upload Image" />
                        </div>
                    </div>
                    <button type="button" name="cancelUpload" id="cancelUploadBtn" class="during-upload btn icon l round danger">
                        <i class="fa fa-ban" aria-hidden="true" title="Cancel Upload" ></i>
                    </button>
                </form> <!-- /#imageUploadForm -->
            </div>
            <hr class="clearfix" />
        </div> <!-- /.imageDisplay_inner -->
        <aside id="newGallery" class="col-md-8">
            <h3>Your Uploads</h3>
            <!-- include gallery -->
<?php include '../include/displayUserGallery.php';
    displayUserGallery($user['username']); ?>
        </aside>
    </section> <!-- /#imageDisplay_inner -->

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
          </div>
          <div class="modal-body">
            ...
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>

    <?php include '../include/footer.php'; ?>

    <script type="text/javascript" src="/matcha/assets/js/camera.js"></script>
    <script type="text/javascript" src="/matcha/assets/js/image_upload.js"></script>

    </body>

    </html>

<?php

} else {
    $_SESSION['errors'] = array('Please log in before accessing this website');
    header('Location: ../index.php');
}
?>
