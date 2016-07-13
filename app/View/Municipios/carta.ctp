<h1><?php echo __('Municipios con Cobertura TDT de la Comunitat Valenciana');?></h1>

<h4>
<?php
$nmunicipios = count($municipios);
echo __('Resultados de la Búsqueda');
if ($nmunicipios > 0){
    echo ' &mdash; '.$nmunicipios.' '.__("Municipios");
}
?>

</h4>

<div class="column-group gutters">
	<div class="control-group large-25">
        <?php
        echo $this->Html->Link(
                 '<i class = "icon-print"></i>  '.__('Exportar a PDF'),
                 array('controller' => 'municipios', 'action' => 'cartaserv'),
                 array('class' => 'ink-button blue', 'title' => __('Exportar a PDF'), 'escape' => false, 'target' => '_blank')
        );
        ?>
    </div>
</div>

<table class="ink-table bordered alternating hover">
	<tr>
		<th><?php echo __('Cod. INE');?></th>
		<th><?php echo __('Provincia');?></th>
		<th><?php echo __('Municipio');?></th>
		<th><?php echo __('Centros TDT');?></th>
	</tr>
	<?php
	foreach ($municipios as $municipio){
	?>
		<tr>
			<td class="content-center"><?php echo $municipio['Municipio']['id'];?></td>
			<td><?php echo $municipio['Municipio']['provincia'];?></td>
			<td><?php echo $municipio['Municipio']['nombre'];?></td>
			<td>
				<p>
				<?php echo __('Total Centros');?> &mdash; <?php echo count($municipio['Centros']);?><br>
				<ul>
				<?php
				foreach ($municipio['Centros'] as $centro){
				?>
					<li><b><?php echo $centro['Centro']['centro'];?></b> &mdash; <?php echo count($centro['Emision']).' '.__('Múltiples');?></li>
					<ul>
					<?php
					foreach ($centro['Emision'] as $emision){
					?>
						<li><?php echo __('Mux').' '.$emision['nombre'];?> &mdash; <?php echo __('Canal').' '.$emision['canal'];?>
						</li>
						<ul>
							<li>
							<?php echo count($emision['programas']).' '.__('Programas');?> &mdash;
							<?php

							echo '| ';
							foreach ($emision['programas'] as $programa){
								echo $programa['Programa']['nombre'].' | ';
							}
							?>
							</li>
						</ul>
					<?php
					}
					?>
					</ul>
				<?php
				}
				?>
				</ul>
				</p>
			</td>
		</tr>
	<?php
	}
	?>
</table>
