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

    <div id="error-messages"></div>

    <section class="col-md-12" id="imageDisplay">
        <aside class="col-sm-5 overlays">
            <form id="overlayForm">
                <!-- ADD PHP HERE TO POPULATE OVERLAY LIST. Save overlay to DB and allow users to upload more overlays -->
                <div class="form-input">
                    <input class="success" type="submit" name="submit" title="Take Photo" value="Take Photo" />
                </div>
                <div class="overlay_images">
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_1"><img src="/matcha/assets/img/overlays/text/uhno.png" title="Uh No" alt="Uh No Text" /></label>
                        <input type="radio" name="overlay" id="overlay_1" value="../assets/img/overlays/text/uhno.png" />
                    </div>
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_2"><img src="/matcha/assets/img/overlays/whiskers.png" title="Whiskers" alt="Whiskers" /></label>
                        <input type="radio" name="overlay" id="overlay_2" value="../assets/img/overlays/whiskers.png" />
                    </div>
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_3"><img src="/matcha/assets/img/overlays/unicorn.png" title="Unicorn" alt="Unicorn" /></label>
                        <input type="radio" name="overlay" id="overlay_3" value="../assets/img/overlays/unicorn.png" />
                    </div>
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_4"><img src="/matcha/assets/img/overlays/text/kewl.png" title="Kewl" alt="Kewl Text" /></label>
                        <input type="radio" name="overlay" id="overlay_4" value="../assets/img/overlays/text/kewl.png" />
                    </div>
                    <div class="form-input">
                        <label class="overlay_label" for="overlay_5"><img src="/matcha/assets/img/overlays/glasses.png" title="Glasses" alt="Glasses" /></label>
                        <input type="radio" name="overlay" id="overlay_5" value="../assets/img/overlays/glasses.png" />
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
                        <div class="form-group">
                            <input type="file" name="userfile" id="file" required="true" />
                        </div>
                        <div class="form-group">
                            <label for="userUpload_ImgTitle" class="control-label">Image Title:</label>
                            <input type="text" name="imgTitle" id="userUpload_ImgTitle" placeholder="Image Title:" required="true" />
                        </div>
                        <div class="form-group">
                          <label for="userUpload_ImgDesc" class="control-label">Image Description :</label>
                          <textarea class="form-control" name="imgDesc" id="userUpload_ImgDesc" placeholder="Image description here" rows="3" ></textarea>
                        </div>
                        <div class="form-group">
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
<?php include '../php/displayUserGallery.php';
    displayCamagruUserGallery($user['username']); ?>
        </aside>
    </section> <!-- /#imageDisplay_inner -->

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
