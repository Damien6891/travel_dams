<?php

/**
 * SEO — récupération avec fallback + injection <title> / <meta description>
 * pour les pages destination (taxonomie `destination`).
 *
 * Chaîne de fallback pour la description :
 *   seo_description (custom) → country_description → description native
 *   du terme → tagline du site.
 *
 * Les champs custom sont définis dans
 * inc/carbon-fields/term-meta-destination-seo.php. Ce fichier n'a pas sa
 * place dans inc/carbon-fields/ (ce n'est pas une définition de champs) —
 * à charger directement depuis functions.php :
 *   require get_template_directory() . '/inc/seo-term-destination-output.php';
 *
 * @package Travel_Dams
 */

function travel_dams_get_destination_seo_title($term_id)
{
    $custom = carbon_get_term_meta($term_id, 'seo_title');

    if (! empty($custom)) {
        return $custom;
    }

    $term = get_term($term_id, 'destination');

    if (! $term || is_wp_error($term)) {
        return get_bloginfo('name');
    }

    return $term->name . ' — ' . get_bloginfo('name');
}

function travel_dams_get_destination_seo_description($term_id)
{
    $custom = carbon_get_term_meta($term_id, 'seo_description');

    if (! empty($custom)) {
        return wp_trim_words(wp_strip_all_tags($custom), 30, '…');
    }

    $description = carbon_get_term_meta($term_id, 'country_description');

    if (empty($description)) {
        $term = get_term($term_id, 'destination');
        $description = (! $term || is_wp_error($term)) ? '' : $term->description;
    }

    if (empty($description)) {
        $description = get_bloginfo('description');
    }

    return wp_trim_words(wp_strip_all_tags($description), 30, '…');
}

add_filter('document_title_parts', function ($title_parts) {
    if (! is_tax('destination')) {
        return $title_parts;
    }

    $custom = carbon_get_term_meta(get_queried_object_id(), 'seo_title');

    if (! empty($custom)) {
        return array('title' => $custom);
    }

    return $title_parts;
});

add_action('wp_head', function () {
    if (! is_tax('destination')) {
        return;
    }

    $description = travel_dams_get_destination_seo_description(get_queried_object_id());

    if (empty($description)) {
        return;
    }

    printf(
        '<meta name="description" content="%s">' . "\n",
        esc_attr($description)
    );
}, 1);
