<?php

/**
 * SEO — JSON-LD Article, pour les articles (carnets, guides, réflexions).
 *
 * headline : titre SEO custom s'il existe, sinon titre brut de l'article
 * — sans le nom du site (contrairement à travel_dams_get_seo_title(),
 * Google déconseille d'inclure le nom du site dans le headline).
 *
 * image : réutilise l'image mise en avant, comme pour l'Open Graph
 * (inc/seo-open-graph.php).
 *
 * author.url : page d'archive auteur native de WordPress.
 *
 * Toutes les URLs passent par travel_dams_absolute_url() (inc/seo-helpers.php)
 * — un JSON-LD avec une URL protocol-relative (//host/...) est rejeté comme
 * invalide par le Rich Results Test de Google.
 *
 * À charger depuis functions.php, après seo-helpers.php :
 *   require get_template_directory() . '/inc/seo-json-ld-article.php';
 *
 * @package Travel_Dams
 */

add_action('wp_head', function () {
    if (! is_singular('post')) {
        return;
    }

    $post_id = get_the_ID();

    $custom_title = carbon_get_post_meta($post_id, 'seo_title');
    $headline = ! empty($custom_title) ? $custom_title : get_the_title($post_id);

    if (mb_strlen($headline) > 110) {
        $headline = mb_substr($headline, 0, 109) . '…';
    }

    $thumbnail_id = get_post_thumbnail_id($post_id);
    $image_url = $thumbnail_id
        ? travel_dams_absolute_url(wp_get_attachment_image_url($thumbnail_id, 'large')) // seo-helpers.php
        : null;

    $author_id = get_post_field('post_author', $post_id);

    $schema = array(
        '@context'         => 'https://schema.org',
        '@type'            => 'Article',
        'headline'         => $headline,
        'datePublished'    => get_the_date('c', $post_id),
        'dateModified'     => get_the_modified_date('c', $post_id),
        'author'           => array(
            '@type' => 'Person',
            'name'  => get_the_author_meta('display_name', $author_id),
            'url'   => travel_dams_absolute_url(get_author_posts_url($author_id)), // seo-helpers.php
        ),
        'publisher'        => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo('name'),
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id'   => travel_dams_absolute_url(get_permalink($post_id)), // seo-helpers.php
        ),
    );

    if ($image_url) {
        $schema['image'] = array($image_url);
    }

    echo '<script type="application/ld+json">'
        . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . '</script>' . "\n";
}, 10);
