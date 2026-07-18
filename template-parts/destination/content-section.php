<?php

/**
 * Un bloc de contenu (une catégorie) pour la page destination courante
 * 
 * @var array $args
 */

/** @var WP_Term $destination_term */
$destination_term = $args['destination_term'];
/** @var WP_Term $category_slug */
$category_slug    = $args['category_slug'];
/** @var WP_Term $section_title */
$section_title    = $args['section_title'];

$query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'no_found_rows'  => true, // pas de pagination ici, pas besoin du compte total
    'category_name'  => $category_slug,
    'tax_query'      => array(
        array(
            'taxonomy' => 'destination',
            'field'    => 'term_id',
            'terms'    => $destination_term->term_id,
        ),
    ),
));

if (! $query->have_posts()) {
    return; // rien pour cette catégorie sur ce pays : on n'affiche rien du tout
}
?>

<section class="destination-section destination-section--<?php echo esc_attr($category_slug); ?>">

    <h2 class="destination-section__title"><?php echo esc_html($section_title); ?></h2>
    <div class="post-grid">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php get_template_part('template-parts/content', 'card', array('show_category_badge' => false)); ?>
        <?php endwhile; ?>
    </div>
</section>

<?php
wp_reset_postdata();
