<?php
    add_action( 'admin_menu', 'fd_add_plugin_page' );
    add_action( 'admin_init', 'fd_page_init' );
	function fd_add_plugin_page(){
		add_submenu_page(
			'edit.php?post_type=invoice',
			'Factuur gegevens',
			'Settings',
			'manage_options',
			'fd-invoice-admin',
			'fd_create_admin_invoice_page'
		);

		add_submenu_page(
			'edit.php?post_type=receipt',
			'Settings',
			'Settings',
			'manage_options',
			'fd-receipt-admin',
			'fd_create_admin_receipt_page'
		);
	}

	function fd_create_admin_receipt_page(){
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Uitgave instellingen</h2>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'fd_option_group_receipt' );
				do_settings_sections( 'fd-receipt-admin' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	function fd_create_admin_invoice_page(){
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>Factuur instellingen</h2>
			<form method="post" action="options.php">
			<?php
				settings_fields( 'fd_option_group' );
				do_settings_sections( 'fd-invoice-admin' );
				submit_button();
			?>
			</form>
		</div>
		<?php
	}

	function fd_print_section_info(){
		print 'Voeg je bedrijfs- en persoonsgegevens toe.';
	}
	function fd_print_section_info2(){
		print 'Voeg je uitgaven details toe.';
	}

	function fd_page_init(){
		$year = date('Y');
		add_option( 'fd_invoice_number', $year.'001' );

		register_setting(
			'fd_option_group',
			'fd_options',
			'sanitize'
		);

		add_settings_section(
			'fd_settings',
			'',
			'fd_print_section_info',
			'fd-invoice-admin'
		);

		add_settings_field(
			'company_name',
			'Bedrijfsnaam',
			'CompanyCallback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'company_url',
			'Website',
			'WebCallback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'contact_name',
			'Contactpersoon',
			'ContactCallback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'contact_email',
			'Emailadres',
			'EmailCallback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'company_phone',
			'Telefoonnummer',
			'PhoneCallback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'address_line1',
			'Straat + huisnummer',
			'Address1Callback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'address_line2',
			'Postcode + woonplaats',
			'Address2Callback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'kvk',
			'Kvknummer',
			'kvkCallback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'btw',
			'BTWnummer',
			'btwCallback',
			'fd-invoice-admin',
			'fd_settings'
		);

		add_settings_field(
			'bank',
			'Rekeningnummer',
			'bankCallback',
			'fd-invoice-admin',
			'fd_settings'
		);

		register_setting(
			'fd_option_group_receipt',
			'fd_options_receipt',
			'sanitize'
		);

		add_settings_section(
			'fd_settings',
			'',
			'fd_print_section_info2',
			'fd-receipt-admin'
		);

		add_settings_field(
			'years',
			'Uitgaven per jaar',
			'YearsCallback',
			'fd-receipt-admin',
			'fd_settings'
		);
	}

	function sanitize( $input ){
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

		$st = 'years';
		if( isset( $input[$st] ) )
			$new_input[$st] = $input[$st];
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

	function CompanyCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'company';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset( $fd_options[$ft] ) ? esc_attr( $fd_options[$ft] ) : ''
		);
	}

	function TagCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'tag';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function WebCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'web';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function ContactCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'contact';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function EmailCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'email';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function Address1Callback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'address1';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function kvkCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'kvk';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function btwCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'btw';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function bankCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'bank';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function Address2Callback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'address2';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function PhoneCallback(){
		$fd_options = get_option( 'fd_options' );
		$ft = 'phone';
		printf(
			'<input style="width:400px;" type="text" id="' . $ft . '" name="fd_options[' . $ft . ']" value="%s" />',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : ''
		);
	}

	function YearsCallback(){
		$fd_options = get_option( 'fd_options_receipt' );
		$years = isset($fd_options['years']) ? $fd_options['years'] : 1;

		for ($i = 0; $i < $years; $i++) {
			$ft = 'year';
			printf(
				'<label style="width: 110px; display: inline-block;" for="' . $ft . '_'.$i.'">Jaar</label><input style="width:150px;" type="text" id="' . $ft . '_'.$i.'" name="fd_options_receipt[' . $ft . ']['.$i.']" value="%s" />',
				isset($fd_options[$ft][$i] ) ? esc_attr($fd_options[$ft][$i]) : ''
			);
			$ft = 'starter';
			printf(
				'<br/><label style="width: 110px; display: inline-block;" for="' . $ft . '_'.$i.'">Startersaftrek</label><input type="checkbox" id="' . $ft . '_'.$i.'" name="fd_options_receipt[' . $ft . ']['.$i.']" %s />',
				isset($fd_options[$ft][$i] ) ? 'checked' : 'false'
			);
			$ft = 'writeoff';
			printf(
				'<br/><label style="width: 110px; display: inline-block;" for="' . $ft . '_'.$i.'">Afschrijving</label><input style="width:150px;" type="text" id="' . $ft . '_'.$i.'" name="fd_options_receipt[' . $ft . ']['.$i.']" value="%s" placeholder="Volledige bedrag"/>',
				isset($fd_options[$ft][$i] ) ?$fd_options[$ft][$i] : ''
			);
			$ft = 'writeoff_expl';
			printf(
				'<br/><label style="width: 110px; display: inline-block;vertical-align:top;" for="' . $ft . '_'.$i.'">Toelichting</label><textarea style="width:250px;height:100px;" id="' . $ft . '_'.$i.'" name="fd_options_receipt[' . $ft . ']['.$i.']" placeholder="Elk bedrag/toelichting op een nieuwe regel">%s</textarea><br/><br/>',
				isset($fd_options[$ft][$i] ) ?$fd_options[$ft][$i] : ''
			);
		}
		$ft = 'years';
		printf(
			'<br/><label style="width: 110px; display: inline-block;" for="' . $ft . '_'.$i.'">Aantal jaar</label><input style="width:260px;" type="number" id="' . $ft . '" name="fd_options_receipt[' . $ft . ']" value="%s" /><br/><small>Wil je een extra jaar toevoegen, verhoog het nummer met 1,<br/> sla op en een nieuwe sectie verschijnt</small>',
			isset($fd_options[$ft] ) ? esc_attr($fd_options[$ft]) : 1
		);
	}
