<?php
// Botones de navegación con JQuery
$next = $this->Paginator->counter('{:page}') + 1;
$prev = $this->Paginator->counter('{:page}') - 1;
$ultima = $this->Paginator->counter('{:pages}');
$this->Js->get("#anterior");
$this->Js->event('click', "$('#CentrosIrapag').val($prev);$('#CentrosIndexForm').submit()");
$this->Js->get("#siguiente");
$this->Js->event('click', "$('#CentrosIrapag').val($next);$('#CentrosIndexForm').submit()");
$this->Js->get("#primera");
$this->Js->event('click', "$('#CentrosIrapag').val(1);$('#CentrosIndexForm').submit()");
$this->Js->get("select");
$this->Js->event('change', '$("#CentrosIndexForm").submit()');
$this->Js->get("#ultima");
$this->Js->event('click', "$('#CentrosIrapag').val($ultima);$('#CentrosIndexForm').submit()");
$funcresetear = "$('#CentrosIrapag').val(1);";
$funcresetear .= "$('#CentrosRegPag').val(30);";
$funcresetear .= "$('select').val('');";
$funcresetear .= "$('input').val('');";
$funcresetear .= "$('#CentrosIndexForm').submit()";
$this->Js->get("#resetear");
$this->Js->event('click', $funcresetear);
$ncentros = count($centros);
$totHabitantes = 0;
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
    <div class="control-group large-33">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Municipio.provincia', __('Provincia'), array('class' => 'content-right large-30'));
            echo $this->Form->input('provincia', array('options' => $provsel, 'empty' => __('Seleccionar'), 'div' => 'control large-70'));
            ?>
        </div>
    </div>
    <div class="control-group large-33">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Centro.nombre', __('Centro'), array('class' => 'content-right large-20'));
            ?>
            <div class="control large-80 append-button">
                <span><?php echo $this->Form->input('nombre');?></span>
                <?php
                echo $this->Form->button('<i class="icon-search"></i>', array(
                    'alt' => 'Buscar', 'title' => 'Buscar', 'escape' => false, 'class' => 'ink-button blue'));
                ?>
            </div>
        </div>
    </div>
    <div class="control-group large-33">
        <div class="column-group gutters">
            <?php
            $opciones = array('C1' => 'Tipo C1', 'C2' => 'Tipo C2');
            echo $this->Form->label('Centro.tipologia', __('Tipología'), array('class' => 'content-right large-40'));
            echo $this->Form->input('tipologia', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-60'));
            ?>
        </div>
    </div>
    <div class="control-group large-33">
        <div class="column-group gutters">
            <?php
            $opciones = array('SI' => 'Sí', 'NO' => 'No');
            echo $this->Form->label('Centro.activo', __('Activo'), array('class' => 'content-right large-40'));
            echo $this->Form->input('activo', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-60'));
            ?>
        </div>
    </div>
    <div class="control-group large-33">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Centro.equipo', __('Equipo'), array('class' => 'content-right large-30'));
            echo $this->Form->input('equipo', array('options' => $equiposel, 'empty' => __('Seleccionar'), 'div' => 'control large-70'));
            ?>
        </div>
    </div>
    <div class="control-group large-33">
        <div class="column-group gutters">
            <?php
            $opciones = array('H' => __('Horizontal'), 'V' => 'Vertical');
            echo $this->Form->label('Centro.polaridad', __('Polaridad'), array('class' => 'content-right large-30'));
            echo $this->Form->input('polaridad', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-70'));
            ?>
        </div>
    </div>
</fieldset>
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
        <div class="large-30 control-group column-group gutters">
            <?php
            $opciones = array(30 => 30, 50 => 50, 100 => 100, $this->Paginator->counter('{:count}') => 'Todos');
            echo $this->Form->label('Centro.regPag', __('Centros por página'), array('class' => 'content-right large-60'));
            echo $this->Form->input('regPag', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-40'));
            ?>
        </div>
        <div class="large-30 content-center">
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
        <div class="control-group large-40">
            <?php
            if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                echo $this->Html->Link(
                    '<i class = "icon-plus"></i> '.__('Agregar'),
                    array('controller' => 'centros', 'action' => 'agregar'),
                    array('class' => 'ink-button blue', 'title' => __('Agregar'), 'alt' => __('Nuevo Centro'), 'escape' => false)
                );
                echo $this->Html->Link(
                     '<i class = "icon-paper-clip"></i> '.__('Anexo'),
                     array('controller' => 'centros', 'action' => 'xlsanexo'),
                     array('class' => 'ink-button blue', 'title' => __('Anexo'), 'alt' => __('Anexo'), 'target' => '_blank',  'escape' => false)
                );
            }
            echo $this->Html->Link(
                 '<i class = "icon-calendar"></i> '.__('Excel'),
                 array('controller' => 'centros', 'action' => 'xlscentros'),
                 array('class' => 'ink-button blue', 'title' => __('Excel'), 'alt' => __('Exportar a Excel'), 'target' => '_blank',  'escape' => false)
            );
            echo $this->Html->Link(
                 '<i class = "icon-download"></i> '.__('Descargar'),
                 array('controller' => 'centros', 'action' => 'xlsdescargar'),
                 array('class' => 'ink-button blue', 'title' => __('Descargar'), 'alt' => __('Descargar BBDD'), 'target' => '_blank',  'escape' => false)
            );
            ?>
        </div>
    </div>
    <?php
    echo $this->Form->end();
    ?>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th><?php echo __('Acciones');?></th>
            <th><?php echo __('ID');?></th>
            <th><?php echo __('Centro');?></th>
            <th><?php echo __('Tipo');?></th>
            <th><?php echo __('Nº Múx');?></th>
            <?php
            foreach ($multiples as $nom_mux) {
            ?>
                <th><?php echo $nom_mux;?></th>
            <?php
            }
            ?>
            <th><?php echo __('Municipios cubiertos');?></th>
            <th><?php echo __('Habitantes cubiertos');?></th>
        </tr>
<?php
        foreach ($centros as $centro) {
?>
            <tr>
                <td class='content-center'>
                    <?php
                        echo $this->Html->Link(
                            '<i class = "icon-zoom-in"></i>',
                            array('controller' => 'centros', 'action' => 'detalle', $centro['Centro']['id']),
                            array('title' => __('Detalle'), 'escape' => false)
                        );
                        if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                            echo ' &mdash; ';
                            echo $this->Html->Link(
                                    '<i class = "icon-edit"></i>',
                                    array('controller' => 'centros', 'action' => 'editar', $centro['Centro']['id']),
                                    array('title' => __('Editar Centro'), 'alt' => __('Editar Centro'), 'escape' => false)
                            );
                            echo ' &mdash; ';
                            echo $this->Form->postLink(
                                    '<i class = "icon-trash"></i>',
                                    array('controller' => 'centros', 'action' => 'borrar', $centro['Centro']['id']),
                                    array('title' => __('Eliminar Centro'), 'alt' => __('Eliminar Centro'), 'escape' => false), __('¿Seguro que desea eliminar el Centro') . " '" . $centro['Centro']['centro'] . "'?"
                            );
                        }
                    ?>
                </td>
                <td class="content-center"><?php echo $centro['Centro']['id'];?></td>
                <td><?php echo $centro['Centro']['centro'];?></td>
                <td class="content-center"><?php echo $centro['Centro']['tipologia'];?></td>
                <td class="content-center"><?php echo count($centro['Emision']);?></td>
                <?php
                    foreach ($multiples as $id_mux => $nom_mux) {
                ?>
                        <td class="content-center"><?php echo $centro['Centro'][$nom_mux];?></td>
                <?php
                    }
                ?>
                <td class="content-center"><?php echo count($centro['Cobertura']);?></td>
                <td class="content-center"><?php echo $this->Number->format($centro['Centro']['habCubiertos'], array('places' => 0, 'before' => '', 'thousands' => '.'));?></td>
            </tr>
<?php
            $totHabitantes = $totHabitantes + $centro['Centro']['habCubiertos'];
        }
?>
        <tr>
            <th colspan="12"><?php echo __('Total Habitantes');?></th>
            <td class="content-center"><?php echo $this->Number->format($totHabitantes, array('places' => 0, 'before' => '', 'thousands' => '.'));?></td>
        </tr>
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
    echo $this->Form->end();
}
?>
