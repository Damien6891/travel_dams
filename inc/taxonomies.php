<?php

/**
 * Custom Taxonomies - travel-dams
 */

function travel_dams_register_taxonomies()
{

    $labels = array(
        'name'              => __('Destinations', 'travel-dams'),
        'singular_name'     => __('Destination', 'travel-dams'),
        'search_items'      => __('Rechercher une destination', 'travel-dams'),
        'all_items'         => __('Toutes les destinations', 'travel-dams'),
        'parent_item'       => __('Zone parente', 'travel-dams'),
        'parent_item_colon' => __('Zone parente :', 'travel-dams'),
        'edit_item'         => __('Modifier la destination', 'travel-dams'),
        'update_item'       => __('Mettre à jour la destination', 'travel-dams'),
        'add_new_item'      => __('Ajouter une destination', 'travel-dams'),
        'new_item_name'     => __('Nom de la nouvelle destination', 'travel-dams'),
        'menu_name'         => __('Destinations', 'travel-dams'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true, // nécessaire pour Gutenberg + API REST
        'query_var'         => true,
        'rewrite'           => array(
            'slug'         => 'destination',
            'with_front'   => false,
            'hierarchical' => true, // permet des URLs du type /destination/europe/georgie/
        ),
    );

    register_taxonomy('destination', array('post'), $args);
}
add_action('init', 'travel_dams_register_taxonomies');
