<h1><?php echo __('Equipos del Centro TDT de ').$centro['Centro']['centro'];?></h1>
<?php
$nequipos = count($equipos);
?>
<?php
if ($nequipos > 0){
?>
    <div class="ink-grid">
        <div class="column-group gutters">
        <?php
        foreach ($equipos as $bastidor) {
        ?>
            <div class="large-100">
                <h2><?php echo __('Bastidor') . ' ' . $bastidor['nombre']; ?></h2>
                <table class="ink-table bordered alternating hover">
                    <tr>
                        <th><?php echo __('Fecha de importación'); ?></th>
                        <th><?php echo __('Código Hardware'); ?></th>
                        <th><?php echo __('Código Software'); ?></th>
                        <th><?php echo __('Número de Serie'); ?></th>
                        <th><?php echo __('Dirección IP'); ?></th>
                        <th><?php echo __('Puerto BTESA'); ?></th>
                    </tr>
                    <tr>
                        <td><?php echo $bastidor['fecha']; ?></td>
                        <td><?php echo $bastidor['codhw']; ?></td>
                        <td><?php echo $bastidor['codsw']; ?></td>
                        <td><?php echo $bastidor['nserie']; ?></td>
                        <td><?php echo $bastidor['dirip']; ?></td>
                        <td><?php echo $bastidor['puerto']; ?></td>
                    </tr>
                </table>
            </div>
            <div class="large-10">
                &nbsp;
            </div>
            <div class="large-90">
            <?php
            foreach ($bastidor['COFRES'] as $cofre) {
            ?>
                <div class="column-group">
                    <div class="large-100">
                        <h3><?php echo __('Cofre') . ' ' . $cofre['nombre']; ?></h3>
                        <table class="ink-table bordered alternating hover">
                            <tr>
                                <th><?php echo __('Código Hardware'); ?></th>
                                <th><?php echo __('Código Software'); ?></th>
                                <th><?php echo __('Número de Serie'); ?></th>
                            </tr>
                            <tr>
                                <td><?php echo $cofre['codhw']; ?></td>
                                <td><?php echo $cofre['codsw']; ?></td>
                                <td><?php echo $cofre['nserie']; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="large-10">
                        &nbsp;
                    </div>
                    <div class="large-90">
                        <h4><?php echo __('Tarjetas'); ?></h4>
                        <table class="ink-table bordered alternating hover">
                            <tr>
                                <th colspan="2">&nbsp;</th>
                                <th><?php echo __('Código Hardware'); ?></th>
                                <th><?php echo __('Código Software'); ?></th>
                                <th><?php echo __('Número de Serie'); ?></th>
                            </tr>
                            <?php
                            $inicio = true;
                            foreach ($cofre['TARJETAS'] as $canal => $tarjeta) {
                                foreach ($tarjeta as $modulo => $valores) {
                            ?>
                                    <tr>
                                        <?php
                                        if ($inicio){
                                        ?>
                                            <th rowspan="2"><?php echo $canal; ?></th>
                                        <?php
                                        }
                                        ?>
                                        <th><?php echo $modulo; ?></th>
                                        <td><?php echo $valores['codhw']; ?></td>
                                        <td><?php echo $valores['codsw']; ?></td>
                                        <td><?php echo $valores['nserie']; ?></td>
                                    </tr>
                            <?php
                                    $inicio = !($inicio);
                                }
                            }
                            ?>
                        </table>
                    </div>

                </div>
            <?php
            }
            ?>
            </div>
        <?php
        }
        ?>
        </div>
<?php
}
else{
?>
    <div class="ink-alert block warning">
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado equipos en este centro'); ?></p>
    </div>
<?php
}
?>
<div class='content-center'>
    <?php
    echo $this->Html->Link(
        '<i class = "icon-arrow-left"></i> '.__('Volver'),
        array('controller' => 'centros', 'action' => 'detalle', $centro['Centro']['id']),
        array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    echo $this->Html->Link(
        '<i class = "icon-upload"></i> ' .  __('Importar equipos BTESA'),
        array('controller' => 'centros', 'action' => 'importarhw', $centro['Centro']['id']),
        array('class' => 'ink-button blue', 'title' => __('Editar Tarjeta'), 'alt' => __('Editar Tarjeta'), 'escape' => false)
    );
    ?>
</div>
