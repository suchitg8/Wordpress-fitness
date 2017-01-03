<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

	</head>

	<body <?php body_class(); ?>>

		<div id="container">
			<header class="header" role="banner">
				<div id="top-area">
					<div class="wrap">
						<div class="phone-address left-area">
							<?php if ( get_theme_mod( 'good_health_phone_number' ) ) { ?>
							<p class="phone"><span class="fa fa-mobile-phone"></span><?php echo esc_html(  get_theme_mod( 'good_health_phone_number' ) ); ?></p>
							<?php } if ( get_theme_mod( 'good_health_address' ) ) { ?>
							<p class="address"><span class="fa fa-map-marker"></span><?php echo esc_html(  get_theme_mod( 'good_health_address' ) ); ?></p>
							<?php } ?>
						</div>
						<div class="right-area">
								<?php
				           	if(function_exists('good_health_social_icons')) :
				           		echo good_health_social_icons();
				           	endif;
				        ?>
						</div>
						<span class="clear"></span>
					</div>
				</div>

				<?php good_health_head_sticky(); good_health_header(); ?>

			</header>
