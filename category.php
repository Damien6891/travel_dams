<?php

/**
 * Template : archive de catégorie native (couvre les 4 piliers éditoriaux)
 */

get_header();

$current_category = get_queried_object();
$image_id = carbon_get_term_meta($current_category->term_id, 'category_cover_image_id');
?>

<main id="primary" class="site-main category-archive">

    <?php
    get_template_part('template-parts/hero', null, array(
        'context'  => 'archive',
        'title'    => single_cat_title('', false),
        'subtitle' => category_description(),
        'image_id' => $image_id ? absint($image_id) : 0
    ));
    ?>

    <div class="container">

        <?php if (have_posts()) : ?>

            <div class="post-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'card', array(
                        'show_category_badge'    => false, // redondant : on est déjà sur cette catégorie
                        'show_destination_badge' => true,
                    ));
                endwhile;
                ?>
            </div>

            <?php the_posts_pagination(); ?>

        <?php else : ?>

            <p><?php esc_html_e('Aucun article pour le moment.', 'travel-dams'); ?></p>

        <?php endif; ?>

    </div>

</main>

<?php get_footer(); ?>