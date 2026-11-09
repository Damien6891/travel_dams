<?php

/**
 * Section "Guides" d'une page pays 
 *
 * @var array $args { @type WP_Term $destination_term }
 */

$destination_term = $args['destination_term'];
$category_slugs   = (array) $args['category_slug']; // string ou array → array
$section_title    = $args['section_title'] ?? '';
$posts_per_page   = $args['posts_per_page'] ?? 4;

$query = new WP_Query(array(
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'no_found_rows'  => true,
    'tax_query'      => array(
        'relation' => 'AND',
        array(
            'taxonomy' => 'destination',
            'field'    => 'term_id',
            'terms'    => $destination_term->term_id,
        ),
        array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => $category_slugs,
        ),
    ),
));

if (! $query->have_posts()) {
    return;
}
?>



<section class="section section--guides">

    <div class="section-header">
        <div>
            <span class="eyebrow badge badge--eyebrow-light"><?php esc_html_e('Histoires vécues', 'travel-dams'); ?></span>
            <h2 class="destination-section__title"><?php echo esc_html($section_title); ?></h2>
        </div>
        <!-- <a href="<?php echo esc_url(get_term_link($destination_term)); ?>" class="section-link"><?php esc_html_e('Tout lire →', 'travel-dams'); ?></a> -->
    </div>

    <div class="post-grid">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <?php
            $highlighted = carbon_get_post_meta(get_the_ID(), 'highlighted_post');
            // $title = carbon_get_post_meta() ;

            get_template_part(
                'template-parts/content-card',
                null,
                array(
                    'variant' => 'background',
                    'show_category_badge' => false,
                    'highlighted' => $highlighted,
                    // 'title' => !empty(carbon_get_post_meta(get_the_ID(), 'breadcrumb_title')) ? carbon_get_post_meta(get_the_ID(), 'breadcrumb_title') : null,
                    'excerpt' => has_excerpt() ? get_the_excerpt() : false,
                    'eyebrow' => get_the_category()[0]->name,
                    'badge_label' => $highlighted ? __('À la une', 'travel-dams') : null,
                    'badge_style' => 'secondary'
                )
            );
            ?>
        <?php endwhile; ?>
    </div>
</section>

<?php
wp_reset_postdata();
