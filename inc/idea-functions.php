<?php 


function justifyblog_add_idea() {	
	
	$idea_title 	= isset($_POST['justifyblog_idea_name']) && $_POST['justifyblog_idea_name'] != '' ? $_POST['justifyblog_idea_name'] : false;
	$idea_content 	= isset($_POST['justifyblog_idea_content']) && $_POST['justifyblog_idea_content'] != '' ? $_POST['justifyblog_idea_content'] : false;
	$idea_author 	= isset($_POST['justifyblog_idea_author_name']) && $_POST['justifyblog_idea_author_name'] != '' ? $_POST['justifyblog_idea_author_name'] : false;
	$idea_image 	= isset($_POST['justifyblog_idea_image_url']) && $_POST['justifyblog_idea_image_url'] != '' ? $_POST['justifyblog_idea_image_url'] : false;
	$idea_email 	= isset($_POST['justifyblog_idea_author_email']) && $_POST['justifyblog_idea_author_email'] != '' ? $_POST['justifyblog_idea_author_email'] : false;	

	$quser = true;
	if( $idea_title && $idea_content ) {
		$quser = justifyblog_check_email( $idea_email );
		if( $quser ) {
			$post_id = insert_idea( $idea_title, $idea_content );
			update_post_meta( $post_id , 'idea_votes', '0' );
			update_post_meta( $post_id , 'author_idea', $idea_author );
			update_post_meta( $post_id , 'author_idea_image', $idea_image );
			
			// $_POST['justifyblog_idea_name'] 		= false;
			// $_POST['justifyblog_idea_content'] 	= false;
		}
	}

	?>
	<!-- substr( 0, strpos( $_SERVER['REQUEST_URI'], '?filter=top' ) ) -->
	<form id="justifyblog-publish" action="<?= substr( $_SERVER['REQUEST_URI'] , 0, strpos( $_SERVER['REQUEST_URI'], '?filter=top' ) ) ?>" class="justifyblog-publish-form" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
		<div class="form-group">
			<input type="text" name="justifyblog_idea_name" class="required" placeholder="Enter your idea" value="<?= !$quser ? $idea_title : '' ?>">
			<span class="input-err">Required field!</span>
		</div>
		<div class="form-group">
			<textarea name="justifyblog_idea_content" class="required" placeholder="Describe your idea..."><?= !$quser ? $idea_content : '' ?></textarea>
			<span class="input-err">Required field!</span>
		</div>
		<input type="hidden" id="justifyblog_idea_image_url" name="justifyblog_idea_image_url" value="">
		<input type="hidden" id="justifyblog_idea_author_name" name="justifyblog_idea_author_name" value="">
		<input type="hidden" id="justifyblog_idea_author_email" name="justifyblog_idea_author_email" value="">
		<input type="submit" value="Post idea">
		<p class="form-err"></p>
		<?= !$quser ? '<p>You are not a Q!</p>' : '' ?>
	</form>
	
	<?php
}


function insert_idea( $idea_name, $idea_content ) {
	// Create post object
	$my_idea = array(
		'post_title'    => wp_strip_all_tags( $idea_name ),
		'post_content'  => sanitize_text_field( $idea_content ),
		'post_status'   => 'publish',
		'post_author'   => 1,
	);
	 
	// Insert the post into the database
	$post_id = wp_insert_post( $my_idea );

	return $post_id;
}


function justifyblog_ideas_filter() {
	echo '<div class="justifyblog-filter">';
		echo '<a href="' . get_site_url() . '/?filter=top' . '">' . esc_html__( 'Top', 'justifyblog' ) . '</a>';
		echo '<a href="' . get_site_url() . '">' . esc_html__( 'New', 'justifyblog' ) . '</a>';
	echo '</div>';
}



















function justifyblog_votes() {
	global $wpdb;
	
	$table_name = $wpdb->prefix . "justifyblog_votes"; 

	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table_name} (
			id BIGINT( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT,
			post_id BIGINT( 20 ) UNSIGNED NOT NULL ,
			email LONGTEXT NULL,
			time DATETIME NULL,
			PRIMARY KEY  (id)
		) DEFAULT CHARSET=utf8;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}	
}
add_action( 'admin_init', 'justifyblog_votes' );
//add_action( 'after_switch_theme', 'justifyblog_votes' );


function justifyblog_add_vote( $post_id, $email ) {
	global $wpdb;
	
	if( empty( $post_id ) || empty( $email ) ) {
		return false;
	}
	
	$table_name = $wpdb->prefix . "justifyblog_votes"; 

	$wpdb->insert( 
		$table_name, 
		array( 
			'post_id'	=> $post_id,
			'email'		=> $email, 
			'time'		=> current_time( 'mysql' ),
		)
	);
	
	update_post_meta( $post_id , 'idea_votes', justifyblog_votes_count( $post_id ) );
	
	return true;
}

function justifyblog_vote_exists( $post_id, $email ) {
	global $wpdb;

	$table_name = $wpdb->prefix . "justifyblog_votes"; 
	
	$vote = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE post_id = '$post_id' AND email = '$email'");

	return $vote;
}

function justifyblog_votes_count( $post_id ) {
	global $wpdb;

	$votes = justifyblog_votes_select( $post_id );
	
	return count( $votes );
}

function justifyblog_votes_select( $post_id ) {
	global $wpdb;

	$table_name = $wpdb->prefix . "justifyblog_votes"; 
	
	$votes = $wpdb->get_results( "SELECT * FROM " . $table_name . " WHERE post_id = " . $post_id, OBJECT );

	return $votes;
}

function justifyblog_vote_delete( $post_id ) {
	global $wpdb;
	
	$table_name = $wpdb->prefix . "justifyblog_votes"; 
	
	$wpdb->delete( $table_name, array( 'id' => $post_id ) );	
}












function justifyblog_check_email( $email ) {
	$domains = array( 'gmail.com' );

	foreach( $domains as $domain ) {
		if( strpos( $email, $domain ) ) {
			return true;
		} 
	}
	return false;
}

function justifyblog_vote_idea() {

    // The $_REQUEST contains all the data sent via ajax
    if ( !isset($_REQUEST) )
		return false;
	 
	$info 		= $_REQUEST['data'];
	$post_id 	= $info[0];
	$email 		= $info[1];

	$our_email = justifyblog_check_email( $email );

	if( $our_email ) {
		$vote_exists = justifyblog_vote_exists( $post_id, $email );
		if( ! $vote_exists ) {
			$vadded = justifyblog_add_vote( intval( $post_id ), $email );
			echo justifyblog_votes_count( $post_id );
		} else {
			echo 'You already voted!';
		}
	} else {
		echo 'You are not a Q!';
	}

	// Always die in functions echoing ajax content
	die();
}
add_action( 'wp_ajax_nopriv_justifyblog_vote_idea', 'justifyblog_vote_idea' );
add_action( 'wp_ajax_justifyblog_vote_idea', 'justifyblog_vote_idea' );


















/*
function justifyblog_add_idea_submit() {

    // The $_REQUEST contains all the data sent via ajax
    if ( !isset($_REQUEST) )
		return false;
	 
	$data_serialized = $_REQUEST['data'];
	parse_str( $data_serialized, $data );

	$idea_name 		= isset( $data['justifyblog_idea_name'] ) ? $data['justifyblog_idea_name'] : '';
	$idea_content 	= isset( $data['justifyblog_idea_content'] ) ? $data['justifyblog_idea_content'] : '';
	
	if( $idea_name && $idea_content ) {		
		$post_id = insert_idea( $idea_name, $idea_content );
		var_dump( $post_id );
		update_post_meta( $post_id , 'idea_votes', '0' );
	}
	
	justifyblog_add_idea();
	justifyblog_ideas_filter();
	$args = array(
		'post_type' => 'post',
		'order'		=> 'DESC',
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) : $query->the_post();
			get_template_part( 'template-parts/content', get_post_format() );
		endwhile;
		the_posts_navigation();
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	

	// Always die in functions echoing ajax content
	die();
}
add_action( 'wp_ajax_nopriv_justifyblog_add_idea_submit', 'justifyblog_add_idea_submit' );
add_action( 'wp_ajax_justifyblog_add_idea_submit', 'justifyblog_add_idea_submit' );
*/





























/**
 * Register meta box Event date.
 */
function justifyblog_votes_meta_boxes() {
    add_meta_box( 'votes-number', __( 'Votes', 'justifyblog' ), 'justifyblog_votes_callback', 'post', 'side', 'low' );
	add_meta_box( 'idea-author', __( 'Author of the idea', 'justifyblog' ), 'justifyblog_idea_author_callback', 'post', 'side', 'low' );
	add_meta_box( 'idea-author-image', __( 'Author image', 'justifyblog' ), 'justifyblog_idea_author_image_callback', 'post', 'side', 'low' );
}
add_action( 'add_meta_boxes', 'justifyblog_votes_meta_boxes' );


/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function justifyblog_votes_callback( $post ) {
	$votes = get_post_meta( $post->ID, 'idea_votes', true );
	echo '<input type="number" value="' . $votes . '" readonly>';
}

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function justifyblog_idea_author_callback( $post ) {
	$author = get_post_meta( $post->ID, 'author_idea', true );
	echo '<input type="text" value="' . $author . '" readonly>';
}


/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function justifyblog_idea_author_image_callback( $post ) {
	$author = get_post_meta( $post->ID, 'author_idea_image', true );
	echo '<input type="text" value="' . $author . '" readonly>';
}
