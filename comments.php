<?php

/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package travel_dams
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
	return;
}
?>

<div id="comments" class="container comments">

	<?php
	// You can start editing here -- including this comment!
	if (have_comments()) :
	?>
		<h2 class="comments__title">
			<?php
			$travel_dams_comment_count = get_comments_number();
			if ('1' === $travel_dams_comment_count) {
				printf(
					/* translators: 1: title. */
					esc_html__('Un commentaire sur &ldquo;%1$s&rdquo;', 'travel-dams'),
					'<span>' . wp_kses_post(get_the_title()) . '</span>'
				);
			} else {
				printf(
					/* translators: 1: comment count number, 2: title. */
					esc_html(_nx('%1$s commentaire sur &ldquo;%2$s&rdquo;', '%1$s commentaires sur &ldquo;%2$s&rdquo;', $travel_dams_comment_count, 'comments title', 'travel-dams')),
					number_format_i18n($travel_dams_comment_count), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'<span>' . wp_kses_post(get_the_title()) . '</span>'
				);
			}
			?>
		</h2><!-- .comments-title -->

		<?php the_comments_navigation(); ?>

		<ol class="comments__list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size' => 0,
					'callback' => 'travel_dams_comment'
				)
			);
			?>
		</ol><!-- .comment-list -->

		<?php
		the_comments_navigation();

		// If comments are closed and there are comments, let's leave a little note, shall we?
		if (! comments_open()) :
		?>
			<p class="no-comments"><?php esc_html_e('Comments are closed.', 'travel_dams'); ?></p>
	<?php
		endif;

	endif; // Check for have_comments().

	$commenter = wp_get_current_commenter();

	comment_form(array(
		'title_reply'         => esc_html__('Laisser un mot', 'travel-dams'),
		'comment_notes_before' => '', // supprime la mention email erronée (plus de champ email)
		'fields'               => array(), // tout est géré dans comment_field pour maîtriser l'ordre
		'logged_in_as'         => '',
		'comment_field'        =>
		'<div class="comment-form__row-group">' .
			'<div class="comment-form__row">' .
			'<input id="author" name="author" type="text" placeholder="' . esc_attr__('Votre prénom', 'travel-dams') . '" value="' . esc_attr($commenter['comment_author']) . '" required /></div>' .
			'<div class="comment-form__row">' .
			'<input id="commenter_location" name="commenter_location" type="text" placeholder="' . esc_attr__("D'où écrivez-vous ?", 'travel-dams') . '" /></div>' .
			'</div>' .
			'<div class="comment-form__row comment-form__row--textarea">' .
			'<textarea id="comment" name="comment" placeholder="' . esc_attr__("Partagez votre expérience ou posez une question sur l'itinéraire…", 'travel-dams') . '" required></textarea></div>',
		'comment_notes_after' => '<p class="comment-form__notice">' . esc_html__('Les commentaires sont relus avant publication.', 'travel-dams') . '</p>',
		'label_submit'        => esc_html__('Publier', 'travel-dams'),
		'class_submit'        => 'comment-form__submit',
	));

	?>

</div><!-- #comments -->