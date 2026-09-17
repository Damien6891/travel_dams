<?php

/**
 * Champs Carbon Fields — SEO (titre & meta description) pour les pages
 * destination de niveau pays (zone > pays). Même filtre que
 * term-meta-destination-country.php, où vit déjà country_description.
 *
 * @package Travel_Dams
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

/** @disregard P1013 */
Container::make('term_meta', __('SEO', 'travel-dams'))
    ->where('term_taxonomy', '=', 'destination')
    ->where('term', 'CUSTOM', function ($term_id) {
        $term = get_term($term_id, 'destination');

        if (!$term || is_wp_error($term)) {
            return false;
        }

        return count(get_ancestors($term->term_id, $term->taxonomy, 'taxonomy')) === 1;
    })
    ->add_fields(array(
        Field::make('text', 'seo_title', __('Titre SEO', 'travel-dams'))
            ->set_help_text(__('~60 caractères. Laisser vide pour utiliser le nom du pays + nom du site.', 'travel-dams'))
            ->set_attribute('maxLength', 70),

        Field::make('textarea', 'seo_description', __('Meta description', 'travel-dams'))
            ->set_help_text(__('~155 caractères. Laisser vide pour générer automatiquement à partir de la description du pays', 'travel-dams'))
            ->set_attribute('maxLength', 170)
            ->set_rows(3)
    ));
