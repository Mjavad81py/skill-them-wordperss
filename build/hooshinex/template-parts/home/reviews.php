<?php
/**
 * Home: latest questions, sourced from the most recent approved comments.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

$hooshinex_comments = get_comments(
	array(
		'status'      => 'approve',
		'number'      => 8,
		'type'        => 'comment',
		'parent'      => 0,
		'post_status' => 'publish',
	)
);

if ( ! $hooshinex_comments ) {
	return;
}
?>
<section class="hx-section hx-section--muted" id="questions">
	<div class="hx-container">

		<?php
		hooshinex_section_header(
			array(
				'accent' => __( 'آخرین', 'hooshinex' ),
				'title'  => __( 'پرسش‌ها', 'hooshinex' ),
			)
		);
		?>

		<div class="hx-carousel" data-hx-carousel data-hx-autoplay="5000">

			<button type="button" class="hx-carousel-nav prev" data-hx-prev aria-label="<?php esc_attr_e( 'قبلی', 'hooshinex' ); ?>">
				<?php hooshinex_icon( 'prev', array( 'stroke' => '2' ) ); ?>
			</button>

			<div class="hx-carousel-viewport">
				<div class="hx-carousel-track hx-question-track" data-hx-track>
					<?php foreach ( $hooshinex_comments as $hooshinex_comment ) : ?>
						<article class="hx-question-card">

							<div class="hx-question-head">
								<span class="hx-question-avatar">
									<?php echo get_avatar( $hooshinex_comment, 44, '', '', array( 'class' => 'hx-avatar' ) ); ?>
								</span>
								<span class="hx-question-author">
									<strong><?php echo esc_html( $hooshinex_comment->comment_author ); ?></strong>
									<time datetime="<?php echo esc_attr( get_comment_date( 'c', $hooshinex_comment ) ); ?>">
										<?php
										printf(
											/* translators: %s: human readable time difference */
											esc_html__( '%s پیش', 'hooshinex' ),
											esc_html( hooshinex_fa_digits( human_time_diff( get_comment_time( 'U', false, false, $hooshinex_comment ) ) ) )
										);
										?>
									</time>
								</span>
								<span class="hx-question-icon"><?php hooshinex_icon( 'chat', array( 'stroke' => '1.8' ) ); ?></span>
							</div>

							<p class="hx-question-text">
								<?php echo esc_html( wp_trim_words( wp_strip_all_tags( $hooshinex_comment->comment_content ), 22 ) ); ?>
							</p>

							<a class="hx-question-link" href="<?php echo esc_url( get_comment_link( $hooshinex_comment ) ); ?>">
								<?php echo esc_html( get_the_title( $hooshinex_comment->comment_post_ID ) ); ?>
								<?php hooshinex_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
							</a>

						</article>
					<?php endforeach; ?>
				</div>
			</div>

			<button type="button" class="hx-carousel-nav next" data-hx-next aria-label="<?php esc_attr_e( 'بعدی', 'hooshinex' ); ?>">
				<?php hooshinex_icon( 'next', array( 'stroke' => '2' ) ); ?>
			</button>

			<div class="hx-dots" data-hx-dots></div>
		</div>

	</div>
</section>
