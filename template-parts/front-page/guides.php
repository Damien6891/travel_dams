<?php

/**
 * Section "Guides pratiques".
 *
 * @package Travel_Dams
 */

$guides_query = new WP_Query(array(
    'category_name'       => TD_SLUG_GUIDES,
    'posts_per_page'      => 3,
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
));

if (! $guides_query->have_posts()) {
    return;
}
?>

<section class="guides-section">

    <div class="container container--wide">

        <h2 class="section-title">
            <?php esc_html_e('Guides pratiques', 'travel-dams'); ?>
        </h2>

        <div class="post-grid">
            <?php while ($guides_query->have_posts()) : $guides_query->the_post(); ?>
                <article <?php post_class('post-card'); ?>>
                    <a href="<?php the_permalink(); ?>" class="post-card__link">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-card__thumbnail">
                                <?php the_post_thumbnail('medium_large'); ?>
                            </div>
                        <?php endif; ?>
                        <h3 class="post-card__title"><?php the_title(); ?></h3>
                    </a>
                </article>
            <?php endwhile; ?>
        </div>

        <a href="<?php echo (travel_dams_get_pillar_link(TD_SLUG_GUIDES)); ?>" class="section-link">
            <?php esc_html_e('Voir tous les guides', 'travel-dams'); ?>
        </a>

    </div>
</section>

<?php wp_reset_postdata(); ?>