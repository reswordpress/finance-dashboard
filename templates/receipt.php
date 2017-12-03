<?php if( is_user_logged_in() ) { ?>
<!doctype html>
<html dir="ltr" lang="en" class="no-js">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width" />

	<title><?php echo get_the_title(); ?></title>

	<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/reset.css" media="all" />
	<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/style.css" media="all" />
	<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/print.css" media="print" />
</head>
<body>
<!-- begin markup -->

<?php if ( have_posts() ) {
		while ( have_posts() ) {
			the_post(); ?>

<div id="invoice">
	<?php $options = get_option('fd_options'); ?>
	<div style="text-align: left;">
		<img src="<?php echo plugin_dir_url(__FILE__); ?>/logo.png" alt="" style="max-width: 55%;"/>
	</div>

	<div style="display: table; width: 100%; text-align: left; padding-top: 30px; min-height: 200px;">

		<div class="vcard" style="display: table-cell; vertical-align: bottom; padding-left: 100px; width: 80%;">
			<strong>Betaald op: </strong>
			<?php
				$months = array('januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december');

				$tk = 'fd_meta_receipt_date';
				$tkv = get_post_meta($post->ID, $tk, true );
				if($tkv){
					$ddate = explode('-', $tkv);
					echo $ddate[2] . ' ' . $months[ intval($ddate[1])-1] . ' ' . $ddate[0];
				}
			?>
			<br/>
			<strong>Bedrag (incl. btw):</strong>
			<?php
				$tk = 'fd_meta_receipt_price';
				$price = get_post_meta($post->ID, $tk, true );
				if($price){

					echo '€ ' . number_format ( $price, 2, ',' , '.' );
				}
			?>
			<br/>
			<?php $tk = 'fd_meta_receipt_tax'; $tkv = get_post_meta($post->ID, $tk, true ); ?>
			<strong>Af te dragen BTW <?php if($tkv){ echo '('.$tkv.'%)'; } ?>:</strong>
			<?php
				$tax = ($price / (100+$tkv)) * $tkv;
				echo '€ ' . number_format ( $tax, 2, ',' , '.' );
			?>
			<?php
				$tk = 'fd_meta_receipt_desc';
				$tkv = get_post_meta($post->ID, $tk, true );
				if( $tkv ) {
			?>
			<br/>
			<strong>Omschrijving:</strong> <?php echo $tkv; ?>
			<?php } ?>
			<?php
				$tk = 'fd_meta_receipt_file';
				$tkv = get_post_meta($post->ID, $tk, true );
				if( $tkv ) {
			?>
			<br/>
			<strong>Bijlage:</strong> <a href="<?php echo $tkv['url']; ?>" target="_blank"><?php echo basename($tkv['url']); ?></a>
			<?php } ?>
		</div><!-- e: vcard -->

		<div class="vcard" style="display: table-cell; vertical-align: bottom; padding-left: 100px; width: 80%;">
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
		</div>

		<div style="display: table-cell;">
			<div class="vcard" style="font-size: 12px; line-height: 18px;">
				<div class="org"><em><?php echo $options['company']; ?></em></div>
				<?php if($options['address1']){; ?>
				<div class="street-address"><?php echo $options['address1']; ?></div>
				<?php }; ?>
				<?php if($options['address2']){; ?>
				<div class="street-address"><?php echo $options['address2']; ?></div>
				<?php }; ?>
				<?php if($options['phone']){; ?>
				<div class="tel"><?php echo $options['phone']; ?></div>
				<?php }; ?>
				<div class="email"><?php echo $options['email']; ?></div>
				<?php if($options['kvk']){; ?>
				<div class="kvk" style="margin-top: 6px;">
					<em>KVK nr.</em>
					<br/>
					<?php echo $options['kvk']; ?>
				</div>
				<?php }; ?>
				<?php if($options['btw']){; ?>
				<div class="btw" style="margin-top: 6px;">
					<em>BTW nr.</em>
					<br/>
					<?php echo $options['btw']; ?>
				</div>
				<?php }; ?>
				<?php if($options['bank']){; ?>
				<div class="bank" style="margin-top: 6px;">
					<em>Rekening nr.</em>
					<br/>
					<?php echo $options['bank']; ?>
				</div>
				<?php }; ?>
			</div>
		</div>
	</div>

	<section class="invoice-financials">

		<div class="invoice-items">
			<?php $tk = 'fd_meta_receipt_file'; $tkv = get_post_meta($post->ID, $tk, true ); if($tkv){ ?>
			<iframe src="<?php echo $tkv['url']; ?>" style="margin-top: 20px;width: 100%;border: none;background: #FFF;height: 500px;"></iframe>
			<?php }else{ ?>
			<strong style="color: #f00;padding-left: 100px;">Let op, je hebt nog geen bijlage van deze uitgave toegevoegd</strong>
			<?php } ?>
		</div>

	</section><!-- e: invoice financials -->

</div><!-- e: invoice -->

<?php } } else{ ?>

<h1>No invoice?! yikes.</h1>

<?php } ?>


</body>
</html>
<?php }else{
	status_header( 404 );
    nocache_headers();
    include( get_query_template( '404' ) );
    die();
}?>
