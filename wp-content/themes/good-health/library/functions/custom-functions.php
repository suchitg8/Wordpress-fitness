<?php

/*-----------------------------------------------------------------------------------*/
/* custom functions below */
/*-----------------------------------------------------------------------------------*/
define('good_health_THEMEURL', get_template_directory_uri());
define('good_health_IMAGES', good_health_THEMEURL.'/images');
define('good_health_JS', good_health_THEMEURL.'/js');
define('good_health_CSS', good_health_THEMEURL.'/css');
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

add_filter( 'post_thumbnail_html', 'good_health_remove_thumbnail_dimensions', 10, 3 );

function good_health_remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}

function good_health_oembed() {

    global $post;

    if ( $post && $post->post_content ) {

        global $shortcode_tags;
        // Make a copy of global shortcode tags - we'll temporarily overwrite it.
        $theme_shortcode_tags = $shortcode_tags;

        // The shortcodes we're interested in.
        $shortcode_tags = array(
            'video' => $theme_shortcode_tags['video'],
            'audio' => $theme_shortcode_tags['audio'],
            'embed' => $theme_shortcode_tags['embed']
        );
        // Get the absurd shortcode regexp.
        $video_regex = '#' . get_shortcode_regex() . '#i';

        // Restore global shortcode tags.
        $shortcode_tags = $theme_shortcode_tags;

        $pattern_array = array( $video_regex );

        // Get the patterns from the embed object.
        if ( ! function_exists( '_wp_oembed_get_object' ) ) {
            include ABSPATH . WPINC . '/class-oembed.php';
        }
        $oembed = _wp_oembed_get_object();
        $pattern_array = array_merge( $pattern_array, array_keys( $oembed->providers ) );

        // Or all the patterns together.
        $pattern = '#(' . array_reduce( $pattern_array, function ( $carry, $item ) {
            if ( strpos( $item, '#' ) === 0 ) {
                // Assuming '#...#i' regexps.
                $item = substr( $item, 1, -2 );
            } else {
                // Assuming glob patterns.
                $item = str_replace( '*', '(.+)', $item );
            }
            return $carry ? $carry . ')|('  . $item : $item;
        } ) . ')#is';

        // Simplistic parse of content line by line.
        $lines = explode( "\n", $post->post_content );

        foreach ( $lines as $line ) {
            $line = trim( $line );

            if ( preg_match( $pattern, $line, $matches ) ) {
                if ( strpos( $matches[0], '[' ) === 0 ) {
                    $ret = do_shortcode( $matches[0] );
                    $audio = explode('"', $matches[0]);
                    if( array_key_exists(1, $audio)){
                      return $audio[1];
                    }
                } else {
                    $ret = wp_oembed_get( $matches[0] );
                }
                return $ret;
            }
        }
    }
}

function good_health_author_excerpt() {
      $text_limit = '50'; //Words to show in author bio excerpt
      $read_more  = __('Read more','good-health'); //Read more text
      $end_of_txt = "...";
      $name_of_author = get_the_author();
      $url_of_author  = get_author_posts_url(get_the_author_meta('ID'));
      $short_desc_author = wp_trim_words(strip_tags(
                          get_the_author_meta('description')), $text_limit,
                          $end_of_txt.'<br/>
                        <a href="'.$url_of_author.'" style="font-weight:bold;">'.$read_more .'</a>');

      return $short_desc_author;
}

 function good_health_catch_that_image() {
  global $post;
  $pattern = '|<img.*?class="([^"]+)".*?/>|';
  $transformed_content = apply_filters('the_content',$post->post_content);
  preg_match($pattern,$transformed_content,$matches);
  if (!empty($matches[1])) {
    $classes = explode(' ',$matches[1]);
    $id = preg_grep('|^wp-image-.*|',$classes);
    if (!empty($id)) {
      $id = str_replace('wp-image-','',$id);
      if (!empty($id)) {
        $id = reset($id);
        $transformed_content = wp_get_attachment_image($id,'full');
        return $transformed_content;
      }
    }
  }

}

function good_health_catch_that_image_thumb() {
 global $post;
 $pattern = '|<img.*?class="([^"]+)".*?/>|';
 $transformed_content = apply_filters('the_content',$post->post_content);
 preg_match($pattern,$transformed_content,$matches);
 if (!empty($matches[1])) {
   $classes = explode(' ',$matches[1]);
   $id = preg_grep('|^wp-image-.*|',$classes);
   if (!empty($id)) {
     $id = str_replace('wp-image-','',$id);
     if (!empty($id)) {
       $id = reset($id);
       $transformed_content = wp_get_attachment_url($id,'full');
       return $transformed_content;
     }
   }
 }

}

function good_health_catch_gallery_image_full()  {
    global $post;
    $gallery = get_post_gallery( $post, false );
    if ( !empty($gallery['ids']) ) {
      $ids = explode( ",", $gallery['ids'] );
      $total_images = 0;
      foreach( $ids as $id ) {
        $link = wp_get_attachment_url( $id );
        $total_images++;

        if ($total_images == 1) {
          $first_img = $link;
          return $first_img;
        }
      }
    }
}

function good_health_catch_gallery_image_thumb()  {
    global $post;
    $gallery = get_post_gallery( $post, false );
    if ( !empty($gallery['ids']) ) {
      $ids = explode( ",", $gallery['ids'] );
      $total_images = 0;
      foreach( $ids as $id ) {

        $title = get_post_field('post_title', $id);
        $meta = get_post_field('post_excerpt', $id);
        $link = wp_get_attachment_url( $id );
        $image  = wp_get_attachment_image( $id, 'thumbnail');
        $total_images++;

        if ($total_images == 1) {
          $first_img = $image;
          return $first_img;
        }
      }
    }
}

/* social icons*/
function good_health_social_icons()  {

  $social_networks = array( array( 'name' => __('Facebook','good-health'), 'theme_mode' => 'good_health_facebook','icon' => 'fa-facebook' ),
                            array( 'name' => __('Twitter','good-health'), 'theme_mode' => 'good_health_twitter','icon' => 'fa-twitter' ),
                            array( 'name' => __('Google+','good-health'), 'theme_mode' => 'good_health_google','icon' => 'fa-google-plus' ),
                            array( 'name' => __('Pinterest','good-health'), 'theme_mode' => 'good_health_pinterest','icon' => 'fa-pinterest' ),
                            array( 'name' => __('Linkedin','good-health'), 'theme_mode' => 'good_health_linkedin','icon' => 'fa-linkedin' ),
                            array( 'name' => __('Youtube','good-health'), 'theme_mode' => 'good_health_youtube','icon' => 'fa-youtube' ),
                            array( 'name' => __('Tumblr','good-health'), 'theme_mode' => 'good_health_tumblr','icon' => 'fa-tumblr' ),
                            array( 'name' => __('Instagram','good-health'), 'theme_mode' => 'good_health_instagram','icon' => 'fa-instagram' ),
                            array( 'name' => __('Flickr','good-health'), 'theme_mode' => 'good_health_flickr','icon' => 'fa-flickr' ),
                            array( 'name' => __('Vimeo','good-health'), 'theme_mode' => 'good_health_vimeo','icon' => 'fa-vimeo-square' ),
                            array( 'name' => __('RSS','good-health'), 'theme_mode' => 'good_health_rss','icon' => 'fa-rss' )
                      );


  for ($row = 0; $row < 11; $row++)
  {

      if (get_theme_mod( $social_networks[$row]["theme_mode"])): ?>
         <a href="<?php echo esc_url( get_theme_mod($social_networks[$row]['theme_mode']) ); ?>" class="social-tw" title="<?php echo esc_url( get_theme_mod( $social_networks[$row]['theme_mode'] ) ); ?>" target="_blank">
          <i class="fa <?php echo $social_networks[$row]['icon']; ?>"></i>
        </a>
      <?php endif;
  }

  if(get_theme_mod('good_health_email')): ?>
        <a href="mailto:<?php echo esc_attr(get_theme_mod('good_health_email')); ?>" class="social-tw" title="<?php echo esc_attr( get_theme_mod('good_health_email')); ?>" target="_blank">
          <i class="fa fa-envelope"></i>
        </a>
  <?php endif;
}

function good_health_carousel(){
  if ( get_theme_mod('good_health_slider_area') ):
            $slider_class = 'slider-hide';
            else:
            $slider_class = '';
            endif;
  ?>
  <div class="carousel-area<?php echo ' ' . $slider_class; ?>">
      <div class="carousel-area-wrap">
        <?php 
        if ( get_theme_mod('good_health_slider_cat') == 'all'){
          $args = array('posts_per_page' => -1,'post_status' => 'publish' );
        } 
        elseif ( get_theme_mod('good_health_slider_cat') ){
          $args = array('posts_per_page' => -1,'post_status' => 'publish', 'category_name' => get_theme_mod('good_health_slider_cat') );
        } else {
          $args = array('post_status' => 'publish','post__in' => get_option("sticky_posts"));
        }
        $fPosts = new WP_Query( $args );?>
        <?php while ( $fPosts -> have_posts() ) : $fPosts -> the_post(); ?>
        <div class="carousel-item">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

          <?php
            $thumb_id 			= get_post_thumbnail_id();
            $thumb_url_array 	= wp_get_attachment_image_src($thumb_id, 'full', true);
            $thumb_url 			= $thumb_url_array[0];
            $image_thumb 		= good_health_catch_that_image_thumb();
            $gallery_thumb 		= good_health_catch_gallery_image_full();
          ?>
          <div class="carousel-holder" style="<?php if( has_post_thumbnail() ) { ?>background-image:url(<?php echo esc_url( $thumb_url ); ?>); <?php } elseif( !empty($image_thumb) ) { ?>background-image:url(<?php echo esc_url( $image_thumb ); ?>); <?php } elseif( !empty($gallery_thumb) ) { ?>background-image:url(<?php echo esc_url( $gallery_thumb ); ?>); <?php } ?>">
            <div class="post-info">
              <h1><?php the_title(); ?></h1>
              <div class="carousel-excerpt"><?php the_excerpt(); ?></div>
            </div>
            <span class="opacity-overlay"></span>
          </div>
        </div>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>

      </div>
    <span class="pattern-overlay"></span>
  </div>

<?php

}

function good_health_head_sticky(){ ?>

    <div id="inner-header" class="head-sticky">
      <div class="wrap">
      <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ): 
      $custom_logo_id = get_theme_mod( 'custom_logo' );
      $image = wp_get_attachment_image_src( $custom_logo_id,'full');
      ?>
      <p id="logo" class="h1"><a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $image[0] ); ?>"></a></p>
       <?php else : ?>
      <p id="logo" class="h1"><a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo('name'); ?></a></p>
      <?php endif; ?>

      <nav role="navigation" class="gh-main-navigation">
        <?php if ( has_nav_menu('main-nav') ) { ?>
        <?php wp_nav_menu(array(
        'container' => false,                           // remove nav container
        'container_class' => 'menu cf',                 // class of container (should you choose to use it)
        'menu_class' => 'nav top-nav cf',               // adding custom nav class
        'theme_location' => 'main-nav',                 // where it's located in the theme
        'before' => '',                                 // before the menu
        'after' => '',                                  // after the menu
        'link_before' => '',                            // before each link
        'link_after' => '',                             // after each link
        'depth' => 0,                                   // limit the depth of the nav
        'fallback_cb' => ''                             // fallback function (if there is one)
        )); ?>
        <?php } else { ?>
        <ul class="nav top-nav cf">
        <?php wp_list_pages('sort_column=menu_order&title_li='); ?>
        </ul>
        <?php } ?>
      </nav>
      </div>
      <span class="clear"></span>

    </div>

<?php

}

function good_health_header() { ?>

    <div id="main-header" class="wrap cf">
      <div class="nav-wrapper">
      <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ): 
      $custom_logo_id = get_theme_mod( 'custom_logo' );
      $image = wp_get_attachment_image_src( $custom_logo_id,'full');
      ?>
      <p id="logo" class="h1"><a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( $image[0] ); ?>"></a></p>
       <?php else : ?>
      <p id="logo" class="h1"><a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo('name'); ?></a></p>
      <?php endif; ?>

      <div id="responsive-nav"><span class="fa fa-bars nav-icon"></span></div>
             <div class="clear no-display"></div>
             <nav role="navigation" class="gh-main-navigation">
              <?php if ( has_nav_menu('main-nav') ) { ?>
            <?php wp_nav_menu(array(
              'container' => false,                           // remove nav container
              'container_class' => 'menu cf',                 // class of container (should you choose to use it)
    
              'menu_class' => 'nav top-nav cf',               // adding custom nav class
              'theme_location' => 'main-nav',                 // where it's located in the theme
              'before' => '',                                 // before the menu
                'after' => '',                                  // after the menu
                'link_before' => '',                            // before each link
                'link_after' => '',                             // after each link
                'depth' => 0,                                   // limit the depth of the nav
              'fallback_cb' => ''                             // fallback function (if there is one)
            )); ?>
          <?php } else { ?>
            <ul class="nav top-nav cf">
              <?php wp_list_pages('sort_column=menu_order&title_li='); ?>
            </ul>
          <?php } ?>
        </nav>
      </div>
        <span class="clear"></span>

    </div>

<?php
}

/**
 * Include the TGM_Plugin_Activation class.
 */
require_once get_template_directory() . '/library/class/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'good_health_register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to good_health_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into good_health_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function good_health_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(


        // This is an example of how to include a plugin from the WordPress Plugin Repository.
        array(
            'name'      => __('WP Gallery Custom Links','good-health'),
            'slug'      => 'wp-gallery-custom-links',
            'required'  => false,
        ),

        array(
            'name'      => __('Instagram Feed','good-health'),
            'slug'      => 'instagram-feed',
            'required'  => false,
        ),

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'good_health-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'good-health' ),
            'menu_title'                      => __( 'Install Plugins', 'good-health' ),
            'installing'                      => __( 'Installing Plugin: %s', 'good-health' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'good-health' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' , 'good-health'), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' , 'good-health'), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' , 'good-health'), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' , 'good-health'), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' , 'good-health'), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' , 'good-health'), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' , 'good-health'), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' , 'good-health'), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' , 'good-health'),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' , 'good-health'),
            'return'                          => __( 'Return to Required Plugins Installer', 'good-health' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'good-health' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'good-health' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );

}