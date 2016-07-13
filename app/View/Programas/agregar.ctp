<h1><?php echo __('Nuevo Programa TDT de la Comunitat');?></h1>
<?php
echo $this->Form->create('Programa',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'type' => 'file', 
    'class' => 'ink-form'
));
?>
<h3><?php echo __('Datos del Programa');?></h3>
<fieldset class="column-group gutters">
    <div class="control-group large-40 required validation error">
        <?php
        echo $this->Form->label('Programa.nombre', __('Nombre del Programa'));
        echo $this->Form->input('Programa.nombre', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Programa.multiple_id', __('Múltiple'));
        echo $this->Form->input('Programa.multiple_id', array(
            'options' => $multiplesel,
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Programa.codificado', __('Codificado'));
        echo $this->Form->input('Programa.codificado', array(
            'options' => array('SI' => 'Sí', 'NO' => __('No')),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Programa.altadef', __('Alta definición'));
        echo $this->Form->input('Programa.altadef', array(
            'options' => array('SI' => 'Sí', 'NO' => __('No')),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
    <div class="control-group large-60 required validation error">
        <?php
        echo $this->Form->label('Programa.fichlogo', __('Logo del Programa'));
        echo $this->Form->input('Programa.fichlogo', array('div' => array('class' => 'control'), 'type' => 'file'));
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
            array('controller' => 'programas', 'action' => 'index'),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
