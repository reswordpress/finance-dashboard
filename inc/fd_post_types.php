<?php
// invoice post type
function fd_invoice_post_type() {
    $invoiceargs = array(
	    'public' => true,
	    'capability_type' => 'post',
	    'query_var' => true,
	    'has_archive' => true,
	    'menu_icon' => 'dashicons-money',
	    'supports' => array(
	        'title',
	        'editor' => true,
	        'excerpt' => false,
	        'trackbacks' => false,
	        'custom-fields' => true,
	        'comments' => false,
	        'revisions' => false,
	        'thumbnail' => false,
	        'author' => false,
	        'page-attributes' => false,
	    ),
	    'rewrite' => array('slug'=>'inkomsten'),
	    'labels' => array(
	        'name' => 'Inkomsten',
	        'singular_name' => 'Factuur',
	        'plural_name' => 'Facturen',
	        'all_items'             =>'Alle inkomsten',
			'add_new_item'          =>'Nieuwe factuur toevoegen',
			'add_new'               =>'Nieuwe factuur toevoegen',
			'new_item'              =>'Nieuwe factuur',
			'edit_item'             =>'Factuur bewerken',
	    )
	);
    register_post_type( 'invoice', $invoiceargs );


    $receiptargs = array(
	    'public' => true,
	    'capability_type' => 'post',
	    'query_var' => true,
	    'menu_icon' => 'dashicons-money',
	    'supports' => array( 'title' ),
	    'has_archive' => true,
	    'rewrite' => array('slug'=>'uitgaven'),
	    'labels' => array(
	        'name' => 'Uitgaven',
	        'singular_name' => 'Factuur',
	        'plural_name' => 'Facturen',
	        'all_items'             =>'Alle uitgaven',
			'add_new_item'          =>'Nieuwe uitgave toevoegen',
			'add_new'               =>'Nieuwe uitgave toevoegen',
			'new_item'              =>'Nieuwe uitgave',
			'edit_item'             =>'Uitgave bewerken',
	    )
	);
    register_post_type( 'receipt', $receiptargs );

    $clientargs = array(
	    'public' => false,
        'show_ui' => true,
	    'capability_type' => 'post',
	    'query_var' => false,
	    'has_archive' => false,
        'exclude_from_search' => true,
	    'menu_icon' => 'dashicons-groups',
        'publicly_queryable'  => false,
        'query_var'           => false,
	    'supports' => array(
	        'title',
	        'editor' => true,
	        'excerpt' => false,
	        'trackbacks' => false,
	        'custom-fields' => true,
	        'comments' => false,
	        'revisions' => false,
	        'thumbnail' => false,
	        'author' => false,
	        'page-attributes' => false,
	    ),
	    'labels' => array(
	        'name' => 'Klanten',
	        'singular_name' => 'Klant',
	        'plural_name' => 'Klanten',
	        'all_items'             =>'Alle klanten',
			'add_new_item'          =>'Nieuwe klant toevoegen',
			'add_new'               =>'Nieuwe klant toevoegen',
			'new_item'              =>'Nieuwe klant',
			'edit_item'             =>'Klant bewerken',
	    )
	);
    register_post_type( 'client', $clientargs );

}
add_action( 'init', 'fd_invoice_post_type' );
