<?php

/**
 * Template part : panneau d'inscription newsletter (voir Newsletter du design system).
 * Pas de branchement mailing-list réel pour le moment — un plugin pourra
 * hooker ce <form> (id="newsletter-form") plus tard.
 *
 * @var array $args {
 *     @type string $eyebrow     Optionnel.
 *     @type string $title       Requis.
 *     @type string $description Optionnel.
 *     @type string $disclaimer  Optionnel.
 *     @type string $tone        'darkest' (défaut) ou 'forest'.
 *     @type string $layout      'center' (défaut) ou 'split'.
 * }
 */

$eyebrow     = $args['eyebrow'] ?? __('Rejoindre le cercle', 'travel-dams');
$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
$disclaimer  = $args['disclaimer'] ?? __("Promis, nous détestons le spam autant que vous détestez les sentiers trop battus.", 'travel-dams');
$tone        = $args['tone'] ?? 'darkest';
$layout      = $args['layout'] ?? 'center';

if ('' === $title) {
    return;
}

$classes = array('newsletter', 'newsletter--' . $tone, 'container');
if ('split' === $layout) {
    $classes[] = 'newsletter--split';
}
?>

<section id="newsletter" class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <div class="newsletter__intro">
        <?php if ($eyebrow) : ?>
            <span class="newsletter__eyebrow eyebrow"><?php echo esc_html($eyebrow); ?></span>
        <?php endif; ?>

        <h2 class="newsletter__title"><?php echo esc_html($title); ?></h2>

        <?php if ($description) : ?>
            <p class="newsletter__description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>
    </div>

    <div class="newsletter__form-wrap">
        <form class="newsletter__form" id="newsletter-form" method="post">
            <div class="newsletter__field">
                <label class="screen-reader-text" for="newsletter-email"><?php esc_html_e('Votre adresse e-mail', 'travel-dams'); ?></label>
                <input type="email" id="newsletter-email" name="email" required class="input input--email" placeholder="<?php esc_attr_e('Votre adresse e-mail', 'travel-dams'); ?>">
            </div>
            <button type="submit" class="btn btn--accent btn--s newsletter__submit"><?php esc_html_e("S'inscrire", 'travel-dams'); ?></button>
        </form>

        <?php if ($disclaimer) : ?>
            <span class="newsletter__disclaimer"><?php echo esc_html($disclaimer); ?></span>
        <?php endif; ?>
    </div>

</section>