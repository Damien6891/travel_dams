<?php

/**
 * SEO — JSON-LD BreadcrumbList.
 *
 * Réutilise travel_dams_get_breadcrumb_trail() (inc/breadcrumb.php) pour
 * que le balisage structuré corresponde exactement au fil d'Ariane affiché.
 *
 * À charger depuis functions.php, après breadcrumb.php :
 *   require get_template_directory() . '/inc/seo-json-ld-breadcrumb.php';
 *
 * @package Travel_Dams
 */

add_action('wp_head', function () {
    $trail = travel_dams_get_breadcrumb_trail();

    if (count($trail) < 2) {
        return;
    }

    $items = array();

    foreach ($trail as $index => $item) {
        $list_item = array(
            '@type'    => 'ListItem',
            'position' => $index + 1,
            'name'     => $item['title'],
        );

        if (! empty($item['url'])) {
            $list_item['item'] = $item['url'];
        }

        $items[] = $list_item;
    }

    $schema = array(
        '@context'        => 'https://schema.org',
        '@type'            => 'BreadcrumbList',
        'itemListElement' => $items,
    );

    echo '<script type="application/ld+json">'
        . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
        . '</script>' . "\n";
}, 10);
