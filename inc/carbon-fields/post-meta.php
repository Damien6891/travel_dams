<?php

/**
 * Add field to get english Atl
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/** @disregard P1013 */
Container::make('post_meta', "Alt de l'image mise en avant")
    ->where('post_type', '=', 'post')
    ->add_fields(array(
        Field::make('text', 'cover_alt')
            ->set_help_text('Laisser vide pour reprendre l\'alt de la médiathèque.'),
        Field::make('complex', 'td_pastilles', 'Pastilles')
            ->set_max(3)
            ->set_layout('tabbed-horizontal')
            ->add_fields([
                Field::make('text', 'label', 'Texte')
                    ->set_attribute('maxLength', 30)
            ])
            ->set_header_template('<%- label %>')
    ));

/** @disregard P1013 */
Container::make('post_meta', "Titre secondaire")
    ->where('post_type', '=', 'post')
    ->set_context('side')
    ->add_fields(array(
        Field::make('text', 'secondary_title', 'TItre fomat LAS-01')
    ));
