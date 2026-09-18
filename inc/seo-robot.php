<?php

/**
 * SEO — noindex conditionnel via le filtre wp_robots (natif WordPress
 * depuis la 5.7, pas besoin d'écrire la balise <meta name="robots"> à la
 * main, WP s'en charge en fonction de ce tableau).
 *
 * Deux cas visés :
 * - Archives de tags peu fournies (< 3 articles) — pas d'intérêt SEO,
 *   souvent perçues comme du contenu fin/dupliqué par Google.
 * - Pagination profonde (page 3 et au-delà) — contenu dupliqué de faible
 *   valeur ajoutée par rapport à la page 1.
 *
 * Seuils ajustables ci-dessous selon ton contenu réel.
 *
 * À charger depuis functions.php :
 *   require get_template_directory() . '/inc/seo-robots.php';
 *
 * @package Travel_Dams
 */

add_filter('wp_robots', function ($robots) {
    if (is_paged() && get_query_var('paged') > 2) {
        $robots['noindex'] = true;
    }

    if (is_tag()) {
        $term = get_queried_object();

        if ($term && ! is_wp_error($term) && $term->count < 3) {
            $robots['noindex'] = true;
        }
    }

    return $robots;
});
