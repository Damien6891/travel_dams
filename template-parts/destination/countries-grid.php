<?php

/**
 * Grille des pays d'une zone (niveau "continent") — tuiles photo avec
 * compteur carnets/guides (voir CountryCard du design system).
 *
 * @var array $args { @type WP_Term $zone }
 */

/** @var WP_Term $zone */
$zone = $args['zone'];

$countries = get_terms(array(
    'taxonomy'   => 'destination',
    'parent'     => $zone->term_id,
    'hide_empty' => false,
    'orderby'    => 'name',
));

if (empty($countries)) :
?>
    <p><?php esc_html_e('Aucune destination pour le moment dans cette zone.', 'travel-dams'); ?></p>
<?php
    return;
endif;
?>

<div class="section-header">
    <div>
        <h2 class="section-title"><?php esc_html_e('Parcourir par pays', 'travel-dams'); ?></h2>
        <p class="section-header__lead"><?php esc_html_e('Sélectionnez votre prochaine aventure parmi nos guides experts.', 'travel-dams'); ?></p>
    </div>
    <div class="view-toggle" role="group" aria-label="<?php esc_attr_e('Mode d\'affichage', 'travel-dams'); ?>">
        <button type="button" class="icon-btn is-active" data-view="grid" aria-pressed="true" aria-label="<?php esc_attr_e('Vue en grille', 'travel-dams'); ?>" title="<?php esc_attr_e('Vue en grille', 'travel-dams'); ?>">▦</button>
        <button type="button" class="icon-btn" data-view="list" aria-pressed="false" aria-label="<?php esc_attr_e('Vue en liste', 'travel-dams'); ?>" title="<?php esc_attr_e('Vue en liste', 'travel-dams'); ?>">☰</button>
    </div>
</div>

<div class="countries-grid" data-view-target="grid">
    <?php foreach ($countries as $country) :
        $image_id = absint(carbon_get_term_meta($country->term_id, 'zone_image_id'));
        $link     = get_term_link($country);
        if (is_wp_error($link)) {
            continue;
        }
        $carnets = travel_dams_count_posts_for_destination($country->term_id, TD_SLUG_CARNETS);
        $guides  = travel_dams_count_posts_for_destination($country->term_id, TD_SLUG_GUIDES);
    ?>
        <a href="<?php echo esc_url($link); ?>" class="country-card">
            <?php if ($image_id) : ?>
                <?php echo wp_get_attachment_image($image_id, 'large'); ?>
            <?php endif; ?>
            <span class="country-card__body">
                <span class="country-card__name"><?php echo esc_html($country->name); ?></span>
                <span class="country-card__stats">
                    <?php
                    printf(
                        /* translators: 1: carnets, 2: guides */
                        esc_html__('%1$d CARNETS · %2$d GUIDES', 'travel-dams'),
                        $carnets,
                        $guides
                    );
                    ?>
                </span>
            </span>
        </a>
    <?php endforeach; ?>
</div>
