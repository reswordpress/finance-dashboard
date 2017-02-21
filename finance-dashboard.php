<?php
/*
Plugin Name: Financieel dashboard
Plugin Script: finance.php
Description: Financieel dashboard
Version: 1.1
Author: Houke de Kwant
Author URI: http://thearthunters.com
License: GPLv2 or later
GitHub Plugin URI: https://github.com/houke/finance-dashboard
GitHub Branch: master
*/


class finaceDashboard
{
    private $options;

    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'fd_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'fd_page_init' ) );
    }


    public function fd_add_plugin_page()
    {
        add_submenu_page(
			'edit.php?post_type=invoice',
			'Settings',
			'Settings',
			'manage_options',
			'fd-invoice-admin',
			array( $this, 'fd_create_admin_invoice_page' )
		);

		add_submenu_page(
			'edit.php?post_type=receipt',
			'Settings',
			'Settings',
			'manage_options',
			'fd-receipt-admin',
			array( $this, 'fd_create_admin_receipt_page' )
		);
    }

    public function fd_create_admin_receipt_page(){
    	$this->options = get_option( 'fd_options_receipt' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Settings</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'fd_option_group_receipt' );
                do_settings_sections( 'fd-receipt-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function fd_create_admin_invoice_page(){

        $this->options = get_option( 'fd_options' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Settings</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'fd_option_group' );
                do_settings_sections( 'fd-invoice-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    public function fd_print_section_info(){
        print 'Voeg je bedrijfs- en persoonsgegevens toe.';
    }
    public function fd_print_section_info2(){
        print 'Voeg je uitgaven details toe.';
    }

    public function fd_page_init()
    {
        register_setting(
            'fd_option_group',
            'fd_options',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'fd_settings',
            '',
            array( $this, 'fd_print_section_info' ),
            'fd-invoice-admin'
        );

        add_settings_field(
            'company_name',
            'Bedrijfsnaam',
            array( $this, 'CompanyCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'company_url',
            'Website',
            array( $this, 'WebCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'contact_name',
            'Contactpersoon',
            array( $this, 'ContactCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'contact_email',
            'Emailadres',
            array( $this, 'EmailCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'company_phone',
            'Telefoonnummer',
            array( $this, 'PhoneCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'address_line1',
            'Straat + huisnummer',
            array( $this, 'Address1Callback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'address_line2',
            'Postcode + woonplaats',
            array( $this, 'Address2Callback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'kvk',
            'Kvknummer',
            array( $this, 'kvkCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'btw',
            'BTWnummer',
            array( $this, 'btwCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'bank',
            'Rekeningnummer',
            array( $this, 'bankCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        add_settings_field(
            'sales_tax',
            'BTW in %',
            array( $this, 'TaxCallback' ),
            'fd-invoice-admin',
            'fd_settings'
        );

        register_setting(
            'fd_option_group_receipt',
            'fd_options_receipt',
            array( $this, 'sanitize' )
        );

        add_settings_section(
            'fd_settings',
            '',
            array( $this, 'fd_print_section_info2' ),
            'fd-receipt-admin'
        );

        add_settings_field(
            'years',
            'Uitgaven per jaar',
            array( $this, 'YearsCallback' ),
            'fd-receipt-admin',
            'fd_settings'
        );
    }

    public function sanitize( $input )
    {
        $new_input = array();

        $st = 'company';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'tag';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'web';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'contact';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'email';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'address1';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'address2';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'kvk';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'btw';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'bank';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'phone';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'tax';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );

        $st = 'years';
        if( isset( $input[$st] ) )
            $new_input[$st] = sanitize_text_field( $input[$st] );
        $st = 'year';
        if( isset( $input[$st] ) )
            $new_input[$st] = $input[$st];
        $st = 'starter';
        if( isset( $input[$st] ) )
            $new_input[$st] = $input[$st];
        $st = 'writeoff';
        if( isset( $input[$st] ) )
            $new_input[$st] = $input[$st];
        $st = 'writeoff_expl';
        if( isset( $input[$st] ) )
            $new_input[$st] = $input[$st];

        return $new_input;
    }

    public function CompanyCallback(){
        $ft = 'company';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function TagCallback(){
        $ft = 'tag';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function WebCallback(){
        $ft = 'web';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function ContactCallback(){
        $ft = 'contact';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function EmailCallback(){
        $ft = 'email';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function Address1Callback(){
        $ft = 'address1';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function kvkCallback(){
        $ft = 'kvk';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function btwCallback(){
        $ft = 'btw';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function bankCallback(){
        $ft = 'bank';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function Address2Callback(){
        $ft = 'address2';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function PhoneCallback(){
        $ft = 'phone';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function TaxCallback(){
        $ft = 'tax';
        printf(
            '<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
            isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : ''
        );
    }

    public function YearsCallback(){
    	$years = isset($this->options['years']) ? $this->options['years'] : 1;

    	for ($i = 0; $i < $years; $i++) {
	        $ft = 'year';
	        printf(
	            '<label style="width: 110px; display: inline-block;" for="' . $ft . '_'.$i.'">Jaar</label><input style="width:150px;" type="text" id="' . $ft . '_'.$i.'" name="fd_options_receipt[' . $ft . ']['.$i.']" value="%s" />',
	            isset( $this->options[$ft][$i] ) ? esc_attr( $this->options[$ft][$i]) : ''
	        );
	        $ft = 'starter';
	        printf(
	        	'<br/><label style="width: 110px; display: inline-block;" for="' . $ft . '_'.$i.'">Startersaftrek</label><input type="checkbox" id="' . $ft . '_'.$i.'" name="fd_options_receipt[' . $ft . ']['.$i.']" %s />',
	            isset( $this->options[$ft][$i] ) ? 'checked' : 'false'
	        );
	        $ft = 'writeoff';
	        printf(
	        	'<br/><label style="width: 110px; display: inline-block;" for="' . $ft . '_'.$i.'">Afschrijving</label><input style="width:150px;" type="text" id="' . $ft . '_'.$i.'" name="fd_options_receipt[' . $ft . ']['.$i.']" value="%s" placeholder="Volledige bedrag"/>',
	            isset( $this->options[$ft][$i] ) ? $this->options[$ft][$i] : ''
	        );
	        $ft = 'writeoff_expl';
	        printf(
	        	'<br/><label style="width: 110px; display: inline-block;vertical-align:top;" for="' . $ft . '_'.$i.'">Toelichting</label><textarea style="width:250px;height:100px;" id="' . $ft . '_'.$i.'" name="fd_options_receipt[' . $ft . ']['.$i.']" placeholder="Elk bedrag/toelichting op een nieuwe regel">%s</textarea><br/><br/>',
	            isset( $this->options[$ft][$i] ) ? $this->options[$ft][$i] : ''
	        );
	    }
	    $ft = 'years';
	    printf(
	    	'<br/><label style="width: 110px; display: inline-block;" for="' . $ft . '_'.$i.'">Aantal jaar</label><input style="width:260px;" type="number" id="' . $ft . '" name="fd_options_receipt[' . $ft . ']" value="%s" /><br/><small>Wil je een extra jaar toevoegen, verhoog het nummer met 1,<br/> sla op en een nieuwe sectie verschijnt</small>',
	        isset( $this->options[$ft] ) ? esc_attr( $this->options[$ft]) : 1
	    );
    }

}

if(is_admin()){
    $finaceDashboard = new finaceDashboard();
}


// invoice post type
function fd_invoice_post_type() {
    $args = array(
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
	    )
	);
    register_post_type( 'invoice', $args );


    $args = array(
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
	    )
	);
    register_post_type( 'receipt', $args );
}
add_action( 'init', 'fd_invoice_post_type' );


// invoice meta boxes
/**
 * Adds a meta box to the post editing screen
 */

function fd_callback_001(){
    global $post; ?>
    <table width='100%'>
    <tr>
        <?php $tk = 'fd_meta_invoice_status'; ?>
        <td style='font-size: 13px; width:200px;'><label>Factuur status</label></td>
        <td><?php  $tkv = get_post_meta($post->ID, $tk, true );?>
            <div>
                <input type='radio' name='<?php echo $tk; ?>' value='onbetaald' <?php if($tkv == 'onbetaald'){echo 'checked'; }; ?>><span style='font-size: 13px;'>Onbetaald</span> /
                <input type='radio' name='<?php echo $tk; ?>' value='betaald' <?php if($tkv == 'betaald'){echo 'checked'; }; ?>><span style='font-size: 13px;'>Betaald</span>
            </div>
        </td>
    </tr>
    <tr>
        <?php $tk = 'fd_meta_invoice_num'; ?>
        <td style='font-size: 13px;'><label>Factuur nummer</label></td>
        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>
    <tr>
        <?php $tk = 'fd_meta_invoice_date'; ?>
        <td style='font-size: 13px;'><label>Factuur datum</label></td>
        <td><input style='width: 100%;' type="date" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>
    <tr>
        <?php $tk = 'fd_meta_invoice_due'; ?>
        <td style='font-size: 13px;'><label>Factuur einddatum</label></td>
        <td><input style='width: 100%;' type="date" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>
    </table>

<?php }
function fd_callback_002(){
    global $post;?>
    <table width='100%'>
    <tr>
        <?php $tk = 'fd_meta_name'; ?>
        <td style='font-size: 13px; width:200px;'><label>Naam</label></td>
        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>

    <tr>
        <?php $tk = 'fd_meta_company'; ?>
        <td style='font-size: 13px;'><label>Bedrijfsnaam</label></td>
        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>

    <tr>
        <?php $tk = 'fd_meta_email'; ?>
        <td style='font-size: 13px;'><label>Email</label></td>
        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>

    <tr>
        <?php $tk = 'fd_meta_phone'; ?>
        <td style='font-size: 13px;'><label>Telefoonnummer</label></td>
        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>

    <tr>
        <?php $tk = 'fd_meta_address_1'; ?>
        <td style='font-size: 13px;'><label>Straat + huisnummer</label></td>
        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>

    <tr>
        <?php $tk = 'fd_meta_address_2'; ?>
        <td style='font-size: 13px;'><label>Postcode + woonplaats</label></td>
        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
    </tr>
    </table>

    <?php }

function fd_callback_003(){
    global $post;
    $invoiceRows = get_post_meta($post->ID, 'invoice_rows', true);

    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#new-row').click(function(){
            var theRow = $('.default-row').clone(true);
            theRow.removeClass('default-row');
            theRow.addClass('invoice-row');
            //$('.default-row td input').val();
            theRow.insertAfter('#invoice-items tbody tr:last-of-type');
            theRow.css('display', '');
        });

        $('.remove-row').click(function(){
            $(this).parents('tr').remove();
        });

        $('[data-ex]').on('blur', function(){
        	var price_ex = vf( jQuery(this).val() );
        	var taxnum = 21;
        	var price_in = ((price_ex/100)*(100+taxnum)).toFixed(2);
        	var tax = ((price_ex/100)*taxnum).toFixed(2);
        	price_ex = price_ex.toFixed(2).toString();

        	$(this).parents('tr').find('[data-in]').val( price_in.replace(".", ",") );
        	$(this).parents('tr').find('[data-tax]').val( tax.replace(".", ",") );
        	$(this).val( price_ex.replace(".", ",") ) ;
        });

        $('[data-in]').on('blur', function(){
        	var price_in = vf(jQuery(this).val());
        	var taxnum = 21;
        	var price_ex = ((price_in/(100+taxnum))*100).toFixed(2);
        	var tax = ((price_in/(100+taxnum))*taxnum).toFixed(2);
        	price_in = price_in.toFixed(2).toString();

        	$(this).parents('tr').find('[data-ex]').val( price_ex.replace(".", ",") );
        	$(this).parents('tr').find('[data-tax]').val( tax.replace(".", ",") );
        	$(this).val( price_in.replace(".", ",") );
        });

        function vf(str) {
            str = str.toString();
            return parseFloat(str.replace(".", "").replace(",", "."));
        }
    });

    </script>

    <table id='invoice-items' width='100%' style='text-align: left; font-size: 12px'>
    <thead width='100%'>
        <tr>
            <th style='width: 60% !important;'	>Naam</th>
            <th>Aantal</th>
            <th>Prijs (excl BTW)</th>
            <th>BTW (21%)</th>
            <th>Prijs (incl BTW)</th>
            <th></th>
        </tr>
    </thead>

    <tbody width='100%'>


        <tr class='default-row' style='display: none'><!-- default row -->
            <td style='width: 60% !important;'><input style='width: 100%' type='text' value='' name='rowname[]' placeholder='Omschrijving' /></td>
            <td style='width: 10% !important;'><input type='text' value='' name='rowcount[]' placeholder='Aantal' /></td>
            <td style='width: 5% !important;'><input type='text' value='' name='rowcost[]' placeholder='Bedrag' data-ex/></td>
            <td style='width: 10% !important;'><input readonly type='text' value='' name='rowtax[]' placeholder='BTW' data-tax/></td>
            <td style='width: 10% !important;'><input type='text' value='' name='rowtotal[]' placeholder='Bedrag' data-in/></td>
            <td style='width: 5% !important;'><a href='#deleterow' class='remove-row' style='display: inline-block; padding: 0 4px; color: #fff; background: red;'>X</a></td>
        </tr>

        <?php if($invoiceRows){
            foreach($invoiceRows as $invoiceRow){?>
            <tr class='existing-row'>
                <td style='width: 70% !important;'><input style='width: 100%' type='text' value='<?php if($invoiceRow['rowItemNames'] != '') echo esc_attr( $invoiceRow['rowItemNames'] ); ?>' name='rowname[]'/></td>
                <td><input type='text' value='<?php if($invoiceRow['rowItemCounts'] != '') echo esc_attr( $invoiceRow['rowItemCounts'] ); ?>' name='rowcount[]'/></td>
                <td><input type='text' value='<?php if($invoiceRow['rowItemCosts'] != '') echo esc_attr( $invoiceRow['rowItemCosts'] ); ?>' name='rowcost[]' data-ex/></td>
                <td><input type='text' value='<?php if($invoiceRow['rowItemTax'] != '') echo esc_attr( $invoiceRow['rowItemTax'] ); ?>' name='rowtax[]' readonly data-tax/></td>
                <td><input type='text' value='<?php if($invoiceRow['rowItemTotal'] != '') echo esc_attr( $invoiceRow['rowItemTotal'] ); ?>' name='rowtotal[]' data-in/></td>
                <td><a href='#deleterow' class='remove-row' style='display: inline-block; padding: 0 4px; color: #fff; background: red;'>X</a></td>
            </tr>
            <?php }; ?>
        <?php }; ?>



    </tbody>

    <tfoot width='100%'>
        <tr>
            <td style='width: 70% !important;'><a href='#addrow' id='new-row'>Nieuw item toevoegen</a></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tfoot>
    </table>
<?php }

function fd_callback_004(){
    global $post;
    $tk = 'fd_meta_notes';
    $tkv = get_post_meta($post->ID, $tk, true );
    if( !$tkv ) {
    	$tkv = '';
    }
?>
    <table width='100%'>
     <tr>
        <td style='font-size: 13px;'>
            <textarea style='width: 100%' rows='8' name='<?php echo $tk; ?>'><?php echo $tkv; ?></textarea>
        </td>
    </tr>
    </table>

<?php }

function fd_callback_005(){
    global $post; ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('[data-ex-out]').on('blur', function(){
        	var taxnum = parseInt(jQuery('input[name="fd_meta_receipt_tax"]:checked').val());
        	var price_ex = vf( jQuery(this).val() );
        	var price_in = ((price_ex/100)*(100+taxnum)).toFixed(2);
        	var tax = ((price_ex/100)*taxnum).toFixed(2);
        	price_ex = price_ex.toFixed(2).toString();

        	$('[data-in-out]').val( price_in.replace(".", ",") );
        	$(this).val( price_ex.replace(".", ",") ) ;
        });

        $('[data-in-out]').on('blur', function(){
        	var taxnum = parseInt(jQuery('input[name="fd_meta_receipt_tax"]:checked').val());
        	var price_in = vf(jQuery(this).val());
        	var price_ex = ((price_in/(100+taxnum))*100).toFixed(2);
        	var tax = ((price_in/(100+taxnum))*taxnum).toFixed(2);
        	price_in = price_in.toFixed(2).toString();

        	$('[data-ex-out]').val( price_ex.replace(".", ",") );
        	$(this).val( price_in.replace(".", ",") );
        });

        $('input[name="fd_meta_receipt_tax"]').on('change', function(){
        	if( $('[data-ex-out]').val() != '' ){
        		$('[data-ex-out]').blur();
        	}else{
        		$('[data-in-out]').blur();
        	}
        });

        function vf(str) {
        	str = str.toString();
		   	return parseFloat(str.replace(".", "").replace(",", "."));
		}
	});
	</script>
    <table width='100%'>
	    <tr>
	        <?php $tk = 'fd_meta_receipt_date'; ?>
	        <td style='font-size: 13px;'><label>Factuur datum</label></td>
	        <td><input style='width: 100%;' type="date" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?>" /></td>
	    </tr>
	    <tr>
	        <?php $tk = 'fd_meta_receipt_price_ex'; ?>
	        <td style='font-size: 13px;'><label>Betaald bedrag (excl. BTW)</label></td>
	        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; }else{ echo '0'; };?>" data-ex-out/></td>
	    </tr>
	    <tr>
	        <?php $tk = 'fd_meta_receipt_tax'; ?>
	        <td style='font-size: 13px; width:200px;'><label>BTW percentage</label></td>
	        <td><?php  $tkv = get_post_meta($post->ID, $tk, true );?>
	            <div>
	                <input type='radio' name='<?php echo $tk; ?>' value='6' <?php if($tkv == '6'){echo 'checked'; }; ?> /><span style='font-size: 13px;'>6%</span> /
	                <input type='radio' name='<?php echo $tk; ?>' value='21' <?php if($tkv == '21' || $tkv == ''){echo 'checked'; }; ?> /><span style='font-size: 13px;'>21%</span>
	            </div>
	        </td>
	    </tr>
	    <tr>
	        <?php $tk = 'fd_meta_receipt_price'; ?>
	        <td style='font-size: 13px;'><label>Betaald bedrag (incl. BTW)</label></td>
	        <td><input style='width: 100%;' type="text" name="<?php echo $tk; ?>" value="<?php  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; }else{ echo '0'; };?>" data-in-out/></td>
	    </tr>
	    <tr>
	        <?php $tk = 'fd_meta_receipt_file'; $tkv = get_post_meta($post->ID, $tk, true ); ?>
	        <td style='font-size: 13px; width:200px;'><label>Bijlage</label></td>
	        <td>
	        	<?php if($tkv){ echo '<small><a href="'.$tkv['url'].'" target="_blank">'.basename($tkv['url']).'</a></small>'; } ?>
	        	<input style='width: 100%;' type="file" name="<?php echo $tk; ?>" value="" />
	        </td>
	    </tr>
	    <tr>
	        <?php $tk = 'fd_meta_receipt_desc'; ?>
	        <td style='font-size: 13px;'><label>Factuur omschrijving</label></td>
	        <td><textarea style='width: 100%;' rows="4" name="<?php echo $tk; ?>"><?php $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){echo $tkv; };?></textarea></td>
	    </tr>
    </table>

<?php }

function fd_meta(){
    add_meta_box( 'fd_metaID_001', 'Administratie', 'fd_callback_001', 'invoice' );
    add_meta_box( 'fd_metaID_002', 'Klantgegevens', 'fd_callback_002', 'invoice' );
    add_meta_box( 'fd_metaID_003', 'Factuuritems', 'fd_callback_003', 'invoice' );
    add_meta_box( 'fd_metaID_004', 'Afspraken', 'fd_callback_004', 'invoice' );
    add_meta_box( 'fd_metaID_005', 'Administratie', 'fd_callback_005', 'receipt' );

    wp_nonce_field( basename( __FILE__ ), 'fdNonce' );
}
add_action( 'add_meta_boxes', 'fd_meta' );


// save meta boxes
function fd_meta_save( $post_id ) {

    // verify savability (it's a word, you like it)
    $autosave = wp_is_post_autosave( $post_id );
    $revision = wp_is_post_revision( $post_id );
    $nonce_valid = ( isset( $_POST[ 'fdNonce' ] ) && wp_verify_nonce( $_POST[ 'fdNonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
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
    $rowItemTotal = isset($_POST['rowtotal']) ? $_POST['rowtotal'] : "";

    $oldRows = get_post_meta($post_id, 'invoice_rows', true);
    $newRows = array();

    $rowCount = count($rowItemNames);

    for ( $i = 1; $i < $rowCount; $i++ ) {
        if($rowItemNames != ''){
            $newRows[$i]['rowItemNames'] = stripslashes(strip_tags($rowItemNames[$i]));
            $newRows[$i]['rowItemCounts'] = stripslashes(strip_tags($rowItemCounts[$i]));
            $newRows[$i]['rowItemCosts'] = stripslashes(strip_tags($rowItemCosts[$i]));
            $newRows[$i]['rowItemTax'] = stripslashes(strip_tags($rowItemTax[$i]));
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


// show correct template
function fd_get_custom_post_type_template($template) {
     global $post;

     if ($post->post_type == 'invoice') {
          $template = fd_custom_search_tpl('invoice');
     }
     if ($post->post_type == 'receipt') {
          $template = fd_custom_search_tpl('receipt');
     }
     if ( is_post_type_archive ( 'invoice' ) || is_post_type_archive ( 'receipt' ) ) {
          $template = dirname( __FILE__ ) . '/tpl-dashboard.php';
     }
     return $template;
}
add_filter( 'single_template', 'fd_get_custom_post_type_template' );
add_filter( 'archive_template', 'fd_get_custom_post_type_template' ) ;

function fd_custom_search_tpl( $template ) {
	$priority_template_lookup = array(
		get_stylesheet_directory() . '/templates/search.php',
		get_template_directory() . '/templates/search.php',
		plugin_dir_path( __FILE__ ) . 'templates/'.$template.'.php',
	);

	foreach ( $priority_template_lookup as $exists ) {
		if ( file_exists( $exists ) ) {
			return $exists;
			exit;
		}
	}
    return $template;
}

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
  $columns['invoice_client'] = 'invoice_client';
  $columns['invoice_amount'] = 'invoice_amount';
  $columns['invoice_state'] = 'invoice_state';
  return $columns;
}
add_filter( 'manage_edit-invoice_sortable_columns', 'fd_make_sortable_column' );

function fd_make_sortable_column_receipt($columns){
  $columns['receipt_date'] = 'receipt_date';
  $columns['receipt_file'] = 'receipt_file';
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
    	$invoiceRows = get_post_meta($post_id, 'invoice_rows', true );
		$rowCount = count($invoiceRows);
		if( $invoiceRows ) {
			$dollarAmount = 0;
			for ( $i = 0; $i < $rowCount; $i++ ) {
				$tmp = str_replace(',', '.', $invoiceRows[$i+1]['rowItemCosts']);
				$dollarAmount = $dollarAmount + $tmp;
			}
			$tt = $dollarAmount * 21 / 100;
			$output = '<span style="display: inline-block; width: 80px;">Subtotaal: </span>€ ' . number_format ( $dollarAmount, 2, ',' , '.' ) . '<br/>';
	    	$output .= '<span style="display: inline-block; width: 80px;">BTW: </span>€ ' . number_format ( $tt, 2, ',' , '.' ) . '<br/>';
			$output .= '<span style="display: inline-block; width: 80px;">Totaal: </span>€ ' . number_format ( $dollarAmount +$tt, 2, ',' , '.' );
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
		if($tkv){
			$tk = 'fd_meta_receipt_price';
			$price = get_post_meta($post_id, $tk, true );
			$price = str_replace(',', '.',$price);
			$tax = ($price / (100+$tkv)) * $tkv;
			echo '€ ' . number_format ( $tax, 2, ',' , '.' );
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
