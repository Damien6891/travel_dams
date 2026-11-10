<?php

/**
 * Carte d'affichage d'un article — réutilisée dans toutes les grilles du site
 *
 * @var bool   $args['show_category_badge']    Afficher le badge de catégorie. Défaut : true.
 * @var string $args['variant']                'default' (carte blanche), 'photo' (texte sous l'image,
 *                                              voir ArticleCard) ou 'overlay' (texte incrusté sur l'image).
 * @var bool   $args['feature']                Carte "hero" en bento (occupe 2 colonnes). Défaut false.
 * @var string|null $args['badge_label']        Affiche le badge avec le texte indiqué
 */

$variant                = $args['variant'] ?? 'default';
$feature                = ! empty($args['feature']);
$eyebrow                = $args['eyebrow'] ?? null;
$excerpt                = $args['excerpt'] ?? null;
$footer_content         = $args['footer'] ?? null;
$badge_label            = $args['badge_label'] ?? null;
$badge_style            = $args['badge_style'] ?? null;
$highlighted            = $args['highlighted'] ?? false;
$showHighlighted        = $args['show_highlighted'] ??  false;
$title                  = carbon_get_post_meta(get_the_ID(), 'breadcrumb_title') ?: get_the_title();
$pastilles = carbon_get_post_meta(get_the_ID(), 'td_pastilles');

// $categories   = get_the_category();
$destinations = get_the_terms(get_the_ID(), 'destination');

$classes = array('content-card', 'content-card--' . $variant);
if ($feature) {
    $classes[] = 'content-card--feature';
}

if ($highlighted) {
    $classes[] = 'content-card--highlighted';
}

?>

<article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>

    <div class="content-card__thumbnail">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail($highlighted ? 'medium' : 'travel-dams-card', array('class' => 'content-card__image')); ?>
        <?php else : ?>
            <div class="content-card__placeholder" aria-hidden="true"></div>
        <?php endif; ?>

        <?php if ($badge_label) : ?>
            <span class="badge <?= $badge_style ? 'badge--' . $badge_style : 'badge--tag' ?> content-card__badge">
                <?php echo esc_html($badge_label); ?>
            </span>
        <?php endif; ?>

    </div>

    <div class="content-card__body">

        <?php if ($eyebrow) : ?>
            <div class="content-card__eyebrow">
                <?= $eyebrow ?>
            </div>
        <?php endif ?>

        <h3 class="content-card__title">
            <a href="<?php the_permalink(); ?>"><?= $title ?></a>
        </h3>

        <?php if ($excerpt) : ?>
            <div class="content-card__excerpt">
                <?php echo esc_html($excerpt); ?>
                <?php //echo esc_html(wp_trim_words($excerpt, 20)); 
                ?>
            </div>
        <?php endif; ?>

        <?php if ($footer_content || !empty($pastilles)) : ?>
            <div class="content-card__footer">
                <?php if ($footer_content) : ?>
                    <span><?= $footer_content ?></span>
                <?php endif ?>

                <?php if (!empty($pastilles)) : ?>
                    <div class="badges">
                        <?php foreach ($pastilles as $pastille) : ?>
                            <span class="badge badge--light ">
                                <?php echo esc_html($pastille['label']); ?>
                            </span>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
            </div>
        <?php endif ?>


    </div>

</article>