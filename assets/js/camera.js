"use strict";

var video, canvas, width, height;

// Start camera
activateUsersCamera();

function activateUsersCamera() {

    video = document.querySelector('#videoStream');
    canvas = document.querySelector('#canvas');
    width = 640;
    height = 480;

    if (video && canvas) {

        // Set initial size for video and canvas elements
        video.setAttribute('width', width);
        video.setAttribute('height', height);
        canvas.setAttribute('width', width);
        canvas.setAttribute('height', height);

        // Vendor specific aliases for 'navigator.getUserMedia'
        navigator.getUserMedia = (navigator.getUserMedia ||
                navigator.webkitGetUserMedia ||
                navigator.mozGetUserMedia ||
                navigator.msGetUserMedia ||
                navigator.oGetUserMedia)
            // Access the users webcam
        if (navigator.getUserMedia) {
            var constraints = {
                audio: false,
                video: {
                    width: {
                        ideal: 1280,
                        max: 1920
                    },
                    height: {
                        ideal: 720,
                        max: 1080
                    },
                    facingMode: "user"
                }
            };
            navigator.getUserMedia(constraints, displayStream, streamError);
        }

    }

    function displayStream(stream) {
        var overlayForm = document.querySelector('#overlayForm');

        // Vendor specific aliases for 'window.URL'
        window.URL = (window.URL || window.mozURL || window.webkitURL)

        // Make stream into URl
        video.src = window.URL.createObjectURL(stream);

        // Taking a photo
        overlayForm.addEventListener('submit', processWebcamPhoto);
    }

    function streamError(e) {
        displayError("<p class=\"warning\">There was an error accessing your webcam. " + e + "</p>");
    }

}


function processWebcamPhoto(e) {
    e.preventDefault();

    //  Draw image
    var context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, width, height);

    //  Add image to gallery
    var data = canvas.toDataURL('image/png');
    ajax_upload_webcam_image(data);
}


// Function for uploading webcam images
function ajax_upload_webcam_image(imgData) {
    var httpRequest,
        data,
        overlay;

    overlay = document.querySelector('input[name="overlay"]:checked');
    if (overlay) {
        overlay = '&overlay=' + overlay.value;
    } else {
        overlay = "";
    }

    data = "submit=1&image=" + imgData + overlay;

    httpRequest = new XMLHttpRequest();

    httpRequest.upload.addEventListener("progress", uploadProgress);
    httpRequest.upload.addEventListener("loadstart", uploadStarted);
    httpRequest.upload.addEventListener("load", uploadSuccess);
    httpRequest.upload.addEventListener("loadend", uploadFinished);
    httpRequest.upload.addEventListener("abort", uploadAborted);
    httpRequest.upload.addEventListener("error", uploadError);
    document.getElementById("cancelUploadBtn").addEventListener("click", cancelUpload);

    try {
        httpRequest.open("POST", "/matcha/php/webcam_image_upload.php", true);
        httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpRequest.send(data);
    } catch (e) {
        displayError("<p class=\"danger\">httpRequest.send error : " + e + "</p>");
    }

    function uploadProgress(event) {
        if (event.lengthComputable) {
            var percent = event.loaded / event.total * 100;
            document.getElementById("progress").setAttribute("value", percent.toFixed(1));
            document.querySelector("progress[value]").setAttribute("data-content", percent.toFixed(1) + "%");

        }
    }

    function uploadStarted(event) {
        document.querySelector("#imageUploadForm .image-upload-fields").className += " hidden absolute";
        var items = document.querySelector("#imageUploadForm").children;
        for (var item of items) {
            if (item.classList.contains("during-upload")) {
                item.setAttribute("style", "display: inline-block;");
            }
        }
    }

    function uploadSuccess() {
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                var response = JSON.parse(httpRequest.responseText);
                displayError(response.statusMsg);
                if (response.status === true) {
                    var data = 'displayGallery=1&username=' + response.username;
                    ajax_post('/matcha/php/displayUserGallery.php', data, function(httpResponse) {
                        var gallery = document.getElementById("newGallery");
                        if (gallery) {
                            gallery.innerHTML = httpResponse.responseText;
                            setupDeleteImageEvents(document.querySelectorAll(".delete_image_btn"));
                        }
                    });
                }
            };
        }
    }

    function uploadFinished(event) {
        document.querySelector("#imageUploadForm .image-upload-fields.hidden").className = "image-upload-fields";
        document.getElementById("progress").value = "0";
        document.querySelector("progress[value]").setAttribute("data-content", "");
        var items = document.querySelector("#imageUploadForm").children;
        for (var item of items) {
            if (item.classList.contains("during-upload")) {
                item.removeAttribute("style");
            }
        }
        /*
        var overlayForm = document.forms["overlayForm"];
        overlayForm.elements['submit'].setAttribute('disabled', true);
        overlayForm.elements['submit'].style.cursor = "auto";
        overlayForm.elements['submit'].title = "First Select an overlay image. . .";
        */
    }

    function uploadAborted(event) {
        displayError("<p class=\"info\">User aborted file upload or the connection was lost. ERROR : " + event.message + "</p>");
    }

    function uploadError(event) {
        displayError("<p class=\"danger\">An error has occured. ERROR : " + event.message + "</p>");
    }

    function cancelUpload() {
        httpRequest.abort();
    }

}
