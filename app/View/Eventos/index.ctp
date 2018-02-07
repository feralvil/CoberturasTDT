<?php
// Botones de navegación con JQuery
$next = $this->Paginator->counter('{:page}') + 1;
$prev = $this->Paginator->counter('{:page}') - 1;
$ultima = $this->Paginator->counter('{:pages}');
$fecha = 'Nada';
if (isset($this->request->data['Evento']['historico'])){
    $fecha  = $this->request->data['Evento']['historico'];
}
$this->Js->get("#anterior");
$this->Js->event('click', "$('#EventoIrapag').val($prev);$('#EventoIndexForm').submit();");
$this->Js->get("#siguiente");
$this->Js->event('click', "$('#EventoIrapag').val($next);$('#EventoIndexForm').submit();");
$this->Js->get("#primera");
$this->Js->event('click', "$('#EventoIrapag').val(1);$('#EventoIndexForm').submit();");
$this->Js->get("#historico");
$this->Js->event('click', "$('#EventoFecha').val('$fecha');$('#HistoricosExcel').submit();");
$this->Js->get("select");
$this->Js->event('change', '$("#EventoIndexForm").submit();');
$this->Js->get("#ultima");
$this->Js->event('click', "$('#EventoIrapag').val($ultima);$('#EventoIndexForm').submit();");
$this->Js->get("#excel");
$this->Js->event('click', '$("#EventosExcel").submit();');
$neventos = count($eventos);
?>
<h1><?php echo __('Eventos de Centros TDT BTESA de la Generalitat');?></h1>
<?php
echo $this->Form->create('Evento',array(
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
            '<i class = "icon-refresh"></i>',
            array('controller' => 'eventos', 'action' => 'index'),
            array('id' => 'resetear', 'title' => __('Borrar Criterios'), 'escape' => false)
    );
    ?>
</h4>
<fieldset class="column-group gutters">
    <div class="control-group large-40">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Evento.centro_id', __('Centro TDT'), array('class' => 'content-right large-30'));
            echo $this->Form->input('Evento.centro_id', array('options' => $centrosel, 'empty' => __('Seleccionar'), 'div' => 'control large-70'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array(0 => __('Sistema'), 1 => __('Alarma'), 2 => __('Aviso'), 3 => __('Información'));
            echo $this->Form->label('Evento.id_nivel', __('Nivel de Evento'), array('class' => 'content-right large-50'));
            echo $this->Form->input('Evento.id_nivel', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-50'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array(0 => __('Activación de Alarma'), 1 => __('Cese de Alarma'), 2 => __('Mensaje Espontáneo'), 3 => __('Evento Desconocido'));
            echo $this->Form->label('Evento.id_tipo', __('Tipo de Evento'), array('class' => 'content-right large-50'));
            echo $this->Form->input('Evento.id_tipo', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-50'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array();
            foreach ($fechas as $fecha) {
                $opciones[$fecha . ' 00:00:00'] = $fecha . ' 00:00:00';
            }
            echo $this->Form->label('Evento.fechadesde', __('Eventos desde'), array('class' => 'content-right large-40'));
            echo $this->Form->input('Evento.fechadesde', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-60'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array();
            $fechaini = '2016-01-01';
            if ((isset($this->request->data['Evento'])) && ($this->request->data['Evento']['fechadesde'] != '')){
                $fechavect = explode(' ', $this->request->data['Evento']['fechadesde']);
                $fechaini = $fechavect[0];
            }
            foreach ($fechas as $fecha) {
                if ($fecha >= $fechaini){
                    $opciones[$fecha . ' 23:59:59'] = $fecha . ' 23:59:59';
                }
            }
            echo $this->Form->label('Evento.fechahasta', __('Eventos hasta'), array('class' => 'content-right large-40'));
            echo $this->Form->input('Evento.fechahasta', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-60'));
            ?>
        </div>
    </div>
    <?php
    if (count($historicos) > 0){
    ?>
        <div class="control-group large-30">
            <div class="column-group gutters">
                <?php
                echo $this->Form->label('Evento.anyos', __('Histórico'), array('class' => 'content-right large-20'));
                echo $this->Form->input('Evento.anyos', array('options' => $anyos, 'empty' => __('Año'), 'div' => 'control large-30'));
                if ((isset($this->request->data['Evento']['anyos'])) && ($this->request->data['Evento']['anyos'] != "")) {
                    $anyo = $this->request->data['Evento']['anyos'];
                    $histsel = array();
                    foreach ($historicos as $mesnum => $mestxt) {
                        if (strncmp($mesnum, $anyo, 4) == 0){
                            $histsel[$mesnum] = $mestxt;
                        }
                    }
                    echo $this->Form->input('Evento.historico', array('options' => $histsel, 'empty' => __('Mes'), 'div' => 'control large-30'));
                    if ((isset($this->request->data['Evento']['historico'])) && ($this->request->data['Evento']['historico'] != "")) {
                        echo $this->Form->button(
                            '<i class = "icon-calendar"></i>',
                            array('id' => 'historico', 'class' => 'ink-button blue', 'title' => __('Exportar a Excel'), 'alt' => __('Exportar a Excel'), 'escape' => false)
                        );
                    }
                }
                ?>
            </div>
        </div>
    <?php
    }
    ?>
</fieldset>

<h4>
    <?php
        echo __('Resultados de la Búsqueda');
        if ($neventos > 0){
            echo ' &mdash; '.$this->Paginator->counter("Eventos {:start} a {:end} de {:count}");
            $toteventos = $this->Paginator->counter("{:count}");
        }
    ?>
</h4>
<div class="column-group gutters">
    <div class="large-40 control-group column-group gutters">
        <?php
        $opciones = array(50 => 50, 100 => 100, 200 => 200, 500 => 500);
        echo $this->Form->label('Evento.regPag', __('Eventos por página'), array('class' => 'content-right large-60'));
        echo $this->Form->input('Evento.regPag', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-40'));
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
                        __('Página') . ' ' . $this->Paginator->counter('{:page}') . '/' . $this->Paginator->counter('{:pages}'), '#', array('title' => __('Página') . ' ' . $this->Paginator->counter('{:page}'), 'escape' => false)
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
    <div class="control-group large-20">
        <?php
        if (((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) && (isset($this->request->data['Evento']['centro_id'])) && ($this->request->data['Evento']['centro_id'] > 0) && ($neventos > 0) && ($toteventos < 10000)) {
            echo $this->Html->Link(
                '<i class = "icon-calendar"></i> '.__('Excel'), '#',
                array('id' => 'excel', 'class' => 'ink-button blue', 'title' => __('Exportar a Excel'), 'alt' => __('Exportar a Excel'), 'escape' => false)
            );
        }
        ?>
    </div>
</div>
<?php
echo $this->Form->end();
echo $this->Form->create('Evento',array(
    'inputDefaults' => array('label' => false, 'div' => false),
    'action' => 'xlshistorico', 'id' => 'HistoricosExcel', 'target' => '_blank'
));
echo $this->Form->hidden('fecha');
echo $this->Form->end();
if ($neventos > 0){
    if (isset($this->request->data['Evento'])){
        echo $this->Form->create('Evento',array(
            'inputDefaults' => array('label' => false, 'div' => false),
            'action' => 'xlseventos', 'id' => 'EventosExcel', 'target' => '_blank'
        ));
        echo $this->Form->hidden('centro_id', array('value' => $this->request->data['Evento']['centro_id']));
        echo $this->Form->hidden('id_nivel', array('value' => $this->request->data['Evento']['id_nivel']));
        echo $this->Form->hidden('id_tipo', array('value' => $this->request->data['Evento']['id_tipo']));
        echo $this->Form->hidden('fechadesde', array('value' => $this->request->data['Evento']['fechadesde']));
        echo $this->Form->hidden('fechahasta', array('value' => $this->request->data['Evento']['fechahasta']));
        echo $this->Form->end();
    }
?>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th><?php echo __('Centro');?></th>
            <th><?php echo __('Ámbito');?></th>
            <th><?php echo __('Origen');?></th>
            <th><?php echo __('Fecha Activación');?></th>
            <th><?php echo __('Fecha Cese');?></th>
            <th><?php echo __('Nivel');?></th>
            <th><?php echo __('Tipo');?></th>
            <th><?php echo __('Alarma');?></th>
            <th><?php echo __('Valor Act.');?></th>
            <th><?php echo __('Valor Cese');?></th>
        </tr>
        <?php
        foreach ($eventos as $evento) {
        ?>
            <tr>
                <td><?php echo $evento['Evento']['centro']; ?></td>
                <td><?php echo $evento['Evento']['ambito']; ?></td>
                <td><?php echo $evento['Evento']['origen']; ?></td>
                <td><?php echo $evento['Evento']['fecha_act']; ?></td>
                <td><?php echo $evento['Evento']['fecha_cese']; ?></td>
                <td><?php echo $evento['Evento']['nivel']; ?></td>
                <td><?php echo $evento['Evento']['tipo']; ?></td>
                <td><?php echo $evento['Evento']['alarma']; ?></td>
                <td><?php echo $evento['Evento']['valor_act']; ?></td>
                <td><?php echo $evento['Evento']['valor_cese']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
}
else{
?>
    <div class="ink-alert block warning">
        <h4>Error en la búsqueda</h4>
        <p><?php echo __('No se han encontrado Eventos con los criterios de búsqueda seleccionados');?></p>
    </div>
<?php
}
?>
