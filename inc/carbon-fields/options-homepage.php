<?php

/**
 * Options de thème — Accueil : sélection éditoriale des destinations
 * mises en avant dans la section "Destinations Coup de Cœur".
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('theme_options', __('Accueil', 'travel-dams'))
    ->add_fields(array(
        Field::make('complex', 'homepage_favorite_destinations', __('Destinations coup de cœur', 'travel-dams'))
            ->set_help_text(__('2 destinations mises en avant sur la page d\'accueil.', 'travel-dams'))
            ->set_min(0)
            ->set_max(2)
            ->add_fields(array(
                Field::make('association', 'destination_term', __('Destination', 'travel-dams'))
                    ->set_types(array(
                        array(
                            'type'     => 'term',
                            'taxonomy' => 'destination',
                        ),
                    ))
                    ->set_max(1),
                Field::make('text', 'description_override', __('Description (optionnel)', 'travel-dams'))
                    ->set_help_text(__('Laisser vide pour utiliser la description du terme.', 'travel-dams')),
            )),
    ));
