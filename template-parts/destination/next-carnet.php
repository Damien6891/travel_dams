<?php

/**
 * Panneau plein cadre "Prochaine étape" — met en avant le carnet lié suivant
 * (voir travel_dams_get_related_posts() dans inc/related-content.php).
 *
 * @var array $args { @type WP_Post $post }
 */

/** @var WP_Post $next */
$next = $args['post'];

if (empty($next)) {
    return;
}

$image_id = get_post_thumbnail_id($next);
?>

<section class="next-carnet">
    <div class="container container--wide">
        <div class="next-carnet__panel">
            <?php if ($image_id) : ?>
                <?php echo wp_get_attachment_image($image_id, 'large', false, array('class' => 'next-carnet__image')); ?>
            <?php endif; ?>
            <div class="next-carnet__scrim"></div>
            <div class="next-carnet__content">
                <span class="eyebrow badge badge--eyebrow-dark"><?php esc_html_e('Prochaine étape', 'travel-dams'); ?></span>
                <h3 class="next-carnet__title"><?php echo esc_html(get_the_title($next)); ?></h3>
                <a href="<?php echo esc_url(get_permalink($next)); ?>" class="btn btn--accent btn--s"><?php esc_html_e('Lire le carnet', 'travel-dams'); ?></a>
            </div>
        </div>
    </div>
</section>
