<?php


function travel_dams_get_term_ids_with_translations($slug)
{
    $term = get_term_by('slug', $slug, 'category');

    // Bypass du filtre de langue Polylang au cas où le contexte admin le restreindrait.
    if (! $term) {
        $terms = get_terms(array(
            'taxonomy'   => 'category',
            'slug'       => $slug,
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
        return array_map('intval', array_values(pll_get_term_translations($term->term_id)));
    }

    return array((int) $term->term_id);
}

function travel_dams_get_carnets_term_ids()
{
    return travel_dams_get_term_ids_with_translations(TD_SLUG_CARNETS);
}

function travel_dams_get_guides_term_ids()
{
    return array_values(array_unique(array_merge(
        travel_dams_get_term_ids_with_translations(TD_SLUG_GUIDES),
        travel_dams_get_term_ids_with_translations(TD_SLUG_DESTINATIONS_GUIDES)
    )));
}
