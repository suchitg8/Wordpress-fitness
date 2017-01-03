<?php

/*
 * @class inital main wordpress advacned backup class
 */

class mega_messages_admin extends mega_messages {
    
    /*
     * @var $db: handle database resources
     */
    
    public static $db = null;
    
    /*
     * @var $backup_class: backup stdclass
     */
    
    protected static $skin = null;
    
    /*
     * @method init: initalization advacned backup admin class
     */
    
    public static function init()
    {
        self::$skin = parent::getSkin();
        add_action( 'admin_menu', array( 'mega_messages_admin', 'manage_box' ) );
    }
    
    /*
     * @method managBackups: Index important files
     */
    
    public static function manage_box()
    {
        $hook = add_options_page( "Light Messages", "Light Messages", 'manage_options', 'light_messages', array( 'mega_messages_admin', 'manage_box_page' ) );
    }

    /*
     * @method manageBackupPage: backup page management
     */
    
    public static function manage_box_page() {
        global $wpdb;

        if( current_user_can( 'manage_options' ) ) {
            $handle_errors = self::$skin->handle_error;
            
            if( count( $handle_errors ) > 0 )
                print implode(".<br />", $handle_errors);
                
            // Require skin file
            self::selectAction( function( $action ){
                switch( $action ) {
                    case "update" :
                        self::updateAction();
                        break;
                }
                
                // Get database values
                if( ! ( self::$db = self::getDatabaseTable() ) ) {
                    print self::$skin->skin->printError("Sorry! The plugin failed in attemp to get information from the database :(");
                    return false;
                }

                // Require dashboard settings
                self::$skin->skin->dashboard( self::$db );
            });
        }
    }
    
    /*
     * @method selectAction: select action and throw it into method
     * @var function $callback: insert the callback of the action
     */

    public static function selectAction( $callback ) {
        $callback( isset( $_POST["action"] ) && ! empty( $_POST["action"] ) && is_string( $_POST["action"] ) ? $_POST["action"] : null );
    }
        
    /*
     * @method updateAction: update settings
     */
    
    public static function updateAction() {
        global $wpdb;
        
        list( $content, $frequency, $design, $active, $bg_opacity, $opacity_fade,
              $box_fading, $box_width, $box_width_type, $box_height, $box_height_type,
              $position_left, $position_top, $auto_closing, $close_button, $filter_page,
              $custom_frequency, $custom_frequency_type, $fontfamily, $fontsize, $draggable ) =
        [
            self::getParam("content"),
            self::getParam("frequency"),
            self::getParam("design"),
            self::getParam("active"),
            self::getParam("bg_opacity"),
            self::getParam("opacity_fade"),
            self::getParam("box_fading"),
            self::getParam("box_size_width"),
            self::getParam("box_size_width_type"),
            self::getParam("box_size_height"),
            self::getParam("box_size_height_type"),
            self::getParam("position_left"),
            self::getParam("position_top"),
            self::getParam("auto_closing"),
            self::getParam("close_button"),
            self::getParam("filter_page"),
            self::getParam("custom_frequency"),
            self::getParam("custom_frequency_type"),
            self::getParam("fontfamily"),
            self::getParam("fontsize"),
            self::getParam("draggable")
        ];
        
        list( $design, $pattern ) = array_pad( explode( "|", $design ), 2, "" );
        
        $wpdb->query(
            $wpdb->prepare(
                "UPDATE wp_mega_messages SET content = %s,
                                             frequency = %d,
                                             design = %s,
                                             pattern = %s,
                                             box_width = %d,
                                             box_width_type = %d,
                                             box_height = %d,
                                             box_height_type = %d,
                                             auto_closing = %d,
                                             custom_frequency = %d,
                                             custom_frequency_type = %d,
                                             font_family = %d,
                                             font_size = %d,
                                             position_top = %f,
                                             position_left = %f,
                                             close_button = %d,
                                             active = %d,
                                             opacity_fade = %d,
                                             box_fading = %d,
                                             draggable = %d,
                                             opacity = %f,
                                             page_filter = %s",
                
                str_replace( ["\\\"", "\'", "\n", "\r"], ["\"", "'", "<br />", ""], $content ),
                
                $frequency,
                $design,
                $pattern,
                $box_width,
                $box_width_type,
                $box_height,
                $box_height_type,
                $auto_closing,
                $custom_frequency,
                $custom_frequency_type,
                $fontfamily,
                $fontsize,
                
                $position_top  < 0 ? 0 : $position_top,
                $position_left < 0 ? 0 : $position_left,
                
                $close_button == "on" ? 1 : 0,
                $active       == "on" ? 1 : 0,
                $opacity_fade == "on" ? 1 : 0,
                $box_fading   == "on" ? 1 : 0,
                $draggable    == "on" ? 1 : 0,
                
                round( $bg_opacity / 100, 2 ),
                
                ! empty( $filter_page ) ? json_encode( $filter_page ) : ""
            )
        );
    }
}