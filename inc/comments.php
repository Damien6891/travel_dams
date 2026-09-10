<?php

/**
 * travel_dams Comments
 *
 * @package travel_dams
 */
if (! function_exists('travel_dams_comment')) :
    function travel_dams_comment($comment, $args, $depth)
    {
        $is_threaded_reply = 0 !== (int) $comment->comment_parent;
        $is_author_reply   = $is_threaded_reply && in_array('bypostauthor', get_comment_class('', $comment), true);
        $initial           = mb_strtoupper(mb_substr(get_comment_author($comment), 0, 1));
        $location          = get_comment_meta($comment->comment_ID, 'commenter_location', true);
?>
        <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
            <article class="comment__body">
                <?php if (!$is_author_reply): ?>
                    <span class="comment__avatar" aria-hidden="true"><?php echo esc_html($initial); ?></span>
                <?php endif ?>

                <div class="comment__main">
                    <header class="comment__header">
                        <div class="comment__meta">
                            <?php if (!$is_author_reply) : ?>
                                <span class="comment__author"><?php comment_author(); ?></span>
                                <span class="comment__infos">
                                    <?php if ($location) : ?>
                                        <?php echo esc_html($location); ?> ·
                                    <?php endif; ?>
                                    <?php echo esc_html(get_comment_date()); ?>
                                </span>
                            <?php else : ?>
                                <p class="comment__reply-label"><?php esc_html_e('Réponse de Damien', 'travel-dams'); ?></p>
                            <?php endif ?>
                        </div>
                    </header>


                    <div class="comment__content">
                        <?php if ('0' === $comment->comment_approved) : ?>
                            <p class="comment__pending"><?php esc_html_e('Votre commentaire est en attente de modération.', 'travel-dams'); ?></p>
                        <?php endif; ?>
                        <?php comment_text(); ?>
                    </div>

                    <?php
                    comment_reply_link(
                        array_merge(
                            $args,
                            array(
                                'depth'      => $depth,
                                'max_depth'  => $args['max_depth'],
                                'reply_text' => esc_html__('Répondre', 'travel-dams'),
                            )
                        ),
                        $comment->comment_ID
                    );
                    ?>
                </div>
            </article>
    <?php
        // Pas de </li> : wp_list_comments() la ferme.
    }
endif;

add_action('comment_post', 'travel_dams_save_comment_location');
function travel_dams_save_comment_location($comment_id)
{
    if (! empty($_POST['commenter_location'])) {
        add_comment_meta(
            $comment_id,
            'commenter_location',
            sanitize_text_field(wp_unslash($_POST['commenter_location']))
        );
    }
}

// remove input cookie register email
// add_filter('pre_option_show_comments_cookies_opt_in', '__return_false');
