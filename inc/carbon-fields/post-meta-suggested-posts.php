<?php

/**
 * Champs Carbon Fields Suggestions d'articles pour les page (type page)
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/** @disregard P1013 */
Container::make('post_meta', 'Suggestions de lecture')
    ->where('post_type', '=', 'page')
    ->add_fields([
        Field::make('checkbox', 'hide_suggestions', 'Masquer les suggestions'),
        Field::make('association', 'page_suggestions', 'Articles suggérer (vide = automatique)')
            ->set_types([
                [
                    'type' => 'post',
                    'post_type' => 'post'
                ]
            ])
            ->set_max(3)
    ]);
