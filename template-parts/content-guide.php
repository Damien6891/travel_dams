<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package travel_dams
 */
$showSummary = carbon_get_post_meta(get_the_ID(), 'show_summary');
$sections = travel_dams_get_guide_sommaire();
$hasChildren = false;
$hasChildren = !empty(array_filter($sections, fn($item) => !empty($item['children'])));
$cat = get_the_category();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">


		<?php if ($showSummary && count($sections) >= 3) : ?>
			<nav class="summary summary--guide" aria-label="<?= esc_attr__('Sommaire', 'travel-dams') ?>" <?= $hasChildren ? 'data-has-children' : '' ?>>
				<details class="summary__container">
					<summary class="summary__toggle">
						<?= __('Sommaire', 'travel-dams') ?> <span class="summary__current"></span>
					</summary>
					<ul class="summary__list">
						<?php foreach ($sections as $section) : ?>
							<li>
								<a class="summary__item" href="#<?= esc_attr($section['id']) ?>">
									<span class="summary__title"><?= esc_html(wptexturize($section['title'])) ?></span>
								</a>
								<?php if ($section['children']) : ?>
									<ul class="summary__sublist">
										<?php foreach ($section['children'] as $child) : ?>
											<li>
												<a class="summary__item summary__item--sub" href="#<?= esc_attr($child['id']) ?>">
													<?= esc_html(wptexturize($child['title'])) ?>
												</a>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</details>
			</nav>
		<?php endif; ?>
		<?php
		the_content(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'travel_dams'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post(get_the_title())
			)
		);

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__('Pages:', 'travel_dams'),
				'after'  => '</div>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<div class="container">

			<?php
			$post_id = get_the_ID();

			$pillar_narratif = array_filter([
				travel_dams_get_pillar_term_id('carnets-de-voyage'),
				travel_dams_get_pillar_term_id('guides-destinations'),
			]);
			$pillar_pratique = travel_dams_get_pillar_term_id('guides-pratiques');

			if (has_category($pillar_narratif, $post_id)) {
				$related = travel_dams_get_related_posts($post_id);
			} elseif ($pillar_pratique && has_category($pillar_pratique, $post_id)) {
				$related = travel_dams_get_related_by_category($post_id);
			} else {
				$related = [];
			}

			if (! empty($related)) {


				$carnets_cat_id = travel_dams_get_pillar_term_id(TD_SLUG_CARNETS);

				// if ($carnets_cat_id && has_category($carnets_cat_id, $post_id)) {
				// 	get_template_part('template-parts/destination/next-carnet', null, ['post' => $related[0]]);
				// } else {
				// 	get_template_part('template-parts/related-posts', null, ['posts' => $related]);
				// }
				get_template_part('template-parts/related-posts', null, ['posts' => $related]);
			}
			?>
		</div>

		<div>
			<?php
			if (comments_open() || get_comments_number()) :
				comments_template();
			endif;
			?>
		</div>

		<!-- <?php travel_dams_entry_footer(); ?> -->
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->