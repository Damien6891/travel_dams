<?php

/**
 * Section "Dernières Aventures".
 *
 * @package Travel_Dams
 */

$carnets_query = new WP_Query(array(
    'category_name'       => TD_SLUG_CARNETS, // slug de la catégorie
    'posts_per_page'      => 3,
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
));

if (! $carnets_query->have_posts()) {
    return;
}

?>

<section class="carnets-section">

    <div class="container container--wide">

        <div class="section-header">
            <div>
                <h2 class="section-title"><?php esc_html_e('Dernières Aventures', 'travel-dams'); ?></h2>
                <p class="section-header__lead"><?php esc_html_e("Des récits immersifs écrits sur le terrain, capturant l'essence même du voyage contemplatif et respectueux.", 'travel-dams'); ?></p>
            </div>
            <a href="<?php echo esc_url(travel_dams_get_pillar_link(TD_SLUG_CARNETS)); ?>" class="section-link"><?php esc_html_e('Voir tout →', 'travel-dams'); ?></a>
        </div>

        <div class="post-grid">
            <?php while ($carnets_query->have_posts()) : $carnets_query->the_post(); ?>
                <?php get_template_part('template-parts/content-card', null, array('variant' => 'photo')); ?>
            <?php endwhile; ?>
        </div>

    </div>
</section>

<?php wp_reset_postdata(); ?>
