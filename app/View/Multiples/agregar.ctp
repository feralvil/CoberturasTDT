<h1><?php echo __('Nuevo Múltiple TDT de la Comunitat');?></h1>
<?php
echo $this->Form->create('Multiples',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<h3><?php echo __('Datos del Múltiple');?></h3>
<fieldset class="column-group gutters">
    <div class="control-group large-40 required validation error">
        <?php
        echo $this->Form->label('Multiple.nombre', __('Nombre del Múltiple'));
        echo $this->Form->input('Multiple.nombre', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30 required validation error">
        <?php
        echo $this->Form->label('Multiple.ambito', __('Ámbito'));
        echo $this->Form->input('Multiple.ambito', array(
            'options' => array('NAC' => 'Nacional', 'AUT' => __('Autonómico'), 'LOC' => 'Local'),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
    <div class="control-group large-30 required validation error">
        <?php
        echo $this->Form->label('Multiple.soportado', __('Soportado'));
        echo $this->Form->input('Multiple.soportado', array(
            'options' => array('SI' => 'Sí', 'NO' => __('No')),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
</fieldset>

<div class='content-center'>
    <?php
    echo $this->Form->button(
            '<i class = "icon-save"></i> '.__('Guardar cambios'),
            array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar Centro'), 'alt' => __('Guardar Centro'), 'escape' => false)
    );
    echo $this->Form->button(
            '<i class = "icon-undo"></i> '.__('Cancelar cambios'),
            array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar cambios'), 'alt' => __('Cancelar cambios'), 'escape' => false)
    );
    echo $this->Html->Link(
            '<i class = "icon-arrow-left"></i> '.__('Volver'),
            array('controller' => 'multiples', 'action' => 'index'),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
