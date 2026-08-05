<?php

/**
 * Template Name: Destinations
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php get_template_part('template-parts/hero', null, [
        'context'  => 'destinations',
        'eyebrow'  => __('Explorer le monde', 'travel-dams'),
        'title'    => get_the_title(),
        'subtitle' => __('Deux continents, dix pays, un même engagement : le voyage lent et respectueux des terres que nous traversons.', 'travel-dams'),
        'image_id' => get_post_thumbnail_id(),
    ]); ?>


    <?php while (have_posts()) : the_post(); ?>
        <?php if (get_the_content()) : ?>
            <div class="container container--narrow page-content">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>

    <?php
    $zones = get_terms([
        'taxonomy'   => 'destination',
        'parent'     => 0,
        'hide_empty' => true,
        'orderby'    => 'name',
    ]);
    ?>

    <section class="destinations-overview">
        <div class="container container--wide">

            <?php
            get_template_part(
                'template-parts/section-heading',
                null,
                [
                    'title' => __("Par continent", 'travel-dams'),
                    'eyebrow' => __("Parcourir", 'travel-dams'),
                    // 'description' => 'super description',
                ]
            )
            ?>

            <div class="continent-panels">
                <?php foreach ($zones as $zone) : ?>
                    <?php get_template_part('template-parts/destination/continent-panel', null, ['zone' => $zone]); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- <div class="container container--wide">
        <?php get_template_part('template-parts/sections/newsletter', null, [
            'eyebrow'     => __('Rejoindre le cercle', 'travel-dams'),
            'title'       => __('Une nouvelle destination chaque saison', 'travel-dams'),
            'description' => __("Recevez un email dès qu'un nouveau pays ou carnet rejoint la carte.", 'travel-dams'),
            'tone'        => 'forest',
        ]); ?>
    </div> -->

</main><!-- #primary -->

<?php
get_footer();
