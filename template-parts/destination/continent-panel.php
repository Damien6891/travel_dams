<?php

/**
 * Bandeau photo d'une zone + rangée de pays (pilules avec stats au survol).
 * Utilisé sur la page "Destinations" (Template Name: Destinations).
 *
 * @var array $args { @type WP_Term $zone }
 */

/** @var WP_Term $zone */
$zone = $args['zone'];

$zone_image_id = absint(carbon_get_term_meta($zone->term_id, 'zone_image_id'));
$zone_link     = get_term_link($zone);
$countries     = get_terms(array(
    'taxonomy'   => 'destination',
    'parent'     => $zone->term_id,
    'hide_empty' => false,
    'orderby'    => 'name',
));

if (empty($countries) || is_wp_error($countries)) {
    return;
}

$total_carnets = travel_dams_count_posts_for_destination($zone->term_id, TD_SLUG_CARNETS);
$total_guides  = travel_dams_count_posts_for_destination($zone->term_id, TD_SLUG_GUIDES);
?>

<div class="continent-panel">
    <a href="<?php echo esc_url(is_wp_error($zone_link) ? '#' : $zone_link); ?>" class="continent-panel__banner">
        <?php if ($zone_image_id) : ?>
            <?php echo wp_get_attachment_image($zone_image_id, 'large', false, array('class' => 'continent-panel__image')); ?>
        <?php endif; ?>
        <div class="continent-panel__banner-content">
            <h2 class="continent-panel__name"><?php echo esc_html($zone->name); ?></h2>
            <span class="continent-panel__summary">
                <?php
                printf(
                    /* translators: 1: nombre de pays, 2: nombre de carnets, 3: nombre de guides */
                    esc_html(_n('%1$d PAYS · %2$d CARNETS · %3$d GUIDES', '%1$d PAYS · %2$d CARNETS · %3$d GUIDES', count($countries), 'travel-dams')),
                    count($countries),
                    $total_carnets,
                    $total_guides
                );
                ?>
            </span>
        </div>
    </a>

    <div class="continent-panel__countries">
        <?php foreach ($countries as $country) :
            $country_image_id = absint(carbon_get_term_meta($country->term_id, 'zone_image_id'));
            $country_link     = get_term_link($country);
            $carnets          = travel_dams_count_posts_for_destination($country->term_id, TD_SLUG_CARNETS);
            $guides           = travel_dams_count_posts_for_destination($country->term_id, TD_SLUG_GUIDES);
        ?>
            <a href="<?php echo esc_url(is_wp_error($country_link) ? '#' : $country_link); ?>" class="continent-panel__country">
                <?php if ($country_image_id) : ?>
                    <?php echo wp_get_attachment_image($country_image_id, 'thumbnail', false, array('class' => 'continent-panel__country-image')); ?>
                <?php endif; ?>
                <span class="continent-panel__country-name"><?php echo esc_html($country->name); ?></span>
                <?php if ($carnets || $guides) : ?>
                    <span class="continent-panel__country-stats">
                        <?php
                        printf(
                            /* translators: 1: carnets, 2: guides */
                            esc_html__('%1$d CARNETS · %2$d GUIDES', 'travel-dams'),
                            $carnets,
                            $guides
                        );
                        ?>
                    </span>
                <?php endif; ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
