<?php get_header(); ?>

			<div id="content">
				<div id="inner-content" class="wrap cf">
					
					<div id="main" class="m-all t-2of3 d-5of7 cf" role="main">
						
						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
							<div class="main-content-area">
								
								<h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

								<?php get_template_part( 'post-content/format', get_post_format() ); ?>
							</div>

							<div class="tag-links">
								<?php
									$category_list = get_the_category_list( __( ', ', 'good-health' ) );
									printf( __('Filed under: %s', 'good-health'),
									$category_list
									);
								?>
							</div>
							<?php if(has_tag()): ?>
							    <div class="tag-links">
							      <div class="clear"></div>
							      <?php _e('Tags: ','good-health'); ?>
							      <?php echo get_the_tag_list('',',','');?>
							    </div>
							 <?php endif; ?>

							<footer class="below-content-area">
								<?php if ( get_theme_mod('good_health_author_area') ):
				                    $author_class = 'author-hide';
				                    else :
				                    $author_class = '';
				                    endif;
				                ?>
								<div class="author-info <?php echo esc_attr($author_class); ?>">
				                  <div class="avatar">
				                  	<?php echo get_avatar( get_the_author_meta( 'ID' ) , 100 ); ?>
				                  </div>
				                  <div class="info">
					                  <h4 class="author-name"><?php the_author(); ?></h4>
					                  <p class="author-desc"> <?php echo get_the_author_meta('description'); ?> </p>
				                  </div>
				                  <div class="clear"></div>
				                </div> <?php // end article footer ?>


								<?php $related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 3, 'post__not_in' => array($post->ID) ) ); ?>
		                    	<?php if ( get_theme_mod('good_health_related_posts') ):
				                    $related_class = 'related-hide';
				                    else :
				                    $related_class = '';
				                    endif;
				                ?>
		                    	<?php if (!empty($related)): ?>
			                    <div class="related-posts <?php echo esc_attr( $related_class ); ?>">
				                    <h3 class="section-title"><?php _e('You may also like ','good-health'); ?></h3>
				                    <div> 
					                    <?php if( $related ): foreach( $related as $post ) { ?>
					                    <?php setup_postdata($post); ?>

					                            <div class="related-item">
					                              <div class="related-image">
						                              <a href="<?php the_permalink() ?>" rel="bookmark">
						                                <?php $image_thumb = good_health_catch_that_image_thumb(); $gallery_thumb = good_health_catch_gallery_image_thumb(); 
						                                if ( has_post_thumbnail()):
						                                	the_post_thumbnail('600x600');  
						                                elseif(has_post_format('gallery') && !empty($gallery_thumb)): 
						                                	echo wp_kses_post( $gallery_thumb ); 
						                                elseif(has_post_format('image') && !empty($image_thumb)): 
						                                	echo wp_kses_post( $image_thumb ); 
						                                else: ?>
						                                <?php $blank = get_template_directory_uri() . '/images/blank.jpg'; ?>
						                                <img src="<?php echo esc_url($blank); ?>">
						                                <?php endif; ?>
						                              </a>
					                              </div>

					                              <div class="related-info">
					                                  <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					                                  
					                              </div>
					                               
					                            </div>
					                    
					                    		<?php } endif; wp_reset_postdata(); ?>
					                     		
				                      </div>
				                      <div class="clear"></div>

			                     </div>
		                     	<?php endif; ?>
								<?php comments_template(); ?>
							</footer>

							</article> <?php // end article ?>

						<?php endwhile; ?>

						<?php else : ?>

							<article id="post-not-found" class="hentry cf">
									<header class="article-header">
										<h1><?php esc_html_e( 'Oops, Post Not Found!', 'good-health' ); ?></h1>
										<p><?php esc_html_e( 'Uh Oh. Something is missing. Try double checking things.', 'good-health' ); ?></p>
									</header>
							</article>

						<?php endif; ?>

					</div>

					<?php get_sidebar(); ?>

				</div>

			</div>

<?php get_footer(); ?>