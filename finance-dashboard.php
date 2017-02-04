<?php if( is_user_logged_in() && strpos($_SERVER['REQUEST_URI'],'financieel-dashboard') ) {
	$currentYear = get_query_var('cyear') != '' ? get_query_var('cyear') : date('Y');
	$currentQuarter = get_query_var('cquarter');
	$currentPage = home_url( '/financieel-dashboard/' );
	$months = array(
		'januari',
		'februari',
		'maart',
		'april',
		'mei',
		'juni',
		'juli',
		'augustus',
		'september',
		'oktober',
		'november',
		'december'
	);
	$incomePerMonth = array();
	$startMonths = array('01','04','07','10');
	$endMonths = array('03','06','09','12');
	$args = array(
		'post_type' => 'invoice',
		'posts_per_page' => -1,
	);
	if($currentQuarter != ''){
		$cq = $currentQuarter-1;
		$args['meta_query'] = array(
            array(
                'key' => 'fd_meta_invoice_date',
                'value' => $currentYear.'-'.$startMonths[$cq].'-01',
                'compare' => '>=',
                'type' => 'date',
            ),
            array(
                'key' => 'fd_meta_invoice_date',
                'value' => $currentYear.'-'.$endMonths[$cq].'-31',
                'compare' => '<=',
                'type' => 'date',
            )
        );
	}else{
		$args['meta_query'] = array(
            array(
                'key' => 'fd_meta_invoice_date',
                'value' => $currentYear.'-01-01',
                'compare' => '>=',
                'type' => 'date',
            ),
            array(
                'key' => 'fd_meta_invoice_date',
                'value' => $currentYear.'-12-31',
                'compare' => '<=',
                'type' => 'date',
            )
        );
	}
	$invoices = get_posts($args);
	$income = 0;
	foreach ($months as $month) {
		$incomePerMonth[$month][] = 0;
	}
	foreach ($invoices as $post) {
		$month = get_post_meta($post->ID, 'fd_meta_invoice_date', true );
		$month = explode('-', $month);
		$month = $months[intval($month[1])-1];
		$invoiceRows = get_post_meta($post->ID, 'invoice_rows', true );
		if($invoiceRows != ''){
			$rowCount = count($invoiceRows);
			for ( $i = 0; $i < $rowCount; $i++ ) {
				$cur_income = str_replace(',', '.', $invoiceRows[$i+1]['rowItemCosts']);
				$incomePerMonth[$month][] = $cur_income;
	    		$income = $income + $cur_income;
	    	}
		}
	}
	$income_tax = $income * 21 / 100;
	$income_total = $income + $income_tax;

	$expensesPerMonth = array();
	$args = array(
		'post_type' => 'receipt',
		'posts_per_page' => -1
	);
	if($currentQuarter != ''){
		$cq = $currentQuarter-1;
		$args['meta_query'] = array(
            array(
                'key' => 'fd_meta_receipt_date',
                'value' => $currentYear.'-'.$startMonths[$cq].'-01',
                'compare' => '>=',
                'type' => 'date',
            ),
            array(
                'key' => 'fd_meta_receipt_date',
                'value' => $currentYear.'-'.$endMonths[$cq].'-31',
                'compare' => '<=',
                'type' => 'date',
            )
        );
	}else{
		$args['meta_query'] = array(
            array(
                'key' => 'fd_meta_receipt_date',
                'value' => $currentYear.'-01-01',
                'compare' => '>=',
                'type' => 'date',
            ),
            array(
                'key' => 'fd_meta_receipt_date',
                'value' => $currentYear.'-12-31',
                'compare' => '<=',
                'type' => 'date',
            )
        );
	}
	$receipts = get_posts($args);
	$expenses = 0;
	$expenses_in = 0;
	foreach ($months as $month) {
		$expensesPerMonth[$month][] = 0;
		$expensesPerMonthIn[$month][] = 0;
	}
	foreach ($receipts as $post) {
		$month = get_post_meta($post->ID, 'fd_meta_receipt_date', true );
		$month = explode('-', $month);
		$month = $months[intval($month[1])-1];
		$cur_expense = str_replace(',', '.', get_post_meta($post->ID, 'fd_meta_receipt_price_ex', true )  );
		$cur_expense_in = str_replace(',', '.', get_post_meta($post->ID, 'fd_meta_receipt_price', true )  );
		$expensesPerMonth[$month][] = $cur_expense;
		$expensesPerMonthIn[$month][] = $cur_expense_in;
		$expenses = $expenses + $cur_expense;
		$expenses_in = $expenses_in + $cur_expense_in;
	}
	$expenses_tax = $expenses_in - $expenses;
	$expenses_total = $expenses_in;
	$tax_difference = $income_tax-$expenses_tax;
?>
<!doctype html>
<html dir="ltr" lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width" />

		<title>Financieel Dashboard</title>
		<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/foundation.min.css" />
		<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/app.css" />
	</head>
	<body>
		<div class="top-bar">
			<div class="row">
				<div class="medium-12 columns">
				  	<h1 class="menu-text">Financieel <span>Dashboard</span></h1>
				    <ul class="dropdown menu" data-dropdown-menu>
			          	<li class="has-submenu">
			          		<a href="<?php echo $currentPage .'jaar/'. $currentYear; ?>" <?php if( $currentQuarter === '' ) { echo 'class="active"'; } ?>>
			          			<?php echo $currentYear; ?>
			          		</a>
			          		<ul>
			          			<li>
			          				<a href="<?php echo $currentPage; ?>jaar/2017/">
			          					2017
			          				</a>
			          			</li>
			          			<li>
			          				<a href="<?php echo $currentPage; ?>jaar/2016/">
			          					2016
			          				</a>
			          			</li>
			          		</ul>
			          	</li>
			          	<li>
			          		<a href="<?php echo $currentPage .'jaar/'. $currentYear; ?>/kwartaal/1" <?php if( $currentQuarter == 1 ) { echo 'class="active"'; } ?>>
			          			Q1
			          		</a>
			          	</li>
			          	<li>
			          		<a href="<?php echo $currentPage .'jaar/'. $currentYear; ?>/kwartaal/2" <?php if( $currentQuarter == 2 ) { echo 'class="active"'; } ?>>
			          			Q2
			          		</a>
			          	</li>
			          	<li>
			          		<a href="<?php echo $currentPage .'jaar/'. $currentYear; ?>/kwartaal/3" <?php if( $currentQuarter == 3 ) { echo 'class="active"'; } ?>>
			          			Q3
			          		</a>
			          	</li>
			          	<li>
			          		<a href="<?php echo $currentPage .'jaar/'. $currentYear; ?>/kwartaal/4" <?php if( $currentQuarter == 4 ) { echo 'class="active"'; } ?>>
			          			Q4
			          		</a>
			          	</li>
				    </ul>
				  <?php if(get_query_var('quarter')){ ?>
				  <div class="top-bar-right">
				    <ul class="menu">
				      <li><input type="search" placeholder="Search"></li>
				      <li><button type="button" class="button">Search</button></li>
				    </ul>
				  </div>
				  <?php } ?>
				</div>
			</div>
		</div>
		<div class="dashboard">
			<div class="row">
				<div class="medium-6 columns">
					<div class="callout secondary callout-table">
					  	<h5 class="income">Inkomsten</h5>
					  	<h1 class="number">
					  		€<?php echo number_format ( $income, 2, ',' , '.' ); ?>
					  		<small>€<?php echo number_format ( $income_tax, 2, ',' , '.' ); ?></small>
					  	</h1>
					  	<table class="table">
					  		<thead>
								<tr>
									<th>Maand</th>
									<th>Inkomsten</th>
									<th>BTW</th>
								</tr>
					  		</thead>
					  		<tbody>
								<?php
									$i = 0;
									$quarter = 0;
									foreach ($incomePerMonth as $month => $value) {
										if($i%3 == 0){
											$quarter += 1;
										}
										$i++;
										if($currentQuarter === '' || $currentQuarter == $quarter){
								?>
										<tr data-quarter="<?php echo $quarter; ?>">
											<td><?php echo ucfirst($month); ?></td>
											<td>€<?php echo number_format( array_sum($value), 2, ',' , '.' ); ?></td>
											<td>€<?php echo number_format( (array_sum($value)*21/100), 2, ',' , '.' ); ?></td>
										</tr>
								<?php
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="medium-6 columns">
					<div class="callout secondary callout-table">
					  	<h5 class="expense">Uitgaven</h5>
					  	<h1 class="number">
					  		€<?php echo number_format ( $expenses, 2, ',' , '.' ); ?>
					  		<small>€<?php echo number_format ( $expenses_tax, 2, ',' , '.' ); ?></small>
					  	</h1>
					  	<table class="table">
					  		<thead>
								<tr>
									<th>Maand</th>
									<th>Uitgaven</th>
									<th>BTW</th>
								</tr>
					  		</thead>
					  		<tbody>
								<?php
									$i = 0;
									$quarter = 0;
									foreach ($expensesPerMonth as $month => $value) {
										if($i%3 == 0){
											$quarter += 1;
										}
										$i++;
										$value_in = array_sum($expensesPerMonthIn[$month]);
										if($currentQuarter === '' || $currentQuarter == $quarter){
								?>
										<tr data-quarter="<?php echo $quarter; ?>">
											<td><?php echo ucfirst($month); ?></td>
											<td>€<?php echo number_format( (array_sum($value)), 2, ',' , '.' ); ?></td>
											<td>€<?php echo number_format( ($value_in  - array_sum($value)), 2, ',' , '.' ); ?></td>
										</tr>
								<?php
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>

			<?php if($currentQuarter === ''){
				$receipt_opts = get_option('fd_options');
				if( isset($receipt_opts['year']) && (array_search($currentYear, $receipt_opts['year'])) ) {
					$starter = isset($receipt_opts['starter'][$year_exists]) ? $receipt_opts['starter'][$year_exists] : false;
					$writeoff = isset($receipt_opts['writeoff'][$year_exists]) ? $receipt_opts['writeoff'][$year_exists] : 0;
					$writeexx = isset($receipt_opts['writeoff_expl'][$year_exists]) ? $receipt_opts['writeoff_expl'][$year_exists] : '';
				}else{
					$starter = 0;
					$writeoff = 0;
					$writeexx = '';
				}
			?>
				<div class="row">
					<div class="medium-12 columns">
						<?php include_once('tax-rules/'.$currentYear.'.php'); ?>
						<?php include_once('finance-rules/'.$currentYear.'.php'); ?>
					</div>
				</div>
			<?php }else{ ?>
				<div class="row" id="inkomsten">
					<div class="medium-12 columns">
						<div class="callout secondary callout-table">
						  	<h5>Verstuurde facturen deze periode</h5>
							<table class="table">
								<thead>
									<tr>
										<th width="20%">Factuurnummer</th>
										<th width="20%">Factuurdatum</th>
										<th width="20%">Klantnaam</th>
										<th width="20%">Bedrag</th>
										<th width="20%">Status</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if(sizeof($invoices) > 0) {
										foreach ($invoices as $post) {
								?>
									<tr>
										<td><?php echo fd_set_invoice_column_content( 'invoice_number', $post->ID ); ?></td>
										<td><?php echo fd_set_invoice_column_content( 'invoice_date', $post->ID ); ?></td>
										<td><?php echo fd_set_invoice_column_content( 'invoice_client', $post->ID ); ?></td>
										<td><?php echo fd_set_invoice_column_content( 'invoice_amount', $post->ID ); ?></td>
										<td><?php echo fd_set_invoice_column_content( 'invoice_state', $post->ID ); ?></td>
									</tr>
								<?php
										}
									}else{
								?>
									<tr>
										<td colspan="5">
											<em>Geen facturen verstuurd deze periode</em>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<div class="row" id="uitgaven">
					<div class="medium-12 columns">
						<div class="callout secondary callout-table">
						  	<h5>Ontvangen facturen deze periode</h5>
							<table class="table">
								<thead>
									<tr>
										<th width="20%">Titel</th>
										<th width="20%">Factuurdatum</th>
										<th width="20%">Bedrag</th>
										<th width="20%">BTW</th>
										<th width="20%">Bijlage</th>
									</tr>
								</thead>
								<tbody>
								<?php
									if(sizeof($receipts) > 0) {
										foreach ($receipts as $post) { ?>
										<tr>
											<td><?php echo $post->post_title; ?></td>
											<td><?php echo fd_set_receipt_column_content( 'receipt_date', $post->ID ); ?></td>
											<td><?php echo fd_set_receipt_column_content( 'receipt_price', $post->ID ); ?></td>
											<td><?php echo fd_set_receipt_column_content( 'receipt_tax', $post->ID ); ?></td>
											<td><?php echo fd_set_receipt_column_content( 'receipt_file', $post->ID ); ?></td>
										</tr>
								<?php
										}
									}else{
								?>
									<tr>
										<td colspan="5">
											<em>Geen facturen ontvangen deze periode</em>
										</td>
									</tr>
								<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
			<small class="risk">
				&copy; Houke de Kwant. Gebruiken op eigen risico. Klopt er iets niet, <a href="https://github.com/houke/finance-dashboard/issues/new" target="_blank">stuur dan een issue in op GitHub</a>!
			</small>
		</div>
	</body>
</html>
<?php
	}elseif( strpos($_SERVER['REQUEST_URI'],'inkomsten') || strpos($_SERVER['REQUEST_URI'],'uitgaven') ){
		wp_redirect( home_url( '/financieel-dashboard/' ), 301 );
	}else{
	global $wp_query;
    $wp_query->set_404();
    status_header(404);
}?>