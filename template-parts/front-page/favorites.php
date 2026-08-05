<?php

/**
 * Section "Destinations Coup de Cœur" — sélection éditoriale
 * (Réglages > Accueil, voir travel_dams_get_homepage_favorites()).
 *
 * @package Travel_Dams
 */


$favorites = travel_dams_get_homepage_favorites();

if (empty($favorites)) {
    return;
}
?>

<section class="favorites-section">
    <div class="container">
        <div class="favorites-section__panel">

            <div class="favorites-section__intro">
                <h2 class="favorites-section__title"><?php esc_html_e('Destinations Coup de Cœur', 'travel-dams'); ?></h2>
                <p class="favorites-section__lead">
                    <?php esc_html_e('Certains lieux ne se visitent pas seulement, ils se ressentent. Voici les terres qui ont marqué notre esprit cette saison par leur beauté brute.', 'travel-dams'); ?>
                </p>

                <div class="favorites-section__list">
                    <?php foreach ($favorites as $favorite) :
                        /** @var WP_Term $term */
                        $term = $favorite['term'];
                        $link = get_term_link($term);
                        if (is_wp_error($link)) {
                            continue;
                        }
                    ?>
                        <a href="<?php echo esc_url($link); ?>" class="favorites-section__item">
                            <?php if ($favorite['image_id']) : ?>
                                <?php echo wp_get_attachment_image($favorite['image_id'], 'thumbnail', false, array('class' => 'favorites-section__item-image')); ?>
                            <?php endif; ?>
                            <span class="favorites-section__item-body">
                                <span class="favorites-section__item-title"><?php echo esc_html($term->name); ?></span>
                                <?php if ($favorite['description']) : ?>
                                    <span class="favorites-section__item-desc"><?php echo esc_html($favorite['description']); ?></span>
                                <?php endif; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="favorites-section__gallery">
                <?php
                $gallery_images = array_filter(wp_list_pluck($favorites, 'image_id'));
                foreach (array_slice($gallery_images, 0, 4) as $image_id) :
                ?>
                    <?php echo wp_get_attachment_image(absint($image_id), 'medium_large', false, array('class' => 'favorites-section__gallery-image')); ?>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</section>