<?php

/**
 * Template : archive de la taxonomie Destination
 * Comportement différent selon le niveau (zone vs pays)
 */

get_header();

$current_term = get_queried_object();
$is_zone      = (0 === $current_term->parent); // pas de parent = c'est une zone
$image_id = carbon_get_term_meta($current_term->term_id, 'zone_image_id');

?>

<main id="primary" class="site-main destination-archive">

    <?php
    get_template_part('template-parts/hero', null, array(
        'context' => 'destination',
        'title' => $current_term->name,
        'subtitle' => $current_term->description,
        'image_id' => $image_id ? absint($image_id) : 0
    ))
    ?>

    <header class="destination-archive__header">
        <h1><?php echo esc_html($current_term->name); ?></h1>
        <?php if (! empty($current_term->description)) : ?>
            <div class="destination-archive__description">
                <?php echo wp_kses_post(wpautop($current_term->description)); ?>
            </div>
        <?php endif; ?>
    </header>

    <?php if ($is_zone) : ?>

        <?php get_template_part('template-parts/destination/countries-grid', null, array('zone' => $current_term)); ?>

    <?php else : ?>

        <?php
        $pillars = array(
            'carnets-de-voyage'   => __('Carnets de voyage', 'travel-dams'),
            'guides-destinations' => __('Guides destination', 'travel-dams'),
            'guides-pratiques'    => __('Guides pratiques', 'travel-dams'),
        );

        foreach ($pillars as $category_slug => $section_title) :

            get_template_part(
                'template-parts/destination/content-section',
                null,
                array(
                    'destination_term' => $current_term,
                    'category_slug'    => $category_slug,
                    'section_title'    => $section_title,
                )
            );
        endforeach;
        ?>

    <?php endif; ?>

</main>

<?php get_footer(); ?>