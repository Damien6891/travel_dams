<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package travel_dams
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<?php echo get_the_post_type_description() ?>

	<div class="entry-content">
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
		<!-- <?php travel_dams_entry_footer(); ?> -->
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->