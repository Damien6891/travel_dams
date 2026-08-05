<?php

/**
 * Section "Guides Pratiques" d'une page pays — liste en 4 colonnes
 * (icône + titre + description + lien), plus sobre que les grilles de cartes.
 *
 * @var array $args { @type WP_Term $destination_term }
 */

/** @var WP_Term $destination_term */
$destination_term = $args['destination_term'];

$query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => 4,
    'no_found_rows'  => true,
    'category_name'  => TD_SLUG_GUIDES,
    'tax_query'      => array(
        array(
            'taxonomy' => 'destination',
            'field'    => 'term_id',
            'terms'    => $destination_term->term_id,
        ),
    ),
));

if (! $query->have_posts()) {
    return;
}
?>

<section class="guides-list-section">
    <div class="guides-list-section__header">
        <span class="eyebrow badge badge--eyebrow-light"><?php esc_html_e('Préparer son voyage', 'travel-dams'); ?></span>
        <h2 class="guides-list-section__title"><?php esc_html_e('Guides Pratiques', 'travel-dams'); ?></h2>
    </div>

    <div class="guides-list">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <article class="guides-list__item">
                <h3 class="guides-list__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <p class="guides-list__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                <a href="<?php the_permalink(); ?>" class="guides-list__link"><?php esc_html_e('Lire la suite', 'travel-dams'); ?></a>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>
