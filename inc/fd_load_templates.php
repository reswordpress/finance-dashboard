<?php
// show correct template
function fd_get_custom_post_type_template($template) {
     global $post;

     if ($post->post_type == 'invoice') {
          $template = fd_custom_tpl('invoice');
     }
     if ($post->post_type == 'receipt') {
          $template = fd_custom_tpl('receipt');
     }
     if ( is_post_type_archive ( 'invoice' ) || is_post_type_archive ( 'receipt' ) ) {
          $template = FD_DIR . 'tpl-dashboard.php';
     }
     return $template;
}
add_filter( 'single_template', 'fd_get_custom_post_type_template' );
add_filter( 'archive_template', 'fd_get_custom_post_type_template' ) ;

function fd_custom_tpl( $template ) {
    $priority_template_lookup = array(
        get_stylesheet_directory() . '/templates/'.$template.'.php',
        get_template_directory() . '/templates/'.$template.'.php',
        FD_DIR . 'templates/'.$template.'.php',
    );

    foreach ( $priority_template_lookup as $exists ) {
        if ( file_exists( $exists ) ) {
            return $exists;
            exit;
        }
    }
    return $template;
}
