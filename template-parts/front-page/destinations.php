<?php

/**
 * Section "Explorer par destination" — grille des zones.
 *
 * @package Travel_Dams
 */

$zones = get_terms(array(
    'taxonomy'   => 'destination',
    'parent'     => 0,
    'hide_empty' => true,
));

if (empty($zones) || is_wp_error($zones)) {
    return;
}
?>

<section class="destinations-section">
    <div class="container">
        <h2 class="section-title">
            <?php esc_html_e('Explorer par destination', 'travel_dams'); ?>
        </h2>

        <div class="destinations-grid">
            <?php foreach ($zones as $zone) :
                $image_id  = get_term_meta($zone->term_id, 'zone_image_id', true);
                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : '';
                $term_link = get_term_link($zone);

                if (is_wp_error($term_link)) {
                    continue;
                }
            ?>
                <a href="<?php echo esc_url($term_link); ?>" class="destination-tile">
                    <?php if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($zone->name); ?>" loading="lazy" />
                    <?php endif; ?>
                    <span class="destination-tile__name"><?php echo esc_html($zone->name); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</section>