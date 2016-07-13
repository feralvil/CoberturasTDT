<h1><?php echo __('Importar Equipos BTESA del Centro') . ' ' . $centro['Centro']['centro'];?></h1>
<h2><?php echo __('Fichero de datos a Importar');?></h2>
<?php
echo $this->Form->create('Programa',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false
    ),
    'type' => 'file',
    'class' => 'ink-form'
));
?>
<div class="ink-alert basic" role="alert">
    <button class="ink-dismiss">&times;</button>
    <p>
        <b><?php echo __('Atención') ?>:</b> <?php echo __('La importación borrará los equipos de BTESA de la Base de Datos para este Centro') ?>
    </p>
</div>
<fieldset class="column-group gutters">
    <div class="control-group large-60 required validation error">
        <?php
        echo $this->Form->label('Centro.fichhw', __('Fichero de datos'));
        echo $this->Form->input('Centro.fichhw', array('div' => array('class' => 'control'), 'type' => 'file'));
        ?>
    </div>
</fieldset>
<div class='content-center'>
    <?php
    echo $this->Form->button(
            '<i class = "icon-save"></i> '.__('Guardar cambios'),
            array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar archivo'), 'alt' => __('Guardar archivo'), 'escape' => false)
    );
    echo $this->Form->button(
            '<i class = "icon-undo"></i> '.__('Cancelar cambios'),
            array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar cambios'), 'alt' => __('Cancelar cambios'), 'escape' => false)
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
