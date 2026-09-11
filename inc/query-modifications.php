<?php

/** Order category.php carnets de voyages by strip_start_date */
add_action('pre_get_posts', function (WP_Query $query) {
    if (is_admin() || !$query->is_main_query() || !$query->is_category(TD_SLUG_CARNETS)) {
        return;
    }

    $query->set('meta_query', [
        'relation' => 'OR',
        'trip_date' => [
            'key' => 'trip_start_date',
            'compare' => 'EXISTS'
        ],
        [
            'key' => 'trip_start_date',
            'compare' => 'NOT EXISTS'
        ]
    ]);

    $query->set('orderby', [
        'trip_date' => 'DESC',
        'date' => 'DESC'
    ]);
});
