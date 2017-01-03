<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div class="container">
 *
 * @package SKT Strong
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
$hideslide = get_theme_mod('hide_slides', 1);
$hideboxes = get_theme_mod('hide_pagethreeboxes', 1);
$hidesocial = get_theme_mod('hide_social', 1);
$hidecontact = get_theme_mod('hide_contact', 1);
?>
<div class="header">
  <div class="container">
   <div class="logo">  
      <?php skt_strong_the_custom_logo(); ?>   
      <a href="<?php echo home_url('/'); ?>">
		<h1><?php bloginfo('name'); ?></h1>
        <p><?php bloginfo('description'); ?></p>      
      </a>    
   </div><!-- logo -->
    
    
<div class="widget-right">
<?php if ( ! dynamic_sidebar( 'header-right-widget' ) ) : ?>  
	
   <?php if($hidecontact == ''){?> 
   <?php if ( get_theme_mod('contact_no') !== "") { ?>      
          <div class="hdrinfo">
          <div class="hdrinfo-left"><img style="margin:0 5px -1px 0;" src="<?php echo esc_url(get_template_directory_uri()); ?>/images/icon-phone.png" alt="" /></div> 
          <div class="hdrinfo-right"><strong><?php esc_attr_e( get_theme_mod( 'contact_no', __('855-321-9876','skt-strong'))); ?></strong>
           <?php if( get_theme_mod('contact_mail') !== ""){ ?>
            <a href="mailto:<?php echo sanitize_email(get_theme_mod('contact_mail','contact@company.com')); ?>">
			    <?php echo get_theme_mod('contact_mail','contact@company.com'); ?>
             </a>	
		  <?php } ?> 
          </div>
          <div class="clear"></div>
          </div>
     <?php } }?>
     
       <?php if($hidesocial == ''){?>             
       <div class="header-social-icons">        
		<?php if ( get_theme_mod('fb_link') !== "") { ?>
        <a title="facebook" class="fb" target="_blank" href="<?php echo esc_url(get_theme_mod('fb_link','#facebook')); ?>"></a> 
        <?php } ?>       
        
        <?php if ( get_theme_mod('twitt_link') !== "") { ?>
        <a title="twitter" class="tw" target="_blank" href="<?php echo esc_url(get_theme_mod('twitt_link','#twitter')); ?>"></a>
        <?php } ?>     
        
        <?php if ( get_theme_mod('gplus_link') !== "") { ?>
        <a title="google-plus" class="gp" target="_blank" href="<?php echo esc_url(get_theme_mod('gplus_link','#gplus')); ?>"></a>
        <?php } ?>        
        
        <?php if ( get_theme_mod('linked_link') !== "") { ?> 
        <a title="linkedin" class="in" target="_blank" href="<?php echo esc_url(get_theme_mod('linked_link','#linkedin')); ?>"></a>
        <?php } ?>                   
      </div> 
      <?php } ?>
      
      <div class="clear"></div>           
    <?php endif; // end sidebar widget area ?>	
</div><!--.widget-right-->
 <div class="clear"></div>
         
  </div> <!-- container -->
  
  <div id="menubar">
    <div class="container menuwrapper">
         <div class="toggle"><a class="toggleMenu" href="#"><?php esc_html_e('Menu','skt-strong'); ?></a></div> 
        <div class="sitenav">
          <?php wp_nav_menu( array('theme_location' => 'primary') ); ?>         
        </div><!-- .sitenav--> 
        <div class="clear"></div> 
      </div> <!-- container -->    
   </div> <!-- #menubar -->    
  
</div><!--.header -->
<?php if ( !is_home() && is_front_page() ) { ?>
<?php if( $hideslide == '') { ?>
<!-- Slider Section -->
<?php for($sld=7; $sld<10; $sld++) { ?>
	<?php if( get_theme_mod('page-setting'.$sld)) { ?>
     <?php $slidequery = new WP_query('page_id='.get_theme_mod('page-setting'.$sld,true)); ?>
		<?php while( $slidequery->have_posts() ) : $slidequery->the_post();
        $image = wp_get_attachment_url( get_post_thumbnail_id($post->ID));
        $img_arr[] = $image;
        $id_arr[] = $post->ID;
        endwhile;
  	  }
    }
?>
<?php if(!empty($id_arr)){ ?>
<section id="home_slider">
  <div class="slider-wrapper theme-default">
    <div id="slider" class="nivoSlider">
      <?php 
	$i=1;
	foreach($img_arr as $url){ ?>
      <img src="<?php echo esc_url($url); ?>" title="#slidecaption<?php echo esc_attr($i); ?>" />
      <?php $i++; }  ?>
    </div>
		<?php 
        $i=1;
        foreach($id_arr as $id){ 
        $title = get_the_title( $id ); 
        $post = get_post($id); 
		$content = esc_html(wp_trim_words( $post->post_content, 25, '' ) );
        ?>
    <div id="slidecaption<?php echo esc_attr($i); ?>" class="nivo-html-caption">
      <div class="slide_info">
        <h2><?php echo wp_kses_post($title); ?></h2>
        <div class="clear"></div>
        <?php echo wp_kses_post($content); ?>
        <div class="clear"></div>         
      </div>
    </div>
    <?php $i++; } ?>
  </div>
  <div class="clear"></div>
</section>
<?php } } ?>
<?php if( $hideboxes == '') { ?>
<section id="pagearea">
  <div class="container">   
      <?php for($p=1; $p<4; $p++) { ?>
      <?php if( get_theme_mod('page-column'.$p,false)) { ?>
      <?php $querycolumns = new WP_query('page_id='.get_theme_mod('page-column'.$p,true)); ?>
      <?php while( $querycolumns->have_posts() ) : $querycolumns->the_post(); ?>
      <div class="threebox box<?php echo esc_attr( $p ) ?> <?php if($p % 3 == 0) { echo "last_column"; } ?>">     	
          <h3><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a> </h3>        
		  <p><?php the_excerpt(); ?></p>
          <a class="ReadMore" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e('Read More','skt-strong'); ?></a>
      </div>
      <?php endwhile;
       wp_reset_postdata(); ?>
      <?php } } ?>
      <div class="clear"></div> 
  </div><!-- container -->
</section><!-- #pagearea -->
<div class="clear"></div>
<?php } } ?>