<?php

/**
 * Champs Carbon Fields — dates de voyage, uniquement sur les articles
 * de la catégorie "Carnets de voyage", peu importe la langue.
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

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
