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
        /*
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
*/
    }
});

// Function that will get and display user profiles from the DB
function displayUserProfiles() {
    var data = "count=9";
    // Send data to get processed
    ajax_post("/matcha/php/displayUserProfiles.php", data, function(httpRequest) {
        var response = JSON.parse(httpRequest.responseText);
        var user_profiles = response.users;
        var main_profiles_div = document.querySelector('#user_profiles');
        var profile_source_images = document.querySelector('#profile_source_images');

        displayError(response.statusMsg);

        user_profiles.forEach(function(profile) {

            //  Get base64 data string to display image
            var tmp_img = document.createElement('IMG');
            tmp_img.setAttribute('src', '/matcha/assets/uploads/thumbnails/' + profile.profile_pic);

            var tmp_canvas = document.createElement('CANVAS');
            var context = tmp_canvas.getContext('2d');
            context.drawImage(tmp_img, 0, 0);

            //  Add image to gallery
            var data = tmp_canvas.toDataURL('image/png');

            console.log(data);


            debugger;
            // Create source image element
            var img_src = document.createElement('IMG');
            img_src.className = "src-image";
            img_src.setAttribute('src', data);
            // append source-image element to image source-div
            profile_source_images.appendChild(img_src);

            // Display the actual profile card
            displayProfile(profile, main_profiles_div);
        });
    });
}

function displayProfile(profile, parent) {
    console.log(profile);

    // Create all elements
    var outer_div = document.createElement('DIV');
    var card_div = document.createElement('DIV');
    var canvas = document.createElement('CANVAS');
    var avatar = document.createElement('DIV');
    var avatar_img = document.createElement('IMG');
    var content = document.createElement('DIV');

    // Set classes
    outer_div.className = "col-md-4 col-sm-6";
    card_div.className = "card";
    canvas.className = "header-bg";
    avatar.className = "avatar";
    content.className = "content";

    // Setup canvas
    canvas.setAttribute('id', "header-blur");
    canvas.setAttribute('width', "250");
    canvas.setAttribute('height', "70");

    // Setup avatar image
    avatar_img.setAttribute('src', "");
    avatar_img.setAttribute('alt', "");

    // Setup content
    content.innerHTML = '<p>Web Developer <br>' +
        'More description here</p>' +
        '<p><button type="button" class="btn btn-default">Contact</button></p>';

    // Build profile card
    avatar.appendChild(avatar_img);
    card_div.appendChild(canvas);
    card_div.appendChild(avatar);
    card_div.appendChild(content);
    outer_div.appendChild(card_div);


    // Append profile card to DOM
    parent.appendChild(outer_div);

    /*
    <div class="col-md-4 col-sm-6">
        <div class="card">
            <canvas class="header-bg" width="250" height="70" id="header-blur"></canvas>
            <div class="avatar">
                <img src="" alt="" />
            </div>
            <div class="content">
                <p>Web Developer <br>
                   More description here</p>
                <p><button type="button" class="btn btn-default">Contact</button></p>
            </div>
        </div>
    </div>
    */
}
