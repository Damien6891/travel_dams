<?php

/**
 * Section "Derniers carnets de voyage".
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

        <h2 class="section-title">
            <?php esc_html_e('Derniers carnets de voyage', 'travel-dams'); ?>
        </h2>

        <div class="post-grid">
            <?php while ($carnets_query->have_posts()) : $carnets_query->the_post(); ?>
                <article <?php post_class('post-card'); ?>>
                    <a href="<?php the_permalink(); ?>" class="post-card__link">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-card__thumbnail">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="post-card__title"><?php the_title(); ?></h3>
                        <p class="post-card__excerpt"><?php the_excerpt(); ?></p>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>

        <a href="<?php echo (travel_dams_get_pillar_link(TD_SLUG_CARNETS)); ?>" class="section-link">
            <?php esc_html_e('Voir tous les carnets', 'travel-dams'); ?>
        </a>

    </div>
</section>

<?php wp_reset_postdata(); ?>