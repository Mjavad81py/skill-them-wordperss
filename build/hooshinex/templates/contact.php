<?php
/**
 * Template Name: تماس با ما
 *
 * Page content first, then the contact details entered in the Customizer and a
 * comment-powered message form so the page is useful before any form plugin is
 * installed.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;

get_header();

$hooshinex_channels = array(
	array(
		'icon'  => 'phone',
		'label' => __( 'تلفن پشتیبانی', 'hooshinex' ),
		'value' => get_theme_mod( 'hooshinex_contact_phone', '' ),
		'href'  => 'tel:',
	),
	array(
		'icon'  => 'mail',
		'label' => __( 'ایمیل', 'hooshinex' ),
		'value' => get_theme_mod( 'hooshinex_contact_email', '' ),
		'href'  => 'mailto:',
	),
	array(
		'icon'  => 'telegram',
		'label' => __( 'تلگرام', 'hooshinex' ),
		'value' => get_theme_mod( 'hooshinex_contact_telegram', '' ),
		'href'  => 'https://t.me/',
	),
	array(
		'icon'  => 'map',
		'label' => __( 'نشانی', 'hooshinex' ),
		'value' => get_theme_mod( 'hooshinex_contact_address', '' ),
		'href'  => '',
	),
);
?>

<main id="primary" class="site-main hx-page">

	<?php hooshinex_page_header(); ?>

	<div class="hx-container">

		<?php
		while ( have_posts() ) :
			the_post();

			if ( trim( get_the_content() ) ) :
				?>
				<div class="entry-content hx-page-intro">
					<?php the_content(); ?>
				</div>
				<?php
			endif;
		endwhile;
		?>

		<div class="hx-contact-grid">

			<div class="hx-contact-cards">
				<?php
				foreach ( $hooshinex_channels as $hooshinex_channel ) :
					if ( ! $hooshinex_channel['value'] ) {
						continue;
					}
					?>
					<div class="hx-contact-card">
						<span class="hx-contact-icon"><?php hooshinex_icon( $hooshinex_channel['icon'], array( 'stroke' => '1.8' ) ); ?></span>
						<span class="hx-contact-label"><?php echo esc_html( $hooshinex_channel['label'] ); ?></span>

						<?php if ( $hooshinex_channel['href'] ) : ?>
							<a class="hx-contact-value" dir="ltr" href="<?php
								echo esc_url(
									'tel:' === $hooshinex_channel['href']
										? 'tel:' . preg_replace( '/[^0-9+]/', '', $hooshinex_channel['value'] )
										: $hooshinex_channel['href'] . ltrim( $hooshinex_channel['value'], '@' )
								);
							?>"><?php echo esc_html( $hooshinex_channel['value'] ); ?></a>
						<?php else : ?>
							<span class="hx-contact-value"><?php echo esc_html( $hooshinex_channel['value'] ); ?></span>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>

				<?php
				$hooshinex_socials = hooshinex_social_links();

				if ( $hooshinex_socials ) :
					?>
					<div class="hx-contact-card hx-contact-card--social">
						<span class="hx-contact-label"><?php esc_html_e( 'ما را دنبال کنید', 'hooshinex' ); ?></span>
						<div class="hx-footer-social">
							<?php foreach ( $hooshinex_socials as $hooshinex_social ) : ?>
								<a href="<?php echo esc_url( $hooshinex_social['url'] ); ?>" target="_blank" rel="noopener noreferrer"
									title="<?php echo esc_attr( $hooshinex_social['label'] ); ?>">
									<?php hooshinex_icon( $hooshinex_social['icon'] ); ?>
									<span class="screen-reader-text"><?php echo esc_html( $hooshinex_social['label'] ); ?></span>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<div class="hx-contact-form">
				<h2 class="hx-contact-form-title"><?php esc_html_e( 'ارسال پیام', 'hooshinex' ); ?></h2>
				<p class="hx-contact-form-note">
					<?php esc_html_e( 'پیام شما در ساعات کاری بررسی می‌شود و پاسخ آن به ایمیلتان ارسال می‌گردد.', 'hooshinex' ); ?>
				</p>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				} else {
					printf(
						'<p class="hx-notice">%s</p>',
						esc_html__( 'برای فعال شدن فرم، دیدگاه‌ها را برای این برگه فعال کنید یا یک افزونه فرم تماس نصب کنید.', 'hooshinex' )
					);
				}
				?>
			</div>

		</div>

	</div>

</main>

<?php
get_footer();
