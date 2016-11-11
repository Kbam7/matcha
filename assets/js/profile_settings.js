// Events for update bio form
var edit_bio_form = document.querySelector("#edit_bio_form");
if (edit_bio_form) {
    edit_bio_form.addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile(edit_bio_form);
    });
}

// Events for update details form
var edit_details_form = document.querySelector("#edit_details_form");
if (edit_details_form) {
    edit_details_form.addEventListener('submit', function(e) {
        e.preventDefault();
        updateProfile(edit_details_form);
    });
}

function updateProfile(form) {
    // Get all input elements in form
    var inputs = form.elements;

    // Build data string
    var data = "";

    // Go through all inputs
    for (var i = 0; i < inputs.length; ++i) {
        var input = inputs[i];

        // Check tags for an update
        if (input.name === "tags") {
            // get all child elements (<span>) and get the inner html, then make a CSV with each tag text.
            // Compare the CSV with the current `$user['tags']` value
            // There must be a CSV value with all the users tags saved in the user node and then
            // create a relationship between the User and the Tag node
            var tags = document.querySelector('#tags_list').children;
            var string = "";
            for (var j = 0; j < tags.length; ++j) {
                // Only <span> elements
                if (tags[j].nodeName === "SPAN") {
                    // Build string
                    if (string === "") {
                        string = tags[j].innerText;
                    } else {
                        string += ',' + tags[j].innerText;
                    }
                }
            }
            input.value = string;
        }

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
                    console.log('found input(' + input.type + ':checked) with value : ' + val);
                }
            } else {
                if (validate_input(input, input.value, input.type)) {
                    val = encodeURIComponent(input.value);
                    if (data === "") {
                        data = "submit=1&" + input.name + "=" + val;
                    } else {
                        data += "&" + input.name + "=" + val;
                    }
                }
                console.log('found input(' + input.type + ') with value : ' + val);
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

// For GPS location
function geoFindMe(e) {
    var output = document.getElementById("out");

    if (!navigator.geolocation) {
        output.innerHTML = "<p>Geolocation is not supported by your browser</p>";
        return;
    }

    function success(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;

        var lat = document.querySelector('#edit_details_form input#edit_latitude');
        var long = document.querySelector('#edit_details_form input#edit_longitude');

        lat.value = latitude;
        long.value = longitude;

        //output.innerHTML = '<p>Latitude is ' + latitude + '° <br>Longitude is ' + longitude + '°</p>';

        var img = new Image();
        img.src = "https://maps.googleapis.com/maps/api/staticmap?center=" + latitude + "," + longitude + "&zoom=13&size=300x300&sensor=false";

        output.appendChild(img);
    };

    function error() {
        output.innerHTML = "Unable to retrieve your location";
    };

    output.innerHTML = "<p>Locating…</p>";

    navigator.geolocation.getCurrentPosition(success, error);
}
