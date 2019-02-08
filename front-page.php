<?php
/**
 * Template Name: Homepage
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package justifyblog
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php justifyblog_add_idea();
			
			justifyblog_ideas_filter();
			
			$args = isset($_GET['filter']) && $_GET['filter'] == 'top' ? 
				array(
					'post_type' => 'post',
					'order' 	=> 'DESC',
					'paged' 	=> $paged,
					'meta_key' 	=> 'idea_votes',
					'orderby' 	=> 'meta_value_num',
				) :
				array(
					'post_type' => 'post',
					'order' 	=> 'DESC',
					'paged' 	=> $paged,
				);
			
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) :

				/* Start the Loop */
				while ( $query->have_posts() ) : $query->the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', get_post_format() );

				endwhile;

//				the_posts_navigation();

				the_posts_pagination( array(
					'prev_text' => ' < ' . '<span class="screen-reader-text">' . __(justifyblog) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'justifyblog' ) . '</span>' . ' > ',
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'justifyblog' ) . ' </span>',
				) );				
				
			else :

				get_template_part( 'template-parts/content', 'none' );

			endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();
