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
    ->where('term', 'CUSTOM', function ($term_id) {
        $term = get_term($term_id, 'destination');
        if (!$term || is_wp_error($term)) {
            return false;
        }
        return count(get_ancestors($term->term_id, $term->taxonomy, 'taxonomy')) === 1;
    })
    ->add_fields(array(
        Field::make('image', 'zone_image_id', __('Image de destination', 'travel-dams')),
        Field::make('image', 'destination_intro_image_id', __('Image d\'introduction (page pays)', 'travel-dams')),
        Field::make('text', 'country_code', __('Code pays (ISO 3166-1 alpha-2)', 'travel-dams'))
            ->set_help_text(__('2 lettres minuscules, ex: th (Thaïlande), lk (Sri Lanka), am (Arménie). Référence : iso.org.', 'travel-dams'))
            ->set_attributes(array('pattern' => '[a-z]{2}', 'maxLength' => 2)),
        Field::make('text', 'country_currency', __('Devise', 'travel-dams')),
        Field::make('text', 'country_capital', __('Capitale', 'travel-dams')),
        Field::make('text', 'country_language', __('Langue(s)', 'travel-dams')),
        Field::make('text', 'country_population', __('Population', 'travel-dams')),
        Field::make('text', 'destination_tags', __('Tags (séparés par une virgule)', 'travel-dams'))
            ->set_help_text(__('Ex: SlowTravel, Nature, Heritage — affichés en pastilles sur la page pays.', 'travel-dams')),
    ));
