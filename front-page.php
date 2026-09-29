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
            'eyebrow'  => __('Blog voyage · Sac à dos · Petit budget', 'travel-dams'),
            'title'    => get_the_title(),
            // 'title'    => __("Dix ans de carnets de voyage, écrits à la main", 'travel-dams'),
            'subtitle' => __('Des récits de routard, souvent seul, parfois à deux, en Asie et en Europe. Et des guides pour voyager simplement, sans se ruiner.', 'travel-dams'),
            'ctas'     => array(
                array(
                    'label'   => __("Lire les carnets", 'travel-dams'),
                    'url'     => travel_dams_get_pillar_link(TD_SLUG_CARNETS),
                    'variant' => 'primary',
                ),
                array(
                    'label'   => __('Voir les destinations', 'travel-dams'),
                    'url'     => home_url('/destinations/'),
                    'variant' => 'outline',
                ),
            ),
        ));
    endif; ?>

    <?php get_template_part('template-parts/front-page/carnets'); ?>

    <?php get_template_part('template-parts/front-page/favorites'); ?>

    <?php
    $show_highlighted_guides = carbon_get_theme_option('homepage_show_highlighted_guides')
    ?>

    <?php if ($show_highlighted_guides) : ?>
        <?php get_template_part('template-parts/front-page/guides-v2'); ?>
    <?php endif ?>

    <!-- <?php get_template_part('template-parts/sections/newsletter', null, array(
                'eyebrow'     => __('Rejoindre le cercle', 'travel-dams'),
                'title'       => __('Recevez nos chroniques sauvages', 'travel-dams'),
                'description' => __('Une fois par mois, recevez une dose d\'inspiration, des récits inédits et des conseils exclusifs directement dans votre boîte mail.', 'travel-dams'),
                'tone'        => 'darkest',
            )); ?> -->

    <?php get_template_part('template-parts/front-page/about-teaser'); ?>

</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
