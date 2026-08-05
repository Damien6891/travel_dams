<?php

/**
 * Template Part : markup Mega menu destination
 * @var array $args { columns: array }
 */

$columns = $args['columns'] ?? array();
$panel_id = $args['panel_id'] ?? '';

if (empty($columns)) {
    return;
}
?>

<div class="mega-menu" id="<?php echo esc_attr($panel_id) ?>">
    <?php foreach ($columns as $column) : ?>
        <div class="mega-menu__column">
            <a href="<?php echo esc_url(get_term_link($column['continent'])); ?>" class="mega-menu__continent">
                <?php echo esc_html($column['continent']->name); ?>
            </a>
            <ul class="mega-menu__countries">
                <?php foreach ($column['countries'] as $country) :
                    $country_image_id = carbon_get_term_meta($country->term_id, 'zone_image_id');
                ?>
                    <li>
                        <a href="<?php echo esc_url(get_term_link($country)); ?>">
                            <?php if ($country_image_id) : ?>
                                <?php echo wp_get_attachment_image(absint($country_image_id), 'thumbnail'); ?>
                            <?php endif; ?>
                            <span><?php echo esc_html($country->name); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>