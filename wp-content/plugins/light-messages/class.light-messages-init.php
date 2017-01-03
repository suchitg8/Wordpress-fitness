<?php
header("Content-Type: text/javascript");

class mega_messages_init extends mega_messages {
    
    /*
     * @var array $json: return the configuration of the message box
     */
    
    private $json = [];
    
    /*
     * @method void __construct(): init the json array
     */
    
    public function __construct()
    {
        // Check if the message is activated by the admin
        if( self::$db->active == 1 )
            $this->json["show_message"] = 1;
        
        // The conent from the database
        $content = self::$db->content;
            
        // Content of the message
        if( ! empty( $content ) )
            $this->json["content"] = htmlspecialchars( $content );
        
        // Design of the message
        if( ! empty( self::$db->design ) ) {
            $this->json["design"] = self::$db->design;
        
            if( ! empty( self::$db->pattern ) )
                $this->json["pattern"] = self::$db->pattern;
        }
        
        // Background Opacity
        if( self::$db->opacity > 0 ) {
            $this->json["opacity"] = (float)self::$db->opacity;
            
            if( self::$db->opacity_fade == 1 )
                $this->json["opacity_fade"] = (float)self::$db->opacity_fade;
        }
        
        // Message Box Opacity
        if( self::$db->box_fading )
            $this->json["box_fading"] = (int)self::$db->box_fading;
            
        // Message box size
        $this->json["box_width"] = [
            "size" => (int)self::$db->box_width,
            "type" => (int)self::$db->box_width_type
        ];
        
        $this->json["box_height"] = [
            "size" => (int)self::$db->box_height,
            "type" => (int)self::$db->box_height_type
        ];
        
        // Position of the box
        $this->json["position"] = [
            "top" => (float)self::$db->position_top,
            "left" => (float)self::$db->position_left,
        ];
        
        // The frequency of the message
        $this->json["frequency"] = (int)self::$db->frequency;
        
        // Custom frequency
        if( (int)self::$db->frequency == 5 ) {
            $this->json["custom_frequency"] = [
                "num"  => (int)self::$db->custom_frequency,
                "type" => (int)self::$db->custom_frequency_type
            ];
        }
        
        // Ignore cookies
        if( isset( $_GET["wp_mbox_preview"] ) )
            $this->json["cookie"] = true;
            
        // Auto closing
        $this->json["auto_closing"] = (int)self::$db->auto_closing;
        
        // Close button
        $this->json["close_button"] = (int)self::$db->close_button;
        
        // font settings
        $this->json["font"] = [
            "family" => (int)self::$db->font_family,
            "size"   => (int)self::$db->font_size
        ];
        
        // Draggable box
        $this->json["draggable"] = (int)self::$db->draggable;
    }
    
    /*
     * @method array __destruct(): create the json of the js
     */
    
    public function __destruct() {
        print "var msg_box_init = ";
        print json_encode( $this->json );
    }
}

$exe = new mega_messages_init;