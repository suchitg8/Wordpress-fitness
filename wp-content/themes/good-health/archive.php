<?php get_header(); ?>
		<div class="front-wrapper">
			<div id="content">
				<div id="blog" class="wrap cf">
					<div id="main" role="main">
						<header class="article-header">
							<?php if (is_category()) : ?>
								<h1 class="archive-title h2">
									<?php single_cat_title(); ?>
								</h1>

							<?php elseif (is_tag()) : ?>
								<h1 class="archive-title h2">
									<?php single_tag_title(); ?>
								</h1>

							<?php elseif (is_author()) :
								global $post;
								$author_id = $post->post_author;
							?>
								<h1 class="archive-title h2">

									<?php the_author_meta('display_name', $author_id); ?>

								</h1>
							<?php elseif (is_day()) : ?>
								<h1 class="archive-title h2">
									<?php the_time(get_option('date_format')); ?>
								</h1>

							<?php elseif (is_month()) : ?>
									<h1 class="archive-title h2">
										<?php the_time(get_option('date_format')); ?>
									</h1>

							<?php elseif (is_year()) : ?>
									<h1 class="archive-title h2">
										<?php the_time(get_option('date_format')); ?>
									</h1>
							<?php endif; ?>
						</header>
						<div id='masonry' class="blog-list container">
								<?php while ( have_posts() ) : the_post(); ?>
				  						<?php get_template_part( 'home-content/home', get_post_format() ); ?>
				  				<?php endwhile;  ?>
								<div class="clear"></div>
			     				<div class="gutter-sizer"></div>
						</div>
						<div class="pagination">
							<?php next_posts_link( __('Older Entries','good-health') );?>
						</div>
					</div>
				</div> <!-- inner-content -->
			</div> <!-- content -->
		</div><!-- front-wrapper -->

<?php get_footer(); ?>