<?php
/*
The comments page for good_health
*/

// don't load it if you can't comment
if ( post_password_required() ) {
  return;
}

?>

<?php // You can start editing here. ?>

  <?php if ( have_comments() ) : ?>

    <section class="commentlist">
      <h3 id="comments-title" class="h2"><?php comments_number( __( '<span>No</span> Comments', 'good-health' ), __( '<span>1</span> Comment', 'good-health' ), _n( '<span>%</span> Comments', '<span>%</span> Comments', get_comments_number(), 'good-health' ) );?></h3>
      <?php
        wp_list_comments( array(
          'style'             => 'div',
          'short_ping'        => true,
          'avatar_size'       => 60,
          'callback'          => 'good_health_comments',
          'type'              => 'all',
          'reply_text'        => 'Reply',
          'page'              => '',
          'per_page'          => '',
          'reverse_top_level' => null,
          'reverse_children'  => '',
          'max_depth'         => 3,
        ) );
      ?>
    </section>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
    	<nav class="navigation comment-navigation" role="navigation">
      	<div class="comment-nav-prev"><?php previous_comments_link( __( '&larr; Previous Comments', 'good-health' ) ); ?></div>
      	<div class="comment-nav-next"><?php next_comments_link( __( 'More Comments &rarr;', 'good-health' ) ); ?></div>
    	</nav>
    <?php endif; ?>

    <?php if ( ! comments_open() ) : ?>
    	<p class="no-comments"><?php _e( 'Comments are closed.' , 'good-health' ); ?></p>
    <?php endif; ?>

  <?php endif; ?>

  <?php comment_form(); ?>