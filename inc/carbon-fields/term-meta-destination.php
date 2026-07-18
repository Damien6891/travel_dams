<?php

/**
 * Champs Carbon Fields — taxonomie Destination (zones/pays).
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('term_meta', __('Détails destination', 'travel-dams'))
    ->where('term_taxonomy', '=', 'destination')
    ->add_fields(array(
        Field::make('image', 'zone_image_id', __('Image de destination', 'travel-dams')),
    ));
