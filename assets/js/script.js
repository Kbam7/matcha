"use strict";

// Timeout variables for error messages
var addClass_timeout, removeError_timeout;

window.onload = function() {

    /*------------------------------------------------------------------------*/
    /* ---------------------[ EVENT LISTENERS ]------------------------- */
    /*------------------------------------------------------------------------*/

    /* --- Global events --- */

    // some drag event code, could be usefull
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

    // Add animation to input elements
    var formList = document.querySelectorAll('form.animate_label');
    for (var j = 0; j < formList.length; ++j) {
        var formInputList = formList[j].querySelectorAll('input[type=text], input[type=password], input[type=email]');
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
    }

    // Event for deleting images
    var deleteButtons = document.querySelectorAll(".delete_image_btn");
    if (deleteButtons) {
        setupDeleteImageEvents(deleteButtons);
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

// Add events for delete buttons
function setupDeleteImageEvents(deleteButtons) {
    for (var j = 0; j < deleteButtons.length; ++j) {
        deleteButtons[j].addEventListener('click', promptAndDelete);
    }

    function promptAndDelete(e) {
        var img = e.currentTarget.id;
        if (window.confirm("Are you sure you want to delete this image?")) {
            var data = 'confirmed=1&img=' + img;
            ajax_post('/matcha/php/delete_image.php', data, function(httpRequest) {
                var response = JSON.parse(httpRequest.responseText);
                displayError(response.statusMsg);
                if (response.status === true) {
                    // remove image from DOM
                    document.querySelector('#img_' + img).remove();

                }
            });
        }
    }
}


/* ----------------[ FORM FUNCTIONS ]------------------- */


// Form validation
function validate_input(input, value, type) {
    var result = true;

    if (value === "" && input.required) {
        displayError("<div class=\"alert alert-info\">'" + input.name + "' cannot be empty.</div>");
        return false;
    }
    if (type === "text") {
        // validate text input for names
        if (input.name === "firstname" || input.name === "lastname") {
            result = /^([A-Z][a-z]+([ ]?[a-z]?['-]?[A-Z][a-z]+)*)$/.test(value);
            if (result === false) {
                displayError("<div class=\"alert alert-warning\">'" + input.name + "' is invalid. Please try format is as follows: 'John Doe' or 'John-Doe'.<br />Names need to start with a CAPITAL letter.</div>");
                return false;
            }
        } else if (input.name === "username") {
            result = /^(?!.*\.\.)(?!.*\.$)[^\W][\w.]{0,24}$/.test(value);
            if (result === false) {
                displayError("<div class=\"alert alert-warning\">'" + input.name + "' is invalid. Please try format is as follows: 'john1' 'John_Doe' or 'John.Doe3'.<br />MAX: 24 Characters</div>");
                return false;
            }
        }

    } else if (type === "email") {
        result = /^([\w\.]+)@([\w\.]+)\.(\w+)/.test(value);
        if (result === false) {
            displayError("<div class=\"alert alert-warning\">'" + input.name + "' is invalid. Please try format is as follows: 'john@doe.com'</div>");
            return false;
        }
    } else if (type === "password") {
        result = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/.test(value);
        if (result === false) {
            displayError("<div class=\"alert alert-warning\">'" + input.name + "' is invalid. Password must contain at least 8 characters, and consist of atleast 1 uppercase letter, 1 lowercase letter, and 1 number. Can contain special characters.</div>");
            return false;
        }
    }
    return (true);
}



/* ----------------[ ERROR FUNCTIONS ]------------------- */

// Function to display errors
function displayError(errMsg, show) {

    var errDiv = document.getElementById("error-messages");
    clearTimeout(addClass_timeout);
    clearTimeout(removeError_timeout);
    debugger;
    show = (show === undefined ? 1 : show);
    if (errDiv && show) {
        errDiv.innerHTML = errMsg;
        var msgs = errDiv.childNodes;
        for (var msg of msgs) {
            addClass(msg, "animated");
            addClass(msg, "swing");
            msg.innerHTML = '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>' + msg.innerHTML;
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
