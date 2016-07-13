<?php
// Botones de navegación con JQuery
$next = $this->Paginator->counter('{:page}') + 1;
$prev = $this->Paginator->counter('{:page}') - 1;
$ultima = $this->Paginator->counter('{:pages}');
$this->Js->get("#anterior");
$this->Js->event('click', "$('#ProgramaIrapag').val($prev);$('#ProgramaIndexForm').submit()");
$this->Js->get("#siguiente");
$this->Js->event('click', "$('#ProgramaIrapag').val($next);$('#ProgramaIndexForm').submit()");
$this->Js->get("#primera");
$this->Js->event('click', "$('#ProgramaIrapag').val(1);$('#ProgramaIndexForm').submit()");
$this->Js->get("select");
$this->Js->event('change', '$("#ProgramaIndexForm").submit()');
$this->Js->get("#ultima");
$this->Js->event('click', "$('#ProgramaIrapag').val($ultima);$('#ProgramaIndexForm').submit()");
$funcresetear = "$('#ProgramaIrapag').val(1);";
$funcresetear .= "$('#ProgramaRegPag').val(30);";
$funcresetear .= "$('select').val('');";
$funcresetear .= "$('input').val('');";
$funcresetear .= "$('#ProgramaIndexForm').submit()";
$this->Js->get("#resetear");
$this->Js->event('click', $funcresetear);
$nprogramas = count($programas);
?>
<h1><?php echo __('Programas TDT de la Comunitat');?></h1>
<?php
echo $this->Form->create('Programa',array(
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
    <div class="control-group large-40">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Programa.multiple_id', __('Múltiple'), array('class' => 'content-right large-20'));
            echo $this->Form->input('Programa.multiple_id', array('options' => $multiplesel, 'empty' => __('Seleccionar'), 'div' => 'control large-80'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array('SI' => 'Sí', 'NO' => __('No'));
            echo $this->Form->label('Programa.altadef', __('Alta definición'), array('class' => 'content-right large-60'));
            echo $this->Form->input('Programa.altadef', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-40'));
            ?>
        </div>
    </div>
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array('SI' => 'Sí', 'NO' => __('No'));
            echo $this->Form->label('Programa.codificado', __('Codificado'), array('class' => 'content-right large-60'));
            echo $this->Form->input('Programa.codificado', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-40'));
            ?>
        </div>
    </div>
</fieldset>
<h4>
    <?php
        echo __('Resultados de la Búsqueda');
        if ($nprogramas > 0){
            echo ' &mdash; '.$this->Paginator->counter("Programas <b>{:start}</b> a <b>{:end}</b> de <b>{:count}</b>");
        }
    ?>
</h4>
<?php
if ($nprogramas > 0){
?>
    <div class="column-group gutters">
        <div class="large-35 control-group column-group gutters">
            <?php
            $opciones = array(30 => 30, 50 => 50, 100 => 100, $this->Paginator->counter('{:count}') => 'Todos');
            echo $this->Form->label('Programa.regPag', __('Programas por página'), array('class' => 'content-right large-60'));
            echo $this->Form->input('Programa.regPag', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-40'));
            ?>
        </div>
        <div class="large-50 content-center">
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
        <div class="control-group large-15 content-center">
            <?php
            echo $this->Html->Link(
                     '<i class = "icon-plus"></i> &mdash; '.__('Agregar'),
                     array('controller' => 'programas', 'action' => 'agregar'),
                     array('class' => 'ink-button blue', 'title' => __('Nuevo Programa'), 'escape' => false)
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
            <th><?php echo __('Nombre');?></th>
            <th><?php echo __('Múltiple');?></th>
            <th><?php echo __('HD');?></th>
            <th><?php echo __('Codificado');?></th>
            <th><?php echo __('Logo');?></th>
        </tr>
<?php
        foreach ($programas as $programa) {
?>
            <tr>
                <td class='content-center'>
                    <?php
                        if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                            echo $this->Html->Link(
                                    '<i class = "icon-edit"></i>',
                                    array('controller' => 'programas', 'action' => 'editar', $programa['Programa']['id']),
                                    array('title' => __('Editar Programa'), 'alt' => __('Editar Programa'), 'escape' => false)
                            );
                            echo ' &mdash; ';
                            echo $this->Form->postLink(
                                    '<i class = "icon-trash"></i>',
                                    array('controller' => 'programas', 'action' => 'borrar', $programa['Programa']['id']),
                                    array('title' => __('Eliminar Programa'), 'alt' => __('Eliminar Programa'), 'escape' => false), __('¿Seguro que desea eliminar el Programa') . " '" . $programa['Programa']['nombre'] . "'?"
                            );
                        }
                    ?>
                </td>
                <td class="content-center"><?php echo $programa['Programa']['id'];?></td>
                <td><?php echo $programa['Programa']['nombre'];?></td>
                <td class="content-center"><?php echo $programa['Multiple']['nombre'];?></td>
                <td class="content-center"><?php echo $programa['Programa']['altadef'];?></td>
                <td class="content-center"><?php echo $programa['Programa']['codificado'];?></td>
                <td class="content-center">
                    <?php
                    echo $this->Html->image($programa['Programa']['logo'], array(
                        'alt' => 'Logo '.$programa['Programa']['nombre'],
                        'title' => 'Logo '.$programa['Programa']['nombre']
                    ));
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
    <div class="ink-alert block warning">
        <h4>Error en la búsqueda</h4>
        <p><?php echo __('No se han encontrado Programas con los criterios de búsqueda seleccionados');?></p>
    </div>
<?php
    echo $this->Form->end();
}
?>
