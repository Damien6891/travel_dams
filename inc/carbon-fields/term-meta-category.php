<?php

/**
 * Carbon Fields - cover image for categories
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('term_meta', __('Image de couverture', 'travel-dams'))
    ->where('term_taxonomy', '=', 'category')
    ->add_fields(array(
        Field::make('image', 'category_cover_image_id', __('Image de couverture', 'travel-dams')),
    ));
