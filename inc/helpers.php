<?php

/**
 * Slugs de référence (français) des catégories piliers du thème.
 * Une seule source de vérité, réutilisée pour les WP_Query et les liens.
 */
define('TD_SLUG_CARNETS', 'carnets-de-voyage'); // vérifie le slug exact en admin
define('TD_SLUG_GUIDES', 'guides-pratiques');
define('TD_SLUG_DESTINATIONS_GUIDES', 'guides-destinations');

/**
 * Retourne le lien de la catégorie d'archive dans la langue courante,
 * à partir de son slug en langue de référence (français).
 *
 * @param string $reference_slug Slug de la catégorie en français.
 * @return string URL de l'archive, ou chaîne vide si introuvable.
 */
function travel_dams_get_pillar_link($reference_slug)
{
    $terms = get_terms(array(
        'taxonomy'   => 'category',
        'slug'       => $reference_slug,
        'lang'       => '', // bypass le filtre de langue Polylang : cherche dans toutes les langues
        'hide_empty' => false,
        'number'     => 1,
    ));


    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }

    $term_id = $terms[0]->term_id;

    if (function_exists('pll_get_term')) {
        $translated_id = pll_get_term($term_id);
        if ($translated_id) {
            $term_id = $translated_id;
        }
    }

    $link = get_category_link($term_id);

    return is_wp_error($link) ? '' : $link;
}


/**
 * Retire le préfixe /category/ des URLs d'archives de catégorie.
 */
add_filter('category_link', function ($link) {
    return str_replace('/category/', '/', $link);
});

/**
 * Ajoute les règles de réécriture correspondantes pour que ces URLs
 * sans préfixe résolvent correctement.
 */
add_filter('category_rewrite_rules', function ($rules) {
    $categories = get_categories(array('hide_empty' => false));
    $new_rules  = array();

    foreach ($categories as $category) {
        $slug = $category->slug;

        $new_rules[$slug . '/?$']                              = 'index.php?category_name=' . $slug;
        $new_rules[$slug . '/page/([0-9]{1,})/?$']              = 'index.php?category_name=' . $slug . '&paged=$matches[1]';
        $new_rules[$slug . '/feed/(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=' . $slug . '&feed=$matches[1]';
    }

    return $new_rules + $rules;
});
