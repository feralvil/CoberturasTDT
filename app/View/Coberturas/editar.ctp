<h1><?php echo __('Editar Cobertura del Centro TDT de').' '.$this->request->data['Centro']['centro'];?></h1>
<?php
echo $this->Form->create('Cobertura',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>

<h3>Cobertura a modificar</h3>
<fieldset class="column-group gutters">
    <div class="control-group large-60">
        <?php
        echo $this->Form->label('Cobertura.municipio_id', __('Municipio'));
        echo $this->Form->input('Cobertura.municipio_id', array(
            'options' => $munisel,
            'disabled' => true,
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
            array('controller' => 'centros', 'action' => 'detalle', $this->request->data['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>