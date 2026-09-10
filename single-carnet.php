<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package travel_dams
 */

get_header();

$post_id       = get_the_ID();
$start         = carbon_get_post_meta($post_id, 'trip_start_date');
$end           = carbon_get_post_meta($post_id, 'trip_end_date');
$hero_image    = carbon_get_post_meta($post_id, 'hero_image');
$carnets_cat   = travel_dams_get_pillar_term_id(TD_SLUG_CARNETS);
$is_carnet     = $carnets_cat && has_category($carnets_cat, $post_id);

$hero_eyebrow = '';
$hero_byline  = '';

// if ($is_carnet) {
$context = travel_dams_get_destination_context($post_id);
$country = $context['country'] ?? null;

$hero_eyebrow = $country
    /* translators: %s: nom du pays */
    ? sprintf(__('Carnet de voyage · %s', 'travel-dams'), $country->name)
    : __('Carnet de voyage', 'travel-dams');

if ($start && $end) {
    $hero_byline = sprintf(
        /* translators: 1: date de début, 2: date de fin, 3: auteur */
        __('%1$s – %2$s · %3$s', 'travel-dams'),
        date_i18n('j F Y', strtotime($start)),
        date_i18n('j F Y', strtotime($end)),
        get_the_author()
    );
}
// }
?>

<main id="primary" class="site-main">

    <button class="scroll-to-top">
        <span class="dashicons dashicons-arrow-up-alt2"></span>
    </button>


    <?php
    get_template_part('template-parts/hero', null, array(
        'context' => $is_carnet ? 'carnet' : 'single',
        'eyebrow' => $hero_eyebrow,
        'title'   => get_the_title(),
        'byline'  => $hero_byline,
        'image_id' => $hero_image ?: get_post_thumbnail_id(),
    ))
    ?>

    <?php
    while (have_posts()) :
        the_post();

        // get_template_part('template-parts/content', get_post_type());
    ?>

        <article id="post-<?php the_ID() ?>" <?php post_class() ?>>

            <?php echo get_the_post_type_description() ?>

            <div class="entry-content">
                <?php $days = travel_dams_get_carnet_sommaire();

                if ($days) : ?>
                    <nav class="carnet-sommaire" aria-label="Sommaire du carnet">
                        <details class="carnet-sommaire__container">
                            <summary class="carnet-sommaire__toggle">
                                <?= __('Sommaire', 'travel-dams') ?> <span class="carnet-sommaire__current"></span>
                            </summary>
                            <ul class="carnet-sommaire__list">
                                <?php foreach ($days as $day) : ?>
                                    <li>
                                        <a class="carnet-sommaire__item" href="#day-<?php echo esc_attr($day['number']); ?>">
                                            <span class="carnet-sommaire__day">
                                                <?= esc_html($day['number_title']) ?>
                                            </span>
                                            <span class="carnet-sommaire__title">
                                                <?= esc_html(wptexturize($day['title'])) ?>
                                            </span>
                                            <span class="carnet-sommaire__date">
                                                <?= $day['date'] ?>
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </details>
                    </nav>
                <?php endif; ?>

                <?php

                the_content(
                    sprintf(
                        wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                            __('Continue reading<span class="screen-reader-text"> "%s"</span>', 'travel_dams'),
                            array(
                                'span' => array(
                                    'class' => array(),
                                ),
                            )
                        ),
                        wp_kses_post(get_the_title())
                    )
                );

                wp_link_pages(
                    array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'travel_dams'),
                        'after'  => '</div>',
                    )
                );
                ?>

            </div>

            <footer class="entry-footer">
                <div class="container">

                    <?php
                    $post_id = get_the_ID();

                    $pillar_narratif = array_filter([
                        travel_dams_get_pillar_term_id('carnets-de-voyage'),
                        travel_dams_get_pillar_term_id('guides-destinations'),
                    ]);
                    $pillar_pratique = travel_dams_get_pillar_term_id('guides-pratiques');

                    if (has_category($pillar_narratif, $post_id)) {
                        $related = travel_dams_get_related_posts($post_id);
                    } elseif ($pillar_pratique && has_category($pillar_pratique, $post_id)) {
                        $related = travel_dams_get_related_by_category($post_id);
                    } else {
                        $related = [];
                    }

                    if (! empty($related)) {


                        $carnets_cat_id = travel_dams_get_pillar_term_id(TD_SLUG_CARNETS);

                        // if ($carnets_cat_id && has_category($carnets_cat_id, $post_id)) {
                        // 	get_template_part('template-parts/destination/next-carnet', null, ['post' => $related[0]]);
                        // } else {
                        // 	get_template_part('template-parts/related-posts', null, ['posts' => $related]);
                        // }
                        get_template_part('template-parts/related-posts', null, ['posts' => $related]);
                    }
                    ?>
                </div>

                <div>
                    <?php
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                </div>

            </footer>

        </article>

    <?php
    // if ($is_carnet) {
    // 	get_template_part('template-parts/carnet-share');
    // }

    endwhile; // End of the loop.
    ?>

</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
