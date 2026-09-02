<?php
/**
 * Template Name: Elementor Canvas
 *
 * A blank canvas: no header, no footer, no theme markup. For landing pages.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class( 'elementor-template-canvas' ); ?>>
<?php wp_body_open(); ?>

<?php
while ( have_posts() ) {
	the_post();
	the_content();
}
?>

<?php wp_footer(); ?>
</body>
</html>
