<section class="col-12" id="imageDisplay">
            <aside class="col-sm-5 overlays">
                <form id="overlayForm">
                    <!-- ADD PHP HERE TO POPULATE OVERLAY LIST. Save overlay to DB and allow users to upload more overlays -->
                    <div class="form-input">
                        <input class="success" type="submit" name="submit" title="First Select an overlay image. . ." value="Take Photo" disabled="true"/>
                    </div>
                    <div class="overlay_images">
                        <div class="form-input">
                            <label class="overlay_label" for="overlay_1"><img src="/matcha/assets/img/overlays/glasses.png" alt="Glasses" /></label>
                            <input type="radio" name="overlay" id="overlay_1" value="/matcha/assets/img/overlays/glasses.png" required="true" />
                        </div>
                        <div class="form-input">
                            <label class="overlay_label" for="overlay_2"><img src="/matcha/assets/img/overlays/whiskers.png" alt="Whiskers" /></label>
                            <input type="radio" name="overlay" id="overlay_2" value="/matcha/assets/img/overlays/whiskers.png" required="true" />
                        </div>
                        <div class="form-input">
                            <label class="overlay_label" for="overlay_3"><img src="/matcha/assets/img/overlays/unicorn.png" alt="Unicorn" /></label>
                            <input type="radio" name="overlay" id="overlay_3" value="/matcha/assets/img/overlays/unicorn.png" required="true" />
                        </div>
                        <div class="form-input">
                            <label class="overlay_label" for="overlay_4"><img src="/matcha/assets/img/overlays/text/kewl.png" alt="Kewl Text" /></label>
                            <input type="radio" name="overlay" id="overlay_4" value="img/overlays/text/kewl.png" required="true" />
                        </div>
                        <div class="form-input">
                            <label class="overlay_label" for="overlay_5"><img src="/matcha/assets/img/overlays/text/uhno.png" alt="Uh No Text" /></label>
                            <input type="radio" name="overlay" id="overlay_5" value="/matcha/assets/img/overlays/text/uhno.png" required="true" />
                        </div>
                    </div>

                </form> <!-- /#overlayForm -->
            </aside> <!-- /.overlays -->
            <div class="col-sm-5 imageDisplay_inner">
                <div class="col-12">
                    <div class="user-upload-img"></div>

                    <div class="overlayPreview"></div>

                    <video autoplay="true" id="videoStream"></video>

                    <canvas id="canvas"></canvas>
                </div>
                <hr class="clearfix" />
                <div class="col-sm-12 imageUploadSection collapsed">
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
            <aside id="newGallery" class="col-sm-12">
                <h3>Your Uploads</h3>

            </aside>
        </section> <!-- /#imageDisplay_inner -->
