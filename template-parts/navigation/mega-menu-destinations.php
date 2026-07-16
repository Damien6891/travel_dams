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
                <?php foreach ($column['countries'] as $country) : ?>
                    <li>
                        <a href="<?php echo esc_url(get_term_link($country)); ?>">
                            <?php echo esc_html($country->name); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>