<?php

/**
 * SEO — JSON-LD TouristDestination, pour les pages destination de niveau
 * pays (taxonomie `destination`, où vivent country_description,
 * country_code... dans inc/carbon-fields/term-meta-destination-country.php).
 *
 * Réutilise travel_dams_get_destination_seo_description()
 * (inc/seo-term-destination-output.php) pour rester cohérent avec la
 * meta description et l'Open Graph déjà en place.
 *
 * addressCountry attend un code ISO 3166-1 alpha-2 en MAJUSCULES — le champ
 * country_code est stocké en minuscules (cf. son aide en ligne), donc
 * converti ici avec strtoupper().
 *
 * À charger depuis functions.php, après seo-helpers.php et
 * seo-term-destination-output.php :
 *   require get_template_directory() . '/inc/seo-json-ld-destination.php';
 *
 * @package Travel_Dams
 */

add_action('wp_head', function () {
    if (! is_tax('destination')) {
        return;
    }

    $term_id = get_queried_object_id();
    $term    = get_queried_object();

    // Uniquement les pages pays (1 ancêtre) — même périmètre que les champs
    // SEO destination et country_description.
    if (! $term || is_wp_error($term) || count(get_ancestors($term_id, 'destination', 'taxonomy')) !== 1) {
        return;
    }

    $url = get_term_link($term_id, 'destination');

    if (is_wp_error($url)) {
        return;
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'TouristDestination',
        'name'        => $term->name,
        'description' => travel_dams_get_destination_seo_description($term_id),
        'url'         => travel_dams_absolute_url($url), // seo-helpers.php
    );

    $country_code = carbon_get_term_meta($term_id, 'country_code');

    if (! empty($country_code)) {
        $schema['address'] = array(
            '@type'          => 'PostalAddress',
            'addressCountry' => strtoupper($country_code),
        );
    }

    $image_id = carbon_get_term_meta($term_id, 'hero_image');

    if ($image_id) {
        $schema['image'] = travel_dams_absolute_url(wp_get_attachment_image_url($image_id, 'large')); // seo-helpers.php
    }

    echo '<script type="application/ld+json">'
        . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . '</script>' . "\n";
}, 10);
