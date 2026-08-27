<?php

/**
 * Bloc d'introduction d'une page pays : description éditoriale, tags,
 * image (voir la section "Thaïlande : L'âme du sourire..." du design system).
 *
 * @var array $args { @type WP_Term $destination_term }
 */

/** @var WP_Term $destination_term */
$destination_term = $args['destination_term'];

$image_id = absint(carbon_get_term_meta($destination_term->term_id, 'destination_intro_image_id'));
$tags_raw = carbon_get_term_meta($destination_term->term_id, 'destination_tags');
$tags     = $tags_raw ? array_filter(array_map('trim', explode(',', $tags_raw))) : array();
$country_description = carbon_get_term_meta($destination_term->term_id, 'country_description');
$country_currency = carbon_get_term_meta($destination_term->term_id, 'country_currency');
$country_capital = carbon_get_term_meta($destination_term->term_id, 'country_capital');
$country_language = carbon_get_term_meta($destination_term->term_id, 'country_language');
$country_code = carbon_get_term_meta($destination_term->term_id, 'country_code');
$map_path = get_template_directory() . '/assets/maps/' . $country_code . '.svg';

// if (empty($destination_term->description) && ! $image_id && empty($tags)) {
//     return;
// }
?>

<section class="country-intro">


    <div class="country-intro__infos">
        <span class="country-infos-title"><?= __('En bref', 'travel-dams') ?></span>

        <ul>
            <li>
                <p><?= __('Pays', 'travel-dams') ?></p>
                <p><?= $destination_term->name ?></p>
            </li>
            <hr>
            <li>
                <p><?= __('Monnaie', 'travel-dams') ?></p>
                <p><?= $country_currency ?></p>
            </li>
            <hr>
            <li>
                <p><?= __('Capitale', 'travel-dams') ?></p>
                <p><?= $country_capital ?></p>
            </li>
            <hr>
            <li>
                <p><?= __('Langue(s)', 'travel-dams') ?></p>
                <p><?= $country_language ?></p>
            </li>
        </ul>


        <?php if ($country_code && file_exists($map_path)) : ?>

            <div class="country-intro__map ">
                <?= file_get_contents($map_path) ?>
            </div>

        <?php endif ?>
    </div>

    <div class="country-intro__text">
        <?php if (! empty($country_description)) : ?>
            <div class="country-intro__description">
                <?php echo wp_kses_post(wpautop($country_description)); ?>
            </div>
        <?php endif; ?>

        <?php if (! empty($tags)) : ?>
            <div class="country-intro__tags">
                <?php foreach ($tags as $tag) : ?>
                    <span class="chip">#<?php echo esc_html($tag); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- <?php if ($image_id) : ?>
        <?php echo wp_get_attachment_image($image_id, 'large', false, array('class' => 'country-intro__image')); ?>
    <?php endif; ?> -->
</section>