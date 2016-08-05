<?php
// Tipos de tarjeta
$tipotarjeta = array(
    'TX' => 'Transmisión', 'MOD' => 'Modulador', 'RX' => 'Recepción',
    'EXC' => 'Excitador', 'CONV' => 'Convertidor', 'FI' => 'FI Transmisión Digital'
);
?>
<h1><?php echo __('Leer Equipos BTESA del Centro') . ' ' . $centro['Centro']['centro'];?></h1>
<div class="ink-grid">
    <div class="column-group gutters">
        <div class="large-100">
            <h2><?php echo __('Supervisor') . ' ' . $hwcentro['RACK']['EQUIPO']; ?></h2>
            <?php
            if (isset($hwcentro['RACK']['NOMBRE'])){
            ?>
                <table class="ink-table bordered alternating hover">
                    <tr>
                        <th><?php echo __('Código Hardware'); ?></th>
                        <th><?php echo __('Código Software'); ?></th>
                        <th><?php echo __('Número de Serie'); ?></th>
                        <th><?php echo __('Fecha de Importación'); ?></th>
                    </tr>
                    <tr>
                        <td><?php echo $hwcentro['RACK']['CODHW']; ?></td>
                        <td><?php echo $hwcentro['RACK']['CODSW']; ?></td>
                        <td><?php echo $hwcentro['RACK']['NSERIE']; ?></td>
                        <td><?php echo $hwcentro['RACK']['FECHA']; ?></td>
                    </tr>
                </table>
            <?php
            }
            else{
            ?>
                <div class="ink-alert basic" role="alert">
                    <p><b><?php echo __('Atención'); ?>:</b> <?php echo __('Este Centro no tiene módulo de supervisión'); ?></p>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="large-10">
            &nbsp;
        </div>
        <div class="large-90">
        <?php
        foreach ($hwcentro['RACK']['COFRES'] as $nomcofre => $cofre) {
        ?>
            <div class="column-group">
                <div class="large-100">
                    <h3><?php echo __('Cofre') . ' ' . $nomcofre; ?></h3>
                    <table class="ink-table bordered alternating hover">
                        <tr>
                            <th><?php echo __('Código Hardware'); ?></th>
                            <th><?php echo __('Número de Serie'); ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $cofre['CODHW']; ?></td>
                            <td><?php echo $cofre['NSERIE']; ?></td>
                        </tr>
                    </table>
                </div>
                <div class="large-10">
                    &nbsp;
                </div>
                <div class="large-90">
                <?php
                if ($hwcentro['RACK']['EQUIPO'] == 'ALPUENTE I') {
                ?>
                    <h4><?php echo __('Transmisor'); ?></h4>
                    <table class="ink-table bordered alternating hover">
                        <tr>
                            <th><?php echo __('Código Hardware'); ?></th>
                            <th><?php echo __('Código Software'); ?></th>
                            <th><?php echo __('Número de Serie'); ?></th>
                            <th><?php echo __('Potencia'); ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $cofre['CONTPAN']['CODHW']; ?></td>
                            <td><?php echo $cofre['CONTPAN']['CODSW']; ?></td>
                            <td><?php echo $cofre['CONTPAN']['NSERIE']; ?></td>
                            <td><?php echo $cofre['CONTPAN']['POTENCIA']; ?></td>
                        </tr>
                    </table>
                <?php
                }
                else{
                ?>
                    <h4><?php echo __('Panel de Control'); ?></h4>
                    <table class="ink-table bordered alternating hover">
                        <tr>
                            <th><?php echo __('Código Hardware'); ?></th>
                            <th><?php echo __('Código Software'); ?></th>
                            <th><?php echo __('Número de Serie'); ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $cofre['CONTPAN']['CODHW']; ?></td>
                            <td><?php echo $cofre['CONTPAN']['CODSW']; ?></td>
                            <td><?php echo $cofre['CONTPAN']['NSERIE']; ?></td>
                        </tr>
                    </table>
                <?php
                }
                ?>
                    <h4><?php echo __('Tarjetas'); ?></h4>
                    <table class="ink-table bordered alternating hover">
                        <tr>
                            <?php
                            if ($hwcentro['RACK']['EQUIPO'] != 'ALPUENTE I'){
                            ?>
                                <th colspan="2">&nbsp;</th>
                            <?php
                            }
                            else{
                            ?>
                                    <th><?php echo __('Tarjeta'); ?></th>
                            <?php
                            }
                            ?>
                            <th><?php echo __('Código Hardware'); ?></th>
                            <th><?php echo __('Código Software'); ?></th>
                            <th><?php echo __('Número de Serie'); ?></th>
                            <?php
                            if ($hwcentro['RACK']['EQUIPO'] != 'ALPUENTE I'){
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
                                $potencia = "&mdash;";
                                if (isset($valores['POTENCIA'])){
                                    $potencia = $valores['POTENCIA'];
                                }
                        ?>
                                <tr>
                                    <?php
                                    if (($inicio) && ($hwcentro['RACK']['EQUIPO'] != 'ALPUENTE I')){
                                    ?>
                                        <th rowspan="2"><?php echo $canal; ?></th>
                                    <?php
                                    }
                                    ?>
                                    <td><?php echo $tipotarjeta[$modulo]; ?></td>
                                    <td><?php echo $valores['CODHW']; ?></td>
                                    <td><?php echo $valores['CODSW']; ?></td>
                                    <td><?php echo $valores['NSERIE']; ?></td>
                                    <?php
                                    if ($hwcentro['RACK']['EQUIPO'] != 'ALPUENTE I'){
                                    ?>
                                        <td><?php echo $potencia; ?></td>
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
    </div>
</div>
<?php
echo $this->Form->create('Centro' ,array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<div class='content-center'>
    <?php
    echo $this->Form->button(
            '<i class = "icon-save"></i> '.__('Guardar Equipos'),
            array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar Centro'), 'alt' => __('Guardar Centro'), 'escape' => false)
    );
    echo $this->Html->Link(
            '<i class = "icon-arrow-left"></i> '.__('Volver'),
            array('controller' => 'centros', 'action' => 'detalle', $centro['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
