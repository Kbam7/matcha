$(document).ready(function() {

    // Events for update account form
    var update_account_form = document.querySelector("#update_account");
    if (update_account_form) {

        // Get all input elements
        var inputs = update_account_form.elements;
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
                        if ((this.name === "password" && this.value !== update_account_form.elements.namedItem("passwd2").value) ||
                            (this.name === "password2" && this.value !== update_account_form.elements.namedItem("passwd").value)) {
                            displayError("<div class=\"alert alert-warning\">Your passwords do not match</div>");
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
        }

        update_account_form.addEventListener('submit', function(e) {
            e.preventDefault();
            updateAccountSettings(update_account_form);
        });
    }

});


function updateAccountSettings(form) {
    // Get all input elements in form
    var inputs = form.elements;

    // Build data string
    var data = "";

    // Go through all inputs
    for (var i = 0; i < inputs.length; ++i) {
        var input = inputs[i];
        // if input field has a value, encode it and add it to the data string
        if (input.value && input.value.length > 0) {
            // Initial value
            var val = "";
            // If its type checkbox or radio and its :checked then get the value. Else ignore it
            if (input.type === 'radio' || input.type === 'checkbox') {
                if (input.checked) {
                    val = encodeURIComponent(input.value);
                    if (data === "") {
                        data = "submit=1&" + input.name + "=" + val;
                    } else {
                        data += "&" + input.name + "=" + val;
                    }
                    console.log('found input[' + input.name + '](' + input.type + ':checked) with value : ' + val);
                }
            } else {
                //if (validate_input(input, input.value, input.type)) {
                val = encodeURIComponent(input.value);
                if (data === "") {
                    data = "submit=1&" + input.name + "=" + val;
                } else {
                    data += "&" + input.name + "=" + val;
                }
                //}
                console.log('found input[' + input.name + '](' + input.type + ') with value : ' + val);
            }

        }
    }
    // Send data to get processed
    ajax_post("/matcha/php/update_profile_info.php", data, function(httpRequest) {
        var response = JSON.parse(httpRequest.responseText);
        displayError(response.statusMsg);
        console.log(response.user);
    });

}
