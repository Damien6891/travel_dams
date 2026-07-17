<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package travel_dams
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'travel_dams'); ?></a>

		<header id="masthead" class="site-header">
			<div class="site-header__inner">
				<div class="site-branding">
					<?php
					the_custom_logo();
					if (is_front_page() && is_home()) :
					?>
						<h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
					<?php
					else :
					?>
						<p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
					<?php
					endif;
					$travel_dams_description = get_bloginfo('description', 'display');
					if ($travel_dams_description || is_customize_preview()) :
					?>
						<p class="site-description"><?php echo $travel_dams_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
													?></p>
					<?php endif; ?>
				</div><!-- .site-branding -->

				<nav id="site-navigation" class="main-navigation">
					<button class="menu-toggle" aria-controls="primary-menu-container" aria-expanded="false">
						<svg class="icon icon-menu" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
							<path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" />
						</svg>
						<svg class="icon icon-close" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
							<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" />
						</svg>
						<span class="screen-reader-text"><?php esc_html_e('Menu', 'travel-dams'); ?></span>
					</button>

					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'menu-1',
							'menu_id'         => 'primary-menu',
							'menu_class'      => 'primary-menu',
							'container'       => 'div',
							'container_id'    => 'primary-menu-container',
							'container_class' => 'primary-menu-container',
							'walker'          => new Travel_Dams_Nav_Walker(),
							'fallback_cb'     => false,
						)
					);
					?>

					<div class="mobile-nav-overlay-backdrop"></div>
				</nav>
				<div class="search">Search</div>
			</div>

		</header>