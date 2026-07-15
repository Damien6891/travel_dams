<?php

/**
 * Grille des pays d'une zone (niveau "continent")
 * 
 * @var array $args
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

<div class="countries-grid">
    <?php foreach ($countries as $country) : ?>
        <a href="<?php echo esc_url(get_term_link($country)); ?>" class="countries-grid__item">
            <h2><?php echo esc_html($country->name); ?></h2>
            <?php
            $count = $country->count;
            if ($count > 0) :
            ?>
                <span class="countries-grid__count">
                    <?php
                    printf(
                        /* translators: %d: nombre d'articles */
                        esc_html(_n('%d article', '%d articles', $count, 'travel-dams')),
                        absint($count)
                    );
                    ?>
                </span>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
</div>