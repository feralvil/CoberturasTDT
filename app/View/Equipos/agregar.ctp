<?php
// Funciones JQuery:
$functab = "$('div[class*=\"tabs-content\"]').addClass('hide-all');";
$functab .= "var divshow = $('select#seltipo').val();";
$functab .= "$('div#' + divshow).removeClass('hide-all');";
$this->Js->get("select#seltipo");
$this->Js->event('change', $functab);
?>
<h1><?php echo __('Agregar Equipo al Centro TDT') . ' ' . $centro['Centro']['centro'];?></h1>
<?php
$tipoequipo = array(
    'SUP' => __('Supervisor'), 'COFRE' => __('Cofre'), 'CONTPAN' => ('Panel de Control'), 'TARJETA' => __('Tarjeta'),
    'YAGI' => __('Antena Yagi'), 'MTR' => __('Decodificador Satélite'), 'GPSC' => 'Cofre GPS', 'GPST' => 'Tarjeta GPS',
    'MODEM' => __('Módem 3G/GPRS'),
);
echo $this->Form->create('Equipo',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<h3><?php echo __('Seleccionar tipo de equipo');?></h3>
<fieldset class="column-group gutters">
    <div class="control-group large-100 required validation error">
        <?php
        echo $this->Form->hidden('Equipo.centro_id', array('value' => $centro['Centro']['id']));
        echo $this->Form->label('Equipo.tipo', __('Tipo de Equipo'));
        echo $this->Form->input('Equipo.tipo', array('options' => $tipoequipo, 'empty' => __('Seleccionar'), 'div' => 'control', 'id' => 'seltipo'));
        ?>
    </div>
</fieldset>
<div id="SUP" class="tabs-content hide-all">
    <fieldset class="column-group gutters">
        <div class="control-group large-20 required validation error">
            <?php
            echo $this->Form->label('Equipo.marca', __('Marca del Equipo'));
            echo $this->Form->input('Equipo.marca', array('div' => array('class' => 'control'), 'value' => 'BTESA'));
            ?>
        </div>
        <div class="control-group large-30 required validation error">
            <?php
            echo $this->Form->label('Equipo.codhw', __('Código Hardware'));
            echo $this->Form->input('Equipo.codhw', array('div' => array('class' => 'control')));
            ?>
        </div>
        <div class="control-group large-20 required validation error">
            <?php
            echo $this->Form->label('Equipo.codsw', __('Código Software'));
            echo $this->Form->input('Equipo.codsw', array('div' => array('class' => 'control')));
            ?>
        </div>
        <div class="control-group large-30">
            <?php
            echo $this->Form->label('Equipo.nserie', __('Número de Serie'));
            echo $this->Form->input('Equipo.nserie', array('div' => array('class' => 'control')));
            ?>
        </div>
    </fieldset>
</div>
<div id="COFRE" class="tabs-content hide-all">
    <fieldset class="column-group gutters">
        <div class="control-group large-30 required validation error">
            <?php
            echo $this->Form->label('Equipo.marca', __('Marca del Equipo'));
            echo $this->Form->input('Equipo.marca', array('div' => array('class' => 'control'), 'value' => 'BTESA'));
            ?>
        </div>
        <div class="control-group large-30 required validation error">
            <?php
            echo $this->Form->label('Equipo.codhw', __('Código Hardware'));
            echo $this->Form->input('Equipo.codhw', array('div' => array('class' => 'control')));
            ?>
        </div>
        <div class="control-group large-40">
            <?php
            echo $this->Form->label('Equipo.nserie', __('Número de Serie'));
            echo $this->Form->input('Equipo.nserie', array('div' => array('class' => 'control')));
            ?>
        </div>
    </fieldset>
</div>

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
            array('controller' => 'equipos', 'action' => 'centro', $centro['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
