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
    <div class="container container--wide">
        <div class="favorites-section__panel">

            <div class="favorites-section__intro">
                <h2 class="favorites-section__title"><?php esc_html_e('Explorer par destination', 'travel-dams'); ?></h2>
                <p class="favorites-section__lead">
                    <?php esc_html_e('Des pays traversés en sac à dos, parfois en quelques jours, parfois pendant un mois. Chacun avec son carnet, et bientôt ses guides.', 'travel-dams'); ?>
                </p>

                <a class="link" href="<?= td_get_destinations_page() ?>"><?= __('Toutes les destinations') ?> →</a>


            </div>


            <div class="favorites-section__gallery">
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
                            <h3 class="favorites-section__item-title"><?php echo esc_html($term->name); ?></h3>
                            <?php if ($favorite['description']) : ?>
                                <span class="favorites-section__item-desc"><?php echo esc_html($favorite['description']); ?></span>
                            <?php endif; ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
</section>