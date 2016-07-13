<?php
$nemisiones = count($emisiones);
?>
<h1><?php echo __('Emisiones de Centros TDT de la Comunitat');?></h1>
<h4>
    <?php 
        echo __('Resultados de la Búsqueda');
        if ($nemisiones > 0){
            echo ' &mdash; '.$nemisiones.' '.__('Emisiones');
        }
    ?>
</h4>
<?php
if ($nemisiones > 0){
    
}
else{
    
}
?>
<?php
if ($nemisiones > 0){
    $id = 0;
?>
    <div class='content-center'>
        <?php
        echo $this->Html->Link(
                '<i class = "icon-arrow-left"></i> '.__('Volver'),
                array('controller' => 'centros', 'action' => 'index'),
                array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
        );
        echo $this->Html->Link(
                '<i class = "icon-download-alt"></i> '.__('Importar'),
                array('controller' => 'centros', 'action' => 'impemisiones'),
                array('class' => 'ink-button blue', 'title' => __('Importar'), 'alt' => __('Importar'), 'escape' => false)
        );
        ?>
    </div>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th><?php echo __('ID');?></th>
            <th><?php echo __('CID');?></th>
            <th><?php echo __('Centro');?></th>
            <th><?php echo __('MID');?></th>
            <th><?php echo __('Múltiple');?></th>
            <th><?php echo __('Canal');?></th>
            <th><?php echo __('Frec (MHz)');?></th>
            <th><?php echo __('Tipo');?></th>
        </tr>
<?php
        foreach ($emisiones as $emision) {
            $id++;
?>
            <tr>
                <td><?php echo $id;?></td>
                <td><?php echo $emision['centro_id'];?></td>
                <td><?php echo $emision['centro'];?></td>
                <td><?php echo $emision['multiple_id'];?></td>
                <td><?php echo $emision['multiple'];?></td>
                <td><?php echo $emision['canal'];?></td>
                <td><?php echo $emision['frecuencia'];?></td>
                <td><?php echo $emision['tipo'];?></td>
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
}
?>


