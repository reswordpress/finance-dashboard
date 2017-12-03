<?php
// save meta boxes
function fd_meta_save( $post_id ) {

    // verify savability (it's a word, you like it)
    $autosave = wp_is_post_autosave( $post_id );
    $revision = wp_is_post_revision( $post_id );
    $nonce_valid = ( isset( $_POST[ 'fd_nonce' ] ) && wp_verify_nonce( $_POST[ 'fd_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    if ( $autosave || $revision || !$nonce_valid ) {
        return;
    }

    // seems fine, let's save the easy ones
    //box 001
    $tm = 'fd_meta_invoice_status';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_invoice_num';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_invoice_date';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_invoice_due';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }

    // box 002
    $tm = 'fd_meta_name';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_email';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_company';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_address_1';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_address_2';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_phone';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }

    // box 004
    $tm = 'fd_meta_notes';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, $_POST[ $tm ] );
    }

    // fd_meta_receipt
    $tm = 'fd_meta_receipt_date';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_receipt_price';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_receipt_price_ex';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_receipt_tax';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }
    $tm = 'fd_meta_receipt_file';
    if(!empty($_FILES[$tm]['name'])) {
        // Setup the array of supported file types. In this case, it's just PDF.
        $supported_types = array('application/pdf', 'image/jpeg', 'image/png');

        // Get the file type of the upload
        $arr_file_type = wp_check_filetype(basename($_FILES[$tm]['name']));
        $uploaded_type = $arr_file_type['type'];

        // Check if the type is supported. If not, throw an error.
        if(in_array($uploaded_type, $supported_types)) {

            // Use the WordPress API to upload the file
            $upload = wp_upload_bits($_FILES[$tm]['name'], null, file_get_contents($_FILES[$tm]['tmp_name']));

            if(isset($upload['error']) && $upload['error'] != 0) {
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
            } else {
                add_post_meta($post_id, $tm, $upload);
                update_post_meta($post_id, $tm, $upload);
            }
        } else {
            wp_die("The file type that you've uploaded is not a PDF.");
        }
    }
    $tm = 'fd_meta_receipt_desc';
    if( isset( $_POST[ $tm ] ) ) {
        update_post_meta( $post_id, $tm, sanitize_text_field( $_POST[ $tm ] ) );
    }

    // now let's save the harder ones
    $rowItemNames = isset($_POST['rowname']) ? $_POST['rowname'] : "";
    $rowItemCounts = isset($_POST['rowcount']) ? $_POST['rowcount'] : "";
    $rowItemCosts = isset($_POST['rowcost']) ? $_POST['rowcost'] : "";
    $rowItemTax = isset($_POST['rowtax']) ? $_POST['rowtax'] : "";
    $rowItemTaxNum = isset($_POST['rowtaxnum']) ? array_values($_POST['rowtaxnum']) : "";
    $rowItemTotal = isset($_POST['rowtotal']) ? $_POST['rowtotal'] : "";


    $oldRows = get_post_meta($post_id, 'invoice_rows', true);
    $newRows = array();

    $rowCount = count($rowItemNames);

    for ( $i = 0; $i < $rowCount; $i++ ) {
        if($rowItemNames != ''){
            $newRows[$i]['rowItemNames'] = stripslashes(strip_tags($rowItemNames[$i]));
            $newRows[$i]['rowItemCounts'] = stripslashes(strip_tags($rowItemCounts[$i]));
            $newRows[$i]['rowItemCosts'] = stripslashes(strip_tags($rowItemCosts[$i]));
            $newRows[$i]['rowItemTax'] = stripslashes(strip_tags($rowItemTax[$i]));
            $newRows[$i]['rowItemTaxNum'] = $rowItemTaxNum[$i];
            $newRows[$i]['rowItemTotal'] = stripslashes(strip_tags($rowItemTotal[$i]));
        }
    }

    if($newRows != $oldRows && !empty($newRows)){
        update_post_meta($post_id, 'invoice_rows', $newRows);
    } else if(empty($newRows) && !empty($oldRows)){
        delete_post_meta($post_id, 'invoice_rows', $oldRows);
    };

}
add_action( 'save_post', 'fd_meta_save' );

function fd_update_edit_form() {
    echo ' enctype="multipart/form-data"';
}
add_action('post_edit_form_tag', 'fd_update_edit_form');
