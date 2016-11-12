$(document).ready(function() {
    var user_profiles = document.querySelector("#user_profiles");
    if (user_profiles) {
        /*
                // found div, display 9 users
                ajax_post('/matcha/php/fetchUsers.php', data, function(httpRequest) {
                    var response = JSON.parse(httpRequest.responseText);
                    displayError(response.statusMsg, 0);

                });

                user_profiles.addEventListener('submit', function(e) {
                    e.preventDefault();
                    updateProfile(edit_bio_form);
                });
        */
        displayUserProfiles();

        setInterval(function() {
            var totalHeight, currentScroll, visibleHeight;

            if (document.documentElement.scrollTop) {
                currentScroll = document.documentElement.scrollTop;
            } else {
                currentScroll = document.body.scrollTop;
            }

            totalHeight = document.body.offsetHeight;
            visibleHeight = document.documentElement.clientHeight;

            $('#data').html(
                'total height: ' + totalHeight + '<br />' +
                'visibleHeight : ' + visibleHeight + '<br />' +
                'currentScroll:' + currentScroll);

            if (totalHeight <= currentScroll + visibleHeight) {
                // At bottom
                //$('#data').addClass('hilite');
                displayUserProfiles();
            } else {
                // Not at bottom
                $('#data').removeClass('hilite');
            }

        }, 500);

    }

});

// Function that will get and display user profiles from the DB
function displayUserProfiles() {
    var data = "count=9";
    // Send data to get processed
    ajax_post("/matcha/php/displayUserProfiles.php", data, function(httpRequest) {
        var response = JSON.parse(httpRequest.responseText);
        displayError(response.statusMsg);
        console.log(response.users);
    });
}
