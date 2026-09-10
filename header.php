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
					<!-- <span class="dashicons dashicons-no-alt"></span> -->

					<div id="primary-menu-container" class="primary-menu-container">

						<button class="menu-close-mobile" aria-controls="primary-menu-container" aria-expanded="false" aria-label="<?= esc_attr__('Fermer le menu', 'travel-dams') ?>">
							<svg class="icon icon-close" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
								<path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" />
							</svg>
						</button>

						<?php
						wp_nav_menu(
							array(
								'theme_location'  => 'menu-1',
								'menu_id'         => 'primary-menu',
								'menu_class'      => 'primary-menu',
								// 'container'       => 'div',
								// 'container_id'    => 'primary-menu-container',
								// 'container_class' => 'primary-menu-container',
								'walker'          => new Travel_Dams_Nav_Walker(),
								'fallback_cb'     => false,
								// Add close button in menu
								// 'items_wrap'	  =>  '<button class="menu-close-mobile" aria-controls="primary-menu-container" aria-expanded="false" aria-label="' . esc_attr__('Fermer le menu', 'travel-dams') . '"><svg class="icon icon-close" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" fill="none" /></svg></button><ul id="%1$s" class="%2$s">%3$s</ul>'
							)
						);
						?>

						<?php
						if (function_exists('pll_the_languages')) :
							$languages = pll_the_languages(array('raw' => 1));
							if ($languages) :
						?>
								<ul class="site-header__lang-switcher site-header__lang-switcher--mobile">
									<!-- <ul class=""> -->
									<?php foreach ($languages as $language) : ?>
										<li class="site-header__lang-item<?php echo $language['current_lang'] ? ' site-header__lang-item--current' : ''; ?>">
											<a class="site-header__lang-link" href="<?php echo esc_url($language['url']); ?>">
												<?php echo esc_html(strtoupper($language['slug'])); ?>
											</a>
										</li>
									<?php endforeach; ?>
									<!-- </ul> -->
								</ul>
						<?php
							endif;
						endif;
						?>

					</div>

					<div class="mobile-nav-overlay-backdrop"></div>
				</nav>

				<div class="site-header__actions">
					<form role="search" method="get" class="header-search" action="<?php echo esc_url(home_url('/')); ?>">
						<label class="screen-reader-text" for="header-search-field"><?php esc_html_e('Rechercher', 'travel-dams'); ?></label>
						<input type="search" id="header-search-field" class="input input--search" name="s" placeholder="<?php esc_attr_e('Rechercher...', 'travel-dams'); ?>" value="<?php echo get_search_query(); ?>">
					</form>

					<!-- <a href="#newsletter" class="btn btn--accent btn--s site-header__subscribe"><?php esc_html_e('Subscribe', 'travel-dams'); ?></a> -->
					<?php
					if (function_exists('pll_the_languages')) :
						$languages = pll_the_languages(array('raw' => 1));
						if ($languages) :
					?>
							<ul class="site-header__lang-switcher">
								<!-- <ul class=""> -->
								<?php foreach ($languages as $language) : ?>
									<li class="footer-bottom__lang-item<?php echo $language['current_lang'] ? ' footer-bottom__lang-item--current' : ''; ?>">
										<a class="footer-bottom__lang-link" href="<?php echo esc_url($language['url']); ?>">
											<?php echo esc_html(strtoupper($language['slug'])); ?>
										</a>
									</li>
								<?php endforeach; ?>
								<!-- </ul> -->
							</ul>
					<?php
						endif;
					endif;
					?>
				</div>
			</div>

		</header>