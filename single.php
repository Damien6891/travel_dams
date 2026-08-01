<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package travel_dams
 */

get_header();

$start = carbon_get_post_meta(get_the_ID(), 'trip_start_date');
$end   = carbon_get_post_meta(get_the_ID(), 'trip_end_date');
$hero_image = carbon_get_post_meta(get_the_ID(), 'hero_image');


// if ($start && $end) {
// 	// format brut Y-m-d, à reformater selon l'affichage voulu (ex: date_i18n)
// 	echo esc_html(date_i18n('j F Y', strtotime($start)));
// 	echo ' – ';
// 	echo esc_html(date_i18n('j F Y', strtotime($end)));
// }
?>

<main id="primary" class="site-main">


	<?php
	get_template_part('template-parts/hero', null, array(
		'context' => 'single',
		'title' => get_the_title(),
		'image_id' => $hero_image ?? get_post_thumbnail_id()
	))
	?>

	<?php if ($start && $end) : ?>
		HERE
		<div class="container">
			<p>Du <?= esc_html(date_i18n('j F Y', strtotime($start))) ?> au <?= esc_html(date_i18n('j F Y', strtotime($end))) ?></p>
		</div>
	<?php endif ?>

	<?php
	while (have_posts()) :
		the_post();

		get_template_part('template-parts/content', get_post_type());

	// the_post_navigation(
	// 	array(
	// 		'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'travel_dams') . '</span> <span class="nav-title">%title</span>',
	// 		'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'travel_dams') . '</span> <span class="nav-title">%title</span>',
	// 	)
	// );

	// // If comments are open or we have at least one comment, load up the comment template.
	// if (comments_open() || get_comments_number()) :
	// 	comments_template();
	// endif;

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
