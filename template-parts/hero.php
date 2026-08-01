<?php

/**
 * Template part : Hero (image de couverture + titre)
 * Appelé depuis header.php, uniquement si travel_dams_has_hero() est vrai.
 */

$hero_image_id = $args['image_id'] ?? travel_dams_get_hero_image_id();
$hero_title    = $args['title'] ?? travel_dams_get_hero_title();
$hero_subtitle    = $args['subtitle'] ?? travel_dams_get_hero_title();
$hero_tag      = tag_escape(travel_dams_get_hero_title_tag());
$context = $args['context'] ?? 'default'; // for modiying class

$classes = array('hero', 'hero--' . $context);
if ($hero_image_id) {
    $classes[] = 'hero--has-image';
}
?>


<!-- <section class="hero hero--<?php echo esc_attr($context); ?>"> -->
<section class="hero hero--<?php echo esc_attr(implode(' ', $classes)); ?>">
    <?php if ($hero_image_id) : ?>
        <div class="hero__background">
            <?php echo wp_get_attachment_image($hero_image_id, 'full'); ?>
        </div>
    <?php endif; ?>

    <div class="hero__content">
        <?php if ($hero_title) : ?>
            <h1 class="hero__title"><?php echo esc_html($hero_title); ?></h1>
        <?php endif; ?>

        <?php if ($hero_subtitle) : ?>
            <p class="hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <?php endif; ?>
    </div>
</section>

<div id="hero-sentinel" aria-hidden="true"></div>