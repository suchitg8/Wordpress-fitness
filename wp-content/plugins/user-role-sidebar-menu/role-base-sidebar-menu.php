<?php
/**
* The widget can then be registered using the widgets_init hook
* @since 0.0.2
*/
add_action( 'widgets_init', 'user_rolebase_load_widgets' );

/**
* Register our widget.
* 'user_rolebase_load_widgets' is the widget class used below.
* @since 0.0.2
*/

function user_rolebase_load_widgets() {
 register_widget( 'User_Role_base_menu_widgets' );
}// End User_rolebase_load_widgets

/**
* rolebasemenu Widget class.
* This class handles everything that needs to be handled with the widget
* the settings, form, display, and update.  Nice!
* @since 0.0.2
*/
class User_Role_base_menu_widgets extends WP_Widget {
	 
   /**
   * Register widget with WordPress.
   * @since 0.0.2
   */
   function __construct() {
   		parent::__construct(
		// widget unique ID
		'user_role_base_widget_id', 
			
		// Widget Name
		__('User Role Base Sidebar Menu', 'user_role_base_widget'), 
			
		// Widget Description
			array( 'description' => __( 'Logged user role based sidebar menu', 'user_role_base_widget' ), ) // Args
		);
	}// End __construct()
  
	/**
     * Display the widget on the screen.
     * @since 0.0.2
     */
      
	function widget( $args, $instance ) {
		if(is_user_logged_in())
	    {
	    	extract( $args );
	    	global $wp_roles;
	    	$current_user = wp_get_current_user();
	    	$roles = $current_user->roles;
	    	$role = array_shift($roles);
	    	isset($wp_roles->role_names[$role]) ? translate_user_role($wp_roles->role_names[$role] ) : false;
	    	
	    	$title = apply_filters('widget_title', $instance['title'] );
	    	$rolename = $instance['user_rolename'];
	    	
	    	$custom_menu = $instance['role_custom_menu'];
	    	$show_custom_menu = isset( $instance['show_custom_menu'] ) ? $instance['show_custom_menu'] : false;
	    	
	    	$user_role = strtolower($rolename);
	    	/* Display the widget title if one was input (before and after defined by themes). */
	    	if ( isset($custom_menu)  && (strtolower($rolename) == strtolower($role))){        		
	    		$nav_menu = wp_get_nav_menu_object($instance['role_custom_menu']);
	    	
	    		if ( !$nav_menu )
	    		{
	    			echo "return from nav_menu";
	    			return;
	    		}
	    		
	    		/* Before widget (defined by themes). */
	    		echo $before_widget;
	    		echo $before_title . $title . $after_title;
	    		
	    		$instance['title'] = apply_filters('widget_title', $menu_title, $instance, $this->id_base);
	    	
	    		wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );
	    		
	    		/* Display rolename from widget settings if one was input. */
	    		echo $after_widget;
	    	}
	    	/**
	    	 * Do nothing 
	    	 * @since 0.0.2
	    	 */
			
	    	/* After widget (defined by themes). */
	    }
	    else
	    {
	    	/**
	    	 * Do nothing 
	    	 * @since 0.0.2
	    	 */
	    }        
	}

    /**
     * Update the widget settings.
	 * @since 0.0.2
     */
     
    function update( $new_instance, $old_instance ) {
	    $instance = $old_instance;
	
	    /* Strip tags for title and rolename to remove HTML (important for text inputs). */
	    $instance['title'] = strip_tags( $new_instance['title'] );
	    $instance['user_rolename'] = strip_tags( $new_instance['user_rolename'] );
	
	    /* No need to strip tags for custom_menu and show_custom_menu. */
	    $instance['role_custom_menu'] = $new_instance['role_custom_menu'];
	    $instance['show_custom_menu'] = $new_instance['show_custom_menu'];
	
	    return $instance;
    }

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_rolename() function
     * when creating your form elements. This handles the confusing stuff.
     */
    function form( $instance ) {

        /* Set up some default widget settings. */
        $defaults = array( 'title' => __('User Role custom menu', 'rolebasemenu'), 'user_rolename' => __('Achyuh Kumar', 'rolebasemenu'), 'custom_menu' => 'menutitle', 'show_custom_menu' => true );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>

        <!-- Widget Title: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
            <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>

        <!-- Your rolename: Text Input -->
        <p>
            <label for="<?php echo $this->get_field_id( 'user_rolename' ); ?>"><?php _e('User Role:', 'rolebasemenu'); ?></label>
        
        <select id="<?php echo $this->get_field_id( 'user_rolename' ); ?>" name="<?php echo $this->get_field_name( 'user_rolename' ); ?>" style="width: 100%;"><?php
            global $wp_roles;
            $roles = $wp_roles->get_names(); 
            foreach ( $roles as $role ) {
                if(($role == $instance['user_rolename'])){
                echo '<option value="'.$role.'" selected="selected">'. $role .'</option>';
                }else{
                echo '<option value="'.$role.'">'. $role .'</option>';
            }}
        ?></select>
        
        </p>

        <!-- custom_menu: Select Box@since 1.0 -->
        <p>
            <label for="<?php echo $this->get_field_id( 'role_custom_menu' ); ?>"><?php _e('Custom menu:', 'rolebasemenu'); ?></label> 
            <?php $menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) ); ?>
            
            <select id="<?php echo $this->get_field_id( 'role_custom_menu' ); ?>" name="<?php echo $this->get_field_name( 'role_custom_menu' ); ?>" style="width: 100%;">
            <?php foreach ( $menus as $menu ) {if(($menu->name  == $instance['role_custom_menu'])){
            //$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
            echo '<option value="'. $menu->name .'" selected="selected">'. $menu->name .'</option>';}else{echo '<option value="'. $menu->name .'">'. $menu->name .'</option>';}}?>
            </select>
            
        <p>
        	<label>Note:<br>1. IF menus are empty, To create a custom menu Please <a href="<?php echo admin_url().'nav-menus.php'; ?>">Click here</a>.</label><br>
        	<label>2. User role must be unique and not white space values.</label>
        </p>

    <?php
    }//End Form()
}//End User_Role_base_menu_widgets Class
?>