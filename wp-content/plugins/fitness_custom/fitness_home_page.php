<?php
/**
 * Defines [fitness_home] shortcode.
 * TODO: This should be forked between Client / Trainer
 */

/*
 * Parses content of Question.
 *
 * Standard format of content
 * \\\name///
 * ---Option1---
 * <<<value1>>>
 * {{{description}}}
 * ---Option2---
 * <<<value2>>>
 * ...
 */
function parse_question_body($str) {
    $raw_data = explode('---', $str);
    $count = count($raw_data);
    $selected = '';

    $name = explode('\\\\\\', explode('///', $raw_data[0])[0])[1];

//    var_dump($name);

    /*
     * $_SESSION["quiz"] FORMAT:
     *  - ["rule"] = "value" : ex $_SESSION["quiz"]["gender"] = "female"
     *  - ["rule"] = "value" : ex $_SESSION["quiz"]["type"] = "Cycling,Cross Trainers"
     */

    if(isset($_POST[$name])) {
        $_SESSION['quiz'][$name] = $_POST[$name];
        $selected = $_POST[$name];
    }

    if(isset($_SESSION['quiz'][$name]))
        $selected = $_SESSION['quiz'][$name];

    $result = '<form method = "POST" id = "question_form">';
    for($i = 1; $i < $count; $i += 2) {
        $q_title = $raw_data[$i];
        $q_value = explode('<<<', explode('>>>', $raw_data[$i + 1])[0])[1];
        $q_description = explode('{{{', explode('}}}', $raw_data[$i + 1])[0])[1];
        $checked = '';
        $style = 'display : none;';
        if($selected == $q_value) {
            $checked = 'checked';
            $style = '';
        }
        $result .= "<label><input value = '$q_value' type = 'radio', name = '$name' $checked>".$q_title."</input></label>";
        $result .= "<div style = '$style' class = 'ft_description'>".$q_description."</div>";
        $result .= "<br/>";
    }
    $result .= '</form>';
    $result .=  '<script language = "javascript">'.
                '   (function($) {'.
                '       $("label").click(function(event) {'.
                '           $(".ft_description").hide(); '.
                '           $(this).next().toggle(); '.
                '           $(this).children("input").prop("checked", true); '.
                '           event.stopPropagation(); '.
                '           event.preventDefault(); '.
                '           $("#question_form").submit(); '.
                '       }); '.
                '   }(jQuery));'.
                '</script>';
    return $result;
}

/*
 * Makes Custom Loop Through Questions.
 */
function fitness_home_client() {
    global $paged, $wp_query;

    // FAILED ON USING get_query_var('paged') function

    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
    $url = $_SERVER['REQUEST_URI'];
    $offset = strpos($url, 'page');
    $end = strpos(substr($url, $offset + 5), '/');
    $paged = intval(substr($url, $offset + 5, $end));

    // Manually grabbed pagenum data from url

    $content = "<h3>Answer the following questions to find your trainer</h3>";
    $args = array(
        'post_type' => 'question',
        'posts_per_page' => 1,
        'paged' => $paged,
    );
    query_posts($args);

//    $wp_query = $the_query;

    while(have_posts()) :
        the_post();
        $q_title = get_the_title();
        $q_content = get_the_content();
        $content .= "<h3>".$q_title."</h3>";
        $content .= parse_question_body($q_content) . "<br/>";
        $content .= get_next_posts_link('Next Question');
        $content .= get_previous_posts_link('Previous Question');
        if($paged == $wp_query -> max_num_pages)
            $content .= '<a href = "'.get_page_link(37).'" style = "float:right;"> Go to Trainers List</a>';
    endwhile;;

    wp_reset_query();
    // Should be called for preventing conflict with others

    return $content;
}

function fitness_home($atts = null, $content = null)
{

    global $bp;

    $role = xprofile_get_field_data('Role', $bp->loggedin_user->userdata->ID);

    if($role == 'Client')
        $content .= fitness_home_client();

    // TODO: if($role == 'Trainer') $content .= LIST OF CHARACTERISTICS

    return $content;
}

function fitness_list_trainer_shortcodes()
{
    add_shortcode('fitness_home', 'fitness_home');
}

add_action('bp_ready', 'fitness_list_trainer_shortcodes');