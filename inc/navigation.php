<?php

/**
 * Navigation : data and cache mega menu Destinations
 */

function travel_dams_get_megamenu_cache_ttl()
{
    // Prod: 1 day, hierachie doesn't move often
    // Local/dev/staging : no cache, to see taxonomies changes instant
    return (wp_get_environment_type() === 'production') ? DAY_IN_SECONDS : 0;
}

function travel_dams_get_destinations_megamenu($item_id = 0)
{
    $lang = function_exists('pll_current_language') ? pll_current_language() : 'fr';
    $cache_key = 'travel_dams_megamenu_' . $item_id . '_' . $lang;
    $ttl = travel_dams_get_megamenu_cache_ttl();

    if ($ttl > 0) {
        $cached = get_transient($cache_key);
        if (false !== $cached) {
            return $cached;
        }
    }

    $continents = get_terms(array(
        'taxonomy'   => 'destination',
        'parent'     => 0,
        'hide_empty' => false,
        'orderby'    => 'name',
    ));

    $columns = array();
    foreach ($continents as $continent) {
        $countries = get_terms(array(
            'taxonomy'   => 'destination',
            'parent'     => $continent->term_id,
            'hide_empty' => false,
            'orderby'    => 'name',
        ));

        if (empty($countries)) {
            continue;
        }

        $columns[] = array(
            'continent' => $continent,
            'countries' => $countries,
        );
    }

    ob_start();
    get_template_part('template-parts/navigation/mega-menu-destinations', null, array(
        'columns' => $columns,
        'panel_id' => 'mega-menu-panel-' . $item_id
    ));
    $html = ob_get_clean();

    if ($ttl > 0) {
        set_transient($cache_key, $html, $ttl);
    }

    return $html;
}

// function travel_dams_megamenu_trigger_attributes($atts, $item, $args, $depth)
// {
//     if (in_array('has-megamenu', $item->classes, true)) {
//         $atts['href'] = '#';
//         $atts['aria-haspopup'] = 'true';
//         $atts['aria-expanded'] = 'fasle';
//     }

//     return $atts;
// }
// add_filter('nav_menu_link_attributes', 'travel_dams_megamenu_trigger_attributes', 10, 4);
