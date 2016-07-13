<h1><?php echo __('Multiples del Centro TDT de ').$this->data['Centro']['centro'];?></h1>
<h2><?php echo __('Modificar múltiple del Centro'); ?></h2>

<?php
echo $this->Form->create('Emision',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>

<fieldset class="column-group gutters">
    <div class="control-group large-30 required validation error">
        <?php
        echo $this->Form->label('Emision.multiple_id', __('Múltiple'));
        echo $this->Form->input('Emision.multiple_id', array(
            'options' => $multisel,
            'disabled' => true,
            'empty' => __('Seleccionar '),
            'div' => array('class' => 'control')
            )
        );
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Emision.canal', __('Canal'));
        echo $this->Form->input('Emision.canal', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30">
        <?php
        echo $this->Form->label('Emision.tipo', __('Tipo'));
        echo $this->Form->input('Emision.tipo', array(
            'options' => array('Emisor' => 'Emisor', 'Gap-Filler' => 'Gap-Filler'),
            'empty' => __('Seleccionar '),
            'div' => array('class' => 'control')
            )
        );
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Emision.canal', __('Retardo') . ' (x100 ns)');
        echo $this->Form->input('Emision.retardo', array('div' => array('class' => 'control')));
        ?>
    </div>
</fieldset>

<div class='content-center'>
    <?php
    echo $this->Form->button(
            '<i class = "icon-save"></i> '.__('Guardar cambios'),
            array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar cambios'), 'alt' => __('Guardar cambios'), 'escape' => false)
    );
    echo $this->Form->button(
            '<i class = "icon-undo"></i> '.__('Cancelar cambios'),
            array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar cambios'), 'alt' => __('Cancelar cambios'), 'escape' => false)
    );
    echo $this->Html->Link(
            '<i class = "icon-arrow-left"></i> '.__('Volver'),
            array('controller' => 'centros', 'action' => 'multiples', $this->data['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
