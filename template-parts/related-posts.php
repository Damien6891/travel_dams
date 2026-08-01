<?php

/**
 * @param array $args {
 *     @type WP_Post[] $posts Articles à afficher.
 *     @type string    $title Titre de la section (optionnel).
 * }
 */

if (empty($args['posts'])) {
    return;
}

$posts = $args['posts'];
$title = $args['title'] ?? __('À lire aussi', 'travel_dams');
?>
<section class="destination-section">
    <h2 class="destination-section__title"><?php echo esc_html($title); ?></h2>
    <div class="post-grid">
        <?php foreach ($posts as $related_post) : ?>
            <!-- <?php get_template_part('template-parts/content', 'card', array('show_category_badge' => false)); ?> -->

            <article id="post-<?php the_ID(); ?>" <?php post_class('content-card'); ?>>

                <a href="<?php echo esc_url(get_permalink($related_post)); ?>" class="content-card__thumbnail-link">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php echo get_the_post_thumbnail($related_post, 'travel-dams-card', array('class' => 'content-card__thumbnail')); ?>
                    <?php else : ?>
                        <div class="content-card__thumbnail content-card__thumbnail--placeholder" aria-hidden="true"></div>
                    <?php endif; ?>

                </a>

                <div class="content-card__body">



                    <h3 class="content-card__title">
                        <a href="<?php echo get_permalink($related_post); ?>"><?php echo esc_html(get_the_title($related_post)); ?></a>
                    </h3>

                    <div class="content-card__excerpt">
                        <?php echo esc_html(wp_trim_words(get_the_excerpt($related_post), 20)); ?>
                    </div>

                    <time class="content-card__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>

                </div>

            </article>

        <?php endforeach; ?>
    </div>
</section>