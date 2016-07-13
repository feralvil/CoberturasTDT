<h1><?php echo __('Supervisión del Centro TDT de ').$centro['Centro']['centro'];?></h1>
<?php
$ntarjetas = count($centro['Sim']);
?>
<h2><?php echo $ntarjetas.' '.__('Tarjeta(s) de Supervisión'); ?></h2>
<div class='content-center'>
    <?php
    echo $this->Html->Link(
            '<i class = "icon-plus"></i> '.__('Añadir tarjeta'),
            array('controller' => 'sims', 'action' => 'agregar', $centro['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Añadir tarjeta'), 'alt' => __('Añadir tarjeta'), 'escape' => false)
    );
    echo $this->Html->Link(
            '<i class = "icon-arrow-left"></i> '.__('Volver'),
            array('controller' => 'centros', 'action' => 'detalle', $centro['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
if ($ntarjetas > 0){
    foreach ($centro['Sim'] as $sim) {
        if ($sim['uso'] == "TDT"){
            $uso = __("Tarjeta de Supervisión TDT");
        }
        elseif ($sim['uso'] == "SUP"){
            $uso = __("Tarjeta de Supervisión Centro");
        }
        else{
            $uso = __("Otros usos");
        }
?>
        <h3><?php echo $uso;?></h3>
        <table class="ink-table bordered hover">
            <tr>
                <th><?php echo __('Número'); ?></th>
                <td><?php echo $sim['numero']; ?></td>
                <th><?php echo __('ICC'); ?></th>
                <td><?php echo $sim['icc']; ?></td>
                <th><?php echo __('Cobertura'); ?></th>
                <td><?php echo $sim['cobertura']; ?></td>
            </tr>
            <tr>
                <th><?php echo __('Dirección IP'); ?></th>
                <td><?php echo $sim['dir_ip']; ?></td>
                <th><?php echo __('Usuario'); ?></th>
                <td><?php echo $sim['usuario']; ?></td>
                <th><?php echo __('Contraseña'); ?></th>
                <td><?php echo $sim['contrasenya']; ?></td>
            </tr>
            <tr>
                <th><?php echo __('Comentarios'); ?></th>
                <td colspan="5"><?php echo $sim['comentarios']; ?></td>
            </tr>
        </table>
        <div class='content-center'>
            <?php
            echo $this->Html->Link(
                '<i class = "icon-edit"></i> ' .  __('Editar Tarjeta'),
                array('controller' => 'sims', 'action' => 'editar', $sim['id']),
                array('class' => 'ink-button blue', 'title' => __('Editar Tarjeta'), 'alt' => __('Editar Tarjeta'), 'escape' => false)
            );
            echo $this->Form->postLink(
                '<i class = "icon-trash"></i> '.  __('Eliminar Tarjeta'),
                array('controller' => 'sims', 'action' => 'borrar', $sim['id']),
                array('class' => 'ink-button blue', 'title' => __('Eliminar Tarjeta'), 'alt' => __('Eliminar Tarjeta'), 'escape' => false), '¿'.__('Seguro que desea eliminar la tarjeta nº').' '.$sim['numero'].' '.__('del Centro')." '" . $centro['Centro']['centro'] . "'?"
            );
            ?>
        </div>
    <?php
    }
}
else{
?>
    <div class="ink-alert block warning">
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado tarjetas de supervisión en este centro'); ?></p>
    </div>
<?php
}
?>
