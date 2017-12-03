<div class="callout secondary callout-table">
	<h5>Winst berekening <label>BETA</label></h5>
	<div class="table-overflow">
		<table class="table table-centered">
			<thead>
				<tr>
					<td colspan="2" style="font-weight: normal;"><em>Deze berekening houdt geen rekening met persoonlijke situaties, er wordt alleen gerekend met door de overheid vastgestelde bedragen. Gebruik de berekening alleen als basis voor je belasting aangifte.</em></td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Omzet (excl. btw)</td>
					<td width="200"><?php echo '€ '.number_format( $income, 2, ',' , '.' ); ?></td>
				</tr>
				<tr>
					<td>AF: kostprijs omzet</td>
					<td><?php echo '€ '.number_format( -$expenses, 2, ',' , '.' ); ?></td>
				</tr>
				<tr>
					<td>Bruto marge</td>
					<td>
						<?php
							$marge = $income-$expenses;
							echo '€ '.number_format( $marge, 2, ',' , '.' );
						?>
					</td>
				</tr>
				<tr>
					<td>AF: Afschrijving</td>
					<td>
						<span class="has-tip"><?php
							echo '€ -'.number_format( $writeoff, 2, ',' , '.' );
							?></span>
						<?php if($writeexx != ''){ ?>
							<div class="tooltip">
								<?php echo nl2br($writeexx); ?>
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td>Winst uit onderneming</td>
					<td>
						<?php
							$profit_temp = $marge-$writeoff;
							echo '€ '.number_format( $profit_temp, 2, ',' , '.' );
						?>
					</td>
				</tr>
				<tr>
					<td>
						AF: Zelfstandigenaftrek (<a target="_blank" href="https://www.belastingdienst.nl/wps/wcm/connect/bldcontentnl/belastingdienst/zakelijk/winst/inkomstenbelasting/verandering_inkomstenbelasting_vorige_jaren/veranderingen_2016/zelfstandigenaftrek_2016">Link</a>)
					</td>
					<td>
						<?php
							$zelfstandigenaftrek = -7280;
							if($profit_temp + $zelfstandigenaftrek < 0){
								echo '<span class="has-tip">€ '.number_format( $profit_temp, 2, ',' , '.' ).'</span><div class="tooltip">(€ '.number_format( -($zelfstandigenaftrek+$profit_temp), 2, ',' , '.' ).' te gebruiken in de komende 9 jaar)</div>';
							}else{
								echo '€ '.number_format( $zelfstandigenaftrek, 2, ',' , '.' );
							}
						?>
					</td>
				</tr>
				<tr>
					<td>AF: Startersaftrek (<a href="https://www.belastingdienst.nl/wps/wcm/connect/bldcontentnl/belastingdienst/zakelijk/winst/inkomstenbelasting/verandering_inkomstenbelasting_vorige_jaren/veranderingen_2016/zelfstandigenaftrek_2016">Link</a>)</td>
					<td>
						<?php
							$starter = $starter ? -2123 : 0;
							if($starter != 0 && $profit_temp+$zelfstandigenaftrek < 2123){
								echo '<span class="has-tip">€ '.number_format( $starter, 2, ',' , '.' ).'</span><div class="tooltip">(Je gebruikt dit jaar niet al je startersaftrek, misschien is het slimmer om het volgend jaar te gebruiken)</div>';
							}else{
								echo '€ '.number_format( $starter, 2, ',' , '.' );
							}
						?>
					</td>
				</tr>
				<tr>
					<td>Winst na ondernemersaftrek</td>
					<td>
						<?php
							$profit = $profit_temp+$zelfstandigenaftrek+$starter;
							$profit = $profit<0 ? 0 : $profit;
							echo '€ '.number_format( $profit, 2, ',' , '.' );
						?>
					</td>
				</tr>
				<tr>
					<td>AF: MKB-winstvrijstelling (<a href="https://www.belastingdienst.nl/wps/wcm/connect/bldcontentnl/belastingdienst/zakelijk/winst/inkomstenbelasting/verandering_inkomstenbelasting_vorige_jaren/veranderingen_2016/mkb_winstvrijstelling">Link</a>)</td>
					<td>
						<?php
							$MKB = ($profit < 0) ? 0 : $profit*-0.14;
							echo '€ '.number_format( $MKB, 2, ',' , '.' );
						?>
					</td>
				</tr>
				<tr>
					<td><strong>Belastbare winst</strong></td>
					<td>
						<strong>
						<?php
							$belastbarewinst = $profit + $MKB;
							$belastbarewinst = ($belastbarewinst < 0) ? 0 : $belastbarewinst;
							echo '€ '.number_format( $belastbarewinst, 2, ',' , '.' );
						?>
						</strong>
					</td>
				</tr>
				<tr class="break">
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr>
					<td>Inkomstenbelasting</td>
					<td>
						<?php
							$tax_income = ($belastbarewinst > 19922) ? ((($belastbarewinst-19922) * 0.404) + (19922*0.3655)) : ($belastbarewinst*0.3655);
							echo '€ '.number_format( $tax_income, 2, ',' , '.' );
						?>
					</td>
				</tr>
				<tr>
					<td>AF:
						<?php
							$heffingskorting = 	($tax_income > 66417) ? 0 :
												($tax_income >19922) ? -(2242-(0.04822*($tax_income-19922))) :
												-2242;
							if($tax_income + $heffingskorting > 0) {
						?>
							Algemene heffingskorting
						<?php }else{ ?>
							<span class="has-tip">Algemene heffingskorting</span>
							<div class="tooltip tip-top tip-large">
								Dit bedrag is nooit hoger dan de te betalen inkomstenbelasting. Als de te betalen inkomstenbelasting lager is dan de heffingskorting, kan deze onder bepaalde voorwaarden worden verrekend als u een fiscale partner hebt. In dit geval gaat het om een restered bedrag van <?php echo '€'.number_format( -($heffingskorting+$tax_income), 2, ',' , '.' ); ?>.
							</div>
						<?php } ?>
					</td>
					<td>
						<?php
							if($tax_income + $heffingskorting > 0){
								echo '€ '.number_format( $heffingskorting, 2, ',' , '.' );
							}else{
								$heffingskorting = -$tax_income;
								echo '€ '.number_format( $tax_income, 2, ',' , '.' );
							}
						?>
					</td>
				</tr>
				<tr>
					<td>
						AF: <span class="has-tip">Arbeidskorting</span>
						<div class="tooltip tip-top tip-large">Let op, er wordt hierbij gekeken naar de <em>bruto marge</em>.</div>
					</td>
					<td>
						<?php
							if($tax_income + $heffingskorting > 0){
								if($marge > 111590){
										$arbeidskorting = 0;
								}
								else if($marge > 34015){
									$arbeidskorting = -(3103 - (0.04*($marge-34015)));
								}
								else if($marge > 19758){
									$arbeidskorting = -3101;
								}
								else if($marge > 9147){
									$arbeidskorting = -(164+(0.27698*($marge-9147)));
								}
								else{
									$arbeidskorting = -(0.01793*$marge);
								}
							}else{
								$arbeidskorting = 0;
							}
							echo '€ '.number_format( $arbeidskorting, 2, ',' , '.' );
						?>
					</td>
				</tr>
				<tr>
					<td>
						<strong>Totaal af te dragen inkomstenbelasting</strong>
					</td>
					<td>
						<strong>
							<?php
								$taxpayment = ($tax_income+$heffingskorting+$arbeidskorting);
								$taxpayment = $taxpayment < 0 ? 0 : $taxpayment;
								echo '€ '.number_format( $taxpayment, 2, ',' , '.' );
							?>
						</strong>
					</td>
				</tr>
				<tr>
					<td>
						<strong>Totaal af te dragen
							<?php if($belastbarewinst > 0){ ?>
								<span class="has-tip">ZVW (Zorgverzekeringswet)</span>
								<div class="tooltip tip-top tip-large">
									Iedereen met een belastbaar inkomen is verplicht een inkomensafhankelijke bijdrage Zvw te betalen.
								</div>
							<?php }else{ ?>
								ZVW (Zorgverzekeringswet)
							<?php } ?>
						</strong>
					</td>
					<td>
						<strong><?php
							$zvw = ($belastbarewinst*0.055);
							echo '€ '.number_format( $zvw, 2, ',' , '.' );
						?></strong>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
