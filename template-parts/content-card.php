<?php

/**
 * Carte d'affichage d'un article — réutilisée dans toutes les grilles du site
 *
 * @var bool $args['show_category_badge']    Afficher le badge de catégorie. Défaut : true.
 * @var bool $args['show_destination_badge'] Afficher le badge destination (pays). Défaut : true.
 */

$show_category_badge    = $args['show_category_badge'] ?? true;
$show_destination_badge = $args['show_destination_badge'] ?? true;

$categories   = get_the_category();
$destinations = get_the_terms(get_the_ID(), 'destination');

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('content-card'); ?>>

    <a href="<?php the_permalink(); ?>" class="content-card__thumbnail-link">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('travel-dams-card', array('class' => 'content-card__thumbnail')); ?>
        <?php else : ?>
            <div class="content-card__thumbnail content-card__thumbnail--placeholder" aria-hidden="true"></div>
        <?php endif; ?>

        <?php if ($show_category_badge && ! empty($categories)) : ?>
            <span class="content-card__badge content-card__badge--category">
                <?php echo esc_html($categories[0]->name); ?>
            </span>
        <?php endif; ?>
    </a>

    <div class="content-card__body">

        <?php if ($show_destination_badge && ! empty($destinations) && ! is_wp_error($destinations)) : ?>
            <div class="content-card__destination">
                <?php echo esc_html(implode(', ', wp_list_pluck($destinations, 'name'))); ?>
            </div>
        <?php endif; ?>

        <h3 class="content-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <div class="content-card__excerpt">
            <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?>
        </div>

        <time class="content-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
            <?php echo esc_html(get_the_date()); ?>
        </time>

    </div>

</article>