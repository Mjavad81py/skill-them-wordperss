<?php
/**
 * Header shell. Delegates to the Elementor `header` location when one is assigned.
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
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#content">
	<?php esc_html_e( 'Skip to content', 'hooshinex' ); ?>
</a>

<div id="page" class="site">

	<?php
	hooshinex_do_location(
		'header',
		function () {
			get_template_part( 'template-parts/header', 'default' );
		}
	);
	?>

	<main id="content" class="site-main">
