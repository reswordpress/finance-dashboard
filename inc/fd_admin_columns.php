<?php
function fd_add_invoice_column( $columns ) {
    $columns['invoice_number'] = __( 'Factuurnummer', 'fd' );
    $columns['invoice_date'] = __( 'Factuurdatum', 'fd' );
    $columns['invoice_client'] = __( 'Klant', 'fd' );
    $columns['invoice_amount'] = __( 'Bedrag', 'fd' );
    $columns['invoice_state'] = __( 'Status', 'fd' );
    return $columns;
}
add_filter( 'manage_edit-invoice_columns', 'fd_add_invoice_column' );

function fd_add_receipt_column( $columns ) {
    $columns['receipt_date'] = __( 'Factuurdatum', 'fd' );
    $columns['receipt_file'] = __( 'Factuurbijlage', 'fd' );
    $columns['receipt_price'] = __( 'Bedrag (excl btw)', 'fd' );
    $columns['receipt_tax'] = __( 'BTW', 'fd' );
    return $columns;
}
add_filter( 'manage_edit-receipt_columns', 'fd_add_receipt_column' );

//Make column sortable
function fd_make_sortable_column($columns){
  $columns['invoice_number'] = 'invoice_number';
  $columns['invoice_date'] = 'invoice_date';
  $columns['invoice_state'] = 'invoice_state';
  return $columns;
}
add_filter( 'manage_edit-invoice_sortable_columns', 'fd_make_sortable_column' );

function fd_make_sortable_column_receipt($columns){
  $columns['receipt_date'] = 'receipt_date';
  $columns['receipt_price'] = 'receipt_price';
  $columns['receipt_tax'] = 'receipt_tax';
  return $columns;
}
add_filter( 'manage_edit-receipt_sortable_columns', 'fd_make_sortable_column_receipt' );

//Add content to column
function fd_set_invoice_column_content( $column_name, $post_id ) {
    if ( $column_name == 'invoice_number' ) {
    	$tk = 'fd_meta_invoice_num';
    	$tkv = get_post_meta($post_id, $tk, true );
    	if($tkv){
    		echo $tkv;
    	}
    }
    if ( $column_name == 'invoice_state' ) {
		$tk = 'fd_meta_invoice_status';
		$tkv = get_post_meta($post_id, $tk, true );
		if($tkv){
			echo ucfirst($tkv);
    	}
    	if($tkv == 'onbetaald'){
	    	$tk = 'fd_meta_invoice_due';
			$tkv = get_post_meta($post_id, $tk, true );
			if($tkv){
				if( date('Y-m-d') > $tkv ){
					echo '<br/><span style="color:#f00;"> (Betaaldatum verlopen)</span><br/><a href="'.add_query_arg( 'reminder', 'true', get_permalink($post_id) ).'" target="_blank">Maak een reminder</a>';
				}
			}
			echo '<br/><a href="'.get_edit_post_link($post_id).'" target="_blank" data-mark="'.$post_id.'">Markeer als betaald</a>';
		}
    }
    if ( $column_name == 'invoice_date' ) {
		$tk = 'fd_meta_invoice_date';
		$months = array('januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');
		$tkv = get_post_meta($post_id, $tk, true );
		if($tkv){
			$tkv = explode('-', $tkv);
			echo $tkv[2] . ' ' . $months[ intval($tkv[1])-1] . ' ' . $tkv[0];
		}
    }
    if ( $column_name == 'invoice_client' ) {
		$tks = array('fd_meta_name', 'fd_meta_company', 'fd_meta_address_1', 'fd_meta_address_2','fd_meta_phone');

		foreach ($tks as $tk) {
		  	$tkv = get_post_meta($post_id, $tk, true );
			if($tkv){
				echo $tkv . '<br/>';
			}
		}
    }
    if ( $column_name == 'invoice_amount' ) {
    	$invoiceRows = array_values( get_post_meta($post_id, 'invoice_rows', true ) );
		$rowCount = count($invoiceRows);
		if( $invoiceRows && sizeof($invoiceRows) > 0 ) {
			$ex = 0;
			$tax = 0;
			$in = 0;
			$private = 0;
			for ( $i = 0; $i < $rowCount; $i++ ) {
				if(
					isset($invoiceRows[$i]['rowItemTaxNum']) &&
					is_array($invoiceRows[$i]['rowItemTaxNum']) &&
					$invoiceRows[$i]['rowItemTaxNum'][0] == -1
				){
					$private += str_replace(',', '.', $invoiceRows[$i]['rowItemTotal']);
				}else{
					$ex += str_replace(',', '.', $invoiceRows[$i]['rowItemCosts']);
					$tax += str_replace(',', '.', $invoiceRows[$i]['rowItemTax']);
					$in += str_replace(',', '.', $invoiceRows[$i]['rowItemTotal']);
				}
			}
			$output = '';
			if( $in != 0 ) {
				$output .= '<span style="display: inline-block; width: 90px;">Subtotaal: </span>€ ' . number_format ( $ex, 2, ',' , '.' ) . '<br/>';
		    	$output .= '<span style="display: inline-block; width: 90px;">BTW: </span>€ ' . number_format ( $tax, 2, ',' , '.' ) . '<br/>';
				$output .= '<span style="display: inline-block; width: 90px;">Totaal: </span>€ ' . number_format ( $in, 2, ',' , '.' ) . '<br/>';
			}

			if($private > 0){
				$output .= '<span style="display: inline-block; width: 90px;">Privé-storting: </span>€ ' . number_format ( $private, 2, ',' , '.' );
			}
			echo $output;
		}
    }
}
add_action( 'manage_invoice_posts_custom_column', 'fd_set_invoice_column_content', 10, 2 );

function fd_set_receipt_column_content( $column_name, $post_id ) {
    if ( $column_name == 'receipt_date' ) {
    	$tk = 'fd_meta_receipt_date';
		$months = array('januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');
		$tkv = get_post_meta($post_id, $tk, true );
		if($tkv){
			$tkv = explode('-', $tkv);
			echo $tkv[2] . ' ' . $months[ intval($tkv[1])-1] . ' ' . $tkv[0];
		}
    }

	if ( $column_name == 'receipt_file' ) {
		$tk = 'fd_meta_receipt_file';
		$tkv = get_post_meta($post_id, $tk, true );
		if( $tkv ) {
			echo '<a href=" ' . $tkv['url'] . '" target="_blank">' . basename($tkv['url']) . '</a>';
		}
	}

	if ( $column_name == 'receipt_price' ) {
		$tk = 'fd_meta_receipt_price_ex';
		$tkv = get_post_meta($post_id, $tk, true );
		if($tkv){
			$tkv = str_replace(',', '.',$tkv);
			echo '€ ' . number_format ( $tkv, 2, ',' , '.' );
		}
	}

	if ( $column_name == 'receipt_tax' ) {
		$tk = 'fd_meta_receipt_tax';
		$tkv = get_post_meta($post_id, $tk, true );
		if($tkv && $tkv >= 0){
			$tk = 'fd_meta_receipt_price';
			$price = get_post_meta($post_id, $tk, true );
			$price = str_replace(',', '.',$price);
			$tax = ($price / (100+$tkv)) * $tkv;
			echo '€ ' . number_format ( $tax, 2, ',' , '.' );
		}else if($tkv){
			echo 'Privé-opname';
		}
	}

}
add_action( 'manage_receipt_posts_custom_column', 'fd_set_receipt_column_content', 10, 2 );

// -----------------------------------
// Update htaccess with a list of all private files
// These files are only accessible when the user is logged in (cookie check)
// -----------------------------------
function fd_protect_files_by_mod_rewrite( $rules ) {
	$str = '';

	// get all publications that are private
	$private_publications = get_posts( array(
		'post_type' => 'receipt',
		'posts_per_page' => -1,
	) );

	// now get all correponding files and create the rewrite rules
	foreach( $private_publications as $publication ) {
		$file = get_post_meta($publication->ID, 'fd_meta_receipt_file', true );
		if ( $file ) {
			// strip out 'the site_url/wp-content' as this is unneeded in the rewrite rule condition
			$file_path = str_replace( site_url() . '/wp-content/', '', $file['url'] );
			$str .= "RewriteCond %{HTTP_COOKIE} !^.*wordpress_logged_in.*$ [NC]\n";
			$str .= "RewriteRule ".$file_path." ".site_url()." [NC,L]\n";
		}
	}

    return $rules . $str;
}
add_filter('mod_rewrite_rules', 'fd_protect_files_by_mod_rewrite');

// -----------------------------------
// Flush the rewrite rules after a publication is saved
// in order to protect private files from an unauthorized download
// -----------------------------------
function fd_flush_rewrite_rules_after_save( $post_id ) {
	if ( get_post_type( $post_id ) == 'receipt' ) {
		flush_rewrite_rules();
	}
}
add_action( 'save_post', 'fd_flush_rewrite_rules_after_save' );

add_filter( 'query_vars', 'fd_add_query_vars');
function fd_add_query_vars( $vars ){
    $vars[] = 'cyear';
    $vars[] = 'cquarter';
	return $vars;
}

// Add rules
function fd_custom_rewrite_basic() {
  add_rewrite_rule(
  	'^financieel-dashboard/jaar/([0-9]+)/kwartaal/([0-9]+)?',
  	'index.php?post_type=receipt&cyear=$matches[1]&cquarter=$matches[2]',
  	'top'
  );
  add_rewrite_rule(
  	'^financieel-dashboard/jaar/([0-9]+)/?',
  	'index.php?post_type=receipt&cyear=$matches[1]',
  	'top'
  );
  add_rewrite_rule(
  	'^financieel-dashboard/?',
  	'index.php?post_type=receipt',
  	'top'
  );
}
add_action('init', 'fd_custom_rewrite_basic');

add_action('admin_bar_menu', 'fd_toolbar_link', 40);

function fd_toolbar_link($wp_admin_bar) {
	$args = array(
        'id' => 'fd-dashboard',
		'title' => 'Financieel Dashboard',
		'href' => home_url( '/financieel-dashboard/' ),
		'meta' => array(
			'target' => '_blank',
		)
	);
	$wp_admin_bar->add_node( $args );
}

add_filter( 'post_row_actions', 'fd_remove_row_actions', 10, 2 );
function fd_remove_row_actions( $actions, $post ){
  	global $current_screen;
    if( $current_screen->post_type == 'invoice' || $current_screen->post_type == 'receipt' ){
    	unset( $actions['inline hide-if-no-js'] );
    }
    return $actions;
}

add_action( 'pre_get_posts', 'fd_order_columns' );

function fd_order_columns( $query ) {
    if( $query->is_main_query() &&
    	$query->get( 'post_type' ) == 'invoice'
	){

		$query->set( 'meta_key', 'fd_meta_invoice_num' );
	    $query->set( 'orderby',  'meta_value_num' );
	    $query->set( 'order',  'desc' );
	}

}
