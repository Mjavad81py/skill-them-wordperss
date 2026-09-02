<?php
/**
 * Template Name: سوالات متداول
 *
 * Renders the page content as an accordion. Questions come from the page's own
 * heading + paragraph pairs when present, so editors write FAQs in the normal
 * editor; a store-oriented default set is used otherwise.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();

	$hooshinex_content = apply_filters( 'the_content', get_the_content() );
	$hooshinex_items   = hooshinex_parse_faq( $hooshinex_content );
	$hooshinex_intro   = '';

	if ( ! $hooshinex_items ) {
		$hooshinex_items = hooshinex_default_faq();
		$hooshinex_intro = $hooshinex_content;
	}

	$hooshinex_pages   = get_option( 'hooshinex_pages', array() );
	$hooshinex_contact = ! empty( $hooshinex_pages['contact'] ) ? get_permalink( (int) $hooshinex_pages['contact'] ) : home_url( '/' );
	?>

	<main id="primary" class="site-main hx-page">

		<?php hooshinex_page_header(); ?>

		<div class="hx-container hx-faq-wrap">

			<?php if ( trim( wp_strip_all_tags( $hooshinex_intro ) ) ) : ?>
				<div class="entry-content hx-page-intro"><?php echo wp_kses_post( $hooshinex_intro ); ?></div>
			<?php endif; ?>

			<div class="hx-faq" data-hx-accordion>
				<?php foreach ( $hooshinex_items as $hooshinex_index => $hooshinex_item ) : ?>
					<div class="hx-faq-item" data-hx-accordion-item>

						<h3 class="hx-faq-question">
							<button type="button" data-hx-accordion-trigger
								aria-expanded="false"
								aria-controls="hx-faq-panel-<?php echo esc_attr( $hooshinex_index ); ?>">
								<span><?php echo esc_html( $hooshinex_item['question'] ); ?></span>
								<span class="hx-faq-marker" aria-hidden="true">
									<?php hooshinex_icon( 'chevron', array( 'stroke' => '2' ) ); ?>
								</span>
							</button>
						</h3>

						<div class="hx-faq-answer" id="hx-faq-panel-<?php echo esc_attr( $hooshinex_index ); ?>" data-hx-accordion-panel>
							<div class="hx-faq-answer-inner"><?php echo wp_kses_post( $hooshinex_item['answer'] ); ?></div>
						</div>

					</div>
				<?php endforeach; ?>
			</div>

			<div class="hx-faq-cta">
				<p><?php esc_html_e( 'پاسخ سوالتان را پیدا نکردید؟', 'hooshinex' ); ?></p>
				<a class="hx-btn-primary" href="<?php echo esc_url( $hooshinex_contact ); ?>">
					<?php esc_html_e( 'تماس با پشتیبانی', 'hooshinex' ); ?>
					<?php hooshinex_icon( 'arrow', array( 'stroke' => '2' ) ); ?>
				</a>
			</div>

		</div>

	</main>

	<?php
endwhile;

get_footer();
