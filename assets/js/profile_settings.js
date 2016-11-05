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
    var data = "submit=1&";
    for (var i = 0; i < inputs.length; ++i) {
        var input = inputs[i];
        // if input field has a value, encode it and add it to the data string
        if (input.value && input.value.length > 0) {

            debugger;
            // Initial value
            var val = "";
            // If its type checkbox or radio and its :checked then get the value. Else ignore it
            if (input.type === 'radio' || input.type === 'checkbox') {
                if (input.checked) {
                    val = encodeURIComponent(input.value);
                    data += input.name + "=" + val;
                    if ((i + 1) < inputs.length) {
                        data += "&";
                    }
                    console.log('found input(' + input.type + ':checked) with value : ' + val);
                }
            } else {
                if (validate_input(input, input.value, input.type)) {
                    val = encodeURIComponent(input.value);
                    data += input.name + "=" + val;
                    if ((i + 1) < inputs.length) {
                        data += "&";
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
