<?php
// Botones de navegación con JQuery
$next = $this->Paginator->counter('{:page}') + 1;
$prev = $this->Paginator->counter('{:page}') - 1;
$ultima = $this->Paginator->counter('{:pages}');
$this->Js->get("#anterior");
$this->Js->event('click', "$('#MunicipiosIrapag').val($prev);$('#MunicipiosIndexForm').submit()");
$this->Js->get("#siguiente");
$this->Js->event('click', "$('#MunicipiosIrapag').val($next);$('#MunicipiosIndexForm').submit()");
$this->Js->get("#primera");
$this->Js->event('click', "$('#MunicipiosIrapag').val(1);$('#MunicipiosIndexForm').submit()");
$this->Js->get("select");
$this->Js->event('change', '$("#MunicipiosIndexForm").submit()');
$this->Js->get("#ultima");
$this->Js->event('click', "$('#MunicipiosIrapag').val($ultima);$('#MunicipiosIndexForm').submit()");
$funcresetear = "$('#MunicipiosIrapag').val(1);";
$funcresetear .= "$('#MunicipiosRegPag').val(25);";
$funcresetear .= "$('select').val('');";
$funcresetear .= "$('input').val('');";
$funcresetear .= "$('#MunicipiosIndexForm').submit()";
$this->Js->get("#resetear");
$this->Js->event('click', $funcresetear);
$nmunicipios = count($municipios);
?>
<h1><?php echo __('Municipios de la Comunitat Valenciana');?></h1>
<?php
echo $this->Form->create('Municipios',array(
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
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Municipio.provincia', __('Provincia'), array('class' => 'content-right large-20'));
            echo $this->Form->input('provincia', array('options' => $provsel, 'empty' => __('Seleccionar Provincia'), 'div' => 'control large-80'));
            ?>
        </div>
    </div>
    <div class="control-group large-40">
        <div class="column-group gutters">
            <?php
            echo $this->Form->label('Municipio.nombre', __('Municipio'), array('class' => 'content-right large-20'));
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
    <div class="control-group large-30">
        <div class="column-group gutters">
            <?php
            $opciones = array('ES' => __('Castellano'), 'VA' => __('Valencià'));
            echo $this->Form->label('Municipio.idioma', __('Idioma'), array('class' => 'content-right large-20'));
            echo $this->Form->input('idioma', array('options' => $opciones, 'empty' => __('Seleccionar Idioma'), 'div' => 'control large-80'));
            ?>
        </div>
    </div>
</fieldset>

<h4>
    <?php
        echo __('Resultados de la Búsqueda');
        if ($nmunicipios > 0){
            echo ' &mdash; '.$this->Paginator->counter("Municipios <b>{:start}</b> a <b>{:end}</b> de <b>{:count}</b>");
        }
    ?>

</h4>
<?php
if ($nmunicipios > 0){
?>
    <div class="column-group gutters">
        <div class="large-30 control-group column-group gutters">
            <?php
            $opciones = array(30 => 30, 50 => 50, 100 => 100, $this->Paginator->counter('{:count}') => 'Todos');
            echo $this->Form->label('Municipio.regPag', __('Municipios por página'), array('class' => 'content-right large-60'));
            echo $this->Form->input('regPag', array('options' => $opciones, 'empty' => __('Seleccionar'), 'div' => 'control large-40'));
            ?>
        </div>
        <div class="large-50 content-center">
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

        <?php
        if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
        ?>
            <div class="control-group large-10">
                <?php
                echo $this->Html->Link(
                         '<i class = "icon-paper-clip"></i>  '.__('Carta'),
                         array('controller' => 'municipios', 'action' => 'carta'),
                         array('class' => 'ink-button blue', 'title' => __('Anexo a Carta'), 'escape' => false)
                );
                ?>
            </div>
            <div class="control-group large-10">
                <?php
                echo $this->Html->Link(
                         '<i class = "icon-calendar"></i>  '.__('Excel'),
                         array('controller' => 'municipios', 'action' => 'xlsmunicipios'),
                         array('class' => 'ink-button blue', 'title' => __('Exportar a Excel'), 'escape' => false, 'target' => '_blank')
                );
                ?>
            </div>
        <?php
        }
        ?>
    </div>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th><?php echo __('Acciones');?></th>
            <th><?php echo __('Cod. INE');?></th>
            <th><?php echo __('Provincia');?></th>
            <th><?php echo __('Municipio');?></th>
            <th><?php echo __('Habitantes (2016)');?></th>
            <th><?php echo __('Hogares (2011)');?></th>
            <th><?php echo __('Idioma');?></th>
            <th><?php echo __('Centros TDT');?></th>
        </tr>
<?php
        foreach ($municipios as $municipio) {
            $idioma = __('Castellano');
            if ($municipio['Municipio']['idioma'] == 'VA'){
                $idioma = __('Valencià');
            }
?>
            <tr>
                <td class='content-center'>
                    <?php
                        echo $this->Html->Link(
                            '<i class = "icon-zoom-in"></i>',
                            array('controller' => 'municipios', 'action' => 'detalle', $municipio['Municipio']['id']),
                            array('title' => __('Detalle'), 'escape' => false)
                        );
                    ?>
                </td>
                <td class="content-center"><?php echo $municipio['Municipio']['id'];?></td>
                <td><?php echo $municipio['Municipio']['provincia'];?></td>
                <td><?php echo $municipio['Municipio']['nombre'];?></td>
                <td class="content-right">
                    <?php
                        echo $this->Number->format($municipio['Municipio']['poblacion'], array('places' => 0, 'before' => '', 'thousands' => '.'));
                    ?>
                </td>
                <td class="content-right">
                    <?php
                        echo $this->Number->format($municipio['Municipio']['hogares'], array('places' => 0, 'before' => '', 'thousands' => '.'));
                    ?>
                </td>
                <td class="content-center"><?php echo $idioma;?></td>
                <td class="content-right"><?php echo count($municipio['Cobertura']);?></td>
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
        <p><?php echo __('No se han encontrado Municipios con los criterios de búsqueda seleccionados');?></p>
    </div>
<?php
}
echo $this->Form->end();
?>
