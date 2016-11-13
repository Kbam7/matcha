// Events for new user form
var createUserForm = document.querySelector("#createUserForm");
if (createUserForm) {
    // Get all input elements
    var inputs = createUserForm.elements;
    var errorDiv = document.getElementById("error-messages");

    // Add 'blur' event listener for error messages
    for (var i = 0; i < inputs.length; ++i) {
        var item = inputs[i];
        // dont add for submit button
        if (item.type !== "submit") {
            // is item a `password`
            if (item.type === "password") {
                item.addEventListener('blur', function(e) {
                    // Check if passwords do not match
                    if ((this.name === "password" && this.value !== createUserForm.elements.namedItem("passwd2").value) ||
                        (this.name === "password2" && this.value !== createUserForm.elements.namedItem("passwd").value)) {
                        displayError("<p class=\"alert alert-warning\">Your passwords do not match</p>");
                    } else {
                        // passwords match, remove any error messages
                        while (errorDiv.children.length) {
                            errorDiv.removeChild(errorDiv.children[0]);
                        }
                        // and then validate
                        validate_input(this, this.value, this.type);
                    }
                });
            } else {
                // item is not `submit` or `password`
                item.addEventListener('blur', function(e) {
                    // remove any error messages
                    while (errorDiv.children.length) {
                        errorDiv.removeChild(errorDiv.children[0]);
                    }
                    // and then validate
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

function createUser(form) {
    var fname = encodeURIComponent(document.getElementById("fname").value);
    var lname = encodeURIComponent(document.getElementById("lname").value);
    var uname = encodeURIComponent(document.getElementById("uname").value);
    var user_age = encodeURIComponent(document.getElementById("user_age").value);
    var email = encodeURIComponent(document.getElementById("email").value);
    var passwd = encodeURIComponent(document.getElementById("passwd").value);

    var data = "submit=1" +
        "&fname=" + fname +
        "&lname=" + lname +
        "&uname=" + uname +
        "&user_age=" + user_age +
        "&email=" + email +
        "&passwd=" + passwd;

    //    validate_input(form);

    ajax_post("/matcha/php/create_acc.php", data, function(httpRequest) {
        //        displayError(httpRequest.responseText);
        var response = JSON.parse(httpRequest.responseText);

        if (response.status === true) {
            displayError(response.statusMsg + " <p class=\"alert alert-info\">Redirecting to login page . . .</p>");
            console.log(response.record);
            setTimeout(function() {
                window.location = "/matcha/index.php";
            }, 5000);
        } else {
            displayError(response.statusMsg);
        }
    });
}
