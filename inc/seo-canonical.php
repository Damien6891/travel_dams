<?php

/**
 * Canonical des archives de taxonomie (SITE-023).
 * WordPress ne la génère que pour les articles et les pages.
 * À retirer si une extension SEO (Yoast, Rank Math…) est installée un jour.
 */

add_action('wp_head', function () {
    if (! (is_category() || is_tax())) {
        return;
    }


    $link = get_term_link(get_queried_object());
    if (is_wp_error($link)) {
        return;
    }

    $paged = (int) get_query_var('paged');
    if ($paged > 1) {
        $link = trailingslashit($link) . user_trailingslashit('page/' . $paged, 'paged');
    }

    echo '<link rel="canonical" href="' . esc_url($link) . '" />' . "\n";
}, 5);
