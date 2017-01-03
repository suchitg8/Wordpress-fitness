<?php

/*
 * @class inital advanced backup main class
 */

class mega_messages_class {
    
    /*
     * @var Object $skin: handle skin
     */
    
    public $skin = null;
    
    /*
     * @method requireSkin: require skin file
     * @var String $file_name: file name of the skin
     */
    
    public function requireSkin( $file_name, $context_skin = true ) {
        require_once( MEGA_MESSAGES_VERSION_PLUGIN_DIR . "view/" . $file_name );
        $stdObj = new mega_messages_skin;

        if( ! $context_skin )
            return $stdObj;
        
        $this->skin = $stdObj;
    }
}

class mega_messages {
    
    /*
     * @var $backup_class: backup stdclass
     */
    
    protected static $skin = null;
    
    /*
     * @var $db: handle database resources
     */
    
    protected static $db = null;

    /*
     * @var int $whosPage check what is the current page
     */
    
    public static $whosPageInt = 0;
    
    /*
     * @method Object getDB(): get database value
     */
    
    public function getSkin() {
        $skin = new mega_messages_class;
        $skin->requireSkin("light_messages_skin.php");

        return $skin;
    }
    
    
    /*
     * @method void init(): init the message box
     */
    
    public static function init() {
        global $wpdb, $wp_query;

        // Init database table
        $wpdb->query("CREATE TABLE IF NOT EXISTS `wp_mega_messages` (
                        `content` text NOT NULL,
                        `frequency` int(10) NOT NULL DEFAULT '0',
                        `active` int(10) NOT NULL DEFAULT '0',
                        `design` varchar(20) NOT NULL DEFAULT 'default',
                        `pattern` varchar(20) NOT NULL DEFAULT 'white',
                        `box_fading` int(10) NOT NULL DEFAULT '0',
                        `opacity` float(10,2) NOT NULL DEFAULT '0.00',
                        `opacity_fade` int(10) NOT NULL DEFAULT '0',
                        `box_width` int(10) NOT NULL DEFAULT '0',
                        `box_height` int(10) NOT NULL DEFAULT '0',
                        `box_width_type` int(10) NOT NULL DEFAULT '0',
                        `box_height_type` int(10) NOT NULL DEFAULT '0',
                        `position_top` float(10,2) NOT NULL DEFAULT '2.00',
                        `position_left` float(10,2) NOT NULL DEFAULT '2.00',
                        `auto_closing` int(10) NOT NULL DEFAULT '50',
                        `close_button` int(10) NOT NULL DEFAULT '0',
                        `page_filter` text NOT NULL,
                        `custom_frequency` bigint(30) NOT NULL DEFAULT '0',
                        `custom_frequency_type` int(10) NOT NULL DEFAULT '0',
                        `font_family` int(10) NOT NULL DEFAULT '0',
                        `font_size` int(10) NOT NULL DEFAULT '15',
                        `draggable` int(10) NOT NULL DEFAULT '0'
                     )");
        
        $rows = $wpdb->get_results("SELECT * FROM wp_mega_messages");
        
        if( count( $rows ) == 0 )
            $wpdb->query("INSERT INTO `wp_mega_messages` (`content`, `frequency`, `active`, `design`, `pattern`, `box_fading`, `opacity`, `opacity_fade`, `box_width`, `box_height`, `box_width_type`, `box_height_type`, `position_top`, `position_left`, `auto_closing`, `close_button`, `custom_frequency`, `custom_frequency_type`, `font_family`, `font_size`, `draggable`)
                                                  VALUES ('Welcome to <strong>Light Messages</strong> Plugin !', 0, 0, 'default', 'white', 0, 0.00, 0, 0, 0, 0, 0, 2.00, 2.00, 50, 0, 0, 0, 0, 15, 0)");
        
        // Init plugin
        self::$db = self::getDatabaseTable();
        self::$skin = self::getSkin();
        self::getResources();
        
        if( ( ! is_admin() || self::isPreview() ) && self::getParam("load", $_GET) == "mega-message" ) {
            if( self::isPreview() || ( ( self::$db->frequency == 0 || ! isset( $_COOKIE["mega_message_cookie"] ) ) && ( empty( self::$db->page_filter ) || in_array( $_SESSION["page_id"], json_decode( self::$db->page_filter ) ) ) ) ) {
                require MEGA_MESSAGES_VERSION_PLUGIN_DIR . "class.light-messages-init.php";
            }
            
            exit;
        }
    }

    public static function whosPage() {
        global $wp_query;

        session_start();
        
        if ( $wp_query->is_page ) 
            $page_id = 0;
        else if ( $wp_query->is_home )
            $page_id = 1;
        else if ( $wp_query->is_single )
            $page_id = 2;
        else if ( $wp_query->is_category )
            $page_id = 3;
        else if ( $wp_query->is_tag )
            $page_id = 4;
        else if ( $wp_query->is_archive )
            $page_id = 5;
        else if ( $wp_query->is_search )
            $page_id = 6;
        else
            $page_id = null;
        
        // Init
        add_action( 'init_message_box', array( 'mega_messages', 'init' ) );
        do_action( "init_message_box" );
        
        // Set page ID session
        $_SESSION["page_id"] = $page_id;
    }
    
    /*
     * @method Boolean isPreview(): check if the page is live preview
     */
    
    public static function isPreview() {
        return self::getParam("preview") || self::getParam("wp_mbox_preview", $_GET);
    }
    
    /*
     * @method getParam: get global parameter
     * @var String $key: the key of the global scope
     */

    public static function getParam( $key, $global = null ) {
        $global = ( ! $global ? $_POST : ( isset( $global ) && count( $global ) > 0 ? $global : null ) );

        if( $global )
            if( array_key_exists( $key, $global ) )
                return $global[ $key ];
        
        return null;
    }
    
    /*
     * @method adminResources: set admin panel resources
     */
    
    public static function getResources() {
        if( ! is_admin() || self::isPreview() )
            wp_enqueue_script( 'mega_messages_script_init', get_home_url() . "/?load=mega-message" . ( self::isPreview() ? "&wp_mbox_preview=true" : "" ), array('jquery'), MEGA_MESSAGES_VERSION );
        
        wp_enqueue_style( 'mega_messages_stylesheet_jquery_ui', MEGA_MESSAGES_VERSION_PLUGIN_URL . 'view/css/jquery_ui.css', array(), MEGA_MESSAGES_VERSION );
        wp_enqueue_style( 'mega_messages_stylesheet', MEGA_MESSAGES_VERSION_PLUGIN_URL . 'view/css/light_messages.css', array(), MEGA_MESSAGES_VERSION );
        wp_enqueue_style( 'mega_messages_icon_stylesheet', MEGA_MESSAGES_VERSION_PLUGIN_URL . 'view/css/icons.css', array(), MEGA_MESSAGES_VERSION );
        wp_enqueue_script( 'mega_messages_script', MEGA_MESSAGES_VERSION_PLUGIN_URL . 'view/script/light_messages_script.js', array('jquery', 'jquery-ui-slider', 'jquery-ui-draggable'), MEGA_MESSAGES_VERSION );
    }
    
    /*
     * @method getDatabaseTable: get database from advanced backup table
     */
    
    public static function getDatabaseTable() {
        global $wpdb;
        
        $initTable = $wpdb->get_results("SELECT * FROM wp_mega_messages");
        
        if( count( $initTable ) > 0 )
            return current( $initTable );
        
        return false;
    }
    
    /*
     * @method void destroy_session() destroy session
     */
    
    public static function destroy_session() {
        session_destroy();
    }
}