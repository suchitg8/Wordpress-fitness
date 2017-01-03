<?php
/**
 * Defines [fitness_list_trainers] shortcode
 * Gets question data from $_SESSION['quiz']
 * TODO: Get Trainers list, Filter them with $_SESSION['quiz']
 * TODO: List them one by one
 * TODO: Move to Right(Message) or Left(Later)
 */



function fitness_list_trainers($atts = null, $content = null)
{

    $quiz = $_SESSION["quiz"];
    $content .= "<h4>LIST OF TRAINERS</h4>";

    // get all users except Administrator.
    $users = get_users(array(
        'role' => 'Client'
    ));

    $trainers = array();

    // Filter users along with $_SESSION["quiz"] from questions page.
    foreach($users as $user) {
        $role = xprofile_get_field_data('Role', $user->ID);
        if($role == 'Trainer') {
            $capabilities = xprofile_get_field_data('Capabilities', $user->ID);
            $capabilities = array_map('strtolower', explode(',', $capabilities));
            $gender = xprofile_get_field_data('Gender', $user->ID);
            if(in_array(strtolower($gender), array_map('strtolower', explode(',', $quiz['gender'])))) {
                $count = 0;
                foreach(explode(',', $quiz["type"]) as $q_item){
                    if(in_array(strtolower($q_item), $capabilities))
                        $count ++;
                }
                if($count > 0)
                    array_push($trainers, array('count' => $count, 'user' => $user));
            }
        }
    }

    // list them in format of <ul>
    $content .= "<table>";

    if(count($trainers ) == 0)
        $content .= "<tr><td><h5>Sorry. No matching trainers.".
            "<a href='".get_page_link(35)."'> Back </a>".  //This is link to Home page.
            "</h5></td></tr>";

    foreach($trainers as $item) {
        $user = $item["user"];
        $content .= "<tr>";
        $content .= "<td style='vertical-align: middle;'><label style='font-size:1.2em;' ><a>:(</a></label></td>";
        $content .= "<td style='vertical-align: middle;'>".get_avatar($user->ID, 50)."</td>";
        $content .= "<td colspan='4'> <div style = 'display:inline-block;'>";
        $content .= "<h4 style = 'margin:10px 0 5px;' >".xprofile_get_field_data('Banner', $user->ID)."</h4>";
        $content .= "<p style='margin-bottom:0;'>".xprofile_get_field_data('Capabilities', $user->ID)."</p>";
        $content .= "</div> </td>";
        // Below get_page_link(40) Links to Chat page;
        $content .= "<td colspan='1' style='text-align:center; vertical-align: middle;'><label style='font-size:2em;' ><a href='".get_page_link(40)."' > :) </a></label></td>";
        $content .= "</tr>";
    }

    $content .= "</table>";

    return $content;
}

function fitness_shortcodes()
{
    add_shortcode('fitness_list_trainers', 'fitness_list_trainers');
}

add_action('bp_ready', 'fitness_shortcodes');

