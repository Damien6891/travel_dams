<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package travel_dams
 */

get_header();

$post_id       = get_the_ID();
$start         = carbon_get_post_meta($post_id, 'trip_start_date');
$end           = carbon_get_post_meta($post_id, 'trip_end_date');
$hero_image    = carbon_get_post_meta($post_id, 'hero_image');
$carnets_cat   = travel_dams_get_pillar_term_id(TD_SLUG_CARNETS);
$is_carnet     = $carnets_cat && has_category($carnets_cat, $post_id);

$hero_eyebrow = '';
$hero_byline  = '';

if ($is_carnet) {
	$context = travel_dams_get_destination_context($post_id);
	$country = $context['country'] ?? null;

	$hero_eyebrow = $country
		/* translators: %s: nom du pays */
		? sprintf(__('Carnet de voyage · %s', 'travel-dams'), $country->name)
		: __('Carnet de voyage', 'travel-dams');

	if ($start && $end) {
		$hero_byline = sprintf(
			/* translators: 1: date de début, 2: date de fin, 3: auteur */
			__('%1$s – %2$s · %3$s', 'travel-dams'),
			date_i18n('j F Y', strtotime($start)),
			date_i18n('j F Y', strtotime($end)),
			get_the_author()
		);
	}
}
?>

<main id="primary" class="site-main">

	<?php
	get_template_part('template-parts/hero', null, array(
		'context' => $is_carnet ? 'carnet' : 'single',
		'eyebrow' => $hero_eyebrow,
		'title'   => get_the_title(),
		'byline'  => $hero_byline,
		'image_id' => $hero_image ?: get_post_thumbnail_id(),
	))
	?>

	<?php
	while (have_posts()) :
		the_post();

		get_template_part('template-parts/content', get_post_type());

	// if ($is_carnet) {
	// 	get_template_part('template-parts/carnet-share');
	// }

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
