<?php

/**
 * Fil d'Ariane — calcul de la hiérarchie et affichage visuel.
 *
 * travel_dams_get_breadcrumb_trail() retourne un tableau de niveaux
 * (title/url), réutilisé à la fois pour l'affichage (ci-dessous) et pour
 * le JSON-LD BreadcrumbList (inc/seo-json-ld-breadcrumb.php).
 *
 * Toutes les URLs passent par travel_dams_absolute_url() (inc/seo-helpers.php)
 * pour éviter les URLs protocol-relative (//host/...) que WordPress peut
 * générer en local — invalides pour les validateurs JSON-LD.
 *
 * À charger depuis functions.php, après seo-helpers.php :
 *   require get_template_directory() . '/inc/breadcrumb.php';
 *
 * @package Travel_Dams
 */

/**
 * Lien vers la page hub des destinations (page-destinations.php, slug
 * "destinations"), insérée entre Accueil et le premier niveau de
 * taxonomie — c'est le vrai point d'entrée de navigation vers les
 * destinations.
 */
function travel_dams_get_destinations_hub_link()
{
    $page = get_page_by_path('destinations');

    if (! $page) {
        return null;
    }

    if (function_exists('pll_get_post')) {
        $translated_id = pll_get_post($page->ID);

        if ($translated_id) {
            $page = get_post($translated_id);
        }
    }

    if (! $page) {
        return null;
    }

    return array(
        'title' => get_the_title($page),
        'url'   => travel_dams_absolute_url(get_permalink($page)), // seo-helpers.php
    );
}

function travel_dams_get_breadcrumb_trail()
{
    $trail = array(
        array(
            'title' => __('Accueil', 'travel-dams'),
            'url'   => travel_dams_absolute_url(home_url('/')), // seo-helpers.php
        ),
    );

    if (is_tax('destination')) {
        $term = get_queried_object();

        if (! $term || is_wp_error($term)) {
            return $trail;
        }

        $hub = travel_dams_get_destinations_hub_link();

        if ($hub) {
            $trail[] = $hub;
        }

        $ancestors = array_reverse(get_ancestors($term->term_id, 'destination', 'taxonomy'));

        foreach ($ancestors as $ancestor_id) {
            $ancestor = get_term($ancestor_id, 'destination');

            if ($ancestor && ! is_wp_error($ancestor)) {
                $trail[] = array(
                    'title' => $ancestor->name,
                    'url'   => travel_dams_absolute_url(get_term_link($ancestor)), // seo-helpers.php
                );
            }
        }

        $trail[] = array('title' => $term->name, 'url' => null);

        return $trail;
    }

    if (is_singular('post')) {
        $post_id = get_the_ID();
        $destination_terms = wp_get_post_terms($post_id, 'destination');

        if (! empty($destination_terms) && ! is_wp_error($destination_terms)) {
            // Plusieurs termes destination peuvent être assignés à la fois
            // (ex : "Asie" + "Thaïlande") — on garde le plus spécifique,
            // pas le premier retourné (wp_get_post_terms trie par nom,
            // donc "Asie" passerait avant "Thaïlande" sinon).
            $term = null;
            $max_depth = -1;

            foreach ($destination_terms as $candidate) {
                $depth = count(get_ancestors($candidate->term_id, 'destination', 'taxonomy'));

                if ($depth > $max_depth) {
                    $max_depth = $depth;
                    $term = $candidate;
                }
            }

            $hub = travel_dams_get_destinations_hub_link();

            if ($hub) {
                $trail[] = $hub;
            }

            $ancestors = array_reverse(get_ancestors($term->term_id, 'destination', 'taxonomy'));

            foreach ($ancestors as $ancestor_id) {
                $ancestor = get_term($ancestor_id, 'destination');

                if ($ancestor && ! is_wp_error($ancestor)) {
                    $trail[] = array(
                        'title' => $ancestor->name,
                        'url'   => travel_dams_absolute_url(get_term_link($ancestor)), // seo-helpers.php
                    );
                }
            }

            $trail[] = array(
                'title' => $term->name,
                'url'   => travel_dams_absolute_url(get_term_link($term)), // seo-helpers.php
            );
        } else {
            $categories = get_the_category($post_id);

            if (! empty($categories)) {
                $trail[] = array(
                    'title' => $categories[0]->name,
                    'url'   => travel_dams_absolute_url(get_category_link($categories[0])), // seo-helpers.php
                );
            }
        }

        $trail[] = array('title' => get_the_title($post_id), 'url' => null);

        return $trail;
    }

    return $trail;
}

function travel_dams_the_breadcrumb()
{
    $trail = travel_dams_get_breadcrumb_trail();

    if (count($trail) < 2) {
        return;
    }

    $last_index = count($trail) - 1;

    echo '<nav class="breadcrumb" aria-label="' . esc_attr__("Fil d'Ariane", 'travel-dams') . '">';
    echo '<ol class="breadcrumb__list">';

    foreach ($trail as $index => $item) {
        echo '<li class="breadcrumb__item">';

        if (! empty($item['url']) && $index !== $last_index) {
            printf('<a class="breadcrumb__link" href="%s">%s</a>', esc_url($item['url']), esc_html($item['title']));
        } else {
            printf('<span class="breadcrumb__current" aria-current="page">%s</span>', esc_html($item['title']));
        }

        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}
