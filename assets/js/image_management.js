// Event for checking if an overlay is selected before submition
var overlayForm = document.querySelector("#overlayForm");
if (overlayForm) {
    for (var i = 0; i < overlayForm.elements.length; ++i) {
        overlayForm.elements[i].addEventListener('change', function() {
            var ovly_path = this.value;

            // Submit button
            overlayForm.elements['submit'].disabled = false;
            overlayForm.elements['submit'].style.cursor = "pointer";
            overlayForm.elements['submit'].title = "Take the photo";

            // overlay preview div
            if (document.querySelector('#videoStream')) {
                superimposeOverlayImage(ovly_path);
                window.addEventListener('resize', function() {
                    superimposeOverlayImage(ovly_path);
                });
            }

        });
    }

    function superimposeOverlayImage(path) {
        var vs, vs_ovly, w, h;

        vs = document.querySelector('#videoStream');
        vs_ovly = document.querySelector('.overlayPreview');
        w = vs.scrollWidth;
        h = vs.scrollHeight;

        //    w = Math.round(w + (0.1 * w));
        vs_ovly.style.width = w + "px";
        vs_ovly.style.height = h + "px";
        vs_ovly.style.background = "url('" + path + "') no-repeat center";
    }
}

// Submit event for image upload form
var imageUploadForm = document.querySelector("#imageUploadForm");
if (imageUploadForm) {
    imageUploadForm.addEventListener('submit', function(e) {
        e.preventDefault();

        ajax_user_upload_image("initial_upload", imageUploadForm);


        if (overlayForm) {

            overlayForm.removeEventListener('submit', processWebcamPhoto);
            // Submit event for finalising users upload
            overlayForm.addEventListener('submit', handleUserUpload);

            function handleUserUpload(e) {

                e.preventDefault();
                ajax_user_upload_image("overwrite_with_new", overlayForm);



                // Remove submit event for finalising users upload
                overlayForm.removeEventListener('submit', handleUserUpload);
                // Set default action back to form
                overlayForm.addEventListener('submit', processWebcamPhoto);

            }
        }
    });

    document.querySelector('.imageDisplay_inner .imageUploadSection h3')
        .addEventListener('click', function() {
            var section = document.querySelector('.imageDisplay_inner .imageUploadSection');
            if (section.classList.contains('collapsed')) {
                removeClass(section, 'collapsed');
                addClass(section, 'expanded');
            } else if (section.classList.contains('expanded')) {
                removeClass(section, 'expanded');
                addClass(section, 'collapsed');
            }
        });

}


/* ----------------[ FUNCTION : ajax_user_image_upload() ]------------------- */

// Function for uploading users images
function ajax_user_upload_image(uploadStatus, uploadForm) {
    var httpRequest = new XMLHttpRequest(),
        formdata = new FormData(uploadForm);



    // Adding custom fields to form data
    formdata.append("submit", "1");
    formdata.append("uploadStatus", uploadStatus);

    // Checks which phase of the upload we are in.
    if (uploadStatus === "overwrite_with_new") {

        // Get image title and src from form. Overlay has been selected and is in `formdata`
        var childList = document.querySelector(".user-upload-img").childNodes;
        for (var i = 0; i < childList.length; ++i) {
            if (childList[i].nodeName === "IMG" && childList[i].nodeType === 1) {
                formdata.append("imgTitle", childList[i].title);
                formdata.append("imgSrc", childList[i].src);
            }
        }
    }

    // Setting up listeners for upload process
    httpRequest.upload.addEventListener("progress", uploadProgress);
    httpRequest.upload.addEventListener("loadstart", uploadStarted);
    httpRequest.upload.addEventListener("load", uploadSuccess);
    httpRequest.upload.addEventListener("loadend", uploadFinished);
    httpRequest.upload.addEventListener("abort", uploadAborted);
    httpRequest.upload.addEventListener("error", uploadError);
    document.getElementById("cancelUploadBtn").addEventListener("click", cancelUpload);


    // Try send the data
    try {
        httpRequest.open("POST", "/matcha/php/user_image_upload.php", true);
        //        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send(formdata);
    } catch (e) {
        displayError("<p class=\"alert alert-danger\">ajax send error : " + e);
    }

    // Event : Progress update for the image
    function uploadProgress(event) {

        if (event.lengthComputable) {
            var percent = event.loaded / event.total * 100;
            document.getElementById("progress").setAttribute("value", percent.toFixed(1));
            document.querySelector("progress[value]").setAttribute("data-content", percent.toFixed(1) + "%");

        }
    }

    // Event : Start of image upload process
    function uploadStarted(event) {
        // display progress bar and cancel button
        addClass(addClass(document.querySelector("#imageUploadForm .image-upload-fields"), "hidden"), "absolute");

        var items = document.querySelector("#imageUploadForm").children;
        for (var item of items) {
            if (item.classList.contains("during-upload")) {
                item.setAttribute("style", "display: inline-block;");
            }
        }
    }

    // Event : Successfully uploaded image
    function uploadSuccess(event) {

        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                var response = JSON.parse(httpRequest.responseText);
                displayError(response.statusMsg);
                if (response.status === true) {
                    if (uploadStatus === "initial_upload") {
                        displayUserUpload(response); // Display the users uploaded image
                    } else if (uploadStatus === "overwrite_with_new") {
                        displayUploadInGallery(response); // Display image in gallery
                    }
                }
            };
        }; /* END -- httpRequest.onreadystatechange */

        function displayUserUpload(response) {
            var newImg = document.createElement("img");
            var userUploadImage = document.querySelector(".user-upload-img");
            if (userUploadImage && newImg) {
                //document.querySelector("#videoStream").className += " hidden absolute";

                // Adding classes using addClass() as a parameter
                addClass(addClass(document.querySelector("#videoStream"), "hidden"), "absolute");

                newImg.setAttribute('src', response.newFile);
                newImg.setAttribute('alt', response.imgTitle);
                newImg.setAttribute('title', response.imgTitle);
                userUploadImage.appendChild(newImg);
                userUploadImage.setAttribute("style", "display: inline-block;");
            };
        }; /* END -- displayUserUpload() */

        function displayUploadInGallery(response) {



            // hide user-upload-img
            var userUploadImage = document.querySelector(".user-upload-img");
            userUploadImage.removeAttribute("style");
            while (userUploadImage.children.length) {
                userUploadImage.removeChild(userUploadImage.children[0]);
            }

            // bring back video stream
            removeClass(removeClass(document.querySelector("#videoStream"), "hidden"), "absolute");

            // Display image in gallery
            var newImg = document.createElement("img");
            var gallery = document.getElementById("newGallery");
            if (gallery && newImg) {
                setTimeout(function() {
                    newImg.className = "gallery-img fade-in-left slow";
                    gallery.appendChild(newImg);
                    newImg.setAttribute('src', response.newFile += "?" + new Date().getTime()); // adds '?{current_timestamp}' to thr images src to force it to refresh.
                    newImg.setAttribute('alt', response.imgTitle);
                    newImg.setAttribute('title', response.imgTitle);
                }, 2000);
            }
        }
    } /* END -- uploadSuccess() */

    function uploadFinished(event) {
        // hide progress bar
        var items = document.querySelector("#imageUploadForm").children;
        for (var item of items) {
            if (item.classList.contains("during-upload")) {
                item.removeAttribute("style");
            }
        }
        document.getElementById("progress").value = "0";
        document.querySelector("progress[value]").setAttribute("data-content", "");

        // display form
        removeClass(removeClass(document.querySelector("#imageUploadForm .image-upload-fields"), "hidden"), "absolute");

    }

    function uploadAborted(event) {
        displayError("<p class=\"alert alert-warning\">User aborted file upload or the connection was lost. ERROR : " + event.message + "</p>");
    }

    function uploadError(event) {
        displayError("<p class=\"alert alert-danger\">An error has occured. ERROR : " + event.message + "</p>");
    }

    function cancelUpload() {
        httpRequest.abort();
    }
} /* END -- ajax_user_upload_image() */

/* ------------------[ END : ajax_user_image_upload() ]---------------------- */
