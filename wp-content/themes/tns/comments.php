<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package TNS
 */

add_filter( 'comment_form_default_fields', 'hide_fields_comment_form' );
function hide_fields_comment_form( $fields ) {
	unset( $fields['cookies'] );

	return $fields;
}

add_filter( 'comment_form_defaults', 'sp_add_comment_form_before' );
function sp_add_comment_form_before( $defaults ) {
	$defaults['comment_notes_before'] = '';

	return $defaults;
}

add_filter('comment_form_default_fields', 'website_remove');
function website_remove($fields)
{
	if(isset($fields['url']))
		unset($fields['url']);
	return $fields;
}

?>
<div id="comments" class="comments-area">
	<?php
	comment_form(
		array(
			'title_reply'        => 'Bình luận',
			'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title fs-20">',
			'title_reply_after'  => '</h2>',
			'cancel_reply_link'  => 'Huỷ trả lời',
		)
	);
	?>

	<?php if ( have_comments() ) : ?>
		<?php the_comments_navigation(); ?>
		<div class="comment-list-wrapper">
			<ol class="comment-list">
				<?php
				wp_list_comments(
					array(
						'style'       => 'ol',
						'short_ping'  => true,
						'avatar_size' => 80,
					)
				);
				?>
			</ol><!-- .comment-list -->
		</div>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'startpress' ); ?></p>
	<?php endif; ?>
</div><!-- .comments-area -->
