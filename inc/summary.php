<?php

/**
 * Sommaire pour les articles "guide destination", "guide pratique"
 */

function travel_dams_get_guide_sommaire($post_id = null)
{
    $post_id  = $post_id ?: get_the_ID();
    $levels   = carbon_get_post_meta($post_id, 'guide_toc_h3') ? [2, 3] : [2];
    $headings = travel_dams_collect_headings(parse_blocks(get_post_field('post_content', $post_id)), $levels);

    // Range les H3 sous leur H2
    $sections = [];
    foreach ($headings as $h) {
        if (2 === $h['level']) {
            $sections[] = $h + ['children' => []];
        } elseif ($sections) {
            $sections[array_key_last($sections)]['children'][] = $h;
        }
    }
    return $sections;
}

function travel_dams_collect_headings(array $blocks, array $levels, array &$items = [])
{
    foreach ($blocks as $block) {
        $level = $block['attrs']['level'] ?? 2;
        if ('core/heading' === $block['blockName'] && in_array($level, $levels, true)) {
            $text    = trim(wp_strip_all_tags($block['innerHTML']));
            $items[] = [
                'level' => $level,
                'title' => $text,
                'id'    => $block['attrs']['anchor'] ?? sanitize_title($text),
            ];
        }
        if (!empty($block['innerBlocks'])) {
            travel_dams_collect_headings($block['innerBlocks'], $levels, $items);
        }
    }
    return $items;
}

add_filter('render_block_core/heading', function ($html, $block) {
    $level = $block['attrs']['level'] ?? 2;
    if (!in_array($level, [2, 3], true)) return $html;

    $p = new WP_HTML_Tag_Processor($html);
    if ($p->next_tag('h' . $level) && !$p->get_attribute('id')) {
        $p->set_attribute('id', sanitize_title(wp_strip_all_tags($html)));
    }
    return $p->get_updated_html();
}, 10, 2);
