<?php
/**
 * Adds a meta box to the post editing screen
 */
function fd_invoice_details(){
	global $post;
?>
	<table width='100%'>
		<tr>
			<?php
				$tk = 'fd_meta_invoice_status';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px; width:200px;'>
				<label>Factuur status</label>
			</td>
			<td>
				<div>
					<input
						type='radio'
						name='<?php echo $tk; ?>'
						value='onbetaald'
						<?php if($tkv == 'onbetaald'){echo 'checked'; }; ?>
					/>
					<span style='font-size: 13px; margin-right: 10px;'>Onbetaald</span>
					<input
						type='radio'
						name='<?php echo $tk; ?>'
						value='betaald'
						<?php if($tkv == 'betaald'){echo 'checked'; }; ?>
					/>
					<span style='font-size: 13px;'>Betaald</span>
				</div>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_invoice_num';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Factuur nummer</label>
			</td>
			<td>
				<div style="width: 75%; float: left; position: relative;" class="invoicenumber">
					<input
						readonly
						style='width: 100%;'
						type="number"
						name="<?php echo $tk; ?>"
						value="<?php if($tkv){echo $tkv; };?>"
					/>
					<button style="border: none; background: none; outline: none; cursor: pointer; font-size: 12px; position: absolute; right: 5px; top: 5px; text-decoration: underline;" data-manual-number>Handmatig aanpassen</button>
				</div>
    			<buttom style="width: 23%; float: right; text-align: center;" data-next-number class="button">Nummer toevoegen</buttom>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_invoice_date';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Factuur datum</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					type="date"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){echo $tkv; };?>"
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_invoice_due';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Factuur einddatum</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					type="date"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){echo $tkv; };?>"
				/>
			</td>
		</tr>
	</table>

<?php }
function fd_client_details(){
	global $post;
	$posttype = get_post_type( $post );
?>
	<table width='100%'>
	<?php if( $posttype != 'client' ) { ?>
		<tr>
			<td style='font-size: 13px; width:200px;'>
				<label>Zoeken:</label>
			</td>
			<td>
				<div style="position: relative;">
					<input
						type="search"
						style="width: 60%;"
						data-search-client
					/>
					<a
						href="<?php echo admin_url( 'edit.php?post_type=client' ); ?>"
						style="margin-left: 10px; font-size: 12px;"
						target="_blank"
					>
						Klanten beheren
					</a>
					<ul
						class="data-search-client-list"
						style="display: none; margin: 0; background: #FFF; border: 1px solid #ddd; box-shadow: 0 2px 5px rgba(0,0,0,0.2); position: absolute; left: 0;
							top: 100%; width: 60%;"
						>
					</ul>
				</div>
			</td>
		</tr>
	<?php } ?>
		<tr>
			<?php
				$tk = 'fd_meta_name';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px; width:200px;'>
				<label>Naam</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					<?php if( $posttype != 'client' ) { echo 'readonly'; } ?>
					type="text"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){echo $tkv; };?>"
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_company';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Bedrijfsnaam</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					<?php if( $posttype != 'client' ) { echo 'readonly'; } ?>
					type="text"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){echo $tkv; };?>"
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_email';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Email</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					<?php if( $posttype != 'client' ) { echo 'readonly'; } ?>
					type="text"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){echo $tkv; };?>"
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_phone';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Telefoonnummer</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					<?php if( $posttype != 'client' ) { echo 'readonly'; } ?>
					type="text"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){echo $tkv; };?>"
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_address_1';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Straat + huisnummer</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					<?php if( $posttype != 'client' ) { echo 'readonly'; } ?>
					type="text"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){echo $tkv; };?>"
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_address_2';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Postcode + woonplaats</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					<?php if( $posttype != 'client' ) { echo 'readonly'; } ?>
					type="text"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){echo $tkv; };?>"
				/>
			</td>
		</tr>
	</table>

<?php }

function fd_invoice_items(){
	global $post;
	$invoiceRows = get_post_meta($post->ID, 'invoice_rows', true);
?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			var xhr = '';
			$('#new-row').click(function(){
				var table = $('.default-table').last().clone(true);
				table.find('input[type="text"]').val('');
				table.find('input[type="radio"]').each(function(){
					var rownum = parseInt($(this).attr('data-tax'))+1;
					$(this).attr('name', 'rowtaxnum['+rownum+'][]').attr('data-tax', rownum);
				});
				table.find('[data-ex]').val('0');
				table.find('[data-in]').val('0');
				table.find('.remove-row').css('display', 'inline-block');
				table.insertBefore('#invoice-items #more-btn');
			});

			$('.remove-row').click(function(){
				$(this).parents('table').remove();
			});

			$('[data-ex]').on('blur', function(){
				var price_ex = vf( jQuery(this).val() );
				var taxnum = parseInt($(this).parents('table').find('[data-tax]:checked').val());
				if(taxnum < 0){ taxnum = 0; }
				console.log(taxnum);
				var price_in = ((price_ex/100)*(100+taxnum)).toFixed(2);
				var tax = ((price_ex/100)*taxnum).toFixed(2);
				price_ex = price_ex.toFixed(2).toString();

				$(this).parents('table').find('[data-in]').val( price_in.replace(".", ",") );
				$(this).parents('table').find('input[type="text"][data-tax]').val( tax.replace(".", ",") );
				$(this).val( price_ex.replace(".", ",") ) ;
			});

			$('[data-in]').on('blur', function(){
				var price_in = vf(jQuery(this).val());
				var taxnum = parseInt($(this).parents('table').find('[data-tax]:checked').val());
				if(taxnum < 0){ taxnum = 0; }
				var price_ex = ((price_in/(100+taxnum))*100).toFixed(2);
				var tax = ((price_in/(100+taxnum))*taxnum).toFixed(2);
				price_in = price_in.toFixed(2).toString();

				$(this).parents('table').find('[data-ex]').val( price_ex.replace(".", ",") );
				$(this).parents('table').find('input[type="text"][data-tax]').val( tax.replace(".", ",") );
				$(this).val( price_in.replace(".", ",") );
			});

			$('[data-tax]').on('change', function(){
				if( $(this).parents('table').find('[data-ex]').val() != '' ){
					$(this).parents('table').find('[data-ex]').blur();
				}else{
					$(this).parents('table').find('[data-in]').blur();
				}
			});

			function vf(str) {
				str = str.toString();
				return parseFloat(str.replace(".", "").replace(",", "."));
			}

			$('[data-search-client]').on('focus', function(){
				$('.data-search-client-list').show();
			});

			$('[data-search-client]').on('blur', function(){
				setTimeout(function(){
					$('.data-search-client-list').hide();
				},300);
			});

			$('[data-search-client]').on('keyup', function(e){
				var val = $(this).val();
				if(xhr){
					xhr.abort();
				}
				$('.data-search-client-list').html('<li style="padding: 5px 15px; margin: 0;">Zoeken...</li>');
				xhr = jQuery.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'JSON',
					timeout: 30000,
					data: {
						action: 'fd_find_client',
						val: $(this).val()
					},
					success: function(response) {
						$('.data-search-client-list').html(response);
					}
				});
			});

			$('[data-manual-number]').on('click', function(e){
				e.preventDefault();
				if( $('input[name="fd_meta_invoice_num"]').attr('readonly') ){
					$('input[name="fd_meta_invoice_num"]').removeAttr('readonly');
					$('input[name="fd_meta_invoice_num"]').focus();
				}else{
					$('input[name="fd_meta_invoice_num"]').blur();
				}
			});

			$('input[name="fd_meta_invoice_num"]').on('blur', function(){
				$(this).attr('readonly', 'readonly');
				var val = $(this).val();
				if( val != '' ){
					if(xhr){
						xhr.abort();
					}
					xhr = jQuery.ajax({
						url: ajaxurl,
						type: 'post',
						dataType: 'JSON',
						timeout: 30000,
						data: {
							action: 'fd_set_invoice_numer',
							value: val
						}
					});
				}
			});

			$('[data-next-number]').on('click', function(e){
				if(xhr){
					xhr.abort();
				}
				xhr = jQuery.ajax({
					url: ajaxurl,
					type: 'post',
					dataType: 'JSON',
					timeout: 30000,
					data: {
						action: 'fd_set_invoice_numer'
					},
					success: function(response) {
						$('input[name="fd_meta_invoice_num"]').val(response);
					}
				});
			});

			$(document).on('click', '.data-search-client-list li', function(e){
				e.preventDefault();
				if($(this).data('client-id') > 0){
					jQuery.ajax({
						url: ajaxurl,
						type: 'post',
						dataType: 'JSON',
						timeout: 30000,
						data: {
							action: 'fd_set_client',
							id: $(this).data('client-id')
						},
						success: function(response) {
							$('input[name="fd_meta_name"]').val(response.name);
							$('input[name="fd_meta_company"]').val(response.company);
							$('input[name="fd_meta_email"]').val(response.email);
							$('input[name="fd_meta_phone"]').val(response.phone);
							$('input[name="fd_meta_address_1"]').val(response.address);
							$('input[name="fd_meta_address_2"]').val(response.zip);
						}
					});
				}
			})
		});
	</script>
	<style>.data-search-client-list li:hover{cursor: pointer; background-color: #eee;}</style>

	<div id='invoice-items'>
		<?php if($invoiceRows){
			foreach($invoiceRows as $i=>$invoiceRow){
				if( isset($invoiceRow['rowItemTaxNum']) &&
					is_array($invoiceRow['rowItemTaxNum']) &&
					sizeof($invoiceRow['rowItemTaxNum']) > 0
				){
					$invoiceRow['rowItemTaxNum'] = $invoiceRow['rowItemTaxNum'][0];
				}else{
					$invoiceRow['rowItemTaxNum'] = '';
				}
	   ?>
			<table
				width='100%'
				style="border: 1px solid #ddd; border-radius: 3px; padding: 10px; margin-bottom: 10px;"
				class="default-table"
			>
			<tr class='existing-row'>
				<td style="font-size: 13px; width:188px;">Omschrijving</td>
				<td>
					<input
						style='width: 100%'
						type='text'
						value='<?php if($invoiceRow['rowItemNames'] != ''){ echo esc_attr( $invoiceRow['rowItemNames'] ); } ?>'
						name='rowname[]'
					/>
				</td>
			</tr>
				<tr class='existing-row'>
					<td style="font-size: 13px; width:188px;">Aantal</td>
					<td>
						<input
							type='text'
							style='width: 100%'
							value='<?php if($invoiceRow['rowItemCounts'] != '') { echo esc_attr( $invoiceRow['rowItemCounts'] ); } ?>'
							name='rowcount[]'
						/>
					</td>
				</tr>
				<tr class='existing-row'>
					<td style="font-size: 13px; width:188px;">Prijs (excl BTW)</td>
					<td>
						<input
							type='text'
							style='width: 100%'
							value='<?php if($invoiceRow['rowItemCosts'] != '') { echo esc_attr( $invoiceRow['rowItemCosts'] ); }else{ echo '0'; } ?>'
							name='rowcost[]'
							data-ex
						/>
					</td>
				</tr>
				<tr class='existing-row'>
					<td style="font-size: 13px; width:188px;">BTW</td>
					<td>
						<input
							type='text'
							value='<?php if($invoiceRow['rowItemTax'] != '') { echo esc_attr( $invoiceRow['rowItemTax'] ); }else{ echo '0'; } ?>'
							name='rowtax[]'
							readonly
							data-tax
							style='width:53%;'
						/>
						<label style="font-size: 13px; margin-right: 20px; margin-left: 10px;">
							<input
								<?php if($invoiceRow['rowItemTaxNum'] == '0') { echo 'checked'; } ?>
								type='radio'
								value='0'
								name='rowtaxnum[<?php echo $i; ?>][]'
								data-tax="<?php echo $i; ?>"/> 0%
							</label>
						<label style="font-size: 13px; margin-right: 20px;">
							<input
								<?php if($invoiceRow['rowItemTaxNum'] == '6') { echo 'checked'; } ?>
								type='radio' value='6'
								name='rowtaxnum[<?php echo $i; ?>][]'
								data-tax="<?php echo $i; ?>"
							/> 6%
						</label>
						<label style="font-size: 13px; margin-right: 20px;">
							<input
								<?php if($invoiceRow['rowItemTaxNum'] == '21' || $invoiceRow['rowItemTaxNum'] == '') { echo 'checked'; } ?>
								type='radio'
								value='21'
								name='rowtaxnum[<?php echo $i; ?>][]'
								data-tax="<?php echo $i; ?>"
							/> 21%
						</label>
						<label style="font-size: 13px;">
							<input
								<?php if($invoiceRow['rowItemTaxNum'] == '-1') { echo 'checked'; } ?>
								type='radio'
								value='-1'
								name='rowtaxnum[<?php echo $i; ?>][]'
								data-tax="<?php echo $i; ?>"
							/> Privé-storting
						</label>
					</td>
				</tr>
				<tr class='existing-row'>
					<td style="font-size: 13px; width:188px;">Prijs (incl BTW)</td>
					<td>
						<input
							type='text'
							style='width: 100%'
							value='<?php if($invoiceRow['rowItemTotal'] != '') echo esc_attr( $invoiceRow['rowItemTotal'] ); ?>'
							name='rowtotal[]'
							data-in
						/>
					</td>
				</tr>
				<tr class='existing-row'>
					<td colspan="2" style="text-align: right;">
						<a
							href='#deleterow'
							class='remove-row'
							style='<?php if( $i == 0 ) { ?>display: none;<?php } ?> color: red; font-size: 12px;'
						>
							Verwijderen
						</a>
					</td>
				</tr>
			</table>
			<?php } ?>
		<?php }else{ ?>
		<table width='100%' style="border: 1px solid #ddd; border-radius: 3px; padding: 10px; margin-bottom: 10px;" class="default-table">
			<tr>
				<td style="font-size: 13px; width:188px;">Omschrijving</td>
				<td>
					<input style='width: 100%' type='text' value='' name='rowname[]' />
				</td>
			</tr>
			<tr>
				<td style="font-size: 13px; width:188px;">Aantal</td>
				<td>
					<input style='width: 100%' type='text' value='' name='rowcount[]' />
				</td>
			</tr>
			<tr>
				<td style="font-size: 13px; width:188px;">Bedrag (excl BTW)</td>
				<td>
					<input style='width: 100%' type='text' value='0' name='rowcost[]' data-ex/>
				</td>
			</tr>
			<tr>
				<td style="font-size: 13px; width:188px;">BTW</td>
				<td>
					<input style='width: 53%' readonly type='text' value='' name='rowtax[]' data-tax/>
					<label style="font-size: 13px; margin-right: 20px; margin-left: 10px;">
						<input type='radio' value='0' name='rowtaxnum[0][]' data-tax="0"/> 0%
					</label>
					<label style="font-size: 13px; margin-right: 20px;">
						<input type='radio' value='6' name='rowtaxnum[0][]' data-tax="0"/> 6%
					</label>
					<label style="font-size: 13px; margin-right: 20px;">
						<input checked type='radio' value='21' name='rowtaxnum[0][]' data-tax="0"/> 21%
					</label>
					<label style="font-size: 13px; margin-right: 20px;">
						<input type='radio' value='-1' name='rowtaxnum[0][]' data-tax="0"/> Privéstorting
					</label>
				</td>
			</tr>
			<tr>
				<td style="font-size: 13px; width:188px;">Bedrag (incl BTW)</td>
				<td>
					<input style='width: 100%' type='text' value='0' name='rowtotal[]' data-in/>
				</td>
			</tr>
			<tr class='existing-row'>
				<td colspan="2" style="text-align: right;">
					<a href='#deleterow' class='remove-row' style='display: none; color: red; font-size: 12px;'>Verwijderen</a>
				</td>
			</tr>
		</table>
		<?php } ?>
		<div style="padding: 10px 0 0;" id="more-btn">
			<a href='#addrow' id='new-row' class="button">Nieuw item toevoegen</a>
		</div>
	</div>
<?php }

function fd_receipt_data(){
	global $post;
?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('[data-ex-out]').on('blur', function(){
				var taxnum = parseInt(jQuery('input[name="fd_meta_receipt_tax"]:checked').val());
				if(taxnum < 0){
					taxnum = 0;
				}
				var price_ex = vf( jQuery(this).val() );
				var price_in = ((price_ex/100)*(100+taxnum)).toFixed(2);
				var tax = ((price_ex/100)*taxnum).toFixed(2);
				price_ex = price_ex.toFixed(2).toString();

				$('[data-in-out]').val( price_in.replace(".", ",") );
				$(this).val( price_ex.replace(".", ",") ) ;
			});

			$('[data-in-out]').on('blur', function(){
				var taxnum = parseInt(jQuery('input[name="fd_meta_receipt_tax"]:checked').val());
				if(taxnum < 0){
					taxnum = 0;
				}
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
			<?php
				$tk = 'fd_meta_receipt_date';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Factuur datum</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					type="date"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){ echo $tkv; }; ?>"
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_receipt_price_ex';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Betaald bedrag (excl. BTW)</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					type="text"
					name="<?php echo $tk; ?>"
					value="<?php if($tkv){ echo $tkv; }else{ echo '0'; };?>" data-ex-out
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_receipt_tax';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px; width:200px;'>
				<label>BTW percentage</label>
			</td>
			<td>
				<div>
					<label>
						<input
							type='radio'
							name='<?php echo $tk; ?>'
							value='0'
							<?php if($tkv == '0'){echo 'checked'; }; ?>
						/>
						<span style='font-size: 13px; margin-right: 20px;'>0%</span>
					</label>
					<label>
						<input
							type='radio'
							name='<?php echo $tk; ?>'
							value='6'
							<?php if($tkv == '6'){echo 'checked'; }; ?>
						/>
						<span style='font-size: 13px; margin-right: 20px;'>6%</span>
					</label>
					<label>
						<input
							type='radio'
							name='<?php echo $tk; ?>'
							value='21'
							<?php if($tkv == '21' || $tkv == ''){echo 'checked'; }; ?>
						/>
						<span style='font-size: 13px; margin-right: 20px;'>21%</span>
					</label>
					<label>
						<input
							type='radio'
							name='<?php echo $tk; ?>'
							value='-1'
							<?php if($tkv == '-1'){echo 'checked'; }; ?>
						/>
						<span style='font-size: 13px;'>Privé-opname</span>
					</label>
				</div>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_receipt_price';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Betaald bedrag (incl. BTW)</label>
			</td>
			<td>
				<input
					style='width: 100%;'
					type="text"
					name="<?php echo $tk; ?>"
					value="<?php   if($tkv){echo $tkv; }else{ echo '0'; };?>"
					data-in-out
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_receipt_file';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px; width:200px;'>
				<label>Bijlage</label>
			</td>
			<td>
				<?php if($tkv){ echo '<small><a href="'.$tkv['url'].'" target="_blank">'.basename($tkv['url']).'</a></small>'; } ?>
				<input
					style='width: 100%;'
					type="file"
					name="<?php echo $tk; ?>"
					value=""
				/>
			</td>
		</tr>
		<tr>
			<?php
				$tk = 'fd_meta_receipt_desc';
				$tkv = get_post_meta($post->ID, $tk, true );
			?>
			<td style='font-size: 13px;'>
				<label>Factuur omschrijving</label>
			</td>
			<td>
				<textarea
					style='width: 100%;'
					rows="4"
					name="<?php echo $tk; ?>"
				><?php  if($tkv){echo $tkv; };?></textarea>
			</td>
		</tr>
	</table>

<?php }

function fd_invoice_extra_details(){
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

function fd_meta(){
	add_meta_box(
		'fd_metaID_001',
		'Administratie',
		'fd_invoice_details',
		'invoice'
	);

	add_meta_box(
		'fd_metaID_002',
		'Klantgegevens',
		'fd_client_details',
		'invoice'
	);

	add_meta_box(
		'fd_metaID_003',
		'Factuuritems',
		'fd_invoice_items',
		'invoice'
	);

	add_meta_box(
		'fd_metaID_004',
		'Afspraken',
		'fd_invoice_extra_details',
		'invoice'
	);


	add_meta_box(
		'fd_metaID_005',
		'Administratie',
		'fd_receipt_data',
		'receipt'
	);


	add_meta_box(
		'fd_metaID_002',
		'Klantgegevens',
		'fd_client_details',
		'client'
	);

	wp_nonce_field( basename( __FILE__ ), 'fd_nonce' );
}
add_action( 'add_meta_boxes', 'fd_meta' );
