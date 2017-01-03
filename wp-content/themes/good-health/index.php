<?php get_header();

		good_health_carousel(); ?>
		
		<div class="front-wrapper">
			<div id="content">
				<div id="blog" class="wrap cf">
					<div id="main" role="main">
						<div id='masonry' class="blog-list container">

								<?php while ( have_posts() ) : the_post(); ?>
				  						<?php get_template_part( 'home-content/home', get_post_format() ); ?>
				  				<?php endwhile;  ?>
								<div class="clear"></div>
			     				<div class="gutter-sizer"></div>
						</div>
						<div class="nav-arrows text-center">
		  					<div class="left-arrow">
								<?php previous_posts_link("<span class='fa fa-long-arrow-left'></span> " . __('Newer Posts','good-health') ); ?>
							</div>
							<div class="right-arrow">
								<?php next_posts_link(__('Older Posts','good-health') . " <span class='fa fa-long-arrow-right'></span>"); ?>
							</div>
							<span class="clear"></span>
						</div>
					</div>
				</div> <!-- inner-content -->
			</div> <!-- content -->
		</div><!-- front-wrapper -->
<?php get_footer(); ?>
