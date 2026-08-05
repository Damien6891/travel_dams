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
        Field::make('image', 'destination_intro_image_id', __('Image d\'introduction (page pays)', 'travel-dams')),
        Field::make('text', 'destination_tags', __('Tags (séparés par une virgule)', 'travel-dams'))
            ->set_help_text(__('Ex: SlowTravel, Nature, Heritage — affichés en pastilles sur la page pays.', 'travel-dams')),
    ));
