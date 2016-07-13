<?php
// Botones de navegación con JQuery
$next = $this->Paginator->counter('{:page}') + 1;
$prev = $this->Paginator->counter('{:page}') - 1;
$ultima = $this->Paginator->counter('{:pages}');
$this->Js->get("#anterior");
$this->Js->event('click', "$('#CoberturasIrapag').val($prev);$('#CoberturasIndexForm').submit()");
$this->Js->get("#siguiente");
$this->Js->event('click', "$('#CoberturasIrapag').val($next);$('#CoberturasIndexForm').submit()");
$this->Js->get("#primera");
$this->Js->event('click', "$('#CoberturasIrapag').val(1);$('#CoberturasIndexForm').submit()");
$this->Js->get("select");
$this->Js->event('change', '$("#CoberturasIndexForm").submit()');
$this->Js->get("#ultima");
$this->Js->event('click', "$('#CoberturasIrapag').val($ultima);$('#CoberturasIndexForm').submit()");
$funcresetear = "$('#CoberturasIrapag').val(1);";
$funcresetear .= "$('#CoberturasRegPag').val(25);";
$funcresetear .= "$('select').val('');";
$funcresetear .= "$('input').val('');";
$funcresetear .= "$('#CoberturasIndexForm').submit()";
$this->Js->get("#resetear");
$this->Js->event('click', $funcresetear);
$ncoberturas = count($coberturas);
?>
<h1><?php echo __('Coberturas de Centros TDT de la Generalitat');?></h1>
<?php
echo $this->Form->create('Coberturas',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
echo $this->Form->hidden('tampag', array('value' => $this->Paginator->counter('{:current}')));
echo $this->Form->hidden('irapag', array('value' => '0'));
?>
<h4>
    <?php 
    echo __('Criterios de Búsqueda');
    echo ' &mdash; ';
    echo $this->Html->Link(
            '<i class = "icon-refresh"></i>', '#', array('id' => 'resetear', 'title' => __('Borrar Criterios'), 'escape' => false)
    );
    ?>
</h4>
<fieldset class="column-group gutters">
    <div class="control-group large-50">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Cobertura.centro', __('Centro'), array('class' => 'content-right large-20'));
            echo $this->Form->input('centro', array('options' => $centrosel, 'empty' => __('Seleccionar Centro'), 'div' => 'control large-80'));
            ?> 
        </div>
    </div>
    <div class="control-group large-50">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Cobertura.municipio', __('Municipio'), array('class' => 'content-right large-20'));
            echo $this->Form->input('municipio', array('options' => $munisel, 'empty' => __('Seleccionar Municipio'), 'div' => 'control large-80'));
            ?> 
        </div>
    </div>
</fieldset>
<h4>
    <?php 
        echo __('Resultados de la Búsqueda');
        if ($ncoberturas > 0){
            echo ' &mdash; '.$this->Paginator->counter("Coberturas <b>{:start}</b> a <b>{:end}</b> de <b>{:count}</b>");
        }
    ?>
</h4>
<?php
if ($ncoberturas > 0){
?>
    <div class="column-group gutters">
        <div class="large-50 control-group column-group gutters">
            <?php
            $opciones = array(20 => 20, 30 => 30, 50 => 50, $this->Paginator->counter('{:count}') => 'Todos');
            echo $this->Form->label('Coberturas.regPag', __('Coberturas por página'), array('class' => 'content-right large-40'));
            echo $this->Form->input('regPag', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-60'));
            ?>
        </div>
        <div class="large-30 content-center">
            <nav class="ink-navigation push-center">
                <ul class="pagination grey shadowed rounded">
                <?php
                    echo '<li';
                    if ($this->Paginator->counter('{:page}') == 1) {
                        echo ' class="disabled"';
                    }
                    echo '>';
                    echo $this->Html->Link(
                            '<i class = "icon-fast-backward"></i>', '#', array('id' => 'primera', 'title' => __('Primera Página'), 'escape' => false)
                    );
                    echo '</li>';
                    echo '<li';
                    if (!$this->Paginator->hasPrev()) {
                        echo ' class="disabled"';
                    }
                    echo '>';
                    echo $this->Html->Link(
                            '<i class = "icon-backward"></i>', '#', array('id' => 'anterior', 'title' => __('Anterior'), 'escape' => false)
                    );
                    echo '</li>';
                    echo '<li>';
                    echo $this->Html->Link(
                            __('Página') . ' ' . $this->Paginator->counter('{:page}') . ' de ' . $this->Paginator->counter('{:pages}'), '#', array('title' => __('Página') . ' ' . $this->Paginator->counter('{:page}'), 'escape' => false)
                    );
                    echo '</li>';
                    echo '<li';
                    if (!$this->Paginator->hasNext()) {
                        echo ' class="disabled"';
                    }
                    echo '>';
                    echo $this->Html->Link(
                            '<i class = "icon-forward"></i>', '#', array('id' => 'siguiente', 'title' => __('Siguiente'), 'escape' => false)
                    );
                    echo '</li>';
                    echo '<li';
                    if ($this->Paginator->counter('{:page}') == $this->Paginator->counter('{:pages}')) {
                        echo ' class="disabled"';
                    }
                    echo '>';
                    echo $this->Html->Link(
                            '<i class = "icon-fast-forward"></i>', '#', array('id' => 'ultima', 'title' => __('Última Página'), 'escape' => false)
                    );
                    echo '</li>';
                ?>
                    
                </ul>
            </nav>
        </div>
        <div class="control-group large-20 content-center">
            <?php
             echo $this->Html->Link(
                     '<i class = "icon-plus"></i> &mdash; '.__('Agregar'),
                     array('controller' => 'coberturas', 'action' => 'agregar'),
                     array('class' => 'ink-button', 'title' => __('Nueva Cobertura'), 'escape' => false)
            );
            ?>
        </div>
    </div>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th><?php echo __('Centro');?></th>
            <th><?php echo __('Nº Múltiplex');?></th>
            <th><?php echo __('Municipio');?></th>
            <th><?php echo __('Población (2012)');?></th>
            <th><?php echo __('Hab. Cubiertos (%)');?></th>
        </tr>
<?php
        foreach ($coberturas as $cobertura) {
?>
            <tr>
                <td>
                    <?php 
                    echo $cobertura['Centro']['centro'].' &mdash; ';
                    echo $this->Html->Link(
                            '<i class = "icon-circle-arrow-right"></i>',
                            array('controller' => 'centros', 'action' => 'detalle', $cobertura['Centro']['id']),
                            array('title' => __('Ir a Centro'), 'escape' => false)
                        );
                    ?>
                </td>
                <td><?php echo $cobertura['Centro']['nmux'];?></td>
                <td>
                    <?php
                    echo $cobertura['Municipio']['nombre'].' ('.$cobertura['Municipio']['provincia'].')'.' &mdash; ';
                    echo $this->Html->Link(
                            '<i class = "icon-circle-arrow-right"></i>',
                            array('controller' => 'municipios', 'action' => 'detalle', $cobertura['Municipio']['id']),
                            array('title' => __('Ir a Centro'), 'escape' => false)
                        );
                    ?>
                </td>
                <td class="content-right">
                    <?php
                        echo $this->Number->format($cobertura['Municipio']['poblacion'], array('places' => 0, 'before' => '', 'thousands' => '.'));
                    ?>
                </td>
                <td class="content-right">
                    <?php
                        $habcub = round($cobertura['Municipio']['poblacion'] * $cobertura['Cobertura']['porcentaje'] / 100);
                        echo $this->Number->format($habcub, array('places' => 0, 'before' => '', 'thousands' => '.')).' ('.$cobertura['Cobertura']['porcentaje'].' %)';
                    ?>
                </td>
            </tr>
<?php

        }
?>

    </table>
<?php
}
else{
?>
    <div class="ink-alert block error">
        <h4>Error en la búsqueda</h4>
        <p><?php echo __('No se han encontrado Coberturas con los criterios de búsqueda seleccionados');?></p>
    </div>
<?php
}
echo $this->Form->end();
?>