<?php
// Botones de navegación con JQuery
$next = $this->Paginator->counter('{:page}') + 1;
$prev = $this->Paginator->counter('{:page}') - 1;
$ultima = $this->Paginator->counter('{:pages}');
$this->Js->get("#anterior");
$this->Js->event('click', "$('#CentrosIrapag').val($prev);$('#CentrosTipologiaForm').submit()");
$this->Js->get("#siguiente");
$this->Js->event('click', "$('#CentrosIrapag').val($next);$('#CentrosTipologiaForm').submit()");
$this->Js->get("#primera");
$this->Js->event('click', "$('#CentrosIrapag').val(1);$('#CentrosTipologiaForm').submit()");
$this->Js->get("#ultima");
$this->Js->event('click', "$('#CentrosIrapag').val($ultima);$('#CentrosTipologiaForm').submit()");
$this->Js->get("select");
$this->Js->event('change', '$("#CentrosTipologiaForm").submit()');
$funcresetear = "$('#CentrosIrapag').val(1);";
$funcresetear .= "$('#CentrosRegPag').val(30);";
$funcresetear .= "$('select').val('');";
$funcresetear .= "$('input').val('');";
$funcresetear .= "$('#CentrosTipologiaForm').submit()";
$this->Js->get("#resetear");
$this->Js->event('click', $funcresetear);
$ncentros = count($centrosext);
?>
<h1><?php echo __('Centros TDT de la Generalitat');?></h1>
<?php
echo $this->Form->create('Centros',array(
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
            echo $this->Form->label('Municipio.provincia', __('Provincia'), array('class' => 'content-right large-20'));
            echo $this->Form->input('provincia', array('options' => $provsel, 'empty' => __('Seleccionar Provincia'), 'div' => 'control large-80'));
            ?> 
        </div>
    </div>
    <div class="control-group large-50">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Centro.nombre', __('Centro'), array('class' => 'content-right large-20'));
            ?>
            <div class="control large-80 append-button">
                <span><?php echo $this->Form->input('nombre');?></span>
                <?php 
                echo $this->Form->button('<i class="icon-search"></i>', array(
                    'alt' => 'Buscar', 'title' => 'Buscar', 'escape' => false, 'class' => 'ink-button'));
                ?>
            </div>
        </div>
    </div>
</fieldset>
<?php
echo $this->Form->end();
?>

<h4>
    <?php 
        echo __('Resultados de la Búsqueda');
        if ($ncentros > 0){
            echo ' &mdash; '.$this->Paginator->counter("Centros <b>{:start}</b> a <b>{:end}</b> de <b>{:count}</b>");
        }
    ?>
    
</h4>
<?php
if ($ncentros > 0){
?>
    <div class="column-group gutters">
        <div class="large-40 control-group column-group gutters">
            <?php
            $opciones = array(30 => 30, 50 => 50, 100 => 100, $this->Paginator->counter('{:count}') => 'Todos');
            echo $this->Form->label('Centro.regPag', __('Centros por página'), array('class' => 'content-right large-40'));
            echo $this->Form->input('Centro.regPag', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-60'));
            ?>
        </div>
        <div class="large-40 content-center">
            <nav class="ink-navigation push-center">
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
        <div class="large-20 content-center">
            <?php
                if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')){
                    echo $this->Html->Link(
                            '<i class = "icon-share"></i>'.' '.__('Actualizar tipología'),
                            array('controller' => 'centros', 'action' => 'updateTipo'),
                            array('title' => __('Actualizar tipología'), 'alt' => __('Actualizar tipología'),'class' => 'ink-button blue', 'escape' => false)
                    );
                }
            ?>
        </div>
    </div>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th>
                <?php echo __('Acciones');?>
                <?php
                if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')){
                    echo ' &mdash; ';
                    echo $this->Html->Link(
                            '<i class = "icon-plus"></i>',
                            array('controller' => 'centros', 'action' => 'agregar'),
                            array('title' => __('Nuevo Centro'), 'alt' => __('Nuevo Centro'), 'escape' => false)
                    );
                }
                ?>
            </th>
            <th><?php echo __('ID');?></th>
            <th><?php echo __('Centro');?></th>
            <th><?php echo __('Nº Múx');?></th>
            <th><?php echo __('Nº Municipios');?></th>
            <th><?php echo __('Nº Habitantes');?></th>
            <th><?php echo __('Tipología');?></th>
            <th><?php echo __('T. resp A1');?></th>
            <th><?php echo __('T. resp A2');?></th>
        </tr>
<?php
        foreach ($centrosext as $centro) {
?>
            <tr>
                <td class='content-center'>
                    <?php
                        echo $this->Html->Link(
                            '<i class = "icon-zoom-in"></i>',
                            array('controller' => 'centros', 'action' => 'detalle', $centro['id']),
                            array('title' => __('Detalle'), 'escape' => false)
                        );
                    ?>
                </td>
                <td class="content-center"><?php echo $centro['id'];?></td>
                <td><?php echo $centro['centro'];?></td>
                <td class="content-center"><?php echo $centro['nmux'];?></td>
                <td class="content-center"><?php echo $centro['nmuni'];?></td>
                <td class="content-center"><?php echo $this->Number->format($centro['nhabcub'], array('places' => 0, 'before' => '', 'thousands' => '.')); ?></td>
                <?php
                    if ($centro['tipologia'] == $centro['tipobbdd']){
                        $icono = "icon-ok";
                    }
                    else{
                        $icono = "icon-remove";
                    }
                ?>
                <td class="content-center"><?php echo $centro['tipobbdd'];?> &mdash; <i class="<?php echo $icono;?>"></i></td>
                <td class="content-center"><?php echo $centro['ta1'];?> h.</td>
                <td class="content-center"><?php echo $centro['ta2'];?> h.</td>
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
        <p><?php echo __('No se han encontrado Centros con los criterios de búsqueda seleccionados');?></p>
    </div>
<?php
}
?>
