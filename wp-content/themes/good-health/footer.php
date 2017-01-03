			<footer class="footer" role="contentinfo">
				<?php if ( shortcode_exists( 'instagram-feed' ) ) { echo do_shortcode('[instagram-feed num=12 cols=6 imagepadding=0 showheader=false  showfollow=false showbutton=false]'); } ?>
				<div id="inner-footer" class="wrap cf">
					<div class="source-org copyright">
						&#169; <?php echo date_i18n(__('Y','good-health')) . ' '; bloginfo( 'name' ); ?>
						<span><?php if(is_home()): ?>
							- <a href="<?php echo esc_url( __( 'https://wordpress.org/', 'good-health' ) ); ?>"><?php printf( __( 'Powered by %s', 'good-health' ), 'WordPress' ); ?></a> <span><?php _e('and','good-health'); ?></span> <a href="<?php echo esc_url( __( 'http://fitnessthemes.net', 'good-health' ) ); ?>"><?php printf( __( '%s', 'good-health' ), 'Fitness Themes' ); ?></a>
						<?php endif; ?>
						</span>
					</div>
				</div>

			</footer>
			<a href="#" class="scrollToTop"><span class="fa fa-chevron-circle-up"></span></a>
		</div>

		<?php wp_footer(); ?>
	</body>

</html> <!-- end of site. what a ride! -->