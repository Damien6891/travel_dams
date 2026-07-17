<?php

/**
 * Teaser "À propos" avec lien vers la page dédiée.
 *
 * @package Travel_Dams
 */

$about_page = get_page_by_path('a-propos'); // adapte le slug à ta page réelle
?>

<section class="about-teaser">
    <div class="container">

        <div class="about-teaser__content">
            <h2 class="section-title"><?php esc_html_e('Qui suis-je ?', 'travel-dams'); ?></h2>
            <p><?php esc_html_e('Deux ou trois lignes de présentation ici.', 'travel-dams'); ?></p>

            <?php if ($about_page) : ?>
                <a href="<?php echo esc_url(get_permalink($about_page)); ?>" class="section-link">
                    <?php esc_html_e('En savoir plus', 'travel-dams'); ?>
                </a>
            <?php endif; ?>

        </div>
    </div>
</section>