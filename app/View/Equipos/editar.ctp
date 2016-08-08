<h1><?php echo __('Modificar Equipo del Centro TDT') . ' ' . $this->request->data['Centro']['centro'];?></h1>
<?php
$tipoequipo = array(
    'SUP' => __('Supervisor'), 'COFRE' => __('Cofre'),
    'CONTPAN' => ('Panel de Control'), 'TARJETA' => __('Tarjeta')
);
echo $this->Form->create('Equipo',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<h3>
    <?php
        echo __('Modificar') . ' ' . $tipoequipo[$this->request->data['Equipo']['tipo']]. ' ' . $this->request->data['Equipo']['nombre'];
        if ($this->request->data['Equipo']['tipo'] == 'TARJETA'){
            echo ' &mdash; CH ' . $this->request->data['Equipo']['canal'];
        }
    ?>
</h3>
<fieldset class="column-group gutters">
    <div class="control-group large-20 required validation error">
        <?php
        echo $this->Form->label('Equipo.marca', __('Marca del Equipo'));
        echo $this->Form->input('Equipo.marca', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30 required validation error">
        <?php
        echo $this->Form->label('Equipo.codhw', __('Código Hardware'));
        echo $this->Form->input('Equipo.codhw', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30">
        <?php
        echo $this->Form->label('Equipo.nserie', __('Número de Serie'));
        echo $this->Form->input('Equipo.nserie', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Equipo.codsw', __('Código Software'));
        echo $this->Form->input('Equipo.codsw', array('div' => array('class' => 'control')));
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
            array('controller' => 'equipos', 'action' => 'centro', $this->request->data['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
