<?php

function getProfilePictureSrc($a_user)
{
    if (isset($a_user['profile_pic'])) {
        echo '/matcha/assets/uploads/thumbnails/'.$a_user['profile_pic'];
    } else {
        echo '../assets/img/default_pp.png';
    }
}

function getUsersTags($a_user)
{
    if (isset($a_user['tags'])) {
        $tags = explode(',', $a_user['tags']);
        if (!empty($tags) && !(empty($tags[0]))) {
            foreach ($tags as $tag) {
                echo '<span>'.$tag.'</span>';
            }
        }
    } else {
        echo '<p>No Tags found</p>';
    }
}
