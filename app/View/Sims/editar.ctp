<h1><?php echo __('Modificar SIM del Centro') . ' ' . $this->data['Centro']['centro'];?></h1>
<?php
echo $this->Form->create('Sim',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<h3><?php echo __('Datos de la tarjeta');?></h3>
<fieldset class="column-group gutters">
    <?php echo $this->Form->input('centro_id', array('type' => 'hidden')); ?>
    <?php echo $this->Form->input('centro_id', array('type' => 'hidden')); ?>
    <div class="control-group large-30 required validation error">
        <?php
        echo $this->Form->label('Sim.numero', __('Número'));
        echo $this->Form->input('Sim.numero', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-40">
        <?php
        echo $this->Form->label('Sim.icc', __('ICC'));
        echo $this->Form->input('Sim.icc', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30">
        <?php
        echo $this->Form->label('Sim.cobertura', __('Cobertura'));
        echo $this->Form->input('Sim.cobertura', array(
            'options' => array('GPRS' => 'GPRS', 'UMTS' => 'UMTS'),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
    <div class="control-group large-25">
        <?php
        echo $this->Form->label('Sim.dir_ip', __('Dirección IP'));
        echo $this->Form->input('Sim.dir_ip', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-25">
        <?php
        echo $this->Form->label('Sim.usuario', __('Usuario'));
        echo $this->Form->input('Sim.usuario', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-25">
        <?php
        echo $this->Form->label('Sim.contrasenya', __('Contraseña'));
        echo $this->Form->input('Sim.contrasenya', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-25">
        <?php
        echo $this->Form->label('Sim.uso', __('Uso'));
        echo $this->Form->input('Sim.uso', array(
            'options' => array('TDT' => 'Supervisión TDT', 'SUP' => 'Supervisión del Centro', 'OTR' => 'Otros Usos'),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
    <div class="control-group large-100">
        <?php
        echo $this->Form->label('Sim.comentarios', __('Comentarios'));
        echo $this->Form->input('Sim.comentarios', array('div' => array('class' => 'control')));
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
            array('controller' => 'centros', 'action' => 'supervision', $this->data['Sim']['centro_id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
