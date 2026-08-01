<?php

/**
 * Champs Carbon Fields — dates de voyage, uniquement sur les articles
 * de la catégorie "Carnets de voyage", peu importe la langue.
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Résout l'ID du terme de référence (slug FR), puis récupère l'ID
 * correspondant dans chaque langue active via Polylang.
 */
function travel_dams_get_carnets_term_ids()
{
    $term = get_term_by('slug', TD_SLUG_CARNETS, 'category');

    // Bypass du filtre de langue Polylang au cas où le contexte admin le restreindrait.
    if (! $term) {
        $terms = get_terms(array(
            'taxonomy'   => 'category',
            'slug'       => TD_SLUG_CARNETS,
            'lang'       => '',
            'hide_empty' => false,
            'number'     => 1,
        ));
        $term = $terms[0] ?? null;
    }

    if (! $term) {
        return array();
    }

    if (function_exists('pll_get_term_translations')) {
        return array_values(pll_get_term_translations($term->term_id));
    }

    return array($term->term_id);
}

$carnets_term_ids = travel_dams_get_carnets_term_ids();

if (! empty($carnets_term_ids)) {

    $container = Container::make('post_meta', __('Dates du voyage', 'travel-dams'))
        ->where('post_type', '=', 'post');

    foreach ($carnets_term_ids as $index => $term_id) {
        $method = 0 === $index ? 'where' : 'or_where';
        $container->$method('post_term', '=', array(
            'field'    => 'term_id',
            'value'    => $term_id,
            'taxonomy' => 'category',
        ));
    }

    $container->add_fields(array(
        Field::make('date', 'trip_start_date', __('Date de début', 'travel-dams'))
            ->set_width(50),
        Field::make('date', 'trip_end_date', __('Date de fin', 'travel-dams'))
            ->set_width(50),
        Field::make('image', 'hero_image', __('Image de couverture'))
    ));
}
