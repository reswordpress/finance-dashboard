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
    $taxPerMonth = array();
    $privateIncome = array();
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
    $income_tax = 0;
    $privateInTotal = 0;
    foreach ($months as $month) {
        $incomePerMonth[$month][] = 0;
    	$taxPerMonth[$month][] = 0;
    	$privateIncome[$month][] = 0;
    }
    foreach ($invoices as $post) {
        $month = get_post_meta($post->ID, 'fd_meta_invoice_date', true );
        $month = explode('-', $month);
        $month = $months[intval($month[1])-1];
        $invoiceRows = array_values( get_post_meta($post->ID, 'invoice_rows', true ) );

        if($invoiceRows != ''){
            $rowCount = count($invoiceRows);
            for ( $i = 0; $i < $rowCount; $i++ ) {
                if( isset( $invoiceRows[$i]['rowItemTaxNum'] ) && $invoiceRows[$i]['rowItemTaxNum'][0] == -1){
                	$curprivate = str_replace(',', '.', $invoiceRows[$i]['rowItemCosts']);
                	$privateIncome[$month][] = $curprivate;
                	$privateInTotal = $privateInTotal + $curprivate;
                }else{
	                $cur_income = str_replace(',', '.', $invoiceRows[$i]['rowItemCosts']);
	                $cur_tax = str_replace(',', '.', $invoiceRows[$i]['rowItemTax']);
	                $incomePerMonth[$month][] = $cur_income;
	                $taxPerMonth[$month][] = $cur_tax;
	                $income = $income + $cur_income;
	                $income_tax = $income_tax + $cur_tax;
                }
            }
        }
    }
    $income_total = $income + $income_tax;

    $expensesPerMonth = array();
    $expensetaxPerMonth = array();
    $privateExpenses = array();
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
    $expenses_tax = 0;
    $privateOutTotal = 0;
    foreach ($months as $month) {
        $expensesPerMonth[$month][] = 0;
        $expensesPerMonthIn[$month][] = 0;
        $expensetaxPerMonth[$month][] = 0;
    	$privateExpenses[$month][] = 0;
    }
    foreach ($receipts as $post) {
        $month = get_post_meta($post->ID, 'fd_meta_receipt_date', true );
        $month = explode('-', $month);
        $month = $months[intval($month[1])-1];
        $cur_expense = str_replace(',', '.', get_post_meta($post->ID, 'fd_meta_receipt_price_ex', true )  );
        $cur_expense_in = str_replace(',', '.', get_post_meta($post->ID, 'fd_meta_receipt_price', true )  );
        $cur_expense_tax = get_post_meta($post->ID, 'fd_meta_receipt_tax', true );
        if($cur_expense_tax == -1){
        	$privateExpenses[$month][] = $cur_expense_in;
        	$privateOutTotal = $privateOutTotal + $cur_expense_in;
        }else{
        	$expensesPerMonth[$month][] = $cur_expense;
	        $expensesPerMonthIn[$month][] = $cur_expense_in;
	        $expensetaxPerMonth[$month][] = $cur_expense_in - $cur_expense;
	        $expenses = $expenses + $cur_expense;
	        $expenses_in = $expenses_in + $cur_expense_in;
	        $expenses_tax = $expenses_tax + ($cur_expense_in - $cur_expense);
        }
    }
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
        <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/assets/foundation.min.css" />
        <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__); ?>/assets/app.css" />
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
                            <a
                            	href="<?php echo $currentPage .'jaar/'. $currentYear; ?>/kwartaal/1"
                            	<?php if( $currentQuarter == 1 ) { echo 'class="active"'; } ?>>
                                Q1
                            </a>
                        </li>
                        <li>
                            <a
                            	href="<?php echo $currentPage .'jaar/'. $currentYear; ?>/kwartaal/2"
                            	<?php if( $currentQuarter == 2 ) { echo 'class="active"'; } ?>>
                                Q2
                            </a>
                        </li>
                        <li>
                            <a
                            	href="<?php echo $currentPage .'jaar/'. $currentYear; ?>/kwartaal/3"
                            	<?php if( $currentQuarter == 3 ) { echo 'class="active"'; } ?>>
                                Q3
                            </a>
                        </li>
                        <li>
                            <a
                            	href="<?php echo $currentPage .'jaar/'. $currentYear; ?>/kwartaal/4"
                            	<?php if( $currentQuarter == 4 ) { echo 'class="active"'; } ?>
                        	>
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
                    <h5 class="income">Inkomsten <span style="float: right;">€<?php echo number_format ( ($income+$income_tax+$privateInTotal), 2, ',' , '.' ); ?></span></h5>
                        <h1 class="number">
                            €<?php echo number_format ( $income, 2, ',' , '.' ); ?>
                            <small>BTW: €<?php echo number_format ( $income_tax, 2, ',' , '.' ); ?></small>
                            <small style="margin-top: 5px;">Privé-storting: €<?php echo number_format ( $privateInTotal, 2, ',' , '.' ); ?></small>
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
                                            <td>
                                            	€<?php echo number_format( array_sum($value), 2, ',' , '.' ); ?>
                                            	<br/><span style="color: #999; ">Privé-storting: €<?php echo number_format( array_sum($privateIncome[$month] ), 2, ',' , '.' ); ?></span>
                                            </td>
                                            <td>€<?php echo number_format( array_sum($taxPerMonth[$month]), 2, ',' , '.' ); ?></td>
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
                        <h5 class="expense">Uitgaven <span style="float: right;">€<?php echo number_format ( ($expenses+$expenses_tax+$privateOutTotal), 2, ',' , '.' ); ?></span></h5>
                        <h1 class="number">
                            €<?php echo number_format ( $expenses, 2, ',' , '.' ); ?>
                            <small>BTW: €<?php echo number_format ( $expenses_tax, 2, ',' , '.' ); ?></small>
                            <small style="margin-top: 5px;">Privé-opname: €<?php echo number_format ( $privateOutTotal, 2, ',' , '.' ); ?></small>
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
                                            <td>
                                            	€<?php echo number_format( (array_sum($value)), 2, ',' , '.' ); ?>
                                            	<br/><span style="color: #999; ">Privé-opname: €<?php echo number_format( array_sum($privateExpenses[$month] ), 2, ',' , '.' ); ?></span>
                                            	</td>
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
                $receipt_opts = get_option('fd_options_receipt');
                if( isset($receipt_opts['year']) && in_array($currentYear, $receipt_opts['year']) ) {
                	$year_exists = array_search($currentYear, $receipt_opts['year']);
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
                        <?php include_once('_tax-rules/'.$currentYear.'.php'); ?>
                        <?php include_once('_finance-rules/'.$currentYear.'.php'); ?>
                    </div>
                </div>
            <?php }else{ ?>
                <div class="row" id="inkomsten">
                    <div class="medium-12 columns">
                        <div class="callout secondary callout-table">
                            <h5>Inkomsten deze periode</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="19%">Factuurnummer</th>
                                        <th width="19%">Factuurdatum</th>
                                        <th width="19%">Klantnaam</th>
                                        <th width="19%">Bedrag</th>
                                        <th width="19%">Status</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tfoot>
									<tr class="new-table">
										<td colspan="6">
											Privé-storting deze periode
										</td>
									</tr>
									<tr style="font-size: 12px;">
										<td>Datum</td>
										<td>Bedrag</td>
										<td colspan="4">Toelichting</td>
									</tr>
									<?php
										$itemtaxnumrows = 0;
	                                    if(sizeof($invoices) > 0) {
	                                        foreach ($invoices as $post) {
	                                        	$data = array_values( get_post_meta($post->ID, 'invoice_rows', true ) );
	                                        	if(isset($data[0]['rowItemTaxNum']) && $data[0]['rowItemTaxNum'][0] == -1){
	                                        		$itemtaxnumrows++;
	                                       	?>
												<tr>
													<td style="font-weight: normal;"><?php echo fd_set_invoice_column_content( 'invoice_date', $post->ID ); ?></td>
													<td style="font-weight: normal;">€ <?php echo $data[0]['rowItemCosts']; ?></td>
													<td colspan="3" style="font-weight: normal;"><?php echo $data[0]['rowItemNames']; ?></td>
			                                        <td width="5%">
			                                        	<a target="_blank" class="icon-link" href="<?php echo get_edit_post_link( $post->ID ); ?>">
			                                        		<svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z"/></svg>
			                                        	</a>
			                                        	<a target="_blank" class="icon-link" href="<?php echo get_permalink( $post->ID ); ?>">
															<svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1664 960q-152-236-381-353 61 104 61 225 0 185-131.5 316.5t-316.5 131.5-316.5-131.5-131.5-316.5q0-121 61-225-229 117-381 353 133 205 333.5 326.5t434.5 121.5 434.5-121.5 333.5-326.5zm-720-384q0-20-14-34t-34-14q-125 0-214.5 89.5t-89.5 214.5q0 20 14 34t34 14 34-14 14-34q0-86 61-147t147-61q20 0 34-14t14-34zm848 384q0 34-20 69-140 230-376.5 368.5t-499.5 138.5-499.5-139-376.5-368q-20-35-20-69t20-69q140-229 376.5-368t499.5-139 499.5 139 376.5 368q20 35 20 69z"/></svg>
			                                        	</a>
			                                        </td>
												</tr>
	                                       	<?php
	                                        	}
	                                        }
	                                    }
	                                    if($itemtaxnumrows == 0){
	                                ?>
	                                    <tr>
	                                        <td colspan="6" style="font-weight: normal;">
	                                            <em>Geen stortingen deze periode</em>
	                                        </td>
	                                    </tr>
	                                <?php
	                                    }
                                    ?>
                                </tfoot>
                                <tbody>
                                <?php
                                    if(sizeof($invoices) > 0) {
                                        foreach ($invoices as $post) {
                                        	$data = array_values( get_post_meta($post->ID, 'invoice_rows', true ) );
                                        	if(!isset($data[0]['rowItemTaxNum']) || $data[0]['rowItemTaxNum'][0] != -1){
                                ?>
		                                    <tr>
		                                        <td><?php echo fd_set_invoice_column_content( 'invoice_number', $post->ID ); ?></td>
		                                        <td><?php echo fd_set_invoice_column_content( 'invoice_date', $post->ID ); ?></td>
		                                        <td><?php echo fd_set_invoice_column_content( 'invoice_client', $post->ID ); ?></td>
		                                        <td><?php echo fd_set_invoice_column_content( 'invoice_amount', $post->ID ); ?></td>
		                                        <td><?php echo fd_set_invoice_column_content( 'invoice_state', $post->ID ); ?></td>
		                                        <td>
		                                        	<a target="_blank" class="icon-link" href="<?php echo get_edit_post_link( $post->ID ); ?>">
		                                        		<svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z"/></svg>
		                                        	</a>
		                                        	<a target="_blank" class="icon-link" href="<?php echo get_permalink( $post->ID ); ?>">
														<svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1664 960q-152-236-381-353 61 104 61 225 0 185-131.5 316.5t-316.5 131.5-316.5-131.5-131.5-316.5q0-121 61-225-229 117-381 353 133 205 333.5 326.5t434.5 121.5 434.5-121.5 333.5-326.5zm-720-384q0-20-14-34t-34-14q-125 0-214.5 89.5t-89.5 214.5q0 20 14 34t34 14 34-14 14-34q0-86 61-147t147-61q20 0 34-14t14-34zm848 384q0 34-20 69-140 230-376.5 368.5t-499.5 138.5-499.5-139-376.5-368q-20-35-20-69t20-69q140-229 376.5-368t499.5-139 499.5 139 376.5 368q20 35 20 69z"/></svg>
		                                        	</a>
		                                        </td>
		                                    </tr>
                                <?php 		}
                                        }
                                    }else{
                                ?>
                                    <tr>
                                        <td colspan="6">
                                            <em>Geen inkomsten deze periode</em>
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
                            <h5>Uitgaven deze periode</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th width="19%">Titel</th>
                                        <th width="19%">Factuurdatum</th>
                                        <th width="19%">Bedrag</th>
                                        <th width="19%">BTW</th>
                                        <th width="19%">Bijlage</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tfoot>
									<tr class="new-table">
										<td colspan="6">
											Privé-opname deze periode
										</td>
									</tr>
									<tr style="font-size: 12px;">
										<td>Datum</td>
										<td>Bedrag</td>
										<td colspan="4">Toelichting</td>
									</tr>
									<?php
										$itemtaxnumrows = 0;
	                                    if(sizeof($receipts) > 0) {
	                                        foreach ($receipts as $post) {
	                                        	if( get_post_meta( $post->ID, 'fd_meta_receipt_tax', true ) == -1 ){
                                        			$itemtaxnumrows++;
	                                       	?>
												<tr>
													<td style="font-weight: normal;"><?php echo fd_set_receipt_column_content( 'receipt_date', $post->ID ); ?></td>
													<td style="font-weight: normal;"><?php echo fd_set_receipt_column_content( 'receipt_price', $post->ID ); ?></td>
													<td style="font-weight: normal;" colspan="3"><?php echo get_post_meta( $post->ID, 'fd_meta_receipt_desc', true ); ?></td>
			                                        <td style="font-weight: normal;" width="5%">
			                                        	<a target="_blank" class="icon-link" href="<?php echo get_edit_post_link( $post->ID ); ?>">
			                                        		<svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z"/></svg>
			                                        	</a>
			                                        	<a target="_blank" class="icon-link" href="<?php echo get_permalink( $post->ID ); ?>">
															<svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1664 960q-152-236-381-353 61 104 61 225 0 185-131.5 316.5t-316.5 131.5-316.5-131.5-131.5-316.5q0-121 61-225-229 117-381 353 133 205 333.5 326.5t434.5 121.5 434.5-121.5 333.5-326.5zm-720-384q0-20-14-34t-34-14q-125 0-214.5 89.5t-89.5 214.5q0 20 14 34t34 14 34-14 14-34q0-86 61-147t147-61q20 0 34-14t14-34zm848 384q0 34-20 69-140 230-376.5 368.5t-499.5 138.5-499.5-139-376.5-368q-20-35-20-69t20-69q140-229 376.5-368t499.5-139 499.5 139 376.5 368q20 35 20 69z"/></svg>
			                                        	</a>
			                                        </td>
												</tr>
                                       	<?php
                                       			}
                                        	}
	                                    }
                                    	if($itemtaxnumrows == 0){
	                                ?>
	                                    <tr>
	                                        <td colspan="6" style="font-weight: normal;">
	                                            <em>Geen opnames deze periode</em>
	                                        </td>
	                                    </tr>
	                                <?php
	                                    }
                                    ?>
                                </tfoot>
                                <tbody>
                                <?php
                                    if(sizeof($receipts) > 0) {
                                        foreach ($receipts as $post) {
                                        	if( get_post_meta( $post->ID, 'fd_meta_receipt_tax', true ) > -1 ){
                                	?>
                                        <tr>
                                            <td><?php echo $post->post_title; ?></td>
                                            <td><?php echo fd_set_receipt_column_content( 'receipt_date', $post->ID ); ?></td>
                                            <td><?php echo fd_set_receipt_column_content( 'receipt_price', $post->ID ); ?></td>
                                            <td><?php echo fd_set_receipt_column_content( 'receipt_tax', $post->ID ); ?></td>
                                            <td><?php echo fd_set_receipt_column_content( 'receipt_file', $post->ID ); ?></td>
                                            <td>
	                                            <a target="_blank" class="icon-link" href="<?php echo get_edit_post_link( $post->ID ); ?>">
	                                        		<svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z"/></svg>
	                                        	</a>
	                                        	<a target="_blank" class="icon-link" href="<?php echo get_permalink( $post->ID ); ?>">
													<svg width="1792" height="1792" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1664 960q-152-236-381-353 61 104 61 225 0 185-131.5 316.5t-316.5 131.5-316.5-131.5-131.5-316.5q0-121 61-225-229 117-381 353 133 205 333.5 326.5t434.5 121.5 434.5-121.5 333.5-326.5zm-720-384q0-20-14-34t-34-14q-125 0-214.5 89.5t-89.5 214.5q0 20 14 34t34 14 34-14 14-34q0-86 61-147t147-61q20 0 34-14t14-34zm848 384q0 34-20 69-140 230-376.5 368.5t-499.5 138.5-499.5-139-376.5-368q-20-35-20-69t20-69q140-229 376.5-368t499.5-139 499.5 139 376.5 368q20 35 20 69z"/></svg>
	                                        	</a>
	                                        </td>
                                        </tr>
                                <?php
                                			}
                                        }
                                    }else{
                                ?>
                                    <tr>
                                        <td colspan="6">
                                            <em>Geen uitgaven deze periode</em>
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
	}
?>
