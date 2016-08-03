<h1><?php echo __('Modificar Contacto del Centro TDT de ').$this->request->data['Centro']['centro'];?></h1>
<?php
echo $this->Form->create('Contacto',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
echo $this->Form->hidden('Contacto.centro_id', array('value' => $this->request->data['Centro']['id']));
?>
<fieldset class="column-group gutters">
    <div class="control-group large-30 required validation error">
        <?php
        echo $this->Form->label('Contacto.nombre', __('Nombre del Contacto'));
        echo $this->Form->input('Contacto.nombre', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-20 required validation error">
        <?php
        echo $this->Form->label('Contacto.telefono', __('Teléfono'));
        echo $this->Form->input('Contacto.telefono', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30">
        <?php
        echo $this->Form->label('Contacto.cargo', __('Cargo'));
        echo $this->Form->input('Contacto.cargo', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Contacto.mail', __('Mail'));
        echo $this->Form->input('Contacto.mail', array('div' => array('class' => 'control')));
        ?>
    </div>
</fieldset>

<div class='content-center'>
    <?php
    echo $this->Form->button(
            '<i class = "icon-save"></i> '.__('Guardar Contacto'),
            array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar Contacto'), 'alt' => __('Guardar Contacto'), 'escape' => false)
    );
    echo $this->Form->button(
            '<i class = "icon-undo"></i> '.__('Cancelar cambios'),
            array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar cambios'), 'alt' => __('Cancelar cambios'), 'escape' => false)
    );
    echo $this->Html->Link(
            '<i class = "icon-arrow-left"></i> '.__('Volver'),
            array('controller' => 'contactos', 'action' => 'centro', $this->request->data['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>

<?php
echo $this->Form->end();
?>
