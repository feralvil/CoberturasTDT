<h1><?php echo __('Nuevo Centro TDT de la Generalitat');?></h1>
<?php
echo $this->Form->create('Centros',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<h3><?php echo __('Datos del Centro');?></h3>
<fieldset class="column-group gutters">
    <div class="control-group large-60 required validation error">
        <?php
        echo $this->Form->label('Centro.centro', __('Nombre del Centro'));
        echo $this->Form->input('Centro.centro', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-40 required validation error">
        <?php
        echo $this->Form->label('Centro.tipologia', __('Tipología'));
        echo $this->Form->input('Centro.tipologia', array(
            'options' => array('C1' => 'C1', 'C2' => 'C2'),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
</fieldset>

<h3><?php echo __('Datos del Centro');?></h3>
<fieldset class="column-group gutters">
    <div class="control-group large-30">
        <?php
        echo $this->Form->label('Centro.latitud', __('Latitud'));
        echo $this->Form->input('Centro.latitud', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30">
        <?php
        echo $this->Form->label('Centro.longitud', __('Longitud'));
        echo $this->Form->input('Centro.longitud', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Centro.utmx', __('UTMX'));
        echo $this->Form->input('Centro.utmx', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Centro.utmy', __('UTMY'));
        echo $this->Form->input('Centro.utmy', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Centro.provincia', __('Provincia'));
        echo $this->Form->input('Centro.provincia', array(
            'options' => array('03' => 'Alicante/Alacant', '12' => 'Castellón/Castelló', '46' => 'Valencia/València'),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
    <div class="control-group large-50">
        <?php
        echo $this->Form->label('Centro.catastro', __('Ref. Catastral'));
        echo $this->Form->input('Centro.catastro', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30">
        <?php
        echo $this->Form->label('Centro.dimensiones', __('Dimensiones de la parcela'));
        echo $this->Form->input('Centro.dimensiones', array('div' => array('class' => 'control')));
        ?>
    </div>
</fieldset>

<h3><?php echo __('Datos de Propiedad del Centro');?></h3>
<fieldset class="column-group gutters">
    <div class="control-group large-25">
        <?php
        echo $this->Form->label('Centro.suelo', __('Propiedad del Suelo'));
        echo $this->Form->input('Centro.suelo', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-25">
        <?php
        echo $this->Form->label('Centro.caseta', __('Propiedad de la Caseta'));
        echo $this->Form->input('Centro.caseta', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-25">
        <?php
        echo $this->Form->label('Centro.torre', __('Propiedad de la Torre'));
        echo $this->Form->input('Centro.torre', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-25">
        <?php
        echo $this->Form->label('Centro.electrico', __('Suministro eléctrico'));
        echo $this->Form->input('Centro.electrico', array('div' => array('class' => 'control')));
        ?>
    </div>
</fieldset>

<h3><?php echo __('Otros datos del Centro');?></h3>
<fieldset class="column-group gutters">
    <div class="control-group large-33">
        <?php
        echo $this->Form->label('Centro.pajustada', __('Potencia ajustada'));
        echo $this->Form->input('Centro.pajustada', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-33">
        <?php
        echo $this->Form->label('Centro.equipo', __('Tipo de equipo'));
        echo $this->Form->input('Centro.equipo', array('div' => array('class' => 'control'),));
        ?>
    </div>
    <div class="control-group large-33">
        <?php
        echo $this->Form->label('Centro.polaridad', __('Polaridad'));
        echo $this->Form->input('Centro.polaridad', array(
            'options' => array('H' => __('Horizontal'), 'V' => __('Vertical')),
            'div' => array('class' => 'control'),
            'empty' => __('Seleccionar')
            )
        );
        ?>
    </div>
    <div class="control-group large-100">
        <?php
        echo $this->Form->label('Centro.info', __('Notas'));
        echo $this->Form->input('Centro.info', array('div' => array('class' => 'control')));
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
            array('controller' => 'centros', 'action' => 'index'),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
