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
 * Résout les destinations "coup de cœur" choisies en Réglages > Accueil
 * (Carbon Fields, voir inc/carbon-fields/options-homepage.php).
 *
 * @return array{term: WP_Term, image_id: int, description: string}[]
 */
function travel_dams_get_homepage_favorites()
{
    $rows      = carbon_get_theme_option('homepage_favorite_destinations');
    $favorites = array();

    if (empty($rows)) {
        return $favorites;
    }

    foreach ($rows as $row) {
        $association = $row['destination_term'] ?? array();
        if (empty($association[0]['id'])) {
            continue;
        }

        $term = get_term(absint($association[0]['id']), 'destination');
        if (! $term || is_wp_error($term)) {
            continue;
        }

        $favorites[] = array(
            'term'        => $term,
            'image_id'    => absint(carbon_get_term_meta($term->term_id, 'zone_image_id')),
            'description' => $row['description_override'] ?: $term->description,
        );
    }

    return $favorites;
}

/**
 * Compte les articles d'un terme `destination` (et sa descendance) pour un
 * pilier donné (carnets-de-voyage, guides-destinations...). Utilisé pour les
 * pastilles "X CARNETS · Y GUIDES" des tuiles pays/zone.
 */
function travel_dams_count_posts_for_destination($term_id, $pillar_slug_fr)
{
    $cat_id = travel_dams_get_pillar_term_id($pillar_slug_fr);

    if (! $cat_id) {
        return 0;
    }

    $query = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 1,
        'no_found_rows'  => false,
        'fields'         => 'ids',
        'cat'            => $cat_id,
        'tax_query'      => array(
            array(
                'taxonomy'         => 'destination',
                'field'            => 'term_id',
                'terms'            => $term_id,
                'include_children' => true,
            ),
        ),
    ));

    return (int) $query->found_posts;
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

/**
 * Get "A propos" texte for Home page depending current language
 */
/**
 * Récupère une option Theme Options traduite, avec fallback FR.
 *
 * @param string        $base_field Nom du champ sans suffixe de langue (ex: 'homepage_about').
 * @param callable|null $resolver   Callback optionnel (mixed $value) => mixed, pour transformer
 *                                  la valeur brute (ex: résoudre un champ association en URL/titre).
 */
function td_get_translated_option(string $base_field, ?callable $resolver = null)
{
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'fr';

    $value = carbon_get_theme_option($base_field . '_' . $lang);

    if (empty($value) && $lang !== 'fr') {
        $value = carbon_get_theme_option($base_field . '_fr');
    }

    if ($resolver) {
        return $value ? $resolver($value) : null;
    }

    return $value ?: '';
}

function td_resolve_page_link(array $value): array
{
    $page_id = $value[0]['id'] ?? null;

    return array(
        'page_url'   => $page_id ? get_permalink($page_id) : '',
        'page_title' => $page_id ? get_the_title($page_id) : '',
    );
}
