<?php

/**
 * Add field to get english Atl
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', "Alt de l'image mise en avant")
    ->where('post_type', '=', 'post')
    ->add_fields(array(
        Field::make('text', 'cover_alt')
            ->set_help_text('Laisser vide pour reprendre l\'alt de la médiathèque.')
    ));
