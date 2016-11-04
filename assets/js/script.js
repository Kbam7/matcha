"use strict";

// Timeout variables for error messages
var addClass_timeout, removeError_timeout;

window.onload = function() {

        // some drag event code
        document.ondragover = function(evt) {
            evt = evt || window.event;
            var x = evt.pageX,
                y = evt.pageY;

            console.log(x, y);
        }

        // Select Error div for observation
        var errorDiv = document.getElementById("error-messages");
        if (errorDiv) {
            observeErrors(errorDiv);
        }

        /* --- Global events --- */

        // Add animation to input elements
        var formInputList = document.querySelectorAll('input[type=text], input[type=password], input[type=email]');
        for (var i = 0; i < formInputList.length; ++i) {
            formInputList[i].addEventListener('click', function() {
                addClass(this.previousElementSibling, "fade-in-up");
                addClass(this.previousElementSibling, "medium");
            });

            formInputList[i].addEventListener('blur', function() {
                var item = this;
                removeClass(item.previousElementSibling, "fade-in-up");
                addClass(item.previousElementSibling, "fade-out-down");
                setTimeout(function() {
                    removeClass(item.previousElementSibling, "fade-out-down");
                    removeClass(item.previousElementSibling, "medium");
                }, 750);
            });
        }

        /* --- NON-Global events --- */

        /*
            // Events for new user login
            var loginForm = document.querySelector("#loginForm");
            if (loginForm) {
                // Get all input elements
                var inputs = loginForm.elements;
                // Add 'blur' event listener
                for (var i = 0; i < inputs.length; ++i) {
                    var item = inputs[i];
                    if (item.type !== "submit") { // dont add for submit button
                        item.addEventListener('blur', function(e) {
                            validate_input(this, this.value, this.type);
                        });
                    }
                };
            }
        */

        // Events for new user form
        var createUserForm = document.querySelector("#createUserForm");
        if (createUserForm) {
            // Get all input elements
            var inputs = createUserForm.elements;

            // Add 'blur' event listener for error messages
            for (var i = 0; i < inputs.length; ++i) {
                var item = inputs[i];
                // dont add for submit button
                if (item.type !== "submit") {
                    if (item.type === "password") {
                        item.addEventListener('blur', function(e) {
                            // Check if passwords do not match
                            if ((this.name === "password" && this.value !== createUserForm.elements.namedItem("passwd2").value) ||
                                (this.name === "password2" && this.value !== createUserForm.elements.namedItem("passwd").value)) {
                                displayError("<p class=\"alert alert-warning\">Your passwords do not match</p>");
                            } else {
                                // remove error messages
                                while (errorDiv.children.length) {
                                    errorDiv.removeChild(errorDiv.children[0]);
                                }
                                validate_input(this, this.value, this.type);
                            }
                        });
                    } else {
                        item.addEventListener('blur', function(e) {
                            // remove error messages
                            while (errorDiv.children.length) {
                                errorDiv.removeChild(errorDiv.children[0]);
                            }
                            validate_input(this, this.value, this.type);
                        });
                    }
                }
            };
            createUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                createUser(createUserForm);
            });
        }

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



    }
    /*------------------------------------------------------------------------*/
    /* ---------------------[ FUNCTION DEFINITIONS ]------------------------- */
    /*------------------------------------------------------------------------*/

// Add class to element
function addClass(el, className) {
    if (!el.classList) {
        el.className = className;
    } else if (!el.classList.contains(className)) {
        el.classList.add(className);
    }
    return el;
}

// Remove class from element
function removeClass(el, className) {
    if (el.classList.contains(className)) {
        el.classList.remove(className);
    }
    return el;
}

// A lightweight function for ajax POST
function ajax_post(url, data, callback) {
    var httpRequest = new XMLHttpRequest();
    httpRequest.addEventListener("error", function(event) {
        console.log("An error has occured. ERROR : " + event.message);
    });
    httpRequest.addEventListener("readystatechange", function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            callback(httpRequest);
        }
    });
    httpRequest.open("POST", url, true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(data);

}



/* ----------------[ FORM FUNCTIONS ]------------------- */

function createUser(form) {
    var fname = encodeURIComponent(document.getElementById("fname").value);
    var lname = encodeURIComponent(document.getElementById("lname").value);
    var uname = encodeURIComponent(document.getElementById("uname").value);
    var email = encodeURIComponent(document.getElementById("email").value);
    var passwd = encodeURIComponent(document.getElementById("passwd").value);

    var data = "submit=1" +
        "&fname=" + fname +
        "&lname=" + lname +
        "&uname=" + uname +
        "&email=" + email +
        "&passwd=" + passwd;

    //    validate_input(form);

    ajax_post("/matcha/php/create_acc.php", data, function(httpRequest) {
        //        displayError(httpRequest.responseText);
        var response = JSON.parse(httpRequest.responseText);

        if (response.status === true) {
            displayError(response.statusMsg + " <p class=\"alert alert-info\">Redirecting to login page . . .</p>");
            console.log(response.record);
            debugger;
            /*
                        setTimeout(function() {
                            window.location = "/matcha/index.php";
                        }, 10000);*/
        } else {
            displayError(response.statusMsg);
        }
    });
}

// Form validation
function validate_input(input, value, type) {
    var result = true;

    if (value === "" && input.required) {
        displayError("<p class=\"alert alert-info\">'" + input.name + "' cannot be empty.</p>");
        return false;
    }
    if (type === "text") {
        // validate text input for names
        if (input.name === "firstname" || input.name === "lastname") {
            result = /^([A-Z][a-z]+([ ]?[a-z]?['-]?[A-Z][a-z]+)*)$/.test(value);
            if (result === false) {
                displayError("<p class=\"alert alert-warning\">'" + input.name + "' is invalid. Please try format is as follows: 'John Doe' or 'John-Doe'.<br />Names need to start with a CAPITAL letter.</p>");
                return false;
            }
        } else if (input.name === "username") {
            result = /^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,24}$/.test(value);
            if (result === false) {
                displayError("<p class=\"alert alert-warning\">'" + input.name + "' is invalid. Please try format is as follows: 'john1' 'John_Doe' or 'John.Doe3'.<br />MAX: 24 Characters</p>");
                return false;
            }
        }
    } else if (type === "email") {
        result = /^([\w\.]+)@([\w\.]+)\.(\w+)/.test(value);
        if (result === false) {
            displayError("<p class=\"alert alert-warning\">'" + input.name + "' is invalid. Please try format is as follows: 'john@doe.com'</p>");
            return false;
        }
    } else if (type === "password") {
        result = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/.test(value);
        if (result === false) {
            displayError("<p class=\"alert alert-warning\">'" + input.name + "' is invalid. Password must contain at least 8 characters, and consist of atleast 1 uppercase letter, 1 lowercase letter, and 1 number. Can contain special characters.</p>");
            return false;
        }
    }
    return (true);
}



/* ----------------[ ERROR FUNCTIONS ]------------------- */

// Function to display errors
function displayError(errMsg) {

    var errDiv = document.getElementById("error-messages");
    clearTimeout(addClass_timeout);
    clearTimeout(removeError_timeout);
    if (errDiv) {
        errDiv.innerHTML = errMsg;
        var msgs = errDiv.childNodes;
        for (var msg of msgs) {
            addClass(msg, "animate");
            addClass(msg, "swing");
        }
    }

    // Remove html. i.e. Get text only
    var tmp = document.createElement("div");
    tmp.innerHTML = errMsg;
    errMsg = tmp.textContent || tmp.innerText || "No error message found.";

    console.log(errMsg);
}

function observeErrors(errorDiv) {

    // Vendor specific aliases for 'MutationObserver'
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

    // create an observer instance
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            var newNodes = mutation.addedNodes;
            //        console.log(newNodes);
            addClass_timeout = setTimeout(function() {
                for (let i = 0; i < newNodes.length; ++i) {
                    addClass(newNodes[i], "scale-out");
                    //    newNodes[i].className += " scale-out";
                }
                removeError_timeout = setTimeout(function() {
                    while (errorDiv.children.length) {
                        errorDiv.removeChild(errorDiv.children[0]);
                    }
                }, 2000);
            }, 30000);
        })
    });

    // configuration of the observer:
    var config = {
        attributes: true,
        childList: true,
        characterData: true
    };

    // pass in the target node, as well as the observer options
    observer.observe(errorDiv, config);
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
