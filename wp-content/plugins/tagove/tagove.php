<?php
/*
  Plugin Name: Tagove - Live Chat Software
  Description: Unique Live Chat software provide video, voice and text chat solution with co browsing,
 screen sharing and real-time monitoring features.
  Version: 1.3
  Author: Tagove Team
  Author URI: http://www.tagove.com
 */

define('TAGOVE_URL','app.tagove.com');

function tagove_custom_menu_page(){
    add_menu_page(
        'Tagove',
        'Tagove',
        'manage_options',
        'tagove','tagove_custom_menu_page_render',
        plugins_url('images/tagove.png', __FILE__),
        90
    );
}

add_action( 'admin_menu', 'tagove_custom_menu_page' );

$option="";


function taggove_apply_widget()
{
    ?>
    <p>
        <?php echo  get_option('chat_widget');  ?>
    </p>
    <?php
}
add_action( 'wp_footer', 'taggove_apply_widget' );



if(isset($_POST['remove_tagove']))
{
    delete_option( 'tagove_site_id' );
    delete_option( 'chat_widget' );
}


function tagove_custom_menu_page_render()
{

    $errormsg="";
    $account_select = false;
    $extUrl = "";
    if(isset($_POST['Login']))
    {
        $tagove_email = $_POST['Email'];
        $tagove_password = $_POST['Password'];

        $url ="https://".TAGOVE_URL."/user/login";
//        $url ="https://surendra.dev.tagove.com/user/login";

        $response = wp_remote_post( $url, array(
                'method' => 'POST',
                'body' => array('api'=> 'true','Login'=>'Login', 'Email' => $tagove_email, 'v5'=>1,'Password' => $tagove_password ),
            )
        );


        if ( is_wp_error( $response ) ) {
            $error_message = $response->get_error_message();
            $error_message="Something went wrong: $error_message";
        } else {

            if ($response['response']['code'] == 200) {
//            $response['body']='dfdfdf';
                $accounts = (array)json_decode($response['body']);

                if(empty($accounts)){

                    $error_message="Something went wrong, please try after sometime!";

                }else {

                    if (sizeof($accounts) > 1) {

                        $option = "";

                        foreach ($accounts as $account => $value) {

                            $option .= '<option value="' . $value . '">' . $account . '</option>';

                        }

                        $account_select = true;

                    } else {

                        $code = "";

                        foreach ($accounts as $account => $value) {

                            $code = $value;

                        }

                        add_option("tagove_site_id", htmlspecialchars_decode($code), "yes");

                        $site_re_code = get_option('tagove_site_id');

                        $chat_code = $site_re_code;

                        add_option("chat_widget", $chat_code, "yes");

                    }
                }

            } else {
                $error = json_decode($response['body']);
                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    $error_message = "Something went wrong: $error_message";
                } else {

                    $error_message = $error->error;
                }
            }

        }

    }

    $site_id_exist = get_option('tagove_site_id');

//    print_r($site_id_exist);die;


    if($account_select){
        ?>

        <div class="wrap" style="display: block">
            <h1>

                Setup Your Tagove Account

            </h1>
            <h4>Congratulation You Successfully logged-in, please select account</h4>
               <div class="postbox" style="padding: 15px">

            <form method="post">
                <table class="form-table" >
                    <tr valign="top">
                        <th scope="row" style="width: 100px">Select Account</th>
                        <td>
                            <select name="accountOption" onchange="this.form.submit()" id="accountOption">
                                <option>Select Account</option>
                                <?= $option?>
                            </select>
                        </td>
                    </tr>
                </table>

            </form>
               </div>
        </div>
        <?php
    }

    $acOption = isset($_POST['accountOption']) ? $_POST['accountOption'] : false;
    if ($acOption)
    {

        add_option("tagove_site_id", stripslashes(htmlspecialchars_decode($_POST['accountOption'])), "yes");

        $site_re_code = get_option('tagove_site_id');

        $chat_code = $site_re_code;

        add_option("chat_widget", $chat_code, "yes");

        ?>
        <div class="wrap">

            <h1>

                Setup Your Tagove Account

            </h1>


            <div class="postbox" style="padding: 5px">
                <div class="handlediv" title="Click to toggle">
                    <br>
                </div>


                <div class="inside">
                    <div class="main">

                        <p  style="padding: 5px">
                            <span>Currently Activate Account</span><br>
                            <span>To start Togove Chat, Launch our dashboard for access all feature including widget customization</span>
                        </p>

                        <form action="" method="post">
                            <a href="http://app.tagove.com/" target="_blank" class="button button-primary" >launch Dashboard</a>
                            <input type="submit" name="remove_tagove" value="Logout" class="button button-primary">
                        </form>


                    </div>
                </div>
            </div>


        </div>
        <?php
    }


    if(empty($site_id_exist) && !$acOption && !$account_select)
    {

        ?>

        <div class="wrap" style="display: block">

            <h1>

                Setup Your Tagove Account

            </h1>

            <div class="postbox" style="padding: 5px">
                <div class="handlediv" title="Click to toggle">
                    <br>
                </div>
                <h3 class="hndle" style="padding: 5px">
                    <span>Linkup With Your Tagove Account  </span>

                </h3>

                <div class="inside">
                    <div class="main">
                        <?php
                        if(@$error_message)
                        {
                            echo "<p style='color: #ff0000'>".$error_message."</p>";
                        }
                        ?>

                        <form method="post" action="">
                            <table class="form-table" >
                                <tr valign="top">
                                    <th scope="row">Email</th>
                                    <td><input type="email" name="Email" value="" required="required" /></td>
                                </tr>
                                <tr valign="top">
                                    <th scope="row">Password</th>
                                    <td><input type="password" name="Password" value=""  required="required"/></td>
                                </tr>

                            </table>
                            <h4>The Tagove Chat Widget will Display on your Website after your account is linked up.</h4>
                            <p class="submit">
                                <input type="submit" name="Login" id="Login" class="button button-primary" value="login">
                            </p>
                            <span>
                            Don't Have Tagove Account Please <a target="_blank" href="https://app.tagove.com/site/signup">Click here</a>
                        </span>

                        </form>


                    </div>
                </div>
            </div>


        </div>

        <?php

    }

    if(!empty($site_id_exist))
    {
        ?>
        <div class="wrap">

            <h1>

                Setup Your Tagove Account

            </h1>


            <div class="postbox" style="padding: 5px">
                <div class="handlediv" title="Click to toggle">
                    <br>
                </div>


                <div class="inside">
                    <div class="main">

                        <p  style="padding: 5px">
                            <span>Currently Activate Account</span><br>
                            <span>To start Togove Chat, Launch our dashboard for access all feature including widget customization</span>
                        </p>

                        <form action="" method="post">
                            <a href="http://app.tagove.com/" target="_blank" class="button button-primary" >launch Dashboard</a>
                            <input type="submit" name="remove_tagove" value="Logout" class="button button-primary">
                        </form>


                    </div>
                </div>
            </div>


        </div>
        <?php
    }

}






