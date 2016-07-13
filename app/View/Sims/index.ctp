<?php
// Botones de navegación con JQuery
$next = $this->Paginator->counter('{:page}') + 1;
$prev = $this->Paginator->counter('{:page}') - 1;
$ultima = $this->Paginator->counter('{:pages}');
$this->Js->get("#anterior");
$this->Js->event('click', "$('#SimIrapag').val($prev);$('#SimIndexForm').submit()");
$this->Js->get("#siguiente");
$this->Js->event('click', "$('#SimIrapag').val($next);$('#SimIndexForm').submit()");
$this->Js->get("#primera");
$this->Js->event('click', "$('#SimIrapag').val(1);$('#SimIndexForm').submit()");
$this->Js->get("select");
$this->Js->event('change', '$("#SimIndexForm").submit()');
$this->Js->get("#ultima");
$this->Js->event('click', "$('#SimIrapag').val($ultima);$('#SimIndexForm').submit()");
$funcresetear = "$('#SimIrapag').val(1);";
$funcresetear .= "$('#SimRegPag').val(30);";
$funcresetear .= "$('select').val('');";
$funcresetear .= "$('input').val('');";
$funcresetear .= "$('#SimIndexForm').submit()";
$this->Js->get("#resetear");
$this->Js->event('click', $funcresetear);
$ntarjetas = count($sims);
?>
<h1><?php echo __('Tarjetas de Supervisión TDT');?></h1>
<?php
echo $this->Form->create('Sim',array(
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form',
    'novalidate' => true
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
    <div class="control-group large-40">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Sim.centro_id', __('Centro'), array('class' => 'content-right large-20'));
            echo $this->Form->input('Sim.centro_id', array('options' => $centrosel, 'empty' => __('Seleccionar Centro'), 'div' => 'control large-80'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array('TDT' => __('Supervisión TDT'), 'SUP' => __('Supervisión de Centro'), 'OTR' => __('Otros Usos'));
            echo $this->Form->label('Sim.uso', __('Uso'), array('class' => 'content-right large-20'));
            echo $this->Form->input('Sim.uso', array('options' => $opciones, 'empty' => __('Seleccionar Uso'), 'div' => 'control large-80'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array('GPRS' => 'GPRS', 'UMTS' => 'UMTS');
            echo $this->Form->label('Sim.cobertura', __('Cobertura'), array('class' => 'content-right large-50'));
            echo $this->Form->input('Sim.cobertura', array('options' => $opciones, 'empty' => __('Seleccionar Uso'), 'div' => 'control large-50'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Sim.numero', __('Número'), array('class' => 'content-right large-20'));
            ?>
            <div class="control large-80 append-button">
                <span><?php echo $this->Form->input('Sim.numero');?></span>
                <?php
                echo $this->Form->button('<i class="icon-search"></i>', array(
                    'alt' => 'Buscar', 'title' => 'Buscar', 'id' => 'buscaNumero', 'escape' => false, 'class' => 'ink-button blue'));
                ?>
            </div>
        </div>
    </div>
    <div class="control-group large-40">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Sim.icc', __('ICC'), array('class' => 'content-right large-20'));
            ?>
            <div class="control large-80 append-button">
                <span><?php echo $this->Form->input('Sim.icc');?></span>
                <?php
                echo $this->Form->button('<i class="icon-search"></i>', array(
                    'alt' => 'Buscar', 'title' => 'Buscar', 'id' => 'buscaIcc', 'escape' => false, 'class' => 'ink-button blue'));
                ?>
            </div>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Sim.dir_ip', __('Dirección IP'), array('class' => 'content-right large-40'));
            ?>
            <div class="control large-60 append-button">
                <span><?php echo $this->Form->input('Sim.dir_ip');?></span>
                <?php
                echo $this->Form->button('<i class="icon-search"></i>', array(
                    'alt' => 'Buscar', 'title' => 'Buscar', 'id' => 'buscaDirip', 'escape' => false, 'class' => 'ink-button blue'));
                ?>
            </div>
        </div>
    </div>

</fieldset>
<h4>
    <?php
        echo __('Resultados de la Búsqueda');
        if ($ntarjetas > 0){
            echo ' &mdash; '.$this->Paginator->counter(__('Tarjetas') . " <b>{:start}</b> a <b>{:end}</b> de <b>{:count}</b>");
        }
    ?>
</h4>
<?php
if ($ntarjetas > 0){
?>
    <div class="column-group gutters">
        <div class="large-40 control-group">
            <?php
            $opciones = array(30 => 30, 50 => 50, 100 => 100, $this->Paginator->counter('{:count}') => 'Todos');
            echo $this->Form->label('Sim.regPag', __('Tarjetas por página'), array('class' => 'content-right large-60'));
            echo $this->Form->input('Sim.regPag', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-40'));
            ?>
        </div>
        <div class="large-40 content-center">
            <nav class="ink-navigation">
                <ul class="pagination blue shadowed rounded">
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
                    echo '<li class="active">';
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
        <div class="large-20 control-group">
            <?php
            if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                echo $this->Html->Link(
                    '<i class = "icon-calendar"></i> '.__('Exportar a Excel'),
                    array('controller' => 'sims', 'action' => 'xlssims'),
                    array('class' => 'ink-button blue', 'title' => __('Exportar a Excel'), 'alt' => __('Exportar a Excel'), 'target' => '_blank', 'escape' => false)
                );
            }
            ?>
        </div>
    </div>
    <?php
    echo $this->Form->end();
    ?>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th><?php echo __('Acciones');?></th>
            <th><?php echo __('Centro');?></th>
            <th><?php echo __('Número');?></th>
            <th><?php echo __('ICC');?></th>
            <th><?php echo __('Uso');?></th>
            <th><?php echo __('Cobertura');?></th>
            <th><?php echo __('Dirección IP');?></th>
        </tr>
<?php
        foreach ($sims as $sim) {
            if ($sim['Sim']['uso'] == "TDT"){
                $uso = __("Supervisión TDT");
            }
            elseif ($sim['Sim']['uso'] == "SUP"){
                $uso = __("Supervisión Centro");
            }
            else{
                $uso = __("Otros usos");
            }
?>
            <tr>
                <td class='content-center'>
                    <?php
                    if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                        echo $this->Html->Link(
                            '<i class = "icon-edit"></i>',
                            array('controller' => 'sims', 'action' => 'editar', $sim['Sim']['id']),
                            array('title' => __('Editar Tarjeta'), 'alt' => __('Editar Tarjeta'), 'escape' => false)
                        );
                        echo ' &mdash; ';
                        echo $this->Form->postLink(
                            '<i class = "icon-trash"></i>',
                            array('controller' => 'sims', 'action' => 'borrar', $sim['Sim']['id']),
                            array('title' => __('Eliminar Tarjeta'), 'alt' => __('Eliminar Tarjeta'), 'escape' => false), __('¿Seguro que desea eliminar la tarjeta número') . " '" . $sim['Sim']['numero'] . "'?"
                        );
                    }
                    ?>
                </td>
                <td><?php echo $sim['Centro']['centro'];?></td>
                <td><?php echo $sim['Sim']['numero'];?></td>
                <td><?php echo $sim['Sim']['icc'];?></td>
                <td><?php echo $uso;?></td>
                <td><?php echo $sim['Sim']['cobertura'];?></td>
                <td><?php echo $sim['Sim']['dir_ip'];?></td>
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
        <p><?php echo __('No se han encontrado tarjetas con los criterios de búsqueda seleccionados');?></p>
    </div>
    <?php
    echo $this->Form->end();
    ?>
<?php
}
?>
