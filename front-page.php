<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package travel_dams
 */

get_header();
?>

<main id="primary" class="site-main">


    <?php if (travel_dams_has_hero()) :

        get_template_part('template-parts/hero', null, array(
            'context'  => 'front-page',
            // 'title'    => __('Ton titre accrocheur ici', 'travel-dams'),
            // 'subtitle' => __('Une accroche courte sur l’esprit du blog.', 'travel-dams'),
        ));
    endif; ?>

    <?php get_template_part('template-parts/front-page/destinations'); ?>

    <?php get_template_part('template-parts/front-page/carnets'); ?>

    <?php get_template_part('template-parts/front-page/guides'); ?>

    <?php get_template_part('template-parts/front-page/about-teaser'); ?>

</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
