<?php get_header(); ?>
		<div class="front-wrapper">
			<div id="content">
				<div id="blog" class="wrap cf">
					<div id="main" role="main">
						<header class="article-header">

							<h1 class="archive-title"><span><?php _e( 'Search Results for:', 'good-health' ); ?></span> <?php echo esc_attr(get_search_query()); ?></h1>

						</header>
						<div id='masonry' class="blog-list container">
								<?php while ( have_posts() ) : the_post(); ?>
				  						<?php get_template_part( 'home-content/home', get_post_format() ); ?>
				  				<?php endwhile;  ?>
								<div class="clear"></div>
			     				<div class="gutter-sizer"></div>
						</div>
						<div class="pagination">
							<?php next_posts_link( 'Older Entries', '');?>
						</div>
					</div>
				</div> <!-- inner-content -->
			</div> <!-- content -->
		</div><!-- front-wrapper -->

<?php get_footer(); ?>