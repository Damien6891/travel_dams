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
        <?php foreach ($posts as $post) :
            setup_postdata($post);
        ?>

            <?php get_template_part(
                'template-parts/content-card',
                null,
                array(
                    // 'show_category_badge' => false
                    // 'variant' => 'background',
                    'badge_label' => get_the_category($post->ID)[0]->name ?? null
                )
            ); ?>


        <?php endforeach;
        wp_reset_postdata();
        ?>
    </div>
</section>