<?php
/**
 * justifyblog functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package justifyblog
 */

if ( ! function_exists( 'justifyblog_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function justifyblog_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on justifyblog, use a find and replace
	 * to change 'justifyblog' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'justifyblog', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'justifyblog' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'justifyblog_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'justifyblog_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function justifyblog_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'justifyblog_content_width', 640 );
}
add_action( 'after_setup_theme', 'justifyblog_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function justifyblog_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'justifyblog' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'justifyblog' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'justifyblog_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function justifyblog_scripts() {
	wp_enqueue_style( 'justifyblog-style', get_stylesheet_uri() );

	wp_enqueue_script( 'justifyblog-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'justifyblog-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	wp_enqueue_script( 'justifyblog-google-api', 'https://apis.google.com/js/platform.js', array(), '20170601', true );

	wp_enqueue_script( 'justifyblog-functions', get_template_directory_uri() . '/js/functions.js', array( 'justifyblog-google-api' ), '20170601', true );	

	// Add idea Ajax
	wp_enqueue_script( 'justifyblog-add-idea-ajax', get_stylesheet_directory_uri() . '/js/add-idea.ajax.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'justifyblog-add-idea-ajax', 'addideaAjax', array(
		'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
		'security' 	=> wp_create_nonce( 'my-special-string' )
	));

	// Vote idea Ajax
	wp_enqueue_script( 'justifyblog-vote-idea-ajax', get_stylesheet_directory_uri() . '/js/vote-idea.ajax.js', array('jquery'), '1.0.0', true );
	wp_localize_script( 'justifyblog-vote-idea-ajax', 'voteideaAjax', array(
		'ajaxurl' 	=> admin_url( 'admin-ajax.php' ),
		'security' 	=> wp_create_nonce( 'my-special-string' )
	));
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'justifyblog_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/idea-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

function edit_admin_menus() {
    global $menu;
    global $submenu;
     
    $menu[5][0] = 'Ideas'; // Change Posts to Ideas
    $submenu['edit.php'][5][0] = 'All Ideas';
    $submenu['edit.php'][10][0] = 'Add a Idea';
//    $submenu['edit.php'][15][0] = 'Meal Types'; // Rename categories to meal types
//    $submenu['edit.php'][16][0] = 'Ingredients'; // Rename tags to ingredients
}
add_action( 'admin_menu', 'edit_admin_menus' );


if ( ! function_exists( 'twentytwelve_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentytwelve_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 */
function twentytwelve_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'twentytwelve' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'twentytwelve' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo '<img src="' . get_comment_author_url() . '" /> ';
					printf( '<cite><b class="fn">%1$s</b> %2$s</cite>',
						get_comment_author(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'justifyblog' ) . '</span>' : ''
					);
					
					printf( '<time datetime="%1$s">%2$s</time></a>',
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'twentytwelve' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentytwelve' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'twentytwelve' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'twentytwelve' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;