<?php

/**
 * Section "Guides Pratiques" — grille bento (voir GuideCard du design system).
 * 4 tons alternés : light / dark (image de fond) / teal / panel (image).
 *
 * @package Travel_Dams
 */

$guides_query = new WP_Query(array(
    'category_name'       => TD_SLUG_GUIDES,
    'posts_per_page'      => 4,
    'no_found_rows'       => true,
    'ignore_sticky_posts' => true,
));

if (! $guides_query->have_posts()) {
    return;
}

$tones = array('light', 'dark', 'teal', 'panel');
$index = 0;
?>

<section class="guides-section">

    <div class="container container--wide">

        <div class="section-header" style="justify-content:center;text-align:center;">
            <div>
                <h2 class="section-title"><?php esc_html_e('Guides Pratiques', 'travel-dams'); ?></h2>
                <p class="section-header__lead" style="margin-inline:auto;">
                    <?php esc_html_e("Tout ce qu'il faut savoir pour préparer vos prochaines expéditions sans stress et de manière responsable.", 'travel-dams'); ?>
                </p>
            </div>
        </div>

        <div class="guides-bento">
            <?php while ($guides_query->have_posts()) : $guides_query->the_post();
                $tone      = $tones[$index % count($tones)];
                $has_image = in_array($tone, array('dark', 'panel'), true) && has_post_thumbnail();
                $thumb_url = $has_image ? get_the_post_thumbnail_url(get_the_ID(), 'medium_large') : '';
                $index++;
            ?>
                <article class="guide-card guide-card--<?php echo esc_attr($tone); ?><?php echo ('dark' === $tone) ? ' guide-card--feature' : ''; ?>"
                    <?php if ('dark' === $tone && $thumb_url) : ?>
                        style="--guide-card-bg: url('<?php echo esc_url($thumb_url); ?>');"
                    <?php endif; ?>>

                    <?php if ('panel' === $tone && $thumb_url) : ?>
                        <div class="guide-card__side">
                            <?php the_post_thumbnail('thumbnail', array('class' => 'guide-card__side-image')); ?>
                        </div>
                        <div class="guide-card__main">
                    <?php endif; ?>

                    <?php if ('dark' === $tone) : ?>
                        <span class="badge badge--outline-dark guide-card__badge"><?php esc_html_e('Dossier spécial', 'travel-dams'); ?></span>
                    <?php endif; ?>

                    <h3 class="guide-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <p class="guide-card__description"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 18)); ?></p>

                    <?php if ('light' === $tone || 'panel' === $tone) : ?>
                        <a href="<?php the_permalink(); ?>" class="guide-card__link"><?php esc_html_e('Lire le guide →', 'travel-dams'); ?></a>
                    <?php endif; ?>

                    <?php if ('panel' === $tone && $thumb_url) : ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endwhile; ?>
        </div>

        <a href="<?php echo esc_url(travel_dams_get_pillar_link(TD_SLUG_GUIDES)); ?>" class="section-link"><?php esc_html_e('Voir tous les guides', 'travel-dams'); ?></a>

    </div>
</section>

<?php wp_reset_postdata(); ?>
