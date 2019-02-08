<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package justifyblog
 */

?>
			<div class="clearfix"></div>
		</div><!-- .container -->
	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'justifyblog' ) ); ?>"><?php printf( esc_html__( 'Proudly powered by %s', 'justifyblog' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'justifyblog' ), 'justifyblog', '<a href="https://mySite.com/" rel="designer">mySite.com</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

<!--<script src="https://apis.google.com/js/platform.js" async defer></script>-->

</body>
</html>
