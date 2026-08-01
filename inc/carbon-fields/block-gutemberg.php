<?php

/**
 * Block gutemberg carnet de voyage
 * To use if update wordpress doesn't allow to add gutemberg block in pattern via hook in function.php
 * add_filter('block_editor_settings_all)
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

$block = Block::make(__('Jour de carnet', 'travel_dams'))
    ->add_fields(array(
        Field::make('text', 'carnet_day_title', __('Titre', 'travel_dams')),
        Field::make('date', 'carnet_day_date', __('Date', 'travel_dams')),
    ));
/** @disregard P1013 */
$block->set_inner_blocks(true)
    ->set_allowed_inner_blocks(array('core/paragraph', 'core/image', 'core/columns'))
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        $date      = $fields['carnet_day_date'] ?? '';
        $formatted = $date ? date_i18n('j F Y', strtotime($date)) : '';
?>
    <div class="carnet-day">
        <h2 class="carnet-day__title"><?php echo esc_html($fields['carnet_day_title']); ?></h2>
        <?php if ($date) : ?>
            <p class="carnet-day__date">
                <time datetime="<?php echo esc_attr($date); ?>"><?php echo esc_html($formatted); ?></time>
            </p>
        <?php endif; ?>
        <div class="carnet-day__content">
            <?php echo $inner_blocks; ?>
        </div>
    </div>
<?php
    });
