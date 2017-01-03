<?php
/*
  Plugin Name: RumbleTalk Chat
  Plugin URI: https://wordpress.org/plugins/rumbletalk-chat-a-chat-with-themes/
  Description: Group chat room for wordpress and budypress websites. Use one or many advanced stylish chat rooms for your community.
  Tags: buddypress
  Version: 5.1.4
  Author: RumbleTalk Ltd
  Author URI: http://www.rumbletalk.com
  License: GPL2

  Copyright 2012-2016 RumbleTalk Ltd (email : support@rumbletalk.com)

  This program is free trial software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

# Include RumbleTalk PHP SDK
require 'rumbletalk-sdk.php';
use RumbleTalk\RumbleTalkSDK;

require 'rumbletalk-chat-ajax.php';

class RumbleTalkChat {

    protected $options;
    protected $cdn = 'https://d1pfint8izqszg.cloudfront.net/';

    public function __construct() {
        $this->options = array(
            'rumbletalk_chat_code',
            'rumbletalk_chat_names',
            'rumbletalk_chat_hashes',
            'rumbletalk_chat_ids',
            'rumbletalk_chat_chats', // [{hash: {width, height, floating, membersOnly}}, ...] as JSON string
            'rumbletalk_chat_width', // deprecated
            'rumbletalk_chat_height', // deprecated
            'rumbletalk_chat_floating', // deprecated
			'rumbletalk_chat_member', // deprecated
    		'rumbletalk_chat_token_key',
    		'rumbletalk_chat_token_secret',
    		'rumbletalk_chat_chatId',
        );

        register_activation_hook(__FILE__, array(&$this, 'install'));
        register_deactivation_hook(__FILE__, array(&$this, 'unInstall'));

        if (is_admin()) {
			add_thickbox();
            add_action('admin_menu', array(&$this, 'adminMenu'));
            add_action('admin_init', array(&$this, 'adminInit'));
			add_action('wp_ajax_rumbletalk_apply_new_token', array(&$this, 'ajaxApplyNewTokenCallback'));
			add_action('wp_ajax_rumbletalk_get_access_token', array(&$this, 'ajaxGetAccessTokenCallback'));
			add_action('wp_ajax_rumbletalk_create_new_chatroom', array(&$this, 'ajaxCreateNewChatRoomCallback'));
			add_action('wp_ajax_rumbletalk_update_chatrooms', array(&$this, 'ajaxUpdateChatRooms'));
			add_action('wp_ajax_rumbletalk_select_chatroom', array(&$this, 'ajaxSelectChatRoom'));
			add_action('wp_ajax_rumbletalk_delete_chatroom', array(&$this, 'ajaxDeleteChatRoom'));
			add_action('wp_ajax_rumbletalk_update_chatroom_options', array(&$this, 'ajaxUpdateChatRoomOptions'));
        } else {
            add_shortcode('rumbletalk-chat', array(&$this, 'embed'));
			add_action('wp_head', array(&$this, 'hook_javascript'));
        }
    }

	public function adminInit() {
		if (current_user_can( 'edit_posts' ) && current_user_can('edit_pages')) {
			add_filter('mce_buttons', array(&$this, 'registerTinyMceButton'));
			add_filter('mce_external_plugins', array(&$this, 'addTinyMceButton'));
		}
	}

	public function registerTinyMceButton($buttons) {
		//array_push($buttons, "button_rumbletalk_chat", "button_rumbletalk_chat2");
		array_push($buttons, "button_rumbletalk_chat2");
		return $buttons;
	}

	public function addTinyMceButton($plugin_array) {
		$plugin_array['rumbletalk_mce_buttons'] = plugins_url('/add-mce-buttons.js', __FILE__ ) ;
		return $plugin_array;
	}

    public function hook_javascript() {
        $code = get_option('rumbletalk_chat_code');
        $current_user = wp_get_current_user();
     
        if( !empty($code)&& !empty($current_user->display_name)) {
            ?>
<script type="text/javascript">
(function(g,v,w,d,s,a,b){w['rumbleTalkMessageQueueName']=g;w[g]=w[g]||
function(){(w[g].q=w[g].q||[]).push({type:arguments[0],data:arguments[1]
})};a=d.createElement(s);b=d.getElementsByTagName(s)[0];a.async=1;
a.src='https://d1pfint8izqszg.cloudfront.net/api/'+v+'/sdk.js';
b.parentNode.insertBefore(a,b);})('rtmq','v0.31',window,document,'script');
</script>
            <?php
        }
    }

    public function adminMenu() {
        add_options_page(
            'RumbleTalk Chat',
            'RumbleTalk Chat',
            'administrator',
            'rumbletalk-chat',
            array(&$this, 'drawAdminPage')
        );
    }

	public function ajaxApplyNewTokenCallback() {
		$rumbleTalkChatAjax = new RumbleTalkChatAjax();
		$rumbleTalkChatAjax->processAjaxRequest('apply_new_token', $_POST);
	}

	public function ajaxGetAccessTokenCallback() {
		$rumbleTalkChatAjax = new RumbleTalkChatAjax();
		$rumbleTalkChatAjax->processAjaxRequest('get_access_token', $_POST);
	}

	public function ajaxCreateNewChatRoomCallback() {
		$rumbleTalkChatAjax = new RumbleTalkChatAjax();
		$rumbleTalkChatAjax->processAjaxRequest('create_new_chatroom', $_POST);
	}

	public function ajaxUpdateChatRooms() {
		$rumbleTalkChatAjax = new RumbleTalkChatAjax();
		$rumbleTalkChatAjax->processAjaxRequest('update_chatrooms', $_POST);
	}

	public function ajaxSelectChatRoom() {
		update_option('rumbletalk_chat_code', $_POST['hash']);
		$result = array('status' => true);
		die(json_encode((array)$result));
	}

	public function ajaxDeleteChatRoom() {
		$rumbleTalkChatAjax = new RumbleTalkChatAjax();
		$rumbleTalkChatAjax->processAjaxRequest('delete_chatroom', $_POST);
	}

	public function ajaxUpdateChatRoomOptions() {
		// var_dump($_POST);
		$chatHash = $_POST['hash'];
		$options = $_POST['options'];

		$currentOptions = array();
		$chats = json_decode(get_option('rumbletalk_chat_chats'), true);
		// var_dump($chats);
		if (!isset($chats[$chatHash])) {
			$chats[$chatHash] = array();
		}
		foreach ($options as $key => $value) {
			if ('false' == $value)
				$value = false;
			else if ('true' == $value)
				$value = true;
			$chats[$chatHash][$key] = $value;
		}
		// var_dump($chats);
		update_option('rumbletalk_chat_chats', json_encode($chats));
		die('ok');
	}

    public function drawAdminPage() {
    	$showCreateAccountForm = false;
    	$createAccountError = "";
    	$createAccountNotes = "";
    	if ($_REQUEST['account_creation_submitted']) {
    		# Initialize key and secret with default values
			$appKey = 'key';
			$appSecret = 'secret';

			# create the RumbleTalk SDK instance using the key and secret
			$rumbletalk = new RumbleTalkSDK($appKey, $appSecret);

			$email = $_REQUEST['email'];
			$password = strval($_REQUEST['password']);
			$data = array(
			   'email' => $email,
			   'password' => $password,
               'referrer' => 'WordPress'
			);
			$result = $rumbletalk->createAccount($data);
    		if (!$result['status']) {
    			$showCreateAccountForm = true;
    			$createAccountError = $result['message'];
    			if ($result['message'] == 'Email address already in use') {
    				$createAccountNotes = 'Please retrieve your token key and secret <a target="_new" href="https://www.rumbletalk.com/admin/">here</a>.';
    			}
    		} else {
    			update_option('rumbletalk_chat_token_key', $result['token']['key']);
    			update_option('rumbletalk_chat_token_secret', $result['token']['secret']);
    			update_option('rumbletalk_chat_chatId', $result['chatId']);
    			update_option('rumbletalk_chat_code', $result['hash']);
    			update_option('rumbletalk_chat_hashes', $result['hash']);
    			update_option('rumbletalk_chat_ids', $result['id']);
    			update_option('rumbletalk_chat_names', 'New Chat');

    			/*
				# create new RumbleTalk SDK instance using the key and secret
				$rumbletalk = new RumbleTalkSDK($result['token']['key'], $result['token']['secret']);

				# fetch (and set) the access token for the account (tokens lasts for 30 days)
				# Looks like we don't need access token for account creation, but need for $rumbletalk->get('chats');
				$accessToken = $rumbletalk->fetchAccessToken($expiration);

	    		# Load and save all chats 
				$chats = $rumbletalk->get('chats');
				var_dump($chats);

				array_map(function($document) {
					return $document['_id'];
				}, iterator_to_array($objects));

				*/
    		}
    	}

    	// delete_option('rumbletalk_chat_chats');
    	// update_option('rumbletalk_chat_code', "V275rH7*");

    	// upgrade from previous versions
    	if (!get_option('rumbletalk_chat_chats') && (get_option('rumbletalk_chat_width', null) !== null) && (get_option('rumbletalk_chat_height', null) !== null)
    		 && (get_option('rumbletalk_chat_floating', null) !== null) && (get_option('rumbletalk_chat_member', null) !== null)) {
    		$hash = get_option('rumbletalk_chat_code');
    		$options = array(
    			$hash => array(
    				'width' => get_option('rumbletalk_chat_width'),
    				'height' => get_option('rumbletalk_chat_height'),
    				'floating' => get_option('rumbletalk_chat_floating') ? true : false,
    				'membersOnly' => get_option('rumbletalk_chat_member') ? true : false,
    			));
    		// var_dump(json_encode($options));
			update_option('rumbletalk_chat_chats', json_encode($options));
			delete_option('rumbletalk_chat_width');
			delete_option('rumbletalk_chat_height');
			delete_option('rumbletalk_chat_floating');
			delete_option('rumbletalk_chat_member');
    	}

    	// var_dump(get_option('rumbletalk_chat_chats'));

        ?>
		<div id="modal-window-error" style="display:none;">
		    <p>.</p>
		</div>						
		<div id="modal-window-confirmation" style="display:none;">
		    <p>.</p>
		</div>						
		<div id="modal-window-prompt" style="display:none;">
		    <p>.</p>
		</div>						
        <div style="width:820px;">
            <h2>RumbleTalk Chat Options</h2>
            <table>
                <tr>
                    <td width="500" valign="top">
                    	<style>
						.upgrade_button {
                            display:inline-block;
                            border-radius: 3px;

                            background-color: #da2424;
                            text-decoration: none;
                            color: #fff;
                            font: bold 15px arial;
                            font-weight: 700;
                            margin-left: 0px;
                            padding: 7px;
						}
                            .upgrade_button:hover {
                                background-color: #b31414;
                                color: #fff;
                            }

                        #TB_ajaxContent {
                        	overflow-y: hidden !important;
                        	position: relative;
                    	}

                    	#TB_window {
                    		display: none !important;
                    	}

                    	#TB_window.visibleImportant {
                    		display: block !important;
                    	}

                    	#chatrooms_refresh {
							background-image: url('<?php echo plugins_url('ico-refresh.png', __FILE__); ?>');
                            background-position: center center;
						    width: 29px;
						    height: 29px;
                            margin: 0 10px;
						    background-repeat: no-repeat;
						    cursor: pointer;
						    display: inline-block;
						    vertical-align: middle;     		
                    	}
						</style>
                        <div style="width:500px;position;relative;">
                        	<form method="post" action="<?= admin_url('options-general.php?page=rumbletalk-chat#options_form') ?>" onsubmit="return validate_account_creation( this );" id="create_form"<?= (get_option("rumbletalk_chat_hashes") == '' || $showCreateAccountForm) ? '' : ' style="display:none;"' ?>> <!--//www.rumbletalk.com/_ajax_reg_remote.php-->
	                        	<input type="hidden" name="account_creation_submitted" value="1" />
	                        	<table valign="top">
									<tr>
										<td colspan="2" align="left"  style="padding-bottom:30px;"><img width="490" src="<?= $this->cdn ?>emails/Mailxa-01.png" /></td>
									</tr>
									<tr>
										<td colspan="2" style="padding-bottom:15px;">
											<a href="#options_form" onclick="toggle_create_account(1);">I already have an account</a>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="padding-bottom:15px;">
											Add RumbleTalk chat-room to your community or event in one minute.<br/><br/>
											1 - Enter your email and preferred password.<br/>
											2 - Click on the create button. It takes up to 20 seconds and than your account is ready.<br/>
											3 - Now, add the exact text <b style="font:arial 8px none; color:#68A500"> [rumbletalk-chat] </b>to your visual editor where you want your chat to show.
										</td>
									</tr>
									<?php if ($createAccountError): ?>
									<tr>
										<td colspan="2">
											<br/>
											<span style="color:red;font-weight:bold;">Info: <?= $createAccountError ?></span>
                                        </td>
									</tr>
									<?php endif ?>
									<?php if ($createAccountNotes): ?>
									<tr>
										<td colspan="2">
											<span><?= $createAccountNotes ?></span>
											<br/><br/>
                                        </td>
									</tr>
									<?php endif ?>
									<tr>
										<td width="20"><b>Email:</b></td>
										<td width="60"><input type="text" name="email" /></td>
									</tr>
									<tr>
										<td width="20"><b>Password:</b></td>
										<td width="60"><input type="password" name="password" /></td>
									</tr>
									<tr>
										<td width="20"><b>Confirm Password:</b></td>
										<td width="60"><input type="password" name="password_c" /></td>
									</tr>
									<tr>
										<td colspan="2">
											<input id="create_chat_button" type="submit" value="Create a Chatroom" />
											<img id="loading_gif" style="display:none;" src="<?= $this->cdn ?>images/mainpage/loading.gif" alt="loading" />
										</td>
									</tr>
									<tr>
										<td colspan="2">
										<br/>
										<?php if ( get_option( 'rumbletalk_chat_code' ) != '' ) { ?>
											<span style="color:red;font-weight:bold;">Note! your current chat will be deleted if you enter a new email and password.</span>
										<?php } ?>
										</td>
									</tr>
                                    <tr>
                                        <td colspan="2" align="left"  style="padding-top:30px;"><img width="490" src="<?= $this->cdn ?>emails/Mailxa-04.png" /></td>
                                    </tr>
								</table>
                        	</form>
                            <form method="post" action="options.php" id="options_form"<?= (get_option("rumbletalk_chat_code") == '' || $showCreateAccountForm) ? ' style="display:none;"' : '' ?>>
                                <input type="hidden" name="action" value="update"/>
                                <input type="hidden" name="page_options" value="rumbletalk_chat_token_key,rumbletalk_chat_token_secret"/>
                                <?php
                                    wp_nonce_field("update-options");
                                    $hideToken = get_option("rumbletalk_chat_token_key") && get_option("rumbletalk_chat_token_secret") && get_option("rumbletalk_chat_code");
                                ?>
                                <table valign="top">
									<tr>
										<td colspan="2" align="left"  style="padding-bottom:30px;"><img width="490" src="<?= $this->cdn ?>emails/Mailxa-01.png" /></td>
									</tr>
									<tr>
										<td width="200" align="left">
											<a href="#options_form" onclick="toggle_create_account();">Create a new account</a>
										</td>
                                        <td width="180"  align="left">
                                    		<a href="#" id="display_token"><?= $hideToken ? 'Token is set: change' : 'Hide' ?> token</a>
                                    	</td>										
									</tr>
                                    
                                    <tr name="tokens_row" <?php if ($hideToken) echo 'style="display: none"' ?>>
										<td colspan="2" style="padding-bottom:15px;">
										 <span style="font:arial 8px none; color:#AAACAD">
										  Token means a two text keys that are unique to your account. Keys are created automatically when one creates his first chat.<br><br>
										  In case you wish to change/update your keys, then go to your <a href="https://www.rumbletalk.com/admin/groups.php" target="_blank">admin panel</a> and find it under the "<a href="https://rumbletalk-images-upload.s3.amazonaws.com/cbc4b58e0cc1741689eb1d8c80959989/1474448961-keys-location.png" target="_blank">Account</a>" info.
										 </span>
										</td>                                    
									</tr>									
                                    <tr name="tokens_row" <?php if ($hideToken) echo 'style="display: none"' ?>>
                                        <td colspan="2">
                                            <b>Token Key:</b>
                                            <input type="text" name="rumbletalk_chat_token_key" id="rumbletalk_chat_token_key" style="width: 300px; float: right;"
                                                value="<?= htmlspecialchars(get_option("rumbletalk_chat_token_key")) ?>" maxlength="32">
                                        </td>
                                    </tr>
                                    <tr name="tokens_row" <?php if ($hideToken) echo 'style="display: none"' ?>>
                                        <td colspan="2">
                                            <b>Token Secret:</b>
                                            <input type="text" name="rumbletalk_chat_token_secret" id="rumbletalk_chat_token_secret" style="width: 300px; float: right;"
                                                value="<?= htmlspecialchars(get_option("rumbletalk_chat_token_secret")) ?>" maxlength="64">
                                        </td>
                                    </tr>
                                    <tr name="tokens_row" <?php if ($hideToken) echo 'style="display: none"' ?>>
                                        <td width="180" colspan="2">
	                                    	<button id="update_chatroom" style="width: 300px">Update chatroom with new Token</button>
											<br><br>
                                        </td>
                                    </tr>
									<tr>
										<td colspan="2" style="padding-bottom:5px;padding-top:20px;">
										   <table width="100%">
										     <tr>
										       <td align="left"  style="padding-left:10px;">
										           <img width="95px" src="<?= $this->cdn ?>blog/floatembed/180x120-01.jpg">
										       </td>
										       <td align="left" style="padding-top:10px;padding-left:20px;">
										           <img width="95px" src="<?= $this->cdn ?>blog/floatembed/180x120-02.jpg">
										       </td>
										     </tr>
										     <tr>
										       <td align="left" style="padding-left:20px;">Chat in a page</td>
										       <td align="left" style="padding-left:10px;">Floating chat (toolbar)</td>
										     </tr>
										   </table>
										</td>
									</tr>
									<tr>
									  <td colspan="2">
									    <table style="padding-top:20px;">
									    	<tr>
										       <td align="left" style="padding-left:5px;"><u><b>Chat HASH</b></u></td>
											   <td align="left" style="padding-left:28px;"><u><b>Width</b></u></td>
											   <td align="left" style="padding-left:22px;"><u><b>Height</b></u></td>
											   <td align="left" style="padding-left:10px;"><u><b>Floating</b></u></td>
											   <td align="left" style="padding-left:5px;"><u><b>Members</b></u></td>
										    </tr>
										</table>	
									  <td>
									</tr>
									<tr>
                                        <td colspan="2">
                                        	<div name="selected_rumbletalk_chat_code" id="selected_rumbletalk_chat_code">
                                        		<?php
                                        		$names = explode(',', get_option('rumbletalk_chat_names'));
                                        		$hashes = explode(',', get_option('rumbletalk_chat_hashes'));
                                        		$ids = explode(',', get_option('rumbletalk_chat_ids'));
                                        		$options = json_decode(get_option('rumbletalk_chat_chats'), true);
                                        		foreach ($names as $key => $name) {
                                        			$hash = $hashes[$key];
                                        			$id = $ids[$key];
                                        			$width = '';
                                        			$height = '';
                                        			$floating = false;
                                        			$membersOnly = false;
                                        			$chatOptions = isset($options[$hash]) ? $options[$hash] : null;
                                        			if ($chatOptions) {
                                        				if (isset($chatOptions['width']))
                                        					$width = $chatOptions['width'];
                                        				if (isset($chatOptions['height']))
                                        					$height = $chatOptions['height'];
                                        				if (isset($chatOptions['floating']))
                                        					$floating = $chatOptions['floating'];
                                        				if (isset($chatOptions['membersOnly']))
                                        					$membersOnly = $chatOptions['membersOnly'];
                                        			}
                                        			echo '<div data-id="'.$id.'">';
                                        			echo '<input type="text" readonly value="' . $hash . '" size="7" /> &nbsp; ';
                                        			//echo 'Size';
													echo '<input type="text" name="width" value="' . $width . '" size="2" /> x ';
													echo '<input type="text" name="height" value="' . $height . '" size="2" /> &nbsp;&nbsp;&nbsp;&nbsp; ';
													echo '<input type="checkbox" name="floating"' . ($floating ? ' checked' : '') . '/> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
													echo '<input type="checkbox" title="Allow members to automatically log-in" name="membersOnly"' . ($membersOnly ? ' checked' : '') . '/>&nbsp;&nbsp;&nbsp; ';
													
													echo '<a name="modal-chat-settings-link" href="#" title="Change chat room settings and style" name="Settings" data-hash="' . $hash . '">Settings</a>&nbsp;&nbsp;';
                                        			echo '<a name="upgrade_chatroom" style="color:red;" target="_blank" href="https://www.rumbletalk.com/upgrade/?hash='. $hash . ' " title="Upgrade your account, get more chat seats and create more rooms">Upgrade</a>&nbsp;&nbsp;&nbsp;';
													echo '<a name="delete_room" href="#" class="delete_chatroom" title="Delete chat room">Delete</a>';
                                        			
                                        			echo '</div>';
                                        		}
                                        		?>
                                        	</div>
                                        	&nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-top:20px;">
	                                        <input type="submit" value="<?php _e("Save Changes") ?>" title="save changes when you add rooms or change chat dimention">
                                            <input type="button" id="create_new_chatroom" value="Add New Chat Room" title="created a new chat room">
                                            <span id="chatrooms_refresh" title="refresh data"></span>
                                        </td>
                                    </tr>										
									<tr>
									  <td colspan="2" style="padding-bottom:5px;padding-top:20px;">
										  <table>
											  <tr>
												  <td  colspan="2" align="left" valign="top">
													  <b><u>How to set your chat?</u></b>
												  </td>
											  </tr>
											  <tr>
												  <td align="left" valign="top" style="padding-top:15px;">
													  <img  width="32px" src="<?= $this->cdn ?>admin/images/SQ-about.png" />
												  </td>
												  <td style="padding-left:5px;">
													  Add the exact text
                                                      <b style="font:arial 8px none; color:#68A500">&#91;rumbletalk-chat&#93;</b>
													  <br/>
                                                      to your visual editor where you want your chat to show.....and you are done.
												  </td>
											  </tr>
											  <tr>
												  <td style="padding-top:15px;" align="left" valign="top">
													  <img width="32px" src="<?= $this->cdn ?>admin/images/SQ-contact.png" />
												  </td>
												  <td style="padding-left:5px;">
													  In case you have more than one chat, you can add the text with an exact chat HASH <b style="font:arial 8px none; color:#68A500">&#91;rumbletalk-chat hash="insert here your chat hash"&#93;</b>
												  </td>
											  </tr>
										 </table>
									   </td>
									  </tr>
	                
                                    <tr>
                                        <td></td>
                                        <td>
                                            <span style="font:arial 8px none; color:#AAACAD">
                                                
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" style="padding-top:10px;">
                                        	<ul class="ul-disc">
                                        		<li><b>Chatroom Hash</b>: This is a unique 8 characters chat room code. It is populated automatically once you register with RumbleTalk.</li>
                                        		<li><b>Chatroom width</b>: The width in pixels of your chat room.<br/>
                                                You can use percentages (e.g. 40%) or leave blank.</li>
                                        		<li><b>Chatroom height (size)</b>: The height of your chat room.<br/>
                                                You can use percentages (e.g. 40%) or leave blank.</li>
                                        		<li><b>Floating</b>: A floating toolbar chat. it will appear on your right bottom corner (you can change it to left of the screen).</li>
                                        		<li><b>Members</b>: Let members of your community automatically login to the chat with no need to supply user and password.
												If you wish to allow ONLY registered users to automatically login the chat. You should <a href="https://www.rumbletalk.com/support/API_Auto_Login/" target="_blank">"Force SDK"</a>. 
												</li>
												<li><a href="https://www.rumbletalk.com/about_us/contact_us/" target="_blank"> CONTACT US</a> for any question, we are friendly !!!</li>
												
                                        	</ul>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" style="padding-top:20px;"><span style="font:arial 8px none; color:green">&#42; In some wordpress themes, there are two known issues, please <br/>see below the way to handle it.</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="left"  style="padding-top:30px;"><img width="490" src="<?= $this->cdn ?>emails/Mailxa-04.png" /></td>
                                    </tr>
                                </table>
                            </form>
                        </div>

						<div id="modal-chat-settings" style="display:none;">
							<div id="modal-chat-settings-status"></div>
							<form id="modalSettingsForm" target="settingsIframe" action="https://iframe.rumbletalk.com/wp/index.php" method="post" style="display: none">
							    <input type="text" name="token" value="" />
							    <input type="text" name="chat_hash" value="" />
							    <input type="submit" />
							</form>
							
							<iFrame id="settingsIframe" src="" name="settingsIframe" style="width: 100%; height: 100%; border: 0;"></iFrame>
						</div>
                    </td>
                    <td  valign="top">

                        <div style="float:right; width:290px; border:1px #DEDEDD dashed; background-color:#FEFAE7; padding:10px 10px 10px 10px">
							<a href="//wordpress.org/support/view/plugin-reviews/rumbletalk-chat-a-chat-with-themes#postform"><img src="<?= $this->cdn ?>blog/5stars.png" style="padding-left:80px;"/>
							<br><span style="padding-left:110px;">rate us</span></a>
							<br><br>
							<b>Description:</b> The <a href="https://www.rumbletalk.com/?utm_source=wordpress&utm_medium=plugin&utm_campaign=fromplugin" target="_blank">RumbleTalk</a> Plugin is a boutique chat room Platform for websites, facebook pages and real-time events. Perfect for Communities, radios and live stream. It is available for all Wordpress installed versions.<br />
                            <br />
                            <b>Like the plugin? "Like" RumbleTalk Chat!</b>
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4&appId=181184391902159";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                            <div class="fb-like" data-href="https://www.facebook.com/rumbletalk" data-width="280" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div>

                            <br />
                            <br />

                            <table style="padding-top:15px;">
                                <tr>
                                    <td align="left" valign="top" style="padding-top:15px;">
									  <span style="font-size:15px;"><b>Get more with our <u>Premium</u> plans</b></span>
										<ul type="circle">
										    <li>* Allow more seats in your chat </li>
											<li>* Create more chat rooms</li>
										    <li>* Create private/public rooms </li>
										    <li>* Live one on eone video/Audio calls </li>
											<li>* Share Docs, Excel, PowerPoint, PDF</li>
											<li>* Upload Images from your own PC</li>
											<li>* Take pictures from your PC camera</li>
											<li>* Integrate your users base (members)</li>
										</ul>		
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top" style="padding-top:15px;">
                                         <a href="https://www.rumbletalk.com/upgrade/?hash=<?= $hash ?>" class="upgrade_button" target="_blank" title="Upgrade your account, create more rooms and get more chat seats">Upgrade your chat, Now!</a>
                                    </td>
                                </tr>
     						</table>
                        </div>
                        <div style="float:right; width:290px; border:1px #DEDEDD dashed; background-color:#FEFAE7; padding:10px 10px 10px 10px;">

                            <table align="center" style="padding-top:15px;">
                                <tr>
                                  <td style="padding-top:20px;padding-bottom:20px;">
                                    <span style="font-size:25px;"> Features </span>
                                  </td>
                                </tr>
                                <tr>
                                    <td>
										<ul type="circle">
										    <li>* Live video & Audio calls </li>
											<li>* Upload Docs, Excel, PowerPoint, PDF</li>
											<li>* Upload Images from your own PC</li>
											<li>* Take pictures from your PC camera</li>											
											<li>* Chat Theme Library</li>
											<li>* Talk from Mobile and Tablet</li>
											<li>* Login using Facebook and Twitter</li>
											<li>* Private chat</li>
											<li>* One chat for your WP and facebook page</li>
											<li>* SSL- talk in a secure channel</li>
											<li>* Advance design with css </li>
											<li>* Manage as many chats as you like</li>
											<li>* Spam filter (create a black listed words)</li>
											<li>* Ban, Delete Trolls</li>
											<li>* Define moderators and rolls</li>
											<li>* Save and export your chat history</li>
											<li>* Chat in 30 languages</li>
											<li>* Offline Mode (when you are not around)</li>
											<li>* Delete single messages</li>
											<li>* Flood control</li>
											<li>* Cool smilies </li>
										</ul>
									</td>
								</td>
                                <tr>
                                    <td align="center" valign="top" style="padding-top:25px;">
										<b>Homepage:</b> <a href="https://www.rumbletalk.com/?utm_source=wordpress&utm_medium=plugin&utm_campaign=fromplugin" target="_blank">RumbleTalk Home</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top" style="padding-top:5px;">
                                         <b>Facebook:</b> <a href="https://www.facebook.com/rumbletalk" target="_blank">Facebook Fan Page</a>
                                    </td>
                                </tr>
     						</table>
                        </div>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td width="500" valign="top">
                    	<div id="options_troubleshooting"<?= get_option("rumbletalk_chat_code") == '' ? ' style="display:none;"' : '' ?>>
							<div style="width:500px;">
								<table>
									<tr>
										<td align="left" valign="top" width="20">
											<img  width="32px" src="<?= $this->cdn ?>admin/images/SQ-faq.png" />
										</td>
										<td style="padding-left:5px;">
											<span style="font:arial; font-size:14px;color:#73AC00">Troubleshooting</span> <br/>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="padding-left:5px;padding-top:10px;">
											RumbleTalk chat room is elastic and can expand to any size. In some themes you might run into 2 possible issues.<br/>
											1 - The height is harder to adjust.<br/>
											2 - Some elements in the page are missing (not shown).<br/><br/>

											The solution: remove RumbleTalk plugin. Than get the full chat code (see below) via the admin panel. add the chatroom code below directly into the html of the page.<br/><br/>
											<span style="font:arial 8px none; color:green">&#42; Copy and paste the code below into your html, make sure you replaced the <b>chatcode HASH</b> with your own chatroom code.</span><br/><br/>

											<div>
												<code>
                                                    <?= htmlspecialchars($this->embed()) ?>
												</code>
											</div>
										</td>
									</tr>
								</table>
							</div>
							<div style="width:500px;">
								<table style="padding-top:20px;">
									<tr>
										<td align="left" valign="top" width="20">
											<img  width="32px" src="<?= $this->cdn ?>admin/images/SQ-faq.png" />
										</td>
										<td style="padding-left:5px;">
											<span style="font:arial; font-size:14px;color:#73AC00">Worpress Hosted</span> <br/>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="padding-left:5px;padding-top:10px;">
											If your website is hosted by wordpress, you are ni able to use RumbleTalk :-(.
                                            <br>
											Wordpress prevent 3rd party widgets to be included in the hosted version.
										</td>
									</tr>
								</table>
							</div>
						</div>
                    </td>
                    <td valign="top">
                        <div style="float:right; width:290px; border:1px #DEDEDD dashed; background-color:#FEFAE7; padding:10px 10px 10px 10px">
                            With RumbleTalk you may create your own chat design (theme), share images and videos, talk from your mobile and even add the same chat installed on your website to your facebook page.
                            <br>
                            <br>
                            <a target="_blank" href="<?= $this->cdn ?>blog/dana1_ppt_godepression.png">
                                <img width="100" src="<?= $this->cdn ?>blog/dana1_ppt_godepression.png" />
                            </a>
                            <a target="_blank" href="<?= $this->cdn ?>images/donotuseyet.png">
                                <img width="100" src="<?= $this->cdn ?>images/donotuseyet.png" />
                            </a>
                            <br>
                            <a target="_blank" href="<?= $this->cdn ?>images/blog/DeleteMessages.png">
                                <img width="100" src="<?= $this->cdn ?>images/blog/DeleteMessages.png" />
                            </a>
                            <a target="_blank" href="<?= $this->cdn ?>images/blog/DeleteAllMessages2.png">
                                <img width="100" src="<?= $this->cdn ?>images/blog/DeleteAllMessages2.png" />
                            </a>
                            <br>
                            <br>
                            <b>Thanks:</b> Thank you for using RumbleTalk plugin. If you have any issues, suggestions or praises send us an email to support@rumbletalk.com
                        </div>
                    </td>
                </tr>
            </table>

			<script type="text/javascript">
                var jQuery = jQuery || $;
                jQuery(function($){

                	function showErrorMessage(error) {
                		var $ = jQuery;
                		$('#modal-window-error').empty();
                		$('#modal-window-error').append($('<p>'));
                		$('#modal-window-error p').text(error);
                		$('#modal-window-error').append($('<div style="text-align: center; bottom: 20px;position: absolute;width: 90%;"><button onClick="jQuery(\'#TB_closeWindowButton\').click();">Close</button></div>'));
						// <a href="#TB_inline?width=600&height=550&inlineId=modal-window-error" class="thickbox">Information</a>

						// <div id="modal-window-id" style="display:none;">
						//     <p>.</p>
						// </div>						
						tb_show('Information', '#TB_inline?width=300&height=250&inlineId=modal-window-error');
                        setTimeout(function() {
                                $('#TB_ajaxContent').css('height', '220px');
                                $('#TB_window').addClass('visibleImportant');
                            }, 
                            100);
                	}

                	function showModalConfirmation(title, text, onSuccessString) {
                		var $ = jQuery;
                		$('#modal-window-confirmation').empty();
                		$('#modal-window-confirmation').append($('<p>'));
                		$('#modal-window-confirmation p').text(text);
                		$('#modal-window-confirmation').append($('<div style="text-align: center; bottom: 20px;position: absolute;width: 90%;"><button onClick="jQuery(\'#TB_closeWindowButton\').click();">Cancel</button> <button onClick="' + onSuccessString + '; jQuery(\'#TB_closeWindowButton\').click();">Confirm</button></div>'));
						// <a href="#TB_inline?width=600&height=550&inlineId=modal-window-error" class="thickbox">Error</a>

						// <div id="modal-window-id" style="display:none;">
						//     <p>.</p>
						// </div>						
						tb_show(title, '#TB_inline?width=300&height=250&inlineId=modal-window-confirmation');
                        setTimeout(function() {
                                $('#TB_ajaxContent').css('height', '100px');
                                $('#TB_window').addClass('visibleImportant');
                            }, 
                            100);
                	}

                	function showModalPrompt(title, text, onSuccessString) {
                		var $ = jQuery;
                		$('#modal-window-prompt').empty();
                		$('#modal-window-prompt').append($('<p>'));
                		$('#modal-window-prompt p').html(text + ' <input type="text" size="30" id="modal-prompt-value" />');
                		$('#modal-window-prompt').append($('<div style="text-align: center; bottom: 20px;position: absolute;width: 90%;"><button onClick="jQuery(\'#TB_closeWindowButton\').click();">Cancel</button> <button onClick="var value=jQuery(\'#modal-prompt-value\').val(); ' + onSuccessString + '; jQuery(\'#TB_closeWindowButton\').click();">Continue</button></div>'));
						// <a href="#TB_inline?width=600&height=550&inlineId=modal-window-error" class="thickbox">Error</a>

						// <div id="modal-window-id" style="display:none;">
						//     <p>.</p>
						// </div>						
						tb_show(title, '#TB_inline?width=300&height=250&inlineId=modal-window-prompt');
                        setTimeout(function() {
                                $('#TB_ajaxContent').css('height', '120px');
                                $('#TB_window').addClass('visibleImportant');
                            }, 
                            100);
                	}

                	var create_form = document.getElementById("create_form"); /*,
						wait_error;

					function submit_in_frame(data)
					{
						clearTimeout( wait_error );

						var response = data.crID;
						if (!isNaN(parseFloat(response)) && isFinite(response))
						{
							alert(error_message(response));
							document.getElementById( "create_chat_button" ).style.display = "inline";
							document.getElementById( "loading_gif" ).style.display = "none";
						}
						else
						{
							document.getElementById('rumbletalk_chat_code').value = response;
							document.getElementById('options_form').submit();
						}

						var jsonp = document.getElementById(data.id);
						jsonp.parentNode.removeChild(jsonp);
					} */

					function error_message( id )
					{
						var message;

						switch ( parseInt( id ) )
						{
							case -1:
								message = "Please enter a valid email address";
								break;

							case -2:
								message = "The password must be at least 6 characters long (spaces are ignored!)";
								break;

							case -3:
								message = "The email address already exists";
								break;

							case -7:
								message = "Please retype the same password";
								break;

							case -11:
								message = "The automatic creation has failed. Please create the account manually. You can find more details in the 'Troubleshooting' section";
								break;

							default:
								message = "Ooops, could not complete the operation, please try again later";
						}

						return message;
					}
					window.error_message = error_message;

					function toggle_create_account( which ) {
						if ( which == 1 ) {
							document.getElementById( "create_form" ).style.display = 'none';
							document.getElementById( "options_form" ).style.display = 'inline';
							document.getElementById( "options_troubleshooting" ).style.display = 'inline';
						} else {
							document.getElementById( "create_form" ).style.display = 'inline';
							document.getElementById( "options_form" ).style.display = 'none';
							document.getElementById( "options_troubleshooting" ).style.display = 'none';
						}
					}
					window.toggle_create_account = toggle_create_account;

					function validate_account_creation( form ) {
						var email = form.elements[ "email" ],
							password = form.elements[ "password" ],
							password_c = form.elements[ "password_c" ];

						if ( !(/^[-0-9A-Za-z!#$%&'*+\/=?^_`{|}~.]+@[-0-9A-Za-z!#$%&'*+\/=?^_`{|}~.]+/).test( email.value ) ) {
							showErrorMessage( error_message( -1 ) );
							email.focus();
							return false;
						}

						if ( password.value.length < 6 ) {
							showErrorMessage( error_message( -2 ) );
							password.focus();
							return false;
						}

						if ( password.value != password_c.value ) {
							showErrorMessage( error_message( -7 ) );
							password_c.focus();
							return false;
						}

						document.getElementById( "create_chat_button" ).style.display = "none";
						document.getElementById( "loading_gif" ).style.display = "inline";

						/*wait_error = setTimeout( 'error_message(-11);', 30000 );

						var jsonp = document.createElement("SCRIPT"),
							d = new Date(),
							t = d.getTime();
						jsonp.id = 'rt-' + t;
						jsonp.src = '//www.rumbletalk.com/_ajax_reg_remote.php?return_code=1&email=' + email.value + '&password=' + password.value + '&id=' + jsonp.id;
						document.getElementsByTagName( 'head' )[ 0 ].appendChild( jsonp );*/

						return true; // do submit the form

					}
					window.validate_account_creation = validate_account_creation;

					function float_check_box( checkbox ) {
						var width_tr = document.getElementById( "chat_width" ),
							height_tr = document.getElementById( "chat_height" );

						if ( checkbox.checked ) {
							width_tr.getElementsByTagName( "input" )[ 0 ].disabled = true;
							height_tr.getElementsByTagName( "input" )[ 0 ].disabled = true;
						} else {
							width_tr.getElementsByTagName( "input" )[ 0 ].disabled = false;
							height_tr.getElementsByTagName( "input" )[ 0 ].disabled = false;
						}
					}
					window.float_check_box = float_check_box;

					function addChatRoomUI(hash, id, width, height, floating, membersOnly) {
						var div = jQuery('<div data-id="' + id + '">');
						div.append('<input type="text" readonly value="' + hash + '" size="7" /> &nbsp; ');
						div.append('<input type="text" name="width" value="' + width + '" size="2" /> x ');
						div.append('<input type="text" name="height" value="' + height + '" size="2" /> &nbsp;&nbsp;&nbsp;&nbsp; ');
						div.append('<input type="checkbox"' + (floating ? ' checked' : '') + ' name="floating" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ');
						div.append('<input type="checkbox"' + (membersOnly ? ' checked' : '') + ' name="membersOnly" />&nbsp;&nbsp;&nbsp;');
						//div.append('<button class="delete_chatroom">Delete</button>');
						
						div.append('<a name="modal-chat-settings-link" href="#" name="Settings" data-hash="' + hash + '">Settings</a>&nbsp;&nbsp;');
						div.append('<a name="upgrade_chatroom" style="color:red;" target="_blank" href="https://www.rumbletalk.com/upgrade/?hash='+ hash +' " title="Upgrade your account, get more chat seats and create more rooms">Upgrade</a>&nbsp;&nbsp;&nbsp;');
						div.append('<a name="delete_room" href="#" class="delete_chatroom" title="Delete chat room">Delete</a><br/>');
						
												
						jQuery('#selected_rumbletalk_chat_code').append(div);
					}


                    function validateUpdateChatroomBtn() {
                        setTimeout(
                            function() {
                                $('#update_chatroom').prop(
                                    'disabled',
                                    $('#rumbletalk_chat_token_key').val().length == 0 || $('#rumbletalk_chat_token_secret').val().length == 0
                                );
                            },
                            100
                        );
                    }
                    
                    validateUpdateChatroomBtn();

                    function updateChatRooms(isPageLoad) {
                        var key = $('#rumbletalk_chat_token_key').val();
                        var secret = $('#rumbletalk_chat_token_secret').val();

                        var data = { action: 'rumbletalk_update_chatrooms', key: key, secret: secret };

                        $('#chatrooms_refresh').hide();
                        $('#chatrooms_refresh').after('<img id="update_chatrooms_loading" src="<?php echo plugins_url('rolling.gif', __FILE__); ?>" style="width: 16; height: 16px; margin-bottom: -3px;" />');

                        $.ajax( {
                            url: ajaxurl,
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            success: function(data) {
                                if (data.status) {
                                	$('#selected_rumbletalk_chat_code').empty();
                                	var hashes = data.hashes.split(",").map(String);
                                	var names = data.names.split(",").map(String);
                                	var ids = data.ids.split(",").map(String);
                                	var options = JSON.parse(data.options);
                                	
                                	$.each(hashes, function(i, hash) {
	                                	var option = { width: '', height: '', floating: false, membersOnly: false };
	                                	if (options.hasOwnProperty(hash)) {
	                                		$.extend(option, options[hash]);
	                                	}
	                                	addChatRoomUI(hash, ids[i], option.width, option.height, option.floating, option.membersOnly);
                                	});
                                	// add handlers
                    				$('#selected_rumbletalk_chat_code input').change(onOptionChange);
									$('.delete_chatroom').click(onDeleteChatRoom);
									$('[name="modal-chat-settings-link"]').click(onModalChatSettingsOpen);

                                    // $('#rumbletalk_chat_code').val(hashes[0]);
                                	// $('#selected_rumbletalk_chat_code').val(chatHash);
                                } else if (!isPageLoad) {
                                    showErrorMessage('Info: ' + data.message);
                                }
                            },
                            complete: function() {
                                $('#update_chatrooms_loading').remove();
                                $('#chatrooms_refresh').show();
                            }
                        });
                    }

                    if (<?= get_option('rumbletalk_chat_names') && get_option('rumbletalk_chat_names') ? 'false' : 'true' ?>) {
                    	updateChatRooms(true);
                    }

                    function initModalSettingsFrame(accessToken, chatHash) {
                        $('#modal-chat-settings-status').html('');

                        $('#modalSettingsForm [name=token]').val(accessToken);
                        $('#modalSettingsForm [name=chat_hash]').val(chatHash);

                        $('#settingsIframe').show();
                        $('#modalSettingsForm').submit();

                        setTimeout(function() {
                                $('#TB_window').css('width', '1030px');
                                $('#TB_window').css('margin-left', '-515px');

                                $('#TB_ajaxContent').css('width', '1000px');
                                
                                $('#TB_window').addClass('visibleImportant');
                            }, 
                            100);
                    }

                    function setChatRoomOption(chatHash, name, value) {
                    	var options = {};
                    	options[name] = value;
                        var data = { action: 'rumbletalk_update_chatroom_options', hash: chatHash, options: options };
                        $.ajax( {
                            url: ajaxurl,
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            success: function(data) {
                                if (data.status) {
                                }
                                else {
                                    showErrorMessage('Error');
                                	// $('#rumbletalk_chat_code').val(oldHash);
                                }
                            },
                            complete: function() {
                            }
                        });
                    }

                    function doDeleteChatRoom(id) {
                    	var $ = jQuery;
                        var key = $('#rumbletalk_chat_token_key').val();
                        var secret = $('#rumbletalk_chat_token_secret').val();
                       	var deleteBtn = $('#selected_rumbletalk_chat_code').parent().find('[data-id="' + id + '"] .delete_chatroom');
                       	// showErrorMessage(id);
                        // return;
                        var data = { action: 'rumbletalk_delete_chatroom', key: key, secret: secret, id: id };

                        deleteBtn.hide();
                        deleteBtn.after('<img id="delete_chatroom_loading" src="<?php echo plugins_url('rolling.gif', __FILE__); ?>" style="width: 20px; height: 20px; margin-left: 30px; margin-bottom: -5px;" />');

                        $.ajax( {
                            url: ajaxurl,
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            success: function(data) {
                                if (data.status) {
                                	$('#selected_rumbletalk_chat_code').find('[data-id="' + id + '"]').remove();
                                }
                                else {
                                    showErrorMessage('Info: ' + data.message);
                                }
                            },
                            complete: function() {
                                $('#delete_chatroom_loading').remove();
                                deleteBtn.show();
                            }
                        });
                    }
                    window.doDeleteChatRoom = doDeleteChatRoom;

                    function onDeleteChatRoom(e) {
                        e.preventDefault();

                        var id = $(e.target).parent().attr('data-id');
                        showModalConfirmation('Please confirm ChatRoom deletion', 'Are you sure you want to delete this ChatRoom?', 'doDeleteChatRoom(\'' + id + '\'); ');
                    }

                    function onModalChatSettingsOpen(e) {
                    	e.preventDefault();
                    	tb_show('', '#TB_inline?width=1000&height=700&inlineId=modal-chat-settings');

                        $('#settingsIframe').hide();
                        $('#modal-chat-settings-status').html('<div style="padding: 20px">Loading...</div>');
                        var key = '<?php echo get_option("rumbletalk_chat_token_key") ?>';
                        var secret = '<?php echo get_option("rumbletalk_chat_token_secret") ?>';
                        var data = { action: 'rumbletalk_get_access_token', key: key, secret: secret };
                        var hash = $(e.target).attr('data-hash');

                        if (rumbleTalkOptions.accessToken) {
                            // use existing access tokens
                            setTimeout(
                                function() {
                                    initModalSettingsFrame(rumbleTalkOptions.accessToken, hash);
                                },
                                100
                            );
                        } else {
                            // Firstly get access token
                            $.ajax( {
                                url: ajaxurl, 
                                data: data,
                                type: 'POST',
                                dataType: 'json',
                                success: function(data) {
                                    if (data.status) {
                                        // Then load data into iframe
                                        rumbleTalkOptions['accessToken'] = data.accessToken;
                                        initModalSettingsFrame(data.accessToken, hash);
                                    } else {
                                    	$('#TB_ajaxContent').empty();
                                        showErrorMessage('Info: ' + data.message);
                                    }
                                }
                            });
                        }
                    }

                    // Show modal settings
                    $('[name="modal-chat-settings-link"]').click(onModalChatSettingsOpen);

                    $('#rumbletalk_chat_token_key').change(validateUpdateChatroomBtn);
                    $('#rumbletalk_chat_token_secret').change(validateUpdateChatroomBtn);
                    $('#rumbletalk_chat_token_key').keyup(validateUpdateChatroomBtn);
                    $('#rumbletalk_chat_token_secret').keyup(validateUpdateChatroomBtn);

                    $('#display_token').click(function(e) {
                        if ($('[name=tokens_row]').css('display') == 'none')
                            $('#display_token').html('Hide token');
                        else
                            $('#display_token').html('Token is set: change token');
                        $('[name=tokens_row]').toggle();
                        e.preventDefault();
                    });

                    $('#update_chatroom').click(function(e) {
                        var key = $('#rumbletalk_chat_token_key').val();
                        var secret = $('#rumbletalk_chat_token_secret').val();
                        var data = { action: 'rumbletalk_apply_new_token', key: key, secret: secret };

                        $('#update_chatroom').hide();
                        $('#update_chatroom').after('<img id="update_chatroom_loading" src="<?php echo plugins_url('rolling.gif', __FILE__); ?>" style="width: 20px; height: 20px; margin-left: 140px;" />');

                        $.ajax( {
                            url: ajaxurl, 
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            success: function(data) {
                                if (data.status) {
                                	window.location.reload();
                                    // $('#rumbletalk_chat_code').val(data.hash);
                                }
                                else {
                                    showErrorMessage('Info: ' + data.message);
                                }
                            },
                            complete: function() {
                                $('#update_chatroom_loading').remove();
                                $('#update_chatroom').show();
                            }
                        });
                        e.preventDefault();
                    });

                    function doCreateChatRoom(chatName) {
                        if (!chatName)
                        	return true;

                    	var $ = jQuery;
                        var key = $('#rumbletalk_chat_token_key').val();
                        var secret = $('#rumbletalk_chat_token_secret').val();
                        var data = { action: 'rumbletalk_create_new_chatroom', key: key, secret: secret, chatName: chatName };

                        $('#create_new_chatroom').hide();
                        $('#create_new_chatroom').after('<img id="create_new_chatroom_loading" src="<?php echo plugins_url('rolling.gif', __FILE__); ?>" style="width: 20px; height: 20px; margin-left: 40px;" />');

                        $.ajax({
                            url: ajaxurl, 
                            data: data,
                            type: 'POST',
                            dataType: 'json',
                            success: function(data) {
                                if (data.status) {
                                	var chatHash = data.hash;
                                	var id = data.id;
                                	var options = data.options;
                                    addChatRoomUI(chatHash, id, '', '', false, false);
                                	// add handlers
                    				$('#selected_rumbletalk_chat_code input').change(onOptionChange);
									$('.delete_chatroom').click(onDeleteChatRoom);
									$('[name="modal-chat-settings-link"]').click(onModalChatSettingsOpen);
                                }
                                else {
                                    showErrorMessage('Info: ' + data.message);
                                }
                            },
                            complete: function() {
                                $('#create_new_chatroom_loading').remove();
                                $('#create_new_chatroom').show();
                            }
                        });
                    }
                    window.doCreateChatRoom = doCreateChatRoom;

                    $('#create_new_chatroom').click(function(e) {
                        e.preventDefault();
                        showModalPrompt('New ChatRoom', 'Please enter new ChatRoom name', 'doCreateChatRoom(value); ');
                    });

                    $('#chatrooms_refresh').click(function(e) {
                        e.preventDefault();
                        updateChatRooms();
                    });

                    $('.delete_chatroom').click(onDeleteChatRoom);

                    function onOptionChange(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var chatHash = $(e.target).parent().children('input:first').val();
                        var name = $(e.target).attr('name');
                        setChatRoomOption(chatHash, name, $(e.target).attr('type') == 'checkbox' ? $(e.target).prop('checked') : $(e.target).val());
                        return false;
                    }

                    // chat settings changed
                    $('#selected_rumbletalk_chat_code input').change(onOptionChange);

                    var rumbleTalkOptions = {
                    };

                    // prevent from submitting on ENTER key
					$("form").keypress(function (e) {
					    if (e.keyCode == 13) {
					    	if ($(e.target).parent().attr('data-id')) {
						        e.preventDefault();
					    		$(':focus').blur();
					    	}
					    }
					});

                }(jQuery));
			</script>
        </div>
        <?php
    }

    public function embed($attr = null) {
    	// var_dump ($attr);
    	$hash = isset($attr["hash"]) ? $attr["hash"] : null;
    	if (!$hash) {
    		$hash = explode(',', get_option('rumbletalk_chat_hashes'));
    		if ($hash)
    			$hash = $hash[0];
    	}
    	if (empty($hash))
    		return '';

    	// default options
    	$chatOptions = array(
	    	'height' => '',
	    	'width' => '',
	    	'floating' => false,
	    	'membersOnly' => false
    	);

    	$options = json_decode(get_option('rumbletalk_chat_chats'), true);
    	if (isset($options[$hash])) {
			$chatOptions = $options[$hash];
    	} elseif (get_option('rumbletalk_chat_member')) {
            $chatOptions['membersOnly'] = true;
        }
    	// var_dump($chatOptions);

        $isw = ( preg_match('/^\d{1,4}%?$/', $chatOptions['width']) == 1 );
        
        if (preg_match('/^\d{1,4}%?$/', $chatOptions['height']) != 1) {
            $chatOptions['height'] = '500';
        }
        
        $style = "height: {$chatOptions['height']}px;" . ($isw ? " max-width: {$chatOptions['width']}px;" : '');
        
        $str = '<div style="' . $style . '">';

        if ($chatOptions['membersOnly']) {
            $current_user = wp_get_current_user();
            if ($current_user->display_name) {
                $loginInfo = array(
                    'username' => $current_user->display_name,
                    'hash' => $hash
                );
            ?>
    <script type="text/javascript">
    rtmq('login', <?= json_encode($loginInfo) ?>);
    </script>
            <?php
            }
        }

        $code = $hash;
        if (!empty($chatOptions['floating'])) {
            $code .= '&1';
        }
        
        $divId = 'rt-' . md5($code);
        $str .= '<div id="' . $divId . '"></div>';
        $url = "https://www.rumbletalk.com/client/?" . $code;
        $str .= '<script type="text/javascript" src="'. $url . '"></script>';
        $str .= '</div>';

        return $str;
    }

    public function install() {
        foreach ($this->options as $opt) {
            add_option($opt);
        }
    }

    public function unInstall() {
        foreach ($this->options as $opt) {
            delete_option($opt);
        }
    }
}

new RumbleTalkChat();
?>