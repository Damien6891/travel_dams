<?php

/**
 * Champs Carbon Fields — titre SEO et meta description, sur tous les
 * articles (carnets, guides-pratiques, guides-destinations, réflexions).
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('post_meta', __('SEO', 'travel-dams'))
    ->where('post_type', '=', 'post')
    ->add_fields(array(
        Field::make('text', 'seo_title', __('Titre SEO', 'travel-dams'))
            ->set_help_text(__('~60 caractères. Laisser vide pour utiliser le titre de l\'article + nom du site.', 'travel-dams'))
            ->set_attribute('maxLength', 70),
        Field::make('textarea', 'seo_description', __('Meta description', 'travel-dams'))
            ->set_help_text(__('~155 caractères. Laisser vide pour générer automatiquement à partir de l\'extrait.', 'travel-dams'))
            ->set_attribute('maxLength', 170)
        // ->set_rows(3),
    ));
