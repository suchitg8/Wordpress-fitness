<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package SKT Strong
 */
?>
<?php
$hidefooter = get_theme_mod('hide_footer', 1);
$hidecontact = get_theme_mod('hide_contact', 1);
?>
<div id="footer-wrapper">
		<?php if($hidefooter == ''){?>
    	<div class="container footer">
             <div class="cols-3 widget-column-1">  
             
              <?php if ( get_theme_mod('about_title') !== "") { ?>
                <h5><?php esc_html_e( get_theme_mod( 'about_title', esc_html__('About Us','skt-strong'))); ?></h5>             
			   <?php } ?>
               
                <?php if ( get_theme_mod('about_description') !== "") { ?>
                <p><?php echo html_entity_decode(esc_attr( get_theme_mod( 'about_description', __('Sed suscipit etisds sit proin efficitur nibh euismod. Proindes venenatis orcfrdeesi sitmauris nec mauris vulputate, a posuere libero congue. Nam laoreet elit eu erat pulvinar, et efficitur nibh euismod. Proin venenatis orci sit amet nisl finibus vehicula. <br /> <br /> Sed suscipit mauris nec mauris vulputate, a posuere libero congue. Nam laoreet elit eu erat pulvinar, et efficitur nibh euismod. Proin venenatis orci sit amet nisl finibus vehicula.','skt-strong')))); ?></p>
			   <?php } ?>                   
         
            </div><!--end .widget-column-1-->                  
			    
                      
               <div class="cols-3 widget-column-2">  
               
                <?php if ( get_theme_mod('newsfeed_title') !== "") { ?>
                <h5><?php esc_html_e( get_theme_mod( 'newsfeed_title', __('Latest News','skt-strong'))); ?></h5>            
			  <?php } ?>  
              
              <?php $args = array( 'posts_per_page' => 2, 'post__not_in' => get_option('sticky_posts'), 'orderby' => 'date', 'order' => 'desc' );
					$postquery = new WP_Query( $args );
					?>
                    <?php while( $postquery->have_posts() ) : $postquery->the_post(); ?>
                        <div class="recent-post">
                            <div class="footerthumb"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_post_thumbnail(); ?></a></div>                           	
                            <p><?php the_excerpt(); ?></p> 
                            <a class="morebtn" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_attr_e('Read More..','skt-strong'); ?></a>                                              
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>                    
				
              </div><!--end .widget-column-3-->
                
             <div class="cols-3 widget-column-3">  
               <?php if($hidecontact == ''){?>              
               <?php if ( get_theme_mod( 'contact_title' ) !== "" ){  ?>
                <h5><?php echo esc_html_e( get_theme_mod( 'contact_title', __('Contact Info','skt-strong'))); ?></h5>              
			   <?php } ?>
                             
               <?php if ( get_theme_mod('contact_add') !== "") { ?>
                <div class="siteaddress">
				   <?php echo esc_html_e( get_theme_mod( 'contact_add', __('591 Christie Way Passaic Street, North Carolina, America ( USA )','skt-strong'))); ?>
                </div>             
			   <?php } ?> 
               
             <div class="phone-no">	  
               <?php if ( get_theme_mod('contact_no') !== "") { ?>
               <p><span><?php esc_html_e('Phone:', 'skt-strong');?></span> <?php echo esc_attr_e( get_theme_mod( 'contact_no', __('+123 456 7890','skt-strong'))); ?></p>             
			   <?php } ?>  
               
               <?php if( get_theme_mod('contact_mail') !== ""){ ?>
               <p><span><?php esc_html_e('Email:', 'skt-strong');?></span>
               <a href="mailto:<?php echo sanitize_email(get_theme_mod('contact_mail','contact@company.com')); ?>"><?php echo get_theme_mod('contact_mail','contact@company.com'); ?></a>	</p>		
			  <?php } ?> 
              
               
              <?php if ( get_theme_mod( 'website_link' ) !== "" ){  ?>
               <p><span><?php esc_html_e('Website:', 'skt-strong');?></span>
               <a href="<?php echo home_url('/'); ?>"><?php echo home_url('/'); ?></a></p>              
			   <?php } ?>           
                        
              
          </div>
           <?php } ?> 
          </div><!--end .widget-column-4-->
                
                
            <div class="clear"></div>
        </div><!--end .container--> 
        <?php } ?>
         <div class="copyright-wrapper">
        	<div class="container">
           		 <div class="copyright-txt">&nbsp;</div>
            	 <div class="design-by"><?php printf('<a target="_blank" href="'.esc_url(SKT_FREE_THEME_URL).'" rel="nofollow">SKT Strong</a>' ); ?></div>
                 <div class="clear"></div>
            </div>           
        </div>
               
    </div><!--end .footer-wrapper-->
<?php wp_footer(); ?>

</body>
</html>