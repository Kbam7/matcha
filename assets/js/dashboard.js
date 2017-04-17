$(document).ready(function() {
    var user_profiles = document.querySelector("#user_profiles");
    if (user_profiles) {
        //observeNewUserProfile(user_profiles);
        displayUserProfiles();

        /*
        //display more users when at bottom of screen

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
        //var profile_source_images = document.querySelector('#profile_source_images');

        displayAlertMessage(response.statusMsg, 1);
        if (response.status === true) {
            user_profiles.forEach(function(profile) {
                // Display the actual profile card
                displayProfile(profile, main_profiles_div);
            });
        }
    });
}

function displayProfile(profile, parent_div) {
    console.log(profile);

    // Create all elements
    var outer_div = document.createElement('DIV');
    var card_div = document.createElement('DIV');
    var inner_top = document.createElement('DIV');
    var avatar = document.createElement('DIV');
    var avatar_img = document.createElement('IMG');
    var fame_div = document.createElement('DIV');
    var user_tags = document.createElement('DIV');
    var title = document.createElement('H4');
    var profile_card_buttons = document.createElement('DIV');

    // Set classes
    outer_div.className = "col-md-4 col-sm-6";
    card_div.className = "card";
    inner_top.className = "header-bg";
    avatar.className = "avatar";
    avatar_img.className = "pull-left";
    fame_div.className = "content";
    user_tags.className = "content tags";
    profile_card_buttons.className = 'profile_card_buttons';

    // ATTRIBUTES: top div
    inner_top.setAttribute('id', "header-blur");
    //    inner_top.setAttribute('width', "250");
    //    inner_top.setAttribute('height', "70");

    // ATTRIBUTES: avatar image
    avatar_img.setAttribute('src', '/matcha/assets/uploads/thumbnails/' + profile.profile_pic);
    avatar_img.setAttribute('alt', profile.username + "'s Profile picture");

    // Set up
    if (profile.age === undefined)
        profile.age = "?";

    // Title of profile. EG: Kyle Bamping, 23
    title.innerHTML = profile.firstname + ' ' + profile.lastname + ', <small><b>' + profile.age + '</b></small>';

    // Need logic to setup button display
    profile_card_buttons.innerHTML = '<div class="btn-group" role="group">' +
        '<button type="button" id="like_' + profile.username + '" class="btn btn-success like_btn" onClick="updateLike(this.id, \'' + profile.username + '\')">Like</button>' +
        '<button type="button" id="block_' + profile.username + '" class="btn btn-danger block_btn">Block</button>' +
        '</div><div class="btn-group" role="group">' +
        '<a href="/matcha/views/view_profile.php?view_user=' + profile.username + '" class="btn btn-info">View Profile</a>' +
        '</div>';

    // split and display profile tags
    var tags_text = profile.tags.split(',');
    tags_text.forEach(function(tag) {
        let label = document.createElement('SPAN');
        label.className = "label label-primary";
        label.innerText = tag;
        user_tags.appendChild(label);
    });

    fame_div.innerHTML = '<dl class="dl-horizontal"><dt>Fame </dt><dd>' + profile.fame + '</dd></dl>';

    // Build avatar
    avatar.appendChild(avatar_img);
    // Build inner top
    inner_top.appendChild(title);
    inner_top.appendChild(user_tags);
    // Build card
    card_div.appendChild(inner_top);
    card_div.appendChild(fame_div);
    card_div.appendChild(avatar);
    card_div.appendChild(profile_card_buttons);
    // Build outer
    outer_div.appendChild(card_div);
    // Append profile card to DOM
    parent_div.appendChild(outer_div);
}

function updateLike(button_id, username) {
    // If btn ID starts with 'like_'
    if (button_id.match(/^like_/)) {
        console.log("like_" + username);

        var data = 'submit=1&like_user=' + username;
        ajax_post('/matcha/php/dashboard_utils.php', data, function(httpRequest) {
            var response = JSON.parse(httpRequest.responseText);
            displayAlertMessage(response.statusMsg);
            if (response.status === true) {

                var like_btn = document.querySelector('#like_' + username);
                var par = like_btn.parentElement;

                // Hide like button
                like_btn.style.display = "none";
                // Display unlike button
                par.innerHTML = '<button type="button" id="unlike_' + username + '" class="btn btn-success unlike_btn" onClick="updateLike(this.id, \'' + username + '\')">Unlike</button>' + par.innerHTML;
            }
        });
        // If btn ID starts with 'unlike_'
    } else if (button_id.match(/^unlike_/)) {
        console.log("unlike_" + username);

        var data = 'submit=1&unlike_user=' + username;
        ajax_post('/matcha/php/dashboard_utils.php', data, function(httpRequest) {
            var response = JSON.parse(httpRequest.responseText);
            displayAlertMessage(response.statusMsg);
            if (response.status === true) {

                var unlike_btn = document.querySelector("#unlike_" + username);
                var par = unlike_btn.parentElement;

                // Hide unlike button
                unlike_btn.style.display = "none";
                // Display like button
                par.innerHTML = '<button type="button" id="like_' + username + '" class="btn btn-success like_btn" onClick="updateLike(this.id, \'' + username + '\')">Like</button>' + par.innerHTML;
            }
        });
    }

}
/*/
function observeNewUserProfile(user_profiles) {

    // Vendor specific aliases for 'MutationObserver'
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

    // create an observer instance
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            var newNodes = mutation.addedNodes;
            console.log(newNodes);
            debugger;
            // add something to new elements
            for (let i = 0; i < newNodes.length; ++i) {
                addClass(newNodes[i], "scale-out");
                //    newNodes[i].className += " scale-out";
            }
        //  remove profile from DOM if blocked
            while (user_profiles.children.length) {
                user_profiles.removeChild(user_profiles.children[0]);
            }
        })
    });

    // configuration of the observer:
    var config = {
        attributes: true,
        childList: true,
        characterData: true
    };

    // pass in the target element, as well as the observer options
    observer.observe(user_profiles, config);
}
*/
