<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

$guides_term_ids = travel_dams_get_guides_term_ids();

if (! empty($guides_term_ids)) {

    Container::make('post_meta', __('Options du guide', 'travel-dams'))
        ->where('post_type', '=', 'post')
        ->where('post_term', 'IN', array_map(fn($term_id) => array(
            'field'    => 'term_id',
            'value'    => $term_id,
            'taxonomy' => 'category',
        ), $guides_term_ids))
        ->add_fields(array(
            Field::make('checkbox', 'show_summary', __('Afficher le sommaire', 'travel-dams')),
            Field::make('checkbox', 'guide_toc_h3', __('Inclure les sous-titres (H3) dans le sommaire', 'travel-dams'))
                ->set_conditional_logic(array(
                    array(
                        'field' => 'show_summary',
                        'value' => true
                    )
                )),
        ));
}
