<?php

class mega_messages_skin {

public static function dashboard( $value ) {
$content = empty( $value->content ) ? "This is mega message box" : $value->content;
$value->page_filter = ! empty( $value->page_filter ) ? json_decode( $value->page_filter ) : [];
$types = ["px", "%", "cm"];
?>
<div id='wp_mega_messages_wrapper'>
    <div id='wp_mega_messages_tabs'>
        <ul>
        <li data-tab='settings' class='wp_mega_messages_tabs_select'>Settings</li>
        <li data-tab='effects'>Effects</li>
        <li data-tab='customize'>Customize</li>
        <li data-tab='position'>Position</li>
        <li style='color: rgb(194, 194, 194); cursor: default; position: relative'>Templates <font style='font-size: 10px;position: absolute;right: -27px;top: 10px;'>(soon)</font></li>
        </ul>
        <form method='post'>
        <input type="submit" name='preview' id="live-preview" class="button button-primary" value="Live Preview" />
        </form>
    </div>
    
    <div id='wp_mega_messages_control'>
        <div class="wrap">
            <form method="post">
            <div data-tab-id='settings' class='wp_mega_messages_tabs_block'>
                <input type="hidden" name="action" value="update">
                <table class="form-table">
                <tbody>
                <tr>
                <th scope="row"><label for="wp_message_box_active">Activate</label></th>
                <td>
                    <input type='checkbox' name='active' id='wp_message_box_active' <?php print $value->active == 1 ? "checked" : ""; ?> />
                    <p class="description">Activate the message box</p>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="wp_message_box_frequency">Frequency</label></th>
                <td>
                    <select name="frequency" id="wp_message_box_frequency">
                    <option value="0" <?php print $value->frequency == 0 ? "selected" : ""; ?>>All the time</option>
                    <option value="1" <?php print $value->frequency == 1 ? "selected" : ""; ?>>Once a day</option>
                    <option value="2" <?php print $value->frequency == 2 ? "selected" : ""; ?>>Once a week</option>
                    <option value="3" <?php print $value->frequency == 3 ? "selected" : ""; ?>>Once a month</option>
                    <option value="4" <?php print $value->frequency == 4 ? "selected" : ""; ?>>Once a year</option>
                    <option value="5" <?php print $value->frequency == 5 ? "selected" : ""; ?>>Custom</option>
                    </select>
                    <p class="description">Select the frequency of the message basing on user cookie (<b><u>example</u>: 1 message a day</b>)</p>
                </td>
                </tr>
                <tr id='custom_frequency_display' style='display: <?php print $value->frequency == 5 ? "table-row" : "none"; ?>'>
                <th scope="row"><label for="wp_message_box_frequency">Custom Frequency</label></th>
                <td>
                    <input name="custom_frequency" type="text" id="custom_frequency" value="<?php print $value->custom_frequency; ?>" class="regular-text">
                    <select name="custom_frequency_type">
                    <option value="0" <?php print $value->custom_frequency_type == 0 ? "selected" : ""; ?>>Milliseconds</option>
                    <option value="1" <?php print $value->custom_frequency_type == 1 ? "selected" : ""; ?>>Seconds</option>
                    <option value="2" <?php print $value->custom_frequency_type == 2 ? "selected" : ""; ?>>Minutes</option>
                    <option value="3" <?php print $value->custom_frequency_type == 3 ? "selected" : ""; ?>>Hours</option>
                    <option value="4" <?php print $value->custom_frequency_type == 4 ? "selected" : ""; ?>>Days</option>
                    </select>
                    <p class="description">Write time in milliseconds \ seconds \ minutes \ hours \ days</p>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="wp_message_box_page_limit">Show in</label></th>
                <td>
                    <select name="filter_page[]" id="wp_message_box_page_limit" multiple>
                        <option value="1" <?php print in_array( 1, $value->page_filter ) ? "selected" : ""; ?>>Home</option>
                        <option value="2" <?php print in_array( 2, $value->page_filter ) ? "selected" : ""; ?>>Post</option>
                        <option value="3" <?php print in_array( 3, $value->page_filter ) ? "selected" : ""; ?>>Category</option>
                        <option value="4" <?php print in_array( 4, $value->page_filter ) ? "selected" : ""; ?>>Tag</option>
                        <option value="5" <?php print in_array( 5, $value->page_filter ) ? "selected" : ""; ?>>Archive</option>
                        <option value="6" <?php print in_array( 6, $value->page_filter ) ? "selected" : ""; ?>>Search</option>
                        <option value="0" <?php print in_array( 0, $value->page_filter ) ? "selected" : ""; ?>>Another Page</option>
                    </select>
                    <p class="description">
                        Choose where the message will appear, leave empty for all pages<br />
                        <b>Hold Ctrl for multiple select.</b>
                    </p>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="wp_message_box_auto_closing">Close automatically after:</label></th>
                <td>
                    <select name="auto_closing" id="wp_message_box_auto_closing">
                    <?php
                    for($i = 0; $i <= 100; $i++) :
                        print "<option value='{$i}' " . ( $value->auto_closing == $i ? "selected" : "" ) . ">" . ( $i == 0 ? "Off" : $i ) . "</option>";
                    endfor;
                    
                    for($i = 110; $i <= 300; $i+=10) :
                        print "<option value='{$i}' " . ( $value->auto_closing == $i ? "selected" : "" ) . ">{$i}</option>";
                    endfor;
                    
                    for($i = 350; $i <= 1000; $i+=50) :
                        print "<option value='{$i}' " . ( $value->auto_closing == $i ? "selected" : "" ) . ">{$i}</option>";
                    endfor;
                    ?>
                    </select>
                    <p class="description">Close the light message automatically (<b>in seconds</b>)</p>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="wp_message_box_close_button">Close Button</label></th>
                <td>
                    <input type='checkbox' name='close_button' id='wp_message_box_close_button' <?php print $value->close_button == 1 ? "checked" : ""; ?> />
                    <p class="description">Show or hide the close button</p>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="wp_message_box_content">Message Content</label></th>
                <td>
                    <?php wp_editor( $value->content, "wp_message_box_content", array( "textarea_name" => "content",
                                                                                       "media_buttons" => false,
                                                                                       "editor_height" => 200 ) ); ?> 
                    <p class="description">Write here the content of the message you want to display to the user</p>
                </td>
                </tr>
                </tbody>
                </table>
            </div>
            <div data-tab-id='effects'>
                <input type="text" name='bg_opacity' id='bg_opacity' value='<?php echo round( $value->opacity * 100 ); ?>' readonly>
                <table class="form-table">
                <tbody>
                <tr>
                <th scope="row"><label for="wp_message_box_bg_box_fade">Light Message Fading Effect</label></th>
                <td>
                    <input type='checkbox' id='wp_message_box_bg_box_fade' name='box_fading' <?php print $value->box_fading == 1 ? "checked" : ""; ?> />
                    <p class="description">Show and Hide your message box in fade effect</p>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="wp_message_box_bg_opacity">Background Opacity</label></th>
                <td>
                    <div id="slider-range"></div>
                    <p class="description">Highlight your message by background opacity</p>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="wp_message_box_bg_opacity_fade">Opacity Fading Effect</label></th>
                <td>
                    <input type='checkbox' id='wp_message_box_bg_opacity_fade' name='opacity_fade' <?php print $value->opacity_fade == 1 ? "checked" : ""; ?> />
                    <p class="description">Show and Hide your background opacity in fade effect</p>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="wp_message_box_draggable">Draggable</label></th>
                <td>
                    <input type='checkbox' id='wp_message_box_draggable' name='draggable' <?php print $value->draggable == 1 ? "checked" : ""; ?> />
                    <p class="description">The light message can be drag by the user</p>
                </td>
                </tr>
                </tbody>
                </table>
            </div>
            <div data-tab-id='position'>
                <input type='hidden' id='position_left' name='position_left' value='<?php echo $value->position_left; ?>' />
                <input type='hidden' id='position_top' name='position_top' value='<?php echo $value->position_top; ?>' />
                <div id='wp_mega_messages_position'>
                    <div id="wp_mega_messages_drag" style='<?php print (int)$value->box_width > 0  ? "width:" . $value->box_width . ( array_key_exists( (int)$value->box_width_type, $types ) ? $types[ $value->box_width_type ] : "px" ) . ";" : ""; ?>
                                                           <?php print (int)$value->box_height > 0 ? "height:" . $value->box_height . ( array_key_exists( (int)$value->box_height_type, $types ) ? $types[ $value->box_height_type ] : "px" ) . ";" : ""; ?>'>
                        <p>Drag the box to your position</p>      
                    </div>
                </div>
                <p class="description wp-message-box-desc-position">
                    The square above is a small version of your screen, drag the square inside in which location you want to set the message box in the screen.<br />
                    <strong>The system converts your choosing from pixel to percentages automatically.</strong>
                </p>
                <span id='wp_mega_messages_position_real_time'>
                    <!--<b>Top: </b> 0 | <b>Left: </b> 0-->
                </span>
            </div>
            <div data-tab-id='customize'>
                <div id='wp_mega_messages_customize_tabs'>
                    <ul>
                    <li data-tab='design' class='wp_mega_messages_customize_tabs_select'>Designs</li>
                    <li data-tab='custom'>Advanced</li>
                    </ul>
                </div>
                <div id='wp_mega_messages_customize_tabs_control'>
                    <div data-custom-tab-id='design' class='wp_mega_messages_tabs_block'>
                        <h3>Design Templates</h3>
                        <table class="form-table">
                        <tbody>
                            
                        <!-- None -->
                        <tr>
                        <th><label><input name="design" type="radio" value="" <?php print empty( $value->design ) ? "checked" : ""; ?>> None</label></th>
                        <td><div class='wp_mega_message_box wp_mega_message_box_block wp_mega_message_box_static'><?php print $content; ?></div></td>
                        </tr>
                        
                        <!-- Default -->
                        <?php $default = $value->design == "default" && ! empty( $value->pattern ) ? $value->pattern : "white"; ?>
                        <tr>
                        <th><label><input name="design" type="radio" id='default' value="default|<?php echo $default; ?>" <?php print $value->design == "default" ? "checked" : ""; ?>> Default</label></th>
                        <td>
                            <div class='wp_mega_message_box wp_mega_message_box_block wp_mega_message_box_static wp_mega_message_box_default wp_mega_message_box_<?php echo $default; ?>'><?php print $content; ?></div>
                            <span data-radio='default' class='pattern <?php if( ( $value->design == "default" && $value->pattern == "white" ) || $value->design != "default" ) echo "select"; ?>' data-pattern='white'></span>
                            <span data-radio='default' class='pattern <?php if( $value->design == "default" && $value->pattern == "blue"   ) echo "select"; ?>' data-pattern='blue'></span>
                            <span data-radio='default' class='pattern <?php if( $value->design == "default" && $value->pattern == "green"  ) echo "select"; ?>' data-pattern='green'></span>
                            <span data-radio='default' class='pattern <?php if( $value->design == "default" && $value->pattern == "red"    ) echo "select"; ?>' data-pattern='red'></span>
                            <span data-radio='default' class='pattern <?php if( $value->design == "default" && $value->pattern == "orange" ) echo "select"; ?>' data-pattern='orange'></span>
                            <span data-radio='default' class='pattern <?php if( $value->design == "default" && $value->pattern == "gray"   ) echo "select"; ?>' data-pattern='gray'></span>
                            <span data-radio='default' class='pattern <?php if( $value->design == "default" && $value->pattern == "dark"   ) echo "select"; ?>' data-pattern='dark'></span>
                        </td>
                        </tr>

                        <!-- Rounded -->
                        <?php $default = $value->design == "rounded" && ! empty( $value->pattern ) ? $value->pattern : "white"; ?>
                        <tr>
                        <th><label><input name="design" type="radio" id='rounded' value="rounded|<?php echo $default; ?>" <?php print $value->design == "rounded" ? "checked" : ""; ?>> Rounded</label></th>
                        <td>
                            <div class='wp_mega_message_box wp_mega_message_box_block wp_mega_message_box_static wp_mega_message_box_rounded wp_mega_message_box_<?php echo $default; ?>'><?php print $content; ?></div>
                            <span data-radio='rounded' class='pattern <?php if( ( $value->design == "rounded" && $value->pattern == "white" ) || $value->design != "rounded" ) echo "select"; ?>' data-pattern='white'></span>
                            <span data-radio='rounded' class='pattern <?php if( $value->design == "rounded" && $value->pattern == "blue"   ) echo "select"; ?>' data-pattern='blue'></span>
                            <span data-radio='rounded' class='pattern <?php if( $value->design == "rounded" && $value->pattern == "green"  ) echo "select"; ?>' data-pattern='green'></span>
                            <span data-radio='rounded' class='pattern <?php if( $value->design == "rounded" && $value->pattern == "red"    ) echo "select"; ?>' data-pattern='red'></span>
                            <span data-radio='rounded' class='pattern <?php if( $value->design == "rounded" && $value->pattern == "orange" ) echo "select"; ?>' data-pattern='orange'></span>
                            <span data-radio='rounded' class='pattern <?php if( $value->design == "rounded" && $value->pattern == "gray"   ) echo "select"; ?>' data-pattern='gray'></span>
                            <span data-radio='rounded' class='pattern <?php if( $value->design == "rounded" && $value->pattern == "dark"   ) echo "select"; ?>' data-pattern='dark'></span>
                        </td>
                        </tr>
                        
                        <!-- Opacity -->
                        <?php $default = $value->design == "opacity" && ! empty( $value->pattern ) ? $value->pattern : "owhite"; ?>
                        <tr>
                        <th><label><input name="design" type="radio" id='opacity' value="opacity|<?php echo $default; ?>" <?php print $value->design == "opacity" ? "checked" : ""; ?>> Opacity</label></th>
                        <td>
                            <div class='wp_mega_message_box wp_mega_message_box_block wp_mega_message_box_static wp_mega_message_box_opacity wp_mega_message_box_<?php echo $default; ?>'><?php print $content; ?></div>
                            <span data-radio='opacity' class='pattern <?php if( ( $value->design == "opacity" && $value->pattern == "owhite" ) || $value->design != "opacity" ) echo "select"; ?>' data-pattern='owhite'></span>
                            <span data-radio='opacity' class='pattern <?php if( $value->design == "opacity" && $value->pattern == "oblue"   ) echo "select"; ?>' data-pattern='oblue'></span>
                            <span data-radio='opacity' class='pattern <?php if( $value->design == "opacity" && $value->pattern == "ogreen"  ) echo "select"; ?>' data-pattern='ogreen'></span>
                            <span data-radio='opacity' class='pattern <?php if( $value->design == "opacity" && $value->pattern == "ored"    ) echo "select"; ?>' data-pattern='ored'></span>
                            <span data-radio='opacity' class='pattern <?php if( $value->design == "opacity" && $value->pattern == "oorange" ) echo "select"; ?>' data-pattern='oorange'></span>
                            <span data-radio='opacity' class='pattern <?php if( $value->design == "opacity" && $value->pattern == "ogray"   ) echo "select"; ?>' data-pattern='ogray'></span>
                            <span data-radio='opacity' class='pattern <?php if( $value->design == "opacity" && $value->pattern == "odark"   ) echo "select"; ?>' data-pattern='odark'></span>
                        </td>
                        </tr>
                        
                        <!-- Gardiant -->
                        <?php $default = $value->design == "gardiant" && ! empty( $value->pattern ) ? $value->pattern : "gwhite"; ?>
                        <tr>
                        <th><label><input name="design" type="radio" id='gardiant' value="gardiant|<?php echo $default; ?>" <?php print $value->design == "gardiant" ? "checked" : ""; ?>> Gardiant</label></th>
                        <td>
                            <div class='wp_mega_message_box wp_mega_message_box_block wp_mega_message_box_static wp_mega_message_box_gardiant wp_mega_message_box_<?php echo $default; ?>'><?php print $content; ?></div>
                            <span data-radio='gardiant' class='pattern <?php if( ( $value->design == "gardiant" && $value->pattern == "gwhite" ) || $value->design != "gardiant" ) echo "select"; ?>' data-pattern='gwhite'></span>
                            <span data-radio='gardiant' class='pattern <?php if( $value->design == "gardiant" && $value->pattern == "gblue"   ) echo "select"; ?>' data-pattern='gblue'></span>
                            <span data-radio='gardiant' class='pattern <?php if( $value->design == "gardiant" && $value->pattern == "ggreen"  ) echo "select"; ?>' data-pattern='ggreen'></span>
                            <span data-radio='gardiant' class='pattern <?php if( $value->design == "gardiant" && $value->pattern == "gred"    ) echo "select"; ?>' data-pattern='gred'></span>
                            <span data-radio='gardiant' class='pattern <?php if( $value->design == "gardiant" && $value->pattern == "gorange" ) echo "select"; ?>' data-pattern='gorange'></span>
                            <span data-radio='gardiant' class='pattern <?php if( $value->design == "gardiant" && $value->pattern == "ggray"   ) echo "select"; ?>' data-pattern='ggray'></span>
                            <span data-radio='gardiant' class='pattern <?php if( $value->design == "gardiant" && $value->pattern == "gdark"   ) echo "select"; ?>' data-pattern='gdark'></span>
                        </td>
                        </tr>
                        </tbody>
                        </table>
                        
                        <h3>Fonts</h3>
                        <table class="form-table">
                        <tbody>
                        
                        <tr>
                            <th scope="row"><label for="fontfamily">Font Family</label></th>
                            <td>
                                <select name="fontfamily" id='fontfamily'>
                                <option value="0" <?php print $value->font_family == 0 ? "selected" : ""; ?>>Cursive</option>
                                <option value="1" <?php print $value->font_family == 1 ? "selected" : ""; ?>>Fantasy</option>
                                <option value="2" <?php print $value->font_family == 2 ? "selected" : ""; ?>>Monospace</option>
                                <option value="3" <?php print $value->font_family == 3 ? "selected" : ""; ?>>Sans-Serif</option>
                                <option value="4" <?php print $value->font_family == 4 ? "selected" : ""; ?>>Serif</option>
                                </select>
                                <p class="description">Set the font family of the light message</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><label for="fontsize">Font Size</label></th>
                            <td>
                                <select name="fontsize" id='fontsize'>
                                <?php
                                for($i = 0; $i <= 35; $i++) :
                                    print "<option value='{$i}' " . ( $value->font_size == $i ? "selected" : "" ) . ">{$i}</option>";
                                endfor;
                                ?>
                                </select>
                                <p class="description">The size of the font in the light message</p>
                            </td>
                        </tr>
                        
                        </tbody>
                        </table>
                        
                        <h3>Message Box Size</h3>
                        <table class="form-table">
                        <tbody>
                        
                        <tr>
                            <th scope="row"><label for="box_size_width">Box Width</label></th>
                            <td>
                                <input name="box_size_width" type="text" id="box_size_width" placeholder='auto' value="<?php print $value->box_width == 0 ? "" : $value->box_width; ?>" class="regular-text">
                                <select name="box_size_width_type">
                                <option value="0" <?php print $value->box_width_type == 0 ? "selected" : ""; ?>>Pixel</option>
                                <option value="1" <?php print $value->box_width_type == 1 ? "selected" : ""; ?>>Percentage</option>
                                <option value="2" <?php print $value->box_width_type == 2 ? "selected" : ""; ?>>Centimeter</option>
                                </select>
                                <p class="description">The width of the light message</p>
                            </td>
                        </tr>
                        
                        <tr>
                            <th scope="row"><label for="box_size_height">Box Height</label></th>
                            <td>
                                <input name="box_size_height" type="text" id="box_size_height" placeholder='auto' value="<?php print $value->box_height == 0 ? "" : $value->box_height; ?>" class="regular-text">
                                <select name="box_size_height_type">
                                <option value="0" <?php print $value->box_height_type == 0 ? "selected" : ""; ?>>Pixel</option>
                                <option value="1" <?php print $value->box_height_type == 1 ? "selected" : ""; ?>>Percentage</option>
                                <option value="2" <?php print $value->box_height_type == 2 ? "selected" : ""; ?>>Centimeter</option>
                                </select>
                                <p class="description">The height of the light message</p>
                            </td>
                        </tr>
                        
                        </tbody>
                        </table>
                    </div>
                    <div data-custom-tab-id='custom'>
                        Soon
                    </div>
                </div>
            </div>
            
            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" onclick='return submitForm();' value="Save Changes"></p>
            </form>
        </div>
    </div>
    
    <!--
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="UP6A43V5NCDVU">
    <input type="image" src="https://www.paypalobjects.com/en_US/IL/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
    </form>
    -->
</div>
<?php
}

public function printError( $error ) {
return $error;
}

}