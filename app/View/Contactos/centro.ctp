<h1><?php echo __('Contactos del Centro TDT de ').$centro['Centro']['centro'];?></h1>
<?php
$ncontactos = count($contactos);
?>
<?php
if ($ncontactos > 0){
?>
<table class="ink-table bordered alternating hover">
    <tr>
        <th><?php echo __('Nombre'); ?></th>
        <th><?php echo __('Cargo'); ?></th>
        <th><?php echo __('Teléfono'); ?></th>
        <th><?php echo __('Mail'); ?></th>
        <th><?php echo __('Acciones'); ?></th>
    </tr>
    <?php
    foreach ($contactos as $contacto) {
    ?>
        <tr>
            <td><?php echo $contacto['Contacto']['nombre']; ?></td>
            <td><?php echo $contacto['Contacto']['cargo']; ?></td>
            <td><?php echo $contacto['Contacto']['telefono']; ?></td>
            <td><?php echo $contacto['Contacto']['mail']; ?></td>
            <td class="content-center">
                <?php
                echo $this->Html->Link(
                        '<i class = "icon-edit"></i>',
                        array('controller' => 'contactos', 'action' => 'editar', $contacto['Contacto']['id']),
                        array('title' => __('Editar Contacto'), 'alt' => __('Editar Contacto'), 'escape' => false)
                );
                echo ' &mdash; ';
                echo $this->Form->postLink(
                        '<i class = "icon-trash"></i>',
                        array('controller' => 'contactos', 'action' => 'borrar',  $contacto['Contacto']['id']),
                        array('title' => __('Eliminar Contacto'), 'alt' => __('Eliminar Contacto'), 'escape' => false), '¿'.__('Seguro que desea eliminar el contacto').' '.$contacto['Contacto']['nombre'].' '.__('del Centro')." '" . $centro['Centro']['centro'] . "'?"
                );
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
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado contactos en este centro'); ?></p>
    </div>
<?php
}
?>
<div class='content-center'>
    <?php
    echo $this->Html->Link(
        '<i class = "icon-arrow-left"></i> '.__('Volver'),
        array('controller' => 'centros', 'action' => 'detalle', $centro['Centro']['id']),
        array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    echo $this->Html->Link(
        '<i class = "icon-plus"></i> ' .  __('Agregar Contacto'),
        array('controller' => 'contactos', 'action' => 'agregar', $centro['Centro']['id']),
        array('class' => 'ink-button blue', 'title' => __('Agregar Contacto'), 'alt' => __('Agregar Contacto'), 'escape' => false)
    );
    ?>
</div>
