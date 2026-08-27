<?php

/**
 * Template : archive de la taxonomie Destination
 * Comportement différent selon le niveau (zone vs pays)
 */

get_header();

$current_term = get_queried_object();
$is_zone      = (0 === $current_term->parent); // pas de parent = c'est une zone
$image_id = carbon_get_term_meta($current_term->term_id, 'hero_image');
$long_description = carbon_get_term_meta($current_term->term_id, 'country_description');
$country_map = null;
// $country_code = carbon_get_term_meta($destination_term->term_id, 'country_code');
if (!$is_zone) {
    $country_map = get_template_directory() . '/assets/maps/' . carbon_get_term_meta($current_term->term_id, 'country_code') . '.svg';
}
?>

<main id="primary" class="site-main destination-archive">

    <?php
    get_template_part('template-parts/hero', null, array(
        'context'  => $is_zone ? 'zone' : 'destination',
        'eyebrow'  => $is_zone ? __('Explorer le monde', 'travel-dams') : '',
        'title'    => $current_term->name,
        'subtitle' => $current_term->description,
        'image_id' => $image_id ? absint($image_id) : 0,
        'country_map' => $country_map
    ))
    ?>

    <!-- <?php if (!$is_zone) : ?>
        <div class="container">
            <div class="country">
                <div class="country__infos">
                    <ul>
                        <li>
                            <p><?= __('Pays', 'travel-dams') ?></p>
                        </li>
                    </ul>
                </div>
                <div class="country__description">
                    <?= wpautop($long_description) ?>
                </div>
            </div>

        </div>
        <?= wpautop($long_description) ?>
    <?php endif ?> -->


    <?php if ($is_zone && ! empty($current_term->description)) : ?>
        <div class="container container--narrow">
            <div class="destination-archive__description">
                <?php echo wp_kses_post(wpautop($current_term->description)); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="container ">
        <!-- <div class="container container--wide"> -->

        <?php if ($is_zone) : ?>

            <?php get_template_part('template-parts/destination/countries-grid', null, array('zone' => $current_term)); ?>

            <?php get_template_part('template-parts/destination/latest-articles', null, array(
                'zone'  => $current_term,
                /* translators: %s: nom de la zone */
                'title' => sprintf(__('Derniers articles en %s', 'travel-dams'), $current_term->name),
            )); ?>

        <?php else : ?>

            <?php get_template_part('template-parts/destination/country-intro', null, array('destination_term' => $current_term)); ?>

            <?php
            $pillars = array(
                TD_SLUG_CARNETS   => __('Carnets de voyage', 'travel-dams'),
                TD_SLUG_DESTINATIONS_GUIDES => __('Guides destinations', 'travel-dams'),
            );

            foreach ($pillars as $category_slug => $section_title) :

                get_template_part(
                    'template-parts/destination/content-section',
                    null,
                    array(
                        'destination_term' => $current_term,
                        'category_slug'    => $category_slug,
                        'section_title'    => $section_title,
                        'bento'            => TD_SLUG_CARNETS === $category_slug,
                    )
                );
            endforeach;
            ?>

            <?php get_template_part('template-parts/destination/guides-list', null, array(
                'destination_term' => $current_term,
            )); ?>

        <?php endif; ?>
    </div>

    <!-- <div class="container container--wide">
        <?php get_template_part('template-parts/sections/newsletter', null, $is_zone ? array(
            'eyebrow'     => __('Restez connectés', 'travel-dams'),
            /* translators: %s: nom de la zone */
            'title'       => sprintf(__("Recevez nos chroniques d'%s chaque mois", 'travel-dams'), $current_term->name),
            'disclaimer'  => __("Pas de spam, seulement de l'inspiration brute et sauvage.", 'travel-dams'),
            'tone'        => 'forest',
        ) : array(
            'eyebrow'     => __('Rejoindre la chronique', 'travel-dams'),
            'title'       => __('Rejoignez la Chronique', 'travel-dams'),
            'description' => __('Recevez chaque mois nos carnets de voyage secrets et nos guides exclusifs pour explorer le monde autrement.', 'travel-dams'),
            'disclaimer'  => __("Promis, nous n'envoyons que de la poésie, pas de spam.", 'travel-dams'),
            'tone'        => 'darkest',
            'layout'      => 'split',
        )); ?>
    </div> -->

</main>

<?php get_footer(); ?>