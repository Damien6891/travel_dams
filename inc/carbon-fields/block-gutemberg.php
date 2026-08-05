<?php

/**
 * Block gutemberg carnet de voyage
 * To use if update wordpress doesn't allow to add gutemberg block in pattern via hook in function.php
 * add_filter('block_editor_settings_all)
 */

use Carbon_Fields\Block;
use Carbon_Fields\Field;

$block = Block::make(__('Jour de carnet', 'travel-dams'))
    ->add_fields(array(
        Field::make('text', 'carnet_day_title', __('Titre', 'travel-dams')),
        Field::make('date', 'carnet_day_date', __('Date', 'travel-dams')),
        Field::make('text', 'carnet_day_day', __('Jour', 'travel-dams'))
    ));
/** @disregard P1013 */
$block->set_inner_blocks(true)
    ->set_inner_blocks_position('below')
    ->set_allowed_inner_blocks(array('core/paragraph', 'core/image', 'core/columns'))
    ->set_render_callback(function ($fields, $attributes, $inner_blocks) {
        static $day_number = 0;
        $day_number++;

        $date      = $fields['carnet_day_date'] ?? '';
        $formatted = $date ? date_i18n('j F', strtotime($date)) : '';
?>
    <div class="carnet-day">
        <div class="carnet-day__meta">
            <span class="carnet-day__number"><?php echo esc_html($fields['carnet_day_day']); ?></span>
            <!-- <span class="carnet-day__number"><?php echo esc_html(sprintf('%02d', $day_number)); ?></span> -->
            <span class="line"></span>
            <?php if ($date) : ?>
                <time class="carnet-day__date" datetime="<?php echo esc_attr($date); ?>"><?php echo esc_html(mb_strtoupper($formatted)); ?></time>
            <?php endif; ?>
        </div>
        <h2 class="carnet-day__title"><?php echo esc_html($fields['carnet_day_title']); ?></h2>
        <div class="carnet-day__content">
            <?php echo $inner_blocks; ?>
        </div>
    </div>
<?php
    });
