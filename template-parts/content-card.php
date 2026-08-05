<?php

/**
 * Carte d'affichage d'un article — réutilisée dans toutes les grilles du site
 *
 * @var bool   $args['show_category_badge']    Afficher le badge de catégorie. Défaut : true.
 * @var bool   $args['show_destination_badge'] Afficher le badge destination (pays). Défaut : true.
 * @var string $args['variant']                'default' (carte blanche), 'photo' (texte sous l'image,
 *                                              voir ArticleCard) ou 'overlay' (texte incrusté sur l'image).
 * @var bool   $args['feature']                Carte "hero" en bento (occupe 2 colonnes). Défaut false.
 */

$show_category_badge    = $args['show_category_badge'] ?? true;
$show_destination_badge = $args['show_destination_badge'] ?? true;
$variant                = $args['variant'] ?? 'default';
$feature                = ! empty($args['feature']);

$categories   = get_the_category();
$destinations = get_the_terms(get_the_ID(), 'destination');

$classes = array('content-card', 'content-card--' . $variant);
if ($feature) {
    $classes[] = 'content-card--feature';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class($classes); ?>>

    <a href="<?php the_permalink(); ?>" class="content-card__thumbnail-link">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('travel-dams-card', array('class' => 'content-card__thumbnail')); ?>
        <?php else : ?>
            <div class="content-card__thumbnail content-card__thumbnail--placeholder" aria-hidden="true"></div>
        <?php endif; ?>

        <?php
        // Sur les variantes photo, le pastille sur l'image affiche le pays plutôt
        // que la catégorie (souvent redondante avec le titre de la section).
        $badge_label = ('default' === $variant && $show_category_badge && ! empty($categories))
            ? $categories[0]->name
            : ((! empty($destinations) && ! is_wp_error($destinations)) ? $destinations[0]->name : '');
        ?>
        <?php if ($badge_label) : ?>
            <span class="badge badge--tag content-card__badge">
                <?php echo esc_html($badge_label); ?>
            </span>
        <?php endif; ?>
    </a>

    <div class="content-card__body">

        <?php if ($show_destination_badge && 'default' === $variant && ! empty($destinations) && ! is_wp_error($destinations)) : ?>
            <div class="content-card__destination">
                <?php echo esc_html(implode(', ', wp_list_pluck($destinations, 'name'))); ?>
            </div>
        <?php elseif ('default' !== $variant) : ?>
            <div class="content-card__destination">
                <?php echo esc_html(get_the_date()); ?>
            </div>
        <?php endif; ?>

        <h3 class="content-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ('default' === $variant) : ?>
            <div class="content-card__excerpt">
                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?>
            </div>
        <?php endif; ?>

        <time class="content-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
            <?php echo esc_html(get_the_date()); ?>
        </time>

    </div>

</article>