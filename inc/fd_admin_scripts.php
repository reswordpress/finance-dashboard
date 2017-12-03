<?php
function fd_add_admin_scripts() {
    global $post;
    if((isset($_GET['post_type']) && $_GET['post_type'] == 'invoice')){
    ?>
	    <script type="text/javascript">
		    jQuery(document).ready(function($) {
		       	jQuery('[data-mark]').on('click', function(e){
		       		e.preventDefault();
		       		var col = jQuery(this).parent();
		       		col.html('Betaalstatus aanpassen...');
		       		jQuery.ajax({
						url: ajaxurl,
			         	type: 'post',
			         	dataType: 'JSON',
			         	timeout: 30000,
			         	data: {
			         		action: 'fd_update_post_status',
			         		id: jQuery(this).data('mark')
			         	},
			         	success: function(response) {
			         		col.html('Betaald');
			         	},
			         	error: function (jqXHR, exception) {
					        col.html('<span style="color:#f00;">Betaalstatus aanpassen niet gelukt. </span>');
					    },
			        });
		       	});
		    });
		</script>
	<?php
	}
}
add_action( 'admin_head-edit.php', 'fd_add_admin_scripts', 10, 1 );

add_action( 'wp_ajax_fd_update_post_status', 'fd_update_post_status' );
function fd_update_post_status() {
	if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$post_id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : "";

		if( get_post_type( $post_id ) == 'invoice' ) {
			update_post_meta($post_id, 'fd_meta_invoice_status', 'betaald');
			echo json_encode( 'true' );
		}else{
			echo json_encode( 'false' );
		}

	}
    wp_die();
}

add_action( 'wp_ajax_fd_set_invoice_numer', 'fd_set_invoice_numer' );
function fd_set_invoice_numer() {
	if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$value = isset( $_POST['value'] ) ? $_POST['value'] : false;

		$year = date('Y');
		$number = intval( get_option( 'fd_invoice_number' ) );
		if($value){
			$new = $value;
		}else if( strpos($number, $year) === false ){
			$new = $year.'001';
		}else{
			$new = intval($number+1);
		}
		update_option( 'fd_invoice_number', $new );
		echo $new;
	}
    wp_die();
}

add_action( 'wp_ajax_fd_find_client', 'fd_find_client' );
function fd_find_client() {
	if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$val = isset( $_POST['val'] ) ? esc_html( $_POST['val'] ) : "";
		$args = array(
			'post_type' => 'client',
			'posts_per_page' => 5,
			's' => $val
		);
		$posts = get_posts($args);
		if( sizeof($posts) > 0 ) {
			$output = '';
			foreach ($posts as $post) {
				$output .= '<li style="padding: 5px 15px; margin: 0;"data-client-id="'.$post->ID.'">'.$post->post_title.'</li>';
			}
			echo json_encode( $output );
		}else{
			echo json_encode( '<li style="padding: 5px 15px; margin: 0;">Deze klant bevindt zich nog niet in het systeem</li>' );
		}

	}
    wp_die();
}

add_action( 'wp_ajax_fd_set_client', 'fd_set_client' );
function fd_set_client() {
	if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$id = isset( $_POST['id'] ) ? intval( $_POST['id'] ) : "";

		$output = array(
			'name' => get_post_meta( $id, 'fd_meta_name', true ),
			'company' => get_post_meta( $id, 'fd_meta_company', true ),
			'email' => get_post_meta( $id, 'fd_meta_email', true ),
			'phone' => get_post_meta( $id, 'fd_meta_phone', true ),
			'address' => get_post_meta( $id, 'fd_meta_address_1', true ),
			'zip' => get_post_meta( $id, 'fd_meta_address_2', true )
		);
		echo json_encode( $output );
	}
    wp_die();
}
