<?php

/**
 * Teaser "À propos" avec lien vers la page dédiée.
 *
 * @package Travel_Dams
 */

$about_page = get_page_by_path('a-propos'); // adapte le slug à ta page réelle
$link = td_get_translated_option('about_page', 'td_resolve_page_link');
$description = td_get_translated_option('homepage_about');
$image_id = carbon_get_theme_option('homepage_about_image');
?>

<section class="about-teaser">
    <div class="container">

        <div class="about-teaser__container">
            <div class="about-teaser__image">

                <img src="<?= wp_get_attachment_image_url($image_id, 'large') ?>" alt="">

            </div>

            <div class="about-teaser__content">
                <span class="eyebrow badge badge--eyebrow-light">
                    <?= __('À propos', 'travel-dams') ?>
                </span>
                <h2 class="section-title"><?php esc_html_e('Moi, c\'est Damien', 'travel-dams'); ?></h2>

                <?= wpautop($description) ?>

                <!-- <?php if ($link && $link['page_url']) : ?>
                    <a href="<?php echo esc_url($link['page_url']) ?>" class="section-link">
                        <?php esc_html_e('En savoir plus', 'travel-dams'); ?>
                    </a>
                <?php endif; ?> -->

                <?php if ($link && $link['page_url']) : ?>
                    <a href="<?= esc_url($link['page_url']) ?>" class="btn btn--primary btn--s">
                        <?php esc_html_e('En savoir plus', 'travel-dams'); ?>
                    </a>
                <?php endif; ?>


            </div>
        </div>
    </div>
</section>