<div class="callout secondary callout-table">
	<h5>Winst berekening <label>BETA</label></h5>
	<div class="table-overflow">
		<table class="table table-centered">
			<thead>
				<tr>
					<th>Winst uit onderneming</th>
					<th>Afschrijving</th>
					<th>Zelfstandigenaftrek</th>
					<th>Startersaftrek</th>
					<th>MKB-Winstvrijstelling</th>
					<th>Belastbare winst</th>
					<th>ZVW</th>
					<th>Algemene heffingskorting</th>
					<th>Arbeidskorting</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="2"><small>Zelfstandigenaftrek</small></td>
					<td colspan="7"><small>€7.280,00</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>Startersaftrek</small></td>
					<td colspan="7"><small>€2123, maximaal 3 keer in de eerste 5 jaar</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>Kleinschaligheidsinvesteringsaftrek</small></td>
					<td colspan="7"><small>28% van het investeringsbedrag als dit tussen € 2.301 t/m € 56.192 ligt</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>MKB-Winstvrijstelling</small></td>
					<td colspan="7"><small>14% van de winst die overblijft na aftrek van Zelfstandigen, startersaftrek en kleinschaligheidsinvesteringsaftrek</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>Oudedagsreserve</small></td>
					<td colspan="7"><small>9,8% van de winst, met in 2015 een maximum van € 8.774</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>Meewerkaftrek</small></td>
					<td colspan="7"><small>1,25% van de winst, Als je fiscalepartner meer dan 525 uur gratis meewerkt</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>Afschrijving</small></td>
					<td colspan="7"><small>Economische levenstijd bepalen, dan aanschafwaarde - restwaarde/ die tijd is wat er af mag</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>ZvW	</small></td>
					<td colspan="7"><small>5,4%, winst uit onderneming - zelfstandigenaftrek - startersaftrek - mkb winstvrijstelling = belastbare winst</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>Algemene heffingskorting</small></td>
					<td colspan="7"><small>€2254, tot een bedrag van €19.982</small></td>
				</tr>
				<tr>
					<td colspan="2"><small>Arbeidskorting</small></td>
					<td colspan="7"><small>1,772% x arbeidsinkomen, tot een bedrag van € 9.309 ( = winst voor de aftrekposten)</small></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<td>
						<?php
							$arbeidsinkomen = $income-$expenses;
							echo '€'.number_format( $arbeidsinkomen, 2, ',' , '.' );
						?>
					</td>
					<td>
						<span class="has-tip"><?php
							echo '€'.number_format( $writeoff, 2, ',' , '.' );
							?></span>
						<?php if($writeexx != ''){ ?>
							<div class="tooltip">
								<?php echo nl2br($writeexx); ?>
							</div>
						<?php } ?>
					</td>
					<td>
						<?php
							$zelfstandigenaftrek = 7280;
							echo '€'.number_format( $zelfstandigenaftrek, 2, ',' , '.' );
						?>
					</td>
					<td>
						<?php
							$starter = $starter ? 2123 : 0;
							echo '€'.number_format( $starter, 2, ',' , '.' );
						?>
					</td>
					<td>
						<?php
							$MKB = $arbeidsinkomen - $writeoff - $zelfstandigenaftrek - $starter;
							$MKB = ($MKB < 0) ? 0 : $MKB*0.14;
							echo '€'.number_format( $MKB, 2, ',' , '.' );
						?>
					</td>
					<td>
						<?php
							$belastbarewinst = $arbeidsinkomen - $writeoff - $zelfstandigenaftrek - $starter - $MKB;
							$belastbarewinst = ($belastbarewinst < 0) ? 0 : $belastbarewinst;
							echo '€'.number_format( $belastbarewinst, 2, ',' , '.' );
						?>
					</td>
					<td>
						<?php echo '€'.number_format( ($belastbarewinst*0.054), 2, ',' , '.' ); ?>
					</td>
					<td>
						<?php
							$heffingskorting = 2254;
							echo '€'.number_format( $heffingskorting, 2, ',' , '.' );
						?>
					</td>
					<td><?php echo '€'.number_format( ($arbeidsinkomen*0.01772), 2, ',' , '.' ); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
