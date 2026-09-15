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
$title = $args['title'] ?? __('À lire aussi', 'travel-dams');
?>
<section class="destination-section">
    <h2 class="destination-section__title"><?php echo esc_html($title); ?></h2>
    <div class="post-grid">
        <?php foreach ($posts as $related_post) : ?>
            <!-- <?php get_template_part('template-parts/content', 'card', array('show_category_badge' => false)); ?> -->


            <article id="post-<?php the_ID(); ?>" <?php post_class('content-card'); ?>>

                <a href="<?php echo esc_url(get_permalink($related_post)); ?>" class="content-card__thumbnail-link">
                    <?php if (has_post_thumbnail($related_post)) : ?>
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


                    <div class="content-card__date">
                        <?php if (in_category(TD_SLUG_CARNETS, $related_post->ID)) : ?>

                            <time datetime="<?php echo esc_html(carbon_get_post_meta($related_post->ID, 'trip_start_date')) ?>">
                                <?php echo date_i18n('j F', strtotime(carbon_get_post_meta($related_post->ID, 'trip_start_date'))) ?>
                                -
                                <?php echo date_i18n('j F Y', strtotime(carbon_get_post_meta($related_post->ID, 'trip_end_date'))) ?>
                            </time>
                        <?php endif ?>
                    </div>

                </div>

            </article>

        <?php endforeach; ?>
    </div>
</section>