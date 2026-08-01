<?php

/**
 * Logique de contenu lié (related posts) pour single.php.
 */

/**
 * Récupère le contexte géographique d'un article : son pays et sa zone parente.
 * Retourne null si l'article n'a pas de terme `destination`.
 */

/**
 * Résout l'ID d'une catégorie pilier dans une langue donnée, à partir de son slug
 * français de référence. Nécessaire car Polylang crée un terme distinct par langue
 * pour les taxonomies traduites (slug ET term_id différents selon la langue).
 */
function travel_dams_get_pillar_term_id($slug_fr, $lang = null)
{
    static $cache = [];

    $lang      = $lang ?: (function_exists('pll_current_language') ? pll_current_language() : '');
    $cache_key = $slug_fr . '_' . $lang;

    if (array_key_exists($cache_key, $cache)) {
        return $cache[$cache_key];
    }

    // Terme "maître" en français, indépendamment de la langue affichée
    $terms = get_terms([
        'taxonomy'   => 'category',
        'slug'       => $slug_fr,
        'lang'       => '', // bypass le filtre Polylang
        'hide_empty' => false,
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return $cache[$cache_key] = null;
    }

    $term_id = $terms[0]->term_id;

    if (function_exists('pll_get_term') && $lang) {
        $term_id = pll_get_term($term_id, $lang) ?: $term_id;
    }

    return $cache[$cache_key] = $term_id;
}

function travel_dams_get_destination_context($post_id)
{
    $terms = get_terms([
        'taxonomy'   => 'destination',
        'object_ids' => $post_id,
        'lang'       => '', // Polylang filtre les requêtes de termes par langue front — on bypass
    ]);

    if (empty($terms) || is_wp_error($terms)) {
        return null;
    }

    $country = null;

    foreach ($terms as $term) {
        if ($term->parent) {
            $country = $term;
            break;
        }
    }

    if ($country) {
        $zone = get_term($country->parent, 'destination');
        $zone = (! is_wp_error($zone)) ? $zone : null;
    } else {
        // Aucun terme enfant : l'article est taggé seulement au niveau zone
        $zone = $terms[0];
    }

    return [
        'country' => $country,
        'zone'    => $zone,
    ];
}

/**
 * Query générique : articles liés à un terme destination (et sa descendance), 
 * filtrable par catégorie.
 */
function travel_dams_query_related_by_term($term, $post_id, $pillar_slug_fr  = null, $limit = 3)
{
    if (! $term) {
        return [];
    }


    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        'post__not_in'   => [$post_id],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => [
            [
                'taxonomy'         => 'destination',
                'field'            => 'term_id',
                'terms'            => $term->term_id,
                'include_children' => true, // capte aussi les pays enfants si $term = zone
            ],
        ],
    ];

    if ($pillar_slug_fr) {
        $cat_id = travel_dams_get_pillar_term_id($pillar_slug_fr);
        if ($cat_id) {
            $args['cat'] = $cat_id;
        }
    }

    $query = new WP_Query($args);
    return $query->posts;
}

/**
 * Cascade pour Carnets de voyage + Guides destinations :
 * guide destination même pays → autre carnet même pays → même zone tout pilier.
 */
function travel_dams_get_related_posts($post_id, $limit = 3)
{
    $environment = wp_get_environment_type();
    $ttl         = ('production' === $environment) ? DAY_IN_SECONDS : 0;
    $cache_key   = 'travel_dams_related_' . $post_id;

    if ($ttl > 0) {
        $cached = get_transient($cache_key);
        if (false !== $cached) {
            return $cached;
        }
    }

    $context = travel_dams_get_destination_context($post_id);
    $related = [];

    // echo '<pre>';
    // var_dump($context);
    // echo '</pre>';


    if ($context) {
        if ($context['country']) {
            $related = travel_dams_query_related_by_term($context['country'], $post_id, 'guides-destinations', $limit);
            // echo '<pre>';
            // var_dump($context['country']);
            // echo '</pre>';
        }

        if (empty($related) && $context['country']) {
            $related = travel_dams_query_related_by_term($context['country'], $post_id, 'carnets-de-voyage', $limit);
            var_dump('here');
        }

        if (empty($related) && $context['zone']) {
            $related = travel_dams_query_related_by_term($context['zone'], $post_id, null, $limit);
        }
    }

    if ($ttl > 0) {
        set_transient($cache_key, $related, $ttl);
    }

    return $related;
}

/**
 * Fallback simple pour Guides pratiques : autres articles du même pilier, par date.
 */
function travel_dams_get_related_by_category($post_id, $limit = 3)
{
    $categories = get_the_category($post_id);
    if (empty($categories)) {
        return [];
    }

    $query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => $limit,
        'post__not_in'   => [$post_id],
        'orderby'        => 'date',
        'order'          => 'DESC',
        'category__in'   => [$categories[0]->term_id],
    ]);

    return $query->posts;
}
