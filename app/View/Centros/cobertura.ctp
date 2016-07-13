<h1><?php echo __('Cobertura TDT del Centro de').' '.$centro['Centro']['centro'];?></h1>
<h2><?php echo __('Canales Emitidos');?> &mdash; <?php echo $centro['Centro']['nmux']; ?></h2>
<table class="ink-table bordered hover alternating">
    <tr>
        <th><?php echo __('Nº Múltiples'); ?></th>
        <th><?php echo __('Tipo SFN'); ?></th>
        <th><?php echo __('Tipo RGE'); ?></th>
        <th><?php echo __('Tipo TDT-A'); ?></th>
    </tr>
    <tr>
        <td><?php echo $centro['Centro']['nmux']; ?></td>
        <td><?php echo $centro['Centro']['tipo_sfns']; ?></td>
        <td><?php echo $centro['Centro']['tipo_rge']; ?></td>
        <td><?php echo $centro['Centro']['tipo_tdta']; ?></td>
    </tr>
</table>

<h2><?php echo __('Municipios Cubiertos');?></h2>
<?php
$totCubiertos = 0;
if (!empty($centro['Cobertura'])){
?>
    <table class="ink-table bordered hover alternating">
        <tr>
            <th>
                <?php echo __('Acciones');?> &mdash;
                <?php
                echo $this->Html->Link(
                        '<i class = "icon-plus"></i>',
                        array('controller' => 'users', 'action' => 'agregar'),
                        array('title' => __('Agregar Municipio'), 'alt' => __('Agregar Municipio'), 'escape' => false)
                );
                ?>
            </th>
            <th><?php echo __('Provincia'); ?></th>
            <th><?php echo __('Municipio'); ?></th>
            <th><?php echo __('Población'); ?></th>
            <th><?php echo __('Hab. Cubiertos (%)'); ?></th>            
        </tr>
<?php
        $i = 0;
        $totHabitantes = 0;
        foreach ($centro['Cobmuni'] as $cobertura) {
            $i++;
            $totHabitantes += $cobertura['poblacion'];
            $totCubiertos += $cobertura['habcub'];
?>
            <tr>
                <td class="content-center">
                    <?php 
                    echo $this->Form->postLink(
                            '<i class = "icon-edit"></i>',
                            array('controller' => 'users', 'action' => 'resetear', $centro['Centro']['id']), 
                            array('title' => __('Modificar Cobertura'), 'alt' => __('Modificar Cobertura'), 'escape' => false), 
                            __('¿Seguro que desea resetear la contraseña del Usuario')." '". $cobertura['municipio'] . "'?"
                            );
                    ?> &mdash;
                    <?php 
                    echo $this->Form->postLink(
                            '<i class = "icon-trash"></i>',
                            array('controller' => 'cobertura', 'action' => 'borrar', $centro['Centro']['id']), 
                            array('title' => __('Borrar Cobertura'), 'escape' => false), 
                            __('¿Seguro que desea eliminar el municipio')." '". $cobertura['municipio'] . "'?"
                            );
                    ?>
                </td>
                <td><?php echo $cobertura['provincia']; ?></td>
                <td>
                    <?php
                    echo $cobertura['municipio'].' &mdash; ';
                    echo $this->Html->Link(
                            '<i class = "icon-circle-arrow-right"></i>',
                            array('controller' => 'municipios', 'action' => 'detalle', $cobertura['municipio_id']),
                            array('title' => __('Ir a Municipio'), 'escape' => false)
                        );
                    ?>
                </td>
                <td class='content-right'><?php echo $this->Number->format($cobertura['poblacion'], array('places' => 0, 'before' => '', 'thousands' => '.')); ?></td>
                <td class='content-right'><?php echo $this->Number->format($cobertura['habcub'], array('places' => 0, 'before' => '', 'thousands' => '.')).' '.$cobertura['cobertura']; ?></td>
            </tr>
<?php            
        }
        $porCubiertos = 100 * ($totCubiertos / $totHabitantes);
?>
            <tr>
                <th colspan='3'><?php echo __('Totales'); ?></th>
                <th class='content-right'><?php echo $this->Number->format($totHabitantes, array('places' => 0, 'before' => '', 'thousands' => '.'));?></th>
                <th class='content-right'><?php echo $this->Number->format($totCubiertos, array('places' => 0, 'before' => '', 'thousands' => '.')).' ('.$this->Number->format($porCubiertos, array('places' => 2, 'before' => '', 'thousands' => '.')).' %)';?></th>
            </tr>
    </table>
<?php
}
else{
?>
    <div class="ink-alert block warning">
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado municipios cubiertos desde este centro');?></p>
    </div>
<?php    
}
?>
<div id="formulario">
    <h3><?php echo __('Agregar municipio a la cobertura del Centro');?></h3>
    
</div>