<?php

function good_health_ahoy() {

  // let's get language support going, if you need it
  load_theme_textdomain( 'good-health', get_template_directory() . '/library/translation' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'good_health_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  good_health_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'good_health_register_sidebars' );

  // cleaning up excerpt
  add_filter( 'excerpt_more', 'good_health_excerpt_more' );

} /* end good_health ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'good_health_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
  $content_width = 640;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes

add_image_size( '300x300', 300, 300, true );
add_image_size( '600x600', 600, 600, true );


add_filter( 'image_size_names_choose', 'good_health_custom_image_sizes' );
function good_health_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        '300x300' => __('300px by 300px','good-health')
    ) );
}

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function good_health_register_sidebars() {
  register_sidebar(array(
    'id' => 'sidebar1',
    'name' => __( 'Posts Sidebar', 'good-health' ),
    'description' => __( 'The Posts sidebar.', 'good-health' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));

  register_sidebar(array(
    'id' => 'sidebar2',
    'name' => __( 'Page Sidebar', 'good-health' ),
    'description' => __( 'The Page sidebar.', 'good-health' ),
    'before_widget' => '<div id="%1$s" class="widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h4 class="widgettitle">',
    'after_title' => '</h4>',
  ));

} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function good_health_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
          echo get_avatar($comment,60);
        ?>
      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'good-health' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'good-health' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'good-health' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><?php comment_time(__( 'F jS, Y', 'good-health' )); ?></time>
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!

add_filter( 'comment_form_defaults', 'good_health_remove_comment_form_allowed_tags' );
function good_health_remove_comment_form_allowed_tags( $defaults ) {

  $defaults['comment_notes_after'] = '';
  return $defaults;

}
