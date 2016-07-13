<h1><?php echo __('Leer Equipos BTESA del Centro') . ' ' . $centro['Centro']['centro'];?></h1>
<div class="ink-grid">
    <div class="column-group gutters">
        <div class="large-100">
            <h2><?php echo __('Bastidor') . ' ' . $hwcentro['RACK']['EQUIPO']; ?></h2>
            <table class="ink-table bordered alternating hover">
                <tr>
                    <th><?php echo __('Código Hardware'); ?></th>
                    <th><?php echo __('Código Software'); ?></th>
                    <th><?php echo __('Número de Serie'); ?></th>
                    <th><?php echo __('Dirección IP'); ?></th>
                    <th><?php echo __('Puerto BTESA'); ?></th>
                </tr>
                <tr>
                    <td><?php echo $hwcentro['RACK']['CODHW']; ?></td>
                    <td><?php echo $hwcentro['RACK']['CODSW']; ?></td>
                    <td><?php echo $hwcentro['RACK']['NSERIE']; ?></td>
                    <td><?php echo $hwcentro['RACK']['DIRIP']; ?></td>
                    <td><?php echo $hwcentro['RACK']['PUERTO']; ?></td>
                </tr>
            </table>
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
                            <th><?php echo __('Código Software'); ?></th>
                            <th><?php echo __('Número de Serie'); ?></th>
                        </tr>
                        <tr>
                            <td><?php echo $cofre['CODHW']; ?></td>
                            <td><?php echo $cofre['CODSW']; ?></td>
                            <td><?php echo $cofre['NSERIE']; ?></td>
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
                                    <td><?php echo $valores['CODHW']; ?></td>
                                    <td><?php echo $valores['CODSW']; ?></td>
                                    <td><?php echo $valores['NSERIE']; ?></td>
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
