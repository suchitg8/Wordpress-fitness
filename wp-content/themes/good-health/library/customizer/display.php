<?php

/**
* Apply Color Scheme
*/
if ( ! function_exists( 'good_health_apply_color' ) ) :
  function good_health_apply_color() {
  if ( get_theme_mod('good_health_color_settings') ) :
  ?>
    <style>
        a,
        .blog-list .item .meta-cat a,
        article.post .meta-cat a,
        .blog-list .item a.excerpt-read-more,
        .scrollToTop span,
        .slide-copy-wrap a:hover, .slide-copy-wrap a:focus,
        .blog-list .item a, .blog-list .widget-item a, article.post h2.post-title a, h2.post-title,
        nav[role="navigation"] .nav li.current_page_item > a,
        #responsive-nav .nav-icon{
          color: <?php echo esc_html( get_theme_mod('good_health_color_settings') ); ?>;
        }
        button,
        html input[type="button"],
        input[type="reset"],
        input[type="submit"],
        .ias-trigger a,
        .nav li ul.children,
        .nav li ul.sub-menu,
        nav[role="navigation"] .nav li ul li a,
        #submit, .blue-btn, .comment-reply-link,
        .widget #wp-calendar caption,
        .slide-copy-wrap .more-link,
        .gallery .gallery-caption,
        #submit:active, #submit:focus, #submit:hover, .blue-btn:active, .blue-btn:focus, .blue-btn:hover, .comment-reply-link:active, .comment-reply-link:focus, .comment-reply-link:hover{
          background: <?php echo esc_html( get_theme_mod('good_health_color_settings') ); ?>;
        }
        .ias-trigger a,
        #submit, .blue-btn, .comment-reply-link,
        .scrollToTop{
          border: 1px solid <?php echo esc_html( get_theme_mod('good_health_color_settings') ); ?>;
        }
        #top-area .opacity-overlay{
          border-bottom: 1px solid <?php echo esc_html( get_theme_mod('good_health_color_settings') ); ?>;
        }
        @media screen and (max-width: 1039px) {
          nav.gh-main-navigation[role="navigation"]{
            background: <?php echo esc_html( get_theme_mod('good_health_color_settings') ); ?>;
          }
        }

    </style>
  <?php
    endif;
  }
endif;
add_action( 'wp_head', 'good_health_apply_color' );
