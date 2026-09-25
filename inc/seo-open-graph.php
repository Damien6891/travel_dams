<?php

/**
 * SEO — Open Graph & Twitter Card
 *
 * Réutilise les mêmes fonctions de titre/description que le SEO classique :
 * travel_dams_get_seo_title()/travel_dams_get_seo_description() pour les
 * articles (inc/seo-meta-output.php), travel_dams_get_destination_seo_title()/
 * travel_dams_get_destination_seo_description() pour les pages destination
 * (inc/seo-term-destination-output.php). Google et les réseaux sociaux
 * voient ainsi toujours le même titre/description.
 *
 * L'image utilise l'image mise en avant pour les articles, hero_image pour
 * les destinations (inc/carbon-fields/term-met-destination.php).
 *
 * Toutes les URLs passent par travel_dams_absolute_url() (inc/seo-helpers.php)
 * pour éviter les URLs protocol-relative invalides pour les réseaux sociaux.
 *
 * À charger depuis functions.php, après seo-helpers.php :
 *   require get_template_directory() . '/inc/seo-open-graph.php';
 *
 * @package Travel_Dams
 */

function travel_dams_get_og_image_url()
{
    if (is_singular('post') || is_front_page() || is_page_template('page-destinations.php')) {
        $thumbnail_id = get_post_thumbnail_id(get_the_ID());

        if ($thumbnail_id) {
            return travel_dams_absolute_url(wp_get_attachment_image_url($thumbnail_id, 'large')); // seo-helpers.php
        }

        return null;
    }

    if (is_tax('destination')) {
        $image_id = carbon_get_term_meta(get_queried_object_id(), 'hero_image');

        if ($image_id) {
            return travel_dams_absolute_url(wp_get_attachment_image_url($image_id, 'large')); // seo-helpers.php
        }
    }

    return null;
}

add_action('wp_head', function () {
    if (is_front_page() || is_page_template('page-destinations.php')) {


        $title       = travel_dams_get_seo_title(get_the_ID());
        $description = travel_dams_get_seo_description(get_the_ID());
        $url         = is_front_page()
            ? travel_dams_absolute_url(home_url('/')) // seo-helpers.php
            : travel_dams_absolute_url(get_permalink()); // seo-helpers.php
        $type        = 'website';
    } elseif (is_singular('post')) {
        $title       = travel_dams_get_seo_title(get_the_ID());
        $description = travel_dams_get_seo_description(get_the_ID());
        $url         = travel_dams_absolute_url(get_permalink()); // seo-helpers.php
        $type        = 'article';
    } elseif (is_tax('destination')) {
        $term_id     = get_queried_object_id();
        $title       = travel_dams_get_destination_seo_title($term_id);
        $description = travel_dams_get_destination_seo_description($term_id);
        $url         = get_term_link($term_id, 'destination');

        if (is_wp_error($url)) {
            $url = '';
        } else {
            $url = travel_dams_absolute_url($url); // seo-helpers.php
        }

        $type = 'website';
    } else {
        return;
    }

    $image = travel_dams_get_og_image_url();

    printf('<meta property="og:type" content="%s">' . "\n", esc_attr($type));
    printf('<meta property="og:title" content="%s">' . "\n", esc_attr($title));

    if (! empty($description)) {
        printf('<meta property="og:description" content="%s">' . "\n", esc_attr($description));
    }

    if (! empty($url)) {
        printf('<meta property="og:url" content="%s">' . "\n", esc_url($url));
    }

    printf('<meta property="og:site_name" content="%s">' . "\n", esc_attr(get_bloginfo('name')));

    if ($image) {
        printf('<meta property="og:image" content="%s">' . "\n", esc_url($image));
    }

    printf('<meta name="twitter:card" content="%s">' . "\n", $image ? 'summary_large_image' : 'summary');
    printf('<meta name="twitter:title" content="%s">' . "\n", esc_attr($title));

    if (! empty($description)) {
        printf('<meta name="twitter:description" content="%s">' . "\n", esc_attr($description));
    }

    if ($image) {
        printf('<meta name="twitter:image" content="%s">' . "\n", esc_url($image));
    }
}, 5);
