<?php
$page_id = get_the_ID();

if (carbon_get_post_meta($page_id, 'hide_suggestions')) {
    return;
}

$picked = wp_list_pluck((array) carbon_get_post_meta($page_id, 'page_suggestions'), 'id');
$ids = array_map('intval', $picked);

// Fallback: most recent of each type
if (!$ids) {
    $slugs = [TD_SLUG_CARNETS, TD_SLUG_DESTINATIONS_GUIDES, TD_SLUG_GUIDES];

    foreach ($slugs as $slug) {
        $term = get_term_by('slug', $slug, 'category');
        if (!$term) {
            continue;
        }

        $term_id = function_exists('pll_get_term') ? pll_get_term($term->term_id) : $term->term_id;
        if (!$term_id) {
            continue;
        }

        $latest = get_posts([
            'cat' => $term_id,
            'posts_per_page' => 1,
            'fields' => 'ids',
            'post__not_in' => $ids
        ]);
        $ids = array_merge($ids, $latest);
    }
}

if (!$ids) {
    return;
}

$query = new WP_Query([
    'post__in' => $ids,
    'orderby' => 'post__in',
    'posts_per_page' => count($ids),
    'ignore_sticky_posts' => true,
    'no_found_rows' => true
]);

if (!$query->have_posts()) {
    return;
}
?>

<section class="page-suggestions">
    <h2 class="page-suggestions__title"><?php esc_html_e('À lire aussi', 'travel-dams') ?></h2>
    <div class="post-grid page-suggestions__grid">

        <?php
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'card');
        }
        wp_reset_postdata()
        ?>
    </div>
</section>