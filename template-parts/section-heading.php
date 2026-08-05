<?php

/**
 * Template part : SectionHeading (eyebrow + titre serif + intro)
 * Réutilisé par la plupart des sections du site.
 *
 * @var array $args {
 *     @type string $eyebrow     Optionnel.
 *     @type string $title       Requis.
 *     @type string $description Optionnel.
 *     @type string $align       'left' (défaut) ou 'center'.
 *     @type bool   $dark        Défaut false.
 *     @type string $tag         Balise du titre, 'h2' par défaut.
 * }
 */

$eyebrow     = $args['eyebrow'] ?? '';
$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
$align       = $args['align'] ?? 'left';
$dark        = ! empty($args['dark']);
$tag         = tag_escape($args['tag'] ?? 'h2');

if ('' === $title) {
    return;
}

$classes = array('section-heading');
if ('center' === $align) {
    $classes[] = 'section-heading--center';
}
if ($dark) {
    $classes[] = 'section-heading--dark';
}
?>

<div class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <?php if ($eyebrow) : ?>
        <span class="section-heading__eyebrow eyebrow badge badge--eyebrow-light"><?php echo esc_html($eyebrow); ?></span>
    <?php endif; ?>

    <<?php echo $tag; ?> class="section-heading__title"><?php echo esc_html($title); ?></<?php echo $tag; ?>>

    <?php if ($description) : ?>
        <p class="section-heading__description"><?= $description ?></p>
        <!-- <p class="section-heading__description"><?php echo esc_html($description); ?></p> -->
    <?php endif; ?>
</div>