<footer id="colophon" class="site-footer">
	<div class="site-footer__container">

		<div class="footer-widgets">

			<nav class="footer-widgets__column footer-nav" aria-label="<?php esc_attr_e('Rubriques', 'travel-dams'); ?>">
				<h2 class="footer-nav__title"><?php esc_html_e('Rubriques', 'travel-dams'); ?></h2>
				<ul class="footer-nav__list">
					<?php
					// Slugs de référence en FR (langue par défaut du site)
					$pillars = array(
						TD_SLUG_CARNETS   => __('Carnets de voyage', 'travel-dams'),
						TD_SLUG_DESTINATIONS_GUIDES => __('Guides destinations', 'travel-dams'),
						TD_SLUG_GUIDES    => __('Guides pratiques', 'travel-dams'),
						// 'reflexions'          => __('Réflexions', 'travel-dams'),
					);


					foreach ($pillars as $slug => $label) :
						// 'lang' => '' pour ignorer le filtre Polylang et trouver le terme FR quelle que soit la langue active
						$terms = get_terms(array(
							'taxonomy'   => 'category',
							'slug'       => $slug,
							'lang'       => '',
							'hide_empty' => false,
						));

						if (is_wp_error($terms) || empty($terms)) {
							continue;
						}

						$category = $terms[0];

						// Bascule vers le terme traduit correspondant à la langue courante
						if (function_exists('pll_get_term')) {
							$translated_id = pll_get_term($category->term_id);
							if ($translated_id) {
								$category = get_category($translated_id);
							}
						}

						if (! $category) {
							continue;
						}

					?>
						<li class="footer-nav__item">
							<a class="footer-nav__link" href="<?php echo esc_url(get_category_link($category)); ?>">
								<?php echo esc_html($label); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<nav class="footer-widgets__column footer-destinations" aria-label="<?php esc_attr_e('Destinations', 'travel-dams'); ?>">
				<h2 class="footer-destinations__title"><?php esc_html_e('Destinations', 'travel-dams'); ?></h2>
				<ul class="footer-destinations__list">
					<?php
					$zones = get_terms(array(
						'taxonomy'   => 'destination',
						'parent'     => 0,
						// 'lang'       => '',
						'number'     => 8,
						'orderby'    => 'count',
						'order'      => 'DESC',
						'hide_empty' => true,
					));

					if (! is_wp_error($zones)) :
						foreach ($zones as $zone) :
					?>
							<li class="footer-destinations__item">
								<a class="footer-destinations__link" href="<?php echo esc_url(get_term_link($zone)); ?>">
									<?php echo esc_html($zone->name); ?>
								</a>
							</li>
					<?php endforeach;
					endif;
					?>
				</ul>
			</nav>

			<div class="footer-widgets__column footer-about">
				<h2 class="footer-about__title"><?php esc_html_e('À propos', 'travel-dams'); ?></h2>
				<p class="footer-about__text">
					<?php esc_html_e('Simplement quelqu\'un qui aime voyager et partager sa passion.', 'travel-dams'); ?>
				</p>
				<ul class="footer-about__socials">
					<li class="footer-about__social-item">
						<a class="footer-about__social-link" href="https://www.instagram.com/travel-dams" target="_blank" rel="noopener noreferrer">
							<span class="dashicons dashicons-instagram"></span>
							<span class="screen-reader-text"><?php esc_html_e('Instagram', 'travel-dams'); ?></span>
						</a>
					</li>
					<!-- <li class="footer-about__social-item">
						<a class="footer-about__social-link" href="mailto:contact@travel-dams.fr">
							<span class="dashicons dashicons-email-alt"></span>
							<span class="screen-reader-text"><?php esc_html_e('Email', 'travel-dams'); ?></span>
						</a>
					</li> -->

				</ul>
			</div>

		</div>

		<div class="footer-bottom">
			<p class="footer-bottom__copyright">
				&copy; <?php echo esc_html(date_i18n('Y')); ?> Travel Dam's
			</p>

			<ul class="footer-bottom__legal">
				<?php
				$legal_page = get_page_by_path('mentions-legales');
				if ($legal_page) :
				?>
					<li class="footer-bottom__legal-item">
						<a class="footer-bottom__legal-link" href="<?php echo esc_url(get_permalink($legal_page)); ?>">
							<?php esc_html_e('Mentions légales', 'travel-dams'); ?>
						</a>
					</li>
					<?php endif;

				if (function_exists('pll_the_languages')) :
					$languages = pll_the_languages(array('raw' => 1));
					if ($languages) :
					?>
						<li class="footer-bottom__legal-item">
							<ul class="footer-bottom__lang-switcher">
								<?php foreach ($languages as $language) : ?>
									<li class="footer-bottom__lang-item<?php echo $language['current_lang'] ? ' footer-bottom__lang-item--current' : ''; ?>">
										<a class="footer-bottom__lang-link" href="<?php echo esc_url($language['url']); ?>">
											<?php echo esc_html(strtoupper($language['slug'])); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</li>
				<?php
					endif;
				endif;
				?>
			</ul>
		</div>

	</div>
</footer>