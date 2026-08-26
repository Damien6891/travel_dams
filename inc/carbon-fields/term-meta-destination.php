<?php

/**
 * Champs carbon fields - taxonomie Destionation (continent(zones)/payes)
 * @package Travel_Dams
 */

use Carbon_Fields\Container\Container;
use Carbon_Fields\Field\Field;

Container::make('term_meta', __('Détails destination', 'travel-dams'))
    ->where('term_taxonomy', '=', 'destination')
    ->add_fields(array(
        Field::make('image', 'hero_image', __('Hero image destination', 'travel-dams')),
        Field::make('image', 'card_image', __('Image afficher dans la carte', 'travel-dams'))
    ));
