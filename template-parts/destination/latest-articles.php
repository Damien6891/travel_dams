<?php

/**
 * Section "Derniers articles" — posts récents de n'importe quel pays
 * appartenant à la zone courante.
 *
 * @var array $args { @type WP_Term $zone, @type string $title }
 */

/** @var WP_Term $zone */
$zone  = $args['zone'];
$title = $args['title'] ?? __('Derniers articles', 'travel-dams');

$query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 3,
    'no_found_rows'  => true,
    'tax_query'      => array(
        array(
            'taxonomy'         => 'destination',
            'field'            => 'term_id',
            'terms'            => $zone->term_id,
            'include_children' => true,
        ),
    ),
));

if (! $query->have_posts()) {
    return;
}
?>

<section class="latest-articles">
    <div class="section-header">
        <h2 class="section-title"><?php echo esc_html($title); ?></h2>
        <a href="<?php echo esc_url(get_term_link($zone)); ?>" class="section-link"><?php esc_html_e('Tout voir →', 'travel-dams'); ?></a>
    </div>

    <div class="latest-articles__grid">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <article class="latest-articles__item">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('travel-dams-card', array('class' => 'latest-articles__image')); ?></a>
                <?php endif; ?>
                <span class="latest-articles__eyebrow eyebrow badge badge--eyebrow-light"><?php echo esc_html(get_the_date()); ?></span>
                <h3 class="latest-articles__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="latest-articles__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>
