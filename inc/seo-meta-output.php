<?php

/**
 * SEO — récupération avec fallback + injection <title> / <meta description>
 * pour les articles ET la page d'accueil (même champs seo_title/
 * seo_description, donc mêmes fonctions de fallback réutilisées telles
 * quelles — voir inc/carbon-fields/post-meta-seo-home.php pour les champs
 * de la home).
 *
 * Les champs eux-mêmes sont définis dans inc/carbon-fields/post-meta-seo.php.
 * Ce fichier n'a pas sa place dans inc/carbon-fields/ (ce n'est pas une
 * définition de champs) — à charger directement depuis functions.php :
 *   require get_template_directory() . '/inc/seo-meta-output.php';
 *
 * @package Travel_Dams
 */

function travel_dams_get_seo_title($post_id)
{
    $custom = carbon_get_post_meta($post_id, 'seo_title');

    if (! empty($custom)) {
        return $custom;
    }

    return get_the_title($post_id) . ' — ' . get_bloginfo('name');
}

function travel_dams_get_seo_description($post_id)
{
    $custom = carbon_get_post_meta($post_id, 'seo_description');

    if (! empty($custom)) {
        return $custom;
    }

    $excerpt = get_the_excerpt($post_id);

    if (empty($excerpt)) {
        $excerpt = wp_strip_all_tags(get_post_field('post_content', $post_id));
    }

    return wp_trim_words($excerpt, 30, '…');
}

add_filter('document_title_parts', function ($title_parts) {
    if (! is_singular('post') && ! is_front_page() && ! is_page_template('page-destinations.php')) {
        return $title_parts;
    }

    $custom = carbon_get_post_meta(get_the_ID(), 'seo_title');

    if (! empty($custom)) {
        return array('title' => $custom);
    }

    return $title_parts;
});

add_action('wp_head', function () {
    if (! is_singular('post') && ! is_front_page() && ! is_page_template('page-destinations.php')) {
        return;
    }

    printf(
        '<meta name="description" content="%s">' . "\n",
        esc_attr(travel_dams_get_seo_description(get_the_ID()))
    );
}, 1);
