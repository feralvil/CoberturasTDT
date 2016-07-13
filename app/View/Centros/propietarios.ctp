<h1><?php echo __('Leer Datos de Propiedad de los Centros TDT');?></h1>
<?php
$ncentros = count($centros);
?>
<h2><?php echo __('Datos de Propiedad encontrados') . '(' . $ncentros . ' ' . __('Centros') . ')';?></h2>
<?php
if ($ncentros > 0){
?>
    <?php
    echo $this->Form->create('Centro',array(
        'inputDefaults' => array(
            'label' => false,
            'div' => false),
        'class' => 'ink-form'
    ));
    ?>
    <div class='content-center'>
        <?php
        echo $this->Form->button(
                '<i class = "icon-save"></i> '.__('Guardar Datos'),
                array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar Datos'), 'alt' => __('Guardar Datos'), 'escape' => false)
        );
        echo $this->Html->Link(
                '<i class = "icon-arrow-left"></i> '.__('Volver'),
                array('controller' => 'centros', 'action' => 'index'),
                array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
        );
        ?>
    </div>
    <?php
    echo $this->Form->end();
    ?>
    <table class="ink-table hover alternating">
        <tr>
            <th>ID</th>
            <th><?php echo __('Centro');?></th>
            <th><?php echo __('Suelo');?></th>
            <th><?php echo __('Caseta');?></th>
            <th><?php echo __('Torre');?></th>
            <th><?php echo __('Eléctrico');?></th>
        </tr>
        <?php
        foreach ($centros as $centro) {
        ?>
            <tr>
                <td><?php echo $centro['Centro']['id'];?></td>
                <td><?php echo $centro['Centro']['centro'];?></td>
                <td><?php echo $centro['Centro']['suelo'];?></td>
                <td><?php echo $centro['Centro']['caseta'];?></td>
                <td><?php echo $centro['Centro']['torre'];?></td>
                <td><?php echo $centro['Centro']['electrico'];?></td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
}
else{
?>
    <div class='content-center'>
        <?php
        echo $this->Html->Link(
                '<i class = "icon-arrow-left"></i> '.__('Volver'),
                array('controller' => 'centros', 'action' => 'index'),
                array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
        );
        ?>
    </div>
    <div class="ink-alert block warning">
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado datos de catastro de los Centros'); ?></p>
    </div>
<?php
}
?>
