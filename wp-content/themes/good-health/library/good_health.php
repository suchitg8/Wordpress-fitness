<?php
/* Welcome to good_health */

// loading modernizr and jquery, and reply script
function good_health_scripts_and_styles() {

  global $wp_styles; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

  if (!is_admin()) {

		// modernizr (without media query polyfill)
		wp_register_script( 'jquery-modernizr', get_template_directory_uri() . '/library/js/libs/modernizr.custom.min.js', array(), '2.5.3', false );

		// register main stylesheet
		wp_enqueue_style( 'good_health-stylesheet', get_template_directory_uri() . '/library/css/style.min.css', array(), '', 'all' );

		if ( is_home() ){
			wp_enqueue_style( 'slick', get_template_directory_uri() . '/library/slick/slick.css', array(), '' );
            wp_enqueue_style( 'slick-theme', get_template_directory_uri() . '/library/slick/slick-theme.css', array(), '' );
		}

		wp_enqueue_style( 'good_health-main-stylesheet', get_stylesheet_uri() , array(), '', 'all' );
		// ie-only style sheet
		wp_enqueue_style( 'good_health-ie-only', get_template_directory_uri() . '/library/css/ie.css', array(), '' );

		wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '' );

		wp_enqueue_style( 'google-font-source-sans-pro', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,400italic,600,700,900' );

	    // comment reply script for threaded comments
	    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
			  wp_enqueue_script( 'comment-reply' );
	    }

		wp_enqueue_script( 'jquery-modernizr' );
		wp_enqueue_script( 'jquery-effects-core ');
		wp_enqueue_script( 'jquery-effects-slide');

		if ( is_home() || is_front_page() || is_archive() || is_search() || is_page_template( 'template-home-banner.php' ) ) :
			wp_enqueue_script( 'jquery-masonry' );
			wp_enqueue_script( 'slick.js', get_template_directory_uri() . '/library//slick/slick.min.js', array(), '', true );
			wp_enqueue_script( 'good_health-js-scripts-home', get_template_directory_uri() . '/library/js/scripts-home.js', array(), '', true );

		endif;

		if( is_page_template( 'template-home-banner.php' ) ) {
			wp_enqueue_script( 'good_health-js-scripts', get_template_directory_uri() . '/js/scripts-banner.js', array(), '', true );
		}

		if( !is_page_template( 'template-home-banner.php' ) ) {
			wp_enqueue_script( 'good_health-js-scripts', get_template_directory_uri() . '/js/scripts.js', array(), '', true );
		}

		$wp_styles->add_data( 'good_health-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet
		wp_enqueue_script( 'good_health-js' , get_template_directory_uri() . '/library/js/scripts.js', array(), '', true );

	}
}
/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function good_health_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );
	add_editor_style();
	// default thumb size
	set_post_thumbnail_size(125, 125, true);

	// wp custom background (thx to @bransonwerner for update)
	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',    // background image default
	    'default-color' => 'fafafa',    // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'video',           // video
			'audio',
			'quote'
		)
	);

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-nav' => __( 'The Main Menu', 'good-health' ),   // main nav in header
			/*'footer-links' => __( 'Footer Links', 'good-health' ) // secondary nav in footer*/
		)
	);

	add_theme_support( 'title-tag' );

	add_theme_support( 'custom-logo' );
} /* end good_health theme support */


add_filter( 'wp_title', 'good_health_rw_title', 10, 3 );
function good_health_rw_title( $title, $sep, $seplocation ) {
  global $page, $paged;

  // Don't affect in feeds.
  if ( is_feed() ) return $title;

  // Add the blog's name
  if ( 'right' == $seplocation ):
    $title .= get_bloginfo( 'name' );
  else:
    $title = get_bloginfo( 'name' ) . $title;
  endif;

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );

  if ( $site_description && ( is_home() || is_front_page() ) ):
    $title .= " {$sep} {$site_description}";
  endif;

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ):
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'good-health' ), max( $paged, $page ) );
  endif;

  return $title;

} // end better title


/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using good_health_related_posts(); )
function good_health_related_posts() {
	echo '<ul id="good_health-related-posts">';
	global $post;
	$tags = wp_get_post_tags( $post->ID );
	if($tags) {
		foreach( $tags as $tag ) {
			$tag_arr .= $tag->slug . ',';
		}
		$args = array(
			'tag' => $tag_arr,
			'numberposts' => 5, /* you can change this to show more */
			'post__not_in' => array($post->ID)
		);
		$related_posts = get_posts( $args );
		if($related_posts) {
			foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; }
		else { ?>
			<?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'good-health' ) . '</li>'; ?>
		<?php }
	}
	wp_reset_postdata();
	echo '</ul>';
} /* end good_health related posts function */

// This removes the annoying […] to a Read More link
function good_health_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '...  <a class="excerpt-read-more" href="'. esc_url( get_permalink($post->ID) ) . '" title="'. __( 'Read ', 'good-health' ) . esc_html( get_the_title($post->ID) ) .'">'. __( 'Read more &raquo;', 'good-health' ) .'</a>';
}

function good_health_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'good_health_excerpt_length', 999 );