<?php

/*******************************************************************
* These are settings for the Theme Customizer in the admin panel.
*******************************************************************/
if ( ! function_exists( 'good_health_theme_customizer' ) ) :
  function good_health_theme_customizer( $wp_customize ) {

    /* color scheme option */
    $wp_customize->add_setting( 'good_health_color_settings', array (
      'default' => '#4fbf70',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'good_health_color_settings', array(
      'label'    => __( 'Theme Accent Color 1', 'good-health' ),
      'section'  => 'colors',
      'settings' => 'good_health_color_settings',
    ) ) );

    $wp_customize->add_setting( 'good_health_color_settings_2', array (
      'default' => '#292929',
      'sanitize_callback' => 'sanitize_hex_color',
    ) );


    /* social media option */
    $wp_customize->add_section( 'good_health_social_section' , array(
      'title'       => __( 'Social Media Icons', 'good-health' ),
      'priority'    => 31,
      'description' => __( 'Optional media icons in the header', 'good-health' ),
    ) );

    $wp_customize->add_setting( 'good_health_facebook', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    /* author bio in posts option */
    $wp_customize->add_section( 'good_health_posts_section' , array(
      'title'       => __( 'Post Settings', 'good-health' ),
      'priority'    => 35,
      'description' => '',
    ) );

    $wp_customize->add_setting( 'good_health_related_posts', array (
      'sanitize_callback' => 'good_health_sanitize_checkbox',
    ) );

    $wp_customize->add_control('related_posts', array(
      'settings' => 'good_health_related_posts',
      'label' => __('Disable the Related Posts?', 'good-health'),
      'section' => 'good_health_posts_section',
      'type' => 'checkbox',
    ));

    $wp_customize->add_setting( 'good_health_author_area', array (
      'sanitize_callback' => 'good_health_sanitize_checkbox',
    ) );

    $wp_customize->add_control('author_info', array(
      'settings' => 'good_health_author_area',
      'label' => __('Disable the Author Information?', 'good-health'),
      'section' => 'good_health_posts_section',
      'type' => 'checkbox',
    ));

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_facebook', array(
      'label'    => __( 'Enter your Facebook url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_facebook',
      'priority'    => 101,
    ) ) );

    $wp_customize->add_setting( 'good_health_twitter', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_twitter', array(
      'label'    => __( 'Enter your Twitter url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_twitter',
      'priority'    => 102,
    ) ) );

    $wp_customize->add_setting( 'good_health_google', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_google', array(
      'label'    => __( 'Enter your Google+ url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_google',
      'priority'    => 103,
    ) ) );

    $wp_customize->add_setting( 'good_health_pinterest', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_pinterest', array(
      'label'    => __( 'Enter your Pinterest url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_pinterest',
      'priority'    => 104,
    ) ) );

    $wp_customize->add_setting( 'good_health_linkedin', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_linkedin', array(
      'label'    => __( 'Enter your Linkedin url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_linkedin',
      'priority'    => 105,
    ) ) );

    $wp_customize->add_setting( 'good_health_youtube', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_youtube', array(
      'label'    => __( 'Enter your Youtube url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_youtube',
      'priority'    => 106,
    ) ) );

    $wp_customize->add_setting( 'good_health_tumblr', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_tumblr', array(
      'label'    => __( 'Enter your Tumblr url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_tumblr',
      'priority'    => 107,
    ) ) );

    $wp_customize->add_setting( 'good_health_instagram', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_instagram', array(
      'label'    => __( 'Enter your Instagram url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_instagram',
      'priority'    => 108,
    ) ) );

    $wp_customize->add_setting( 'good_health_flickr', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_flickr', array(
      'label'    => __( 'Enter your Flickr url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_flickr',
      'priority'    => 109,
    ) ) );

    $wp_customize->add_setting( 'good_health_vimeo', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_vimeo', array(
      'label'    => __( 'Enter your Vimeo url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_vimeo',
      'priority'    => 110,
    ) ) );

    $wp_customize->add_setting( 'good_health_rss', array (
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_rss', array(
      'label'    => __( 'Enter your RSS url', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_rss',
      'priority'    => 112,
    ) ) );

    $wp_customize->add_setting( 'good_health_email', array (
      'sanitize_callback' => 'sanitize_email',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_email', array(
      'label'    => __( 'Enter your email address', 'good-health' ),
      'section'  => 'good_health_social_section',
      'settings' => 'good_health_email',
      'priority'    => 113,
    ) ) );

   /* slider options */

    $wp_customize->add_section( 'good_health_slider_section' , array(
      'title'       => __( 'Slider Settings', 'good-health' ),
      'priority'    => 33,
      'description' => __( 'Adjust the behavior of the image slider.', 'good-health' ),
    ) );

    $wp_customize->add_setting( 'good_health_slider_area', array (
      'sanitize_callback' => 'good_health_sanitize_checkbox',
    ) );

    $wp_customize->add_control('slider_area', array(
      'settings' => 'good_health_slider_area',
      'label' => __('Disable home page slider?', 'good-health'),
      'section' => 'good_health_slider_section',
      'type' => 'checkbox',
    ));

    $cat_array = array();
    $blank_array = array(' ' => 'Sticky Posts','all' => 'All');

    $categories = get_categories( array(
        'orderby' => 'name'
    ) );
    foreach ( $categories as $category ) {

      $cat_array[esc_html( $category->slug )] = esc_html( $category->name );

    }
    
    $merge = array_merge( $blank_array,$cat_array ); 

    $wp_customize->add_setting( 'good_health_slider_cat', array(
      'default' => '',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control( 'cat_select_box', array(
      'settings' => 'good_health_slider_cat',
      'label' => __( 'Select A Category:', 'good-health' ),
      'section' => 'good_health_slider_section',
      'type' => 'select',
      'choices' => $merge
    ));

    $wp_customize->add_section( 'good_health_top_section' , array(
      'title'       => __( 'Top Area', 'good-health' ),
      'priority'    => 32,
      'description' => __( 'Insert Phone Number and Address.', 'good-health' ),
    ) );

    $wp_customize->add_setting( 'good_health_phone_number', array (
      'sanitize_callback' => 'good_heath_sanitize_text_field',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_phone_number', array(
      'label'    => __( 'Phone Number', 'good-health' ),
      'section'  => 'good_health_top_section',
      'settings' => 'good_health_phone_number',
    ) ) );

    $wp_customize->add_setting( 'good_health_address', array (
      'sanitize_callback' => 'good_heath_sanitize_text_field',
    ) );

    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'good_health_address', array(
      'label'    => __( 'Address', 'good-health' ),
      'section'  => 'good_health_top_section',
      'settings' => 'good_health_address',
    ) ) );


  }
endif;
add_action('customize_register', 'good_health_theme_customizer');

/**
 * Sanitize checkbox
 */
if ( ! function_exists( 'good_health_sanitize_checkbox' ) ) :
  function good_health_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
      return 1;
    } else {
      return '';
    }
  }
endif;

function good_heath_sanitize_text_field( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}


/**
 * Sanitize integer input
 */
if ( ! function_exists( 'good_health_sanitize_integer' ) ) :
  function good_health_sanitize_integer( $input ) {
    return absint($input);
  }
endif;
