<?php

/**
 * Bloc d'introduction d'une page pays : description éditoriale, tags,
 * image (voir la section "Thaïlande : L'âme du sourire..." du design system).
 *
 * @var array $args { @type WP_Term $destination_term }
 */

/** @var WP_Term $destination_term */
$destination_term = $args['destination_term'];

$image_id = absint(carbon_get_term_meta($destination_term->term_id, 'destination_intro_image_id'));
$tags_raw = carbon_get_term_meta($destination_term->term_id, 'destination_tags');
$tags     = $tags_raw ? array_filter(array_map('trim', explode(',', $tags_raw))) : array();

if (empty($destination_term->description) && ! $image_id && empty($tags)) {
    return;
}
?>

<section class="country-intro">
    <div class="country-intro__text">
        <?php if (! empty($destination_term->description)) : ?>
            <div class="country-intro__description">
                <?php echo wp_kses_post(wpautop($destination_term->description)); ?>
            </div>
        <?php endif; ?>

        <?php if (! empty($tags)) : ?>
            <div class="country-intro__tags">
                <?php foreach ($tags as $tag) : ?>
                    <span class="chip">#<?php echo esc_html($tag); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($image_id) : ?>
        <?php echo wp_get_attachment_image($image_id, 'large', false, array('class' => 'country-intro__image')); ?>
    <?php endif; ?>
</section>
