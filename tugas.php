<?php 
/**
 * Plugin Name: Tugas
 * Description: Contoh tugas plugin
 * Author: Azhary Arliansyah
 *
 * Folder name: tugas
 *
 */

add_action( 'edit_form_after_title', 'lp_instant_wp_canvas_button' );

function lp_instant_wp_canvas_button( $post ) {
	if ( 'page' !== $post->post_type && 'post' !== $post->post_type ) {
		return;
	}

	echo '<h3>' . $post->post_id . '</h3>';
	echo '<button type="button" onclick="go_to_canvas();" id="wpcanvas">WP Canvas</button>';
	echo '<script>
		function go_to_canvas() {
			$.post(ajaxurl, { action: "redirect_to_canvas" }, function(response) {
				window.location = response;
			})
			.fail(function(err){
				console.log(err.responseText);
			});
		}
	</script>';
}

add_action( 'wp_ajax_redirect_to_canvas', 'redirect_to_canvas' );

function redirect_to_canvas() {
	save_post_action();
	wp_die(); // avoid default admin-ajax.php response
}

function save_post_action() {
	if ( empty( $_GET['post_type'] ) ) {
		$post_type = 'post';
	} else {
		$post_type = $_GET['post_type'];
	}

	$post_data = [
		'post_type'		=> $post_type,
		'post_title'	=> 'Tugas'
	];

	$post_id = wp_insert_post( $post_data );
	
	$post_data['ID'] = $post_id;
	$post_data['title'] .= ' #' . $post_id;

	wp_update_post( $post_data );
	echo get_redirect_link( $post_id );
}

function get_redirect_link( $post_id = 0 ) {
	return admin_url( 'post.php?post=' . $post_id . '&action=wpcanvas' );
}


// View WP Canvas page here
if ( $_REQUEST[ 'action' ] == 'wpcanvas' ) {
	echo 'WP Canvas';
	exit;
}