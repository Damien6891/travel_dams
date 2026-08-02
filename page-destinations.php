<?php

/**
 * Template Name: Destinations
 */

get_header();
?>

<main id="primary" class="site-main">

    <!-- <?php if (travel_dams_has_hero()) : ?>
        <?php endif; ?> -->
    <?php get_template_part('template-parts/hero', null, [
        'title'    => get_the_title(),
        'image_id' => get_post_thumbnail_id(),
        'context'  => 'destinations',
    ]); ?>

    <?php while (have_posts()) : the_post(); ?>
        <div class="page-content">
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>

    <?php
    $zones = get_terms([
        'taxonomy'   => 'destination',
        'parent'     => 0,
        'hide_empty' => true,
    ]);
    ?>

    <section class="destinations-overview">
        <div class="destinations-overview__grid">
            <?php foreach ($zones as $zone) :
                $count = count(get_terms([
                    'taxonomy'   => 'destination',
                    'parent'     => $zone->term_id,
                    'hide_empty' => true,
                ]));
            ?>
                <?php get_template_part('template-parts/destination-tile', null, [
                    'term'  => $zone,
                    'label' => sprintf(_n('%d pays', '%d pays', $count, 'travel-dams'), $count),
                ]); ?>
            <?php endforeach; ?>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();
