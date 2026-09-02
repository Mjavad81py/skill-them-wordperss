<?php
/**
 * Footer shell. Delegates to the Elementor `footer` location when one is assigned.
 *
 * @package Hooshinex
 */

defined( 'ABSPATH' ) || exit;
?>
	</main><!-- #content -->

	<?php
	hooshinex_do_location(
		'footer',
		function () {
			get_template_part( 'template-parts/footer', 'default' );
		}
	);
	?>

</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
