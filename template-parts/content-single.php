<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package justifyblog
 */

$post_id = get_the_ID();

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div id="vote-idea" class="vote-idea-wrap">
		<span id="vote-idea-<?=$post_id?>" class="vote-idea-count">
			<?=justifyblog_votes_count( $post_id )?>
		</span>
		votes
		<br/>
		<button class="vote-idea" data-post="<?=$post_id?>">Vote</button>
	</div>

	<div class="idea-content-wrap">
		<header class="entry-header">
			<?php
			if ( is_single() ) :
				the_title( '<h1 class="entry-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;
			/*
			if ( 'post' === get_post_type() ) : ?>
			<div class="entry-meta">
				<?php justifyblog_posted_on(); ?>
			</div><!-- .entry-meta -->
			<?php
			endif;*/ ?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php
				the_content( sprintf(
					/* translators: %s: Name of current post. */
					wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'justifyblog' ), array( 'span' => array( 'class' => array() ) ) ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				) );

				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'justifyblog' ),
					'after'  => '</div>',
				) );
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
		
			<?php
				
				$time_string = sprintf( $time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() ),
					esc_attr( get_the_modified_date( 'c' ) ),
					esc_html( get_the_modified_date() )
				);
				
				$author_info = get_post_meta( get_the_ID() );
					
				echo '<img src="' . esc_url( $author_info['author_idea_image'][0], 'justifyblog' ) . '" /> ' . esc_html__( $author_info['author_idea'][0], 'justifyblog' ) . esc_html__( ' shared this idea on ', 'justifyblog' ) . get_the_date();
				
			?>
		
			<?php justifyblog_entry_footer(); ?>
		</footer><!-- .entry-footer -->
	</div>
</article><!-- #post-## -->
