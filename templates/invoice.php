<?php if( is_user_logged_in() ) { ?>
<!doctype html>
<html dir="ltr" lang="en" class="no-js">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width" />

	<title>Factuur <?php echo get_the_title(); ?></title>

	<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/reset.css" media="all" />
	<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/style.css" media="all" />
	<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/print.css" media="print" />
</head>
<body>
<!-- begin markup -->

<?php if ( have_posts() ) {  while ( have_posts() ) { the_post(); ?>
<button class="o-print-btn" onclick="window.print();">PDF</button>
<div id="invoice">
	<?php $options = get_option('fd_options'); ?>

	<div style="text-align: left; height: 0;">
		<img src="<?php echo plugin_dir_url(__FILE__); ?>/logo.png" alt="" style="max-width: 55%; max-height: 200px;"/>
	</div>

	<div style="display: table; width: 100%; text-align: left; padding-top: 30px; height: 350px">


		<div class="vcard" style="display: table-cell; vertical-align: bottom; padding-left: 5px; width: 80%;">
			<?php $tk = 'fd_meta_name';  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){?>
			<span class="fn"><?php echo $tkv; ?></span>
			<?php }; ?>

			<?php $tk = 'fd_meta_company';  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){?>
			<div class="org"><?php echo $tkv; ?></div>
			<?php }; ?>

			<div class="adr">
				<?php $tk = 'fd_meta_address_1';  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){?>
				<div class="street-address"><?php echo $tkv; ?></div>
				<?php }; ?>

				<?php $tk = 'fd_meta_address_2';  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){?>
				<div class="street-address"><?php echo $tkv; ?></div>
				<?php }; ?>
			</div>

			<?php $tk = 'fd_meta_phone';  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){?>
			<div class="tel"><?php echo $tkv; ?></div>
			<?php }; ?>
		</div><!-- e: vcard -->


		<div style="display: table-cell;">
			<div class="vcard" style="font-size: 12px; line-height: 18px;">
				<?php if( isset($options['company']) ) { ?>
				<div class="org"><em><?php echo $options['company']; ?></em></div>
				<?php }; ?>
				<?php if( isset($options['address1'])){ ?>
				<div class="street-address"><?php echo $options['address1']; ?></div>
				<?php }; ?>
				<?php if( isset($options['address2'])){; ?>
				<div class="street-address"><?php echo $options['address2']; ?></div>
				<?php }; ?>
				<?php if( isset($options['phone'])){ ?>
				<div class="tel"><?php echo $options['phone']; ?></div>
				<?php }; ?>
				<?php if( isset($options['email'])){ ?>
				<div class="email"><?php echo $options['email']; ?></div>
				<?php }; ?>
				<?php if(isset($options['kvk'])){ ?>
				<div class="kvk" style="margin-top: 6px;">
					<em>KVK nr.</em>
					<br/>
					<?php echo $options['kvk']; ?>
				</div>
				<?php }; ?>
				<?php if( isset($options['btw']) ){ ?>
				<div class="btw" style="margin-top: 6px;">
					<em>BTW nr.</em>
					<br/>
					<?php echo $options['btw']; ?>
				</div>
				<?php }; ?>
				<?php if( isset($options['bank']) ){ ?>
				<div class="bank" style="margin-top: 6px;">
					<em>Rekening nr.</em>
					<br/>
					<?php echo $options['bank']; ?>
				</div>
				<?php }; ?>
			</div><!-- e: vcard -->
		</div><!-- e invoice-from -->
	</div>

	<?php
		$months = array('januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');

	?>
	<header id="header" class="c-header" style="padding-top: 40px;">
		<dl class="invoice-meta">
			<h4>Factuur <?php if( isset( $_GET['reminder'] ) && $_GET['reminder'] == 'true' ) { ?>/ Herinnering <?php } ?></h4>
			<?php $tk = 'fd_meta_invoice_num';  $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){?>
			<dt class="invoice-number">Factuurnummer</dt>
			<dd>: <?php echo $tkv; ?></dd>
			<?php }; ?>

			<?php
				$tk = 'fd_meta_invoice_date';
				$tkv = get_post_meta($post->ID, $tk, true );
				if($tkv){
					$tkv = explode('-', $tkv);
			?>
					<dt class="invoice-date">Factuurdatum</dt>
					<dd>: <strong><?php echo $tkv[2]; ?> <?php echo $months[ intval($tkv[1])-1]; ?> <?php echo $tkv[0]; ?></strong></dd>
			<?php }; ?>

			<?php
				$tk = 'fd_meta_invoice_due';
				$tkv = get_post_meta($post->ID, $tk, true );
				if($tkv){
					$tkv = explode('-', $tkv);
			?>
					<dt class="invoice-due">Vervaldatum</dt>
					<dd>: <strong <?php if( isset( $_GET['reminder'] ) && $_GET['reminder'] == 'true' ) { ?>style="color: #f00;"<?php } ?>><?php echo $tkv[2]; ?> <?php echo $months[ intval($tkv[1])-1]; ?> <?php echo $tkv[0]; ?></strong></dd>
			<?php }; ?>
		</dl>
	</header>
	<!-- e: invoice header -->


	<?php $invoiceRows = get_post_meta($post->ID, 'invoice_rows', true ); if($invoiceRows != ''){ ?>
		<section class="invoice-financials">

			<div class="invoice-items">
				<table>
					<thead>
						<tr>
							<th>Omschrijving</th>
							<th>Aantal</th>
							<th>Bedrag</th>
						</tr>
					</thead>
					<tbody>

						<?php $rowCount = count($invoiceRows);
						$dollarAmount = (float)0;
					    for ( $i = 0; $i < $rowCount; $i++ ) { ?>
					    	<?php
					    		$dollarAmount = $dollarAmount + (float)(str_replace(',', '.', $invoiceRows[$i+1]['rowItemCosts']));
					    	?>
					        <tr>
								<th><?php echo $invoiceRows[$i+1]['rowItemNames']; ?></th>
								<td><?php echo $invoiceRows[$i+1]['rowItemCounts']; ?></td>
								<td>€ <?php echo $invoiceRows[$i+1]['rowItemCosts']; ?></td>
							</tr>
					    <?php } ?>

					</tbody>
				</table>
			</div><!-- e: invoice items -->


			<div class="invoice-totals">
				<table>
					<tbody>
						<tr>
							<th>Subtotaal: </th>
							<td>€ <?php echo number_format ( $dollarAmount, 2, ',' , '.' ); ?></td>
						</tr>
						<?php if( isset($options['tax'])){ ?>
						<tr>
							<th>BTW <?php echo $options['tax']; ?>%: </th>
							<td>€ <?php $tt = $dollarAmount * $options['tax'] / 100; echo number_format ( $tt, 2, ',' , '.' ); ?></td>
						</tr>
						<?php }; ?>
						<tr>
							<th><strong>Totaal te voldoen: </strong></th>
							<?php if( isset($options['tax'])){?>
								<td>€ <?php echo number_format ( $dollarAmount +$tt, 2, ',' , '.' ); ?></td>
							<?php } else { ?>
								<td>€ <?php echo number_format ( $dollarAmount, 2, ',' , '.' ); ?></td>
							<?php } ?>
						</tr>
					</tbody>
				</table>

			</div><!-- e: invoice totals -->


			<div class="invoice-notes" style="padding-top: 100px;">
				<table>
					<tr>
						<td>
							<?php if( isset($options['tax']) ){ ?>
								<strong>Rekeningnr.</strong>
								<?php echo $options['bank']; ?>
							<?php } ?>
						</td>
						<td>
							<strong>Factuurnummer</strong>
							<?php $tk = 'fd_meta_invoice_num';  $tkv = get_post_meta($post->ID, $tk, true ); ?>
							<?php echo $tkv; ?>
						</td>
						<td>
							<strong>Factuurbedrag</strong>
							<?php if( isset($options['tax']) ){?>
								€ <?php echo number_format ( $dollarAmount +$tt, 2, ',' , '.' ); ?>
							<?php } else { ?>
								€ <?php echo number_format ( $dollarAmount, 2, ',' , '.' ); ?>
							<?php } ?>
						</td>
					</tr>
				</table>
				<p style="text-align: center; font-size: 14px; max-width: 80%; margin: 30px auto 0; padding-bottom: 0;">
					Wij verzoeken u vriendelijk het verschuldigde bedrag binnen 14 dagen over te maken onder vermelding van het factuurnummer.
				</p>
			</div>

		</section><!-- e: invoice financials -->
	<?php } ?>

</div><!-- e: invoice -->

<?php } }else{ ?>

<h1>No invoice?! yikes.</h1>

<?php } ?>


</body>
</html>
<?php }else{
	global $wp_query;
    $wp_query->set_404();
    status_header(404);
}?>
