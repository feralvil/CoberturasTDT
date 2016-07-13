<h1><?php echo __('Nueva Cobertura del Centro TDT de').' '.$centro['Centro']['centro'];?></h1>
<h3><?php echo __('Municipios Cubiertos');?></h3>
<?php
if (!empty($centro['Cobertura'])){
?>
    <table class="ink-table bordered hover alternating">
        <tr>
            <th><?php echo __('Nº'); ?></th>
            <th><?php echo __('Provincia'); ?></th>
            <th><?php echo __('Municipio'); ?></th>
            <th><?php echo __('Población'); ?></th>
            <th><?php echo __('Hab. Cubiertos (%)'); ?></th>
            <th><?php echo __('Acciones'); ?></th>
        </tr>
<?php
        $i = 0;
        foreach ($centro['Cobmuni'] as $cobertura) {
            $i++;
?>
            <tr>
                <td><?php echo $i; ?></td>
                <td><?php echo $cobertura['provincia']; ?></td>
                <td>
                    <?php
                    echo $cobertura['municipio'].' &mdash; ';
                    echo $this->Html->Link(
                            '<i class = "icon-circle-arrow-right"></i>',
                            array('controller' => 'municipios', 'action' => 'detalle', $cobertura['municipio_id']),
                            array('title' => __('Ir a Municipio'), 'escape' => false)
                        );
                    ?>
                </td>
                <td class='content-right'><?php echo $this->Number->format($cobertura['poblacion'], array('places' => 0, 'before' => '', 'thousands' => '.')); ?></td>
                <td class='content-right'><?php echo $this->Number->format($cobertura['habcub'], array('places' => 0, 'before' => '', 'thousands' => '.')).' '.$cobertura['cobertura']; ?></td>
                <td class="content-center">
                    <?php
                    echo $this->Html->Link(
                            '<i class = "icon-edit"></i>',
                            array('controller' => 'coberturas', 'action' => 'editar', $cobertura['idcob']),
                            array('title' => __('Modificar Cobertura'), 'alt' => __('Modificar Cobertura'), 'escape' => false)
                    );
                    echo ' &mdash; ';
                    echo $this->Form->postLink(
                            '<i class = "icon-trash"></i>',
                            array('controller' => 'coberturas', 'action' => 'borrar', $cobertura['idcob']), 
                            array('title' => __('Borrar Cobertura'), 'alt' => __('Borrar Cobertura'), 'escape' => false), 
                            __('¿Seguro que desea eliminar el municipio')." '". $cobertura['municipio'] . "'?"
                    );
                    ?>
                </td>
            </tr>
<?php            
        }
?>
    </table>
<?php
}
else{
?>
    <div class="ink-alert block warning">
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado municipios cubiertos desde este centro');?></p>
    </div>
<?php    
}
?>


<?php
echo $this->Form->create('Cobertura',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<h3>Seleccionar Municipio a agregar</h3>
<fieldset class="column-group gutters">
    <div class="control-group large-60">
        <?php
        echo $this->Form->label('Cobertura.municipio_id', __('Municipio'));
        echo $this->Form->input('Cobertura.municipio_id', array(
            'options' => $munisel,
            'empty' => __('Seleccionar Municipio'),
            'div' => array('id' => 'InputMuni', 'class' => 'control'),
            'after' => '<p class="tip">'.__('Seleccionar un municipio al que el centro seleccionado ofrezca cobertura').'</p>'
            )
        );
        ?>
    </div>
    <div class="control-group large-40">
        <?php
        echo $this->Form->label('Cobertura.porcentaje', __('% Población'));
        echo $this->Form->input('Cobertura.porcentaje', array(
            'div' => array('class' => 'control'),
            'after' => '<p class="tip">'.__('Inidicar el % de población del municipio cubierto por el centro').'</p>'
            )
        );
        ?>
    </div>
</fieldset>
<div id="Botones" class='content-center'>
    <?php
    echo $this->Form->button(
            '<i class = "icon-save"></i> '.__('Guardar cambios'),
            array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar cambios'), 'alt' => __('Guardar Municipio'), 'escape' => false)
    );
    echo $this->Form->button(
            '<i class = "icon-undo"></i> '.__('Cancelar cambios'),
            array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar cambios'), 'alt' => __('Agregar Municipio'), 'escape' => false)
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