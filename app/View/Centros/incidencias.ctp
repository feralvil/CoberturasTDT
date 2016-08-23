<h1><?php echo __('Actualizar ID de Eventos de Centro TDT');?></h1>
<table class="ink-table bordered alternating hover">
    <tr>
        <th><?php echo __('Centro');?></th>
        <th><?php echo __('Equipo');?></th>
    </tr>
    <tr>
        <td><?php echo $centro['Centro']['centro'];?></td>
        <td><?php echo $centro['Centro']['equipo'];?></td>
    </tr>
</table>

<h2><?php echo __('Seleccionar Centro TDT de la BBDD de Supervision');?></h2>
<?php
echo $this->Form->create('Centro',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
if ($centro['Centro']['equipo'] == 'BTESA'){
?>
    <fieldset class="column-group gutters">
        <div class="control-group large-60 required validation error">
            <?php
            echo $this->Form->label('Centro.eventos_id', __('Seleccionar centro BBDD'));
            echo $this->Form->input('Centro.eventos_id', array(
                'options' => $centrosinc,
                'div' => array('class' => 'control'),
                'empty' => __('Seleccionar')
                )
            );
            ?>
        </div>
    </fieldset>
<?php
}
else{
?>
    <div class="ink-alert block warning">
        <h4><?php echo __('Centro NO Supervisado');?></h4>
        <p><?php echo __('El equipamiento de este centro NO es de BTESA por lo que no aparece en la Consola de Supervisión'); ?></p>
    </div>
<?php
}
?>
<div class='content-center'>
    <?php
    if ($centro['Centro']['equipo'] == 'BTESA'){
        echo $this->Form->button(
                '<i class = "icon-save"></i> '.__('Guardar cambios'),
                array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar Datos'), 'alt' => __('Guardar Datos'), 'escape' => false)
        );
        echo $this->Form->button(
                '<i class = "icon-undo"></i> '.__('Cancelar cambios'),
                array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar cambios'), 'alt' => __('Cancelar cambios'), 'escape' => false)
        );
    }
    echo $this->Html->Link(
            '<i class = "icon-arrow-left"></i> '.__('Volver'),
            array('controller' => 'centros', 'action' => 'detalle', $centro['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    if (isset($vecinos['prev'])){
        $centropre = $vecinos['prev']['Centro']['id'];
        echo $this->Html->Link(
                '<i class = "icon-backward"></i> '.__('Centro Anterior'),
                array('controller' => 'centros', 'action' => 'incidencias', $centropre),
                array('class' => 'ink-button blue', 'title' => __('Centro Anterior'), 'alt' => __('Centro Anterior'), 'escape' => false)
        );
    }
    if (isset($vecinos['next'])){
        $centrosig = $vecinos['next']['Centro']['id'];
        echo $this->Html->Link(
                '<i class = "icon-forward"></i> '.__('Centro Siguiente'),
                array('controller' => 'centros', 'action' => 'incidencias', $centrosig),
                array('class' => 'ink-button blue', 'title' => __('Centro Siguiente'), 'alt' => __('Centro Siguiente'), 'escape' => false)
        );
    }
    ?>
</div>
<?php
echo $this->Form->end();
?>
