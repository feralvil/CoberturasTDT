<?php
// Funciones JQuery
//$funcShowMuni = '$("div").removeClass("hide-all");';
//$funcShowMuni .= '$("#LabelMuni").removeClass("hide-all");';
$funcShowMuni = <<<FUNCION
        $("#CoberturaMunicipio").prop("selectedIndex", 0);
        var centro = $(this).val();
        if (centro == ""){
            $("#InputMuni").addClass("hide-all");
            $("#LabelMuni").addClass("hide-all");
            $("#BotonMuni").addClass("hide-all");
        }
        else{
            $("#InputMuni").removeClass("hide-all");
            $("#LabelMuni").removeClass("hide-all");
        }
FUNCION;
$this->Js->get("#CoberturaCentro")->event('change', $funcShowMuni);
$funcShowBoton = <<<FUNCION
        var muni = $(this).val();
        if (muni == ""){
            $("#BotonMuni").addClass("hide-all");
        }
        else{
            $("#BotonMuni").removeClass("hide-all");
        }
FUNCION;
$this->Js->get("#CoberturaMunicipio")->event('change', $funcShowBoton);
?>
<h1><?php echo __('Nueva Cobertura de Centros TDT de la Generalitat');?></h1>
<?php
echo $this->Form->create('Cobertura',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
?>
<fieldset>
    <legend>Seleccionar Centro y Municipios Cubiertos</legend>
    <div class="control-group column-group gutters">
        <?php
        echo $this->Form->label('Cobertura.centro', __('Centro'), array('class' => 'content-right large-10'));
        echo $this->Form->input('Cobertura.centro', array(
            'options' => $centrosel,
            'empty' => __('Seleccionar Centro'),
            'div' => 'control large-90',
            'after' => '<p class="tip">Seleccionar el Centro al que se le desean agregar coberturas</p>'
            )
        );
        echo $this->Form->label('Cobertura.municipio', __('Municipio'), array('class' => 'content-right large-10 hide-all', 'id' => 'LabelMuni'));
        echo $this->Form->input('Cobertura.municipio', array(
            'options' => $munisel,
            'empty' => __('Seleccionar Municipio'),
            'div' => array('id' => 'InputMuni', 'class' => 'control large-70 hide-all'),
            'after' => '<p class="tip">Seleccionar un municipio al que el centro seleccionado ofrezca cobertura</p>'
            )
        );
        ?>
        <div id="BotonMuni" class='control large-20 content-left hide-all'>
            <?php
            echo $this->Form->button(
                    '<i class = "icon-plus"></i> &mdash; '.__('Agregar'),
                    array(
                        'type' => 'button',
                        'class' => 'ink-button blue',
                        'escape' => false
                    )
            );
        ?>
        </div>
    </div>
</fieldset>
<?php
echo $this->Form->end();
?>