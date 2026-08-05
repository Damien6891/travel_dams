<?php

/**
 * Template part : Hero (image de couverture + titre)
 *
 * @var array $args {
 *     @type int    $image_id Défaut : image de hero du contexte courant.
 *     @type string $title
 *     @type string $subtitle
 *     @type string $eyebrow  Libellé tracké au-dessus du titre. Optionnel.
 *     @type string $byline   Ligne de méta sous le titre (dates, auteur...). Optionnel.
 *     @type array  $ctas     [{label, url, variant}] — boutons d'action. Optionnel.
 *     @type string $context  'default'|'front-page'|'single'|'destination'|'zone'|'archive'|'carnet'.
 * }
 */

$hero_image_id = $args['image_id'] ?? travel_dams_get_hero_image_id();
$hero_title    = $args['title'] ?? travel_dams_get_hero_title();
$hero_subtitle = $args['subtitle'] ?? travel_dams_get_hero_title();
$hero_eyebrow  = $args['eyebrow'] ?? '';
$hero_byline   = $args['byline'] ?? '';
$hero_ctas     = $args['ctas'] ?? array();
$hero_tag      = tag_escape(travel_dams_get_hero_title_tag());
$context       = $args['context'] ?? 'default';

$classes = array('hero', 'hero--' . $context);
if ($hero_image_id) {
    $classes[] = 'hero--has-image';
}
?>

<section class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <?php if ($hero_image_id) : ?>
        <div class="hero__background">
            <?php echo wp_get_attachment_image($hero_image_id, 'hero'); ?>
        </div>
    <?php endif; ?>

    <div class="hero__content">
        <?php if ($hero_eyebrow) : ?>
            <span class="hero__eyebrow eyebrow badge badge--eyebrow-dark"><?php echo esc_html($hero_eyebrow); ?></span>
        <?php endif; ?>

        <?php if ($hero_title) : ?>
            <<?php echo $hero_tag; ?> class="hero__title"><?php echo esc_html($hero_title); ?></<?php echo $hero_tag; ?>>
        <?php endif; ?>

        <?php if ($hero_byline) : ?>
            <span class="hero__byline"><?php echo esc_html($hero_byline); ?></span>
        <?php endif; ?>

        <?php if ($hero_subtitle) : ?>
            <p class="hero__subtitle"><?php echo esc_html($hero_subtitle); ?></p>
        <?php endif; ?>

        <?php if (! empty($hero_ctas)) : ?>
            <div class="hero__ctas">
                <?php foreach ($hero_ctas as $cta) :
                    $variant = $cta['variant'] ?? 'primary';
                ?>
                    <a href="<?php echo esc_url($cta['url'] ?? '#'); ?>" class="btn btn--<?php echo esc_attr($variant); ?> btn--m">
                        <?php echo esc_html($cta['label'] ?? ''); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<div id="hero-sentinel" aria-hidden="true"></div>
