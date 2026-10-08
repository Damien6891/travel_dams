<?php

/**
 * Section "Guides Pratiques" d'une page pays
 *
 * @var array $args { @type WP_Term $destination_term }
 */

/** @var WP_Term $destination_term */
$destination_term = $args['destination_term'];

$query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
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

$category = td_get_guides_category();

if (! $query->have_posts()) {
    return;
}
?>

<section class="guides-list-section">

    <div class="container">
        <div class="guides-list-section__header">
            <span class="eyebrow eyebrow--muted"><?php esc_html_e('Préparer son voyage', 'travel-dams'); ?></span>
            <h2 class="guides-list-section__title"><?= $category->name ?></h2>
            <?php if ($category->description) : ?>
                <p class="guides-list-section__description"><?= esc_html($category->description) ?></p>
            <?php endif ?>
            <p class="guides-list-section__count"><?php printf(esc_html(_n('%d guide', '%d guides', $query->post_count, 'travel-dams')), $query->post_count) ?></p>
        </div>

        <div class="guides-list">
            <?php while ($query->have_posts()) :  $query->the_post(); ?>
                <?php
                $index = sprintf('%02d', $query->current_post + 1);
                ?>
                <article class="guides-list__item">
                    <span class="guides-list__index"><?= esc_html($index)  ?></span>
                    <div class="guides-list__body">
                        <h3 class="guides-list__title"><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
                        <p class="guides-list__excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?></p>
                    </div>
                    <div class="guides-list__arrow">
                        <span class="dashicons dashicons-arrow-right-alt"></span>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    </div>

</section>

<?php wp_reset_postdata(); ?>