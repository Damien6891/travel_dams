<?php

/**
 * Champ "Titre (fil d'Ariane)" sur les articles.
 * 
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/** @disregard P1013 */
Container::make('post_meta', "Fil d'Ariane")
    ->where('post_type', '=', 'post')
    ->set_context('side')
    ->add_fields(array(
        Field::make('text', 'breadcrumb_title', 'Titre court')
            ->set_attribute('maxLength', 40)
            ->set_help_text("Affiché dans le fil d'Ariane à la place du titre de l'article. Laisser vide pour utiliser le titre complet.")
    ));
