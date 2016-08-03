<?php
// Tipos de tarjeta
$tipotarjeta = array(
    'TX' => 'Transmisión', 'MOD' => 'Modulador', 'RX' => 'Recepción',
    'EXC' => 'Excitador', 'CONV' => 'Convertidor', 'FI' => 'FI Transmisión Digital'
);
?>
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
                <h2><?php echo __('Supervisor') . ' ' . $bastidor['nombre']; ?></h2>
                <table class="ink-table bordered alternating hover">
                    <tr>
                        <th><?php echo __('Fecha de importación'); ?></th>
                        <th><?php echo __('Marca'); ?></th>
                        <th><?php echo __('Código Hardware'); ?></th>
                        <th><?php echo __('Código Software'); ?></th>
                        <th><?php echo __('Número de Serie'); ?></th>
                    </tr>
                    <tr>
                        <td><?php echo $bastidor['fecha']; ?></td>
                        <td><?php echo $bastidor['marca']; ?></td>
                        <td><?php echo $bastidor['codhw']; ?></td>
                        <td><?php echo $bastidor['codsw']; ?></td>
                        <td><?php echo $bastidor['nserie']; ?></td>
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
                                <th><?php echo __('Marca'); ?></th>
                                <th><?php echo __('Código Hardware'); ?></th>
                                <th><?php echo __('Número de Serie'); ?></th>
                                <th><?php echo __('Acciones'); ?></th>
                            </tr>
                            <tr>
                                <td><?php echo $cofre['marca']; ?></td>
                                <td><?php echo $cofre['codhw']; ?></td>
                                <td><?php echo $cofre['nserie']; ?></td>
                                <td class="content-center">
                                    <?php
                                    echo $this->Html->Link(
                                            '<i class = "icon-edit"></i>',
                                            array('controller' => 'equipos', 'action' => 'editar', $cofre['id']),
                                            array('title' => __('Editar Equipo'), 'alt' => __('Editar Emisión'), 'escape' => false)
                                    );
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="large-10">
                        &nbsp;
                    </div>
                    <div class="large-90">
                        <?php
                        if ($centro['Centro']['id'] == 13){
                        ?>
                            <h4><?php echo __('Transmisor'); ?></h4>
                            <table class="ink-table bordered alternating hover">
                                <tr>
                                    <th><?php echo __('Marca'); ?></th>
                                    <th><?php echo __('Código Hardware'); ?></th>
                                    <th><?php echo __('Código Software'); ?></th>
                                    <th><?php echo __('Número de Serie'); ?></th>
                                    <th><?php echo __('Potencia'); ?></th>
                                </tr>
                                <tr>
                                    <td><?php echo $cofre['TRANSMISOR']['marca']; ?></td>
                                    <td><?php echo $cofre['TRANSMISOR']['codhw']; ?></td>
                                    <td><?php echo $cofre['TRANSMISOR']['codsw']; ?></td>
                                    <td><?php echo $cofre['TRANSMISOR']['nserie']; ?></td>
                                    <td><?php echo $cofre['TRANSMISOR']['potencia']; ?></td>
                                </tr>
                            </table>
                        <?php
                        }
                        else{
                        ?>
                            <h4><?php echo __('Panel de Control'); ?></h4>
                            <table class="ink-table bordered alternating hover">
                                <tr>
                                    <th><?php echo __('Marca'); ?></th>
                                    <th><?php echo __('Código Hardware'); ?></th>
                                    <th><?php echo __('Código Software'); ?></th>
                                    <th><?php echo __('Número de Serie'); ?></th>
                                </tr>
                                <tr>
                                    <td><?php echo $cofre['CONTPAN']['marca']; ?></td>
                                    <td><?php echo $cofre['CONTPAN']['codhw']; ?></td>
                                    <td><?php echo $cofre['CONTPAN']['codsw']; ?></td>
                                    <td><?php echo $cofre['CONTPAN']['nserie']; ?></td>
                                </tr>
                            </table>
                        <?php
                        }
                        ?>
                        <h4><?php echo __('Tarjetas'); ?></h4>
                        <table class="ink-table bordered alternating hover">
                            <tr>
                                <?php
                                if ($centro['Centro']['id'] == 13){
                                ?>
                                    <th><?php echo __('Tarjeta'); ?></th>
                                <?php
                                }
                                else{
                                ?>
                                    <th colspan="2"><?php echo __('Tarjeta'); ?></th>
                                <?php
                                }
                                ?>
                                <th><?php echo __('Marca'); ?></th>
                                <th><?php echo __('Código Hardware'); ?></th>
                                <th><?php echo __('Código Software'); ?></th>
                                <th><?php echo __('Número de Serie'); ?></th>
                                <?php
                                if ($centro['Centro']['id'] != 13){
                                ?>
                                    <th><?php echo __('Potencia'); ?></th>
                                <?php
                                }
                                ?>
                            </tr>
                            <?php
                            $inicio = true;
                            foreach ($cofre['TARJETAS'] as $canal => $tarjeta) {
                                foreach ($tarjeta as $modulo => $valores) {
                            ?>
                                    <tr>
                                        <?php
                                        if (($inicio) && ($centro['Centro']['id'] != 13)){
                                        ?>
                                            <th rowspan="2"><?php echo $canal; ?></th>
                                        <?php
                                        }
                                        ?>
                                        <td><?php echo $tipotarjeta[$modulo]; ?></td>
                                        <td><?php echo $valores['marca']; ?></td>
                                        <td><?php echo $valores['codhw']; ?></td>
                                        <td><?php echo $valores['codsw']; ?></td>
                                        <td><?php echo $valores['nserie']; ?></td>
                                        <?php
                                        if ($centro['Centro']['id'] != 13){
                                        ?>
                                            <td><?php echo $valores['potencia']; ?></td>
                                        <?php
                                        }
                                        ?>
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
        array('controller' => 'equipos', 'action' => 'importarhw', $centro['Centro']['id']),
        array('class' => 'ink-button blue', 'title' => __('Editar Tarjeta'), 'alt' => __('Editar Equipos'), 'escape' => false)
    );
    echo $this->Html->Link(
        '<i class = "icon-plus"></i> ' .  __('Agregar Equipo'),
        array('controller' => 'equipos', 'action' => 'agregar', $centro['Centro']['id']),
        array('class' => 'ink-button blue', 'title' => __('Agregar Equipo'), 'alt' => __('Agregar Equipo'), 'escape' => false)
    );
    ?>
</div>
