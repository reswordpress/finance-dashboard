<div class="callout secondary callout-table">
	<h5>Kleineondernemersregeling</h5>
	<div class="table-overflow">
		<table class="table">
			<tfoot>
				<tr>
					<td colspan="2">
						<small>Krijgt u een vermindering van de btw door de kleineondernemersregeling? Het voordeel dat u hierdoor hebt, moet u als opbrengst (buitengewone bate) opnemen in uw winstberekening.				</small>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>BTW</td>
					<td width="200">€<?php echo number_format( $tax_difference, 2, ',' , '.' ); ?></td>
				</tr>
				<tr>
					<td>Vermindering <small>(=<1345 of >1346 EN =< 1883 of > 1884)</small></td>
					<td>
						<?php
							if( $tax_difference <= 1345 ){
								$tax_reduce = $tax_difference;
							}elseif( $tax_difference <= 1883 ){
								$tax_reduce = 2.5 * (1883 - $tax_difference);
							}else{
								$tax_reduce = 0;
							}
							echo '<span class="has-tip">€' . number_format( $tax_reduce, 2, ',' , '.' ) . '</span>';
						?>

						<div class="tooltip">
							Aanvragen bij de aangifte over Q4 van dit jaar
						</div>
					</td>
				</tr>
				<tr>
					<td><strong>Betalen aan het eind van het jaar</strong></td>
					<td><?php echo '€' . number_format( ($tax_difference-$tax_reduce), 2, ',' , '.' ); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
