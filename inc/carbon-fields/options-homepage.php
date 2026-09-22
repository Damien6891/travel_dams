<?php

/**
 * Options de thème — Accueil : sélection éditoriale des destinations
 * mises en avant dans la section "Destinations Coup de Cœur".
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/** @disregard P1013 */
Container::make('theme_options', __('Accueil', 'travel-dams'))
    ->add_tab('Destination', array(
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
    ))
    ->add_tab('À propos', array(
        Field::make('separator', 'homepage_about_fr_separator', 'Français'),
        Field::make('textarea', 'homepage_about_fr', 'Description (Français)'),
        Field::make('association', 'about_page_fr', 'Lien vers la page à propos (fr)')
            ->set_types(array(
                array(
                    'type'      => 'post',
                    'post_type' => 'page',
                )
            ))
            ->set_max(1),
        Field::make('separator', 'homepage_about_fen_separator', 'Anglais'),
        Field::make('textarea', 'homepage_about_en', 'Description (Anglais)'),
        Field::make('association', 'about_page_en', 'Lien vers la page à propos (en)')
            ->set_types(array(
                array(
                    'type'      => 'post',
                    'post_type' => 'page',
                )
            ))
            ->set_max(1),
        Field::make('image', 'homepage_about_image', 'Image section à propos')
            ->set_type(array('image'))
    ));
