<h1><?php echo __('Multiples del Centro TDT de ').$centro['Centro']['centro'];?></h1>
<h2><?php echo count($centro['Emision']).' '.__('Múltiples Emitidos'); ?></h2>
<?php
if (!empty($centro['Multiples'])){
?>
    <table class="ink-table bordered hover alternating">
        <tr>
            <th><?php echo __('Múltiple'); ?></th>
            <th><?php echo __('Canal'); ?></th>
            <th><?php echo __('Frecuencia'); ?></th>
            <th><?php echo __('Tipo'); ?></th>
            <th><?php echo __('Retardo') . ' (x100 ns)'; ?></th>
            <th><?php echo __('Acciones'); ?></th>
        </tr>
        <?php
        foreach ($centro['Multiples'] as $multiple) {
        ?>
            <tr>
                <td><?php echo $multiple['nombre']; ?></td>
                <td class="content-center"><?php echo $multiple['canal']; ?></td>
                <td class="content-center"><?php echo ($multiple['canal'] - 21) * 8 + 474; ?> MHz</td>
                <td class="content-center"><?php echo $multiple['tipo']; ?></td>
                <td class="content-center">
                    <?php
                    if ($multiple['tipo'] == "Emisor"){
                        echo $multiple['retardo'];
                    }
                    else{
                        echo '&mdash;';
                    }
                    ?>
                </td>
                <td class="content-center">
                    <?php
                    echo $this->Html->Link(
                            '<i class = "icon-edit"></i>',
                            array('controller' => 'emisions', 'action' => 'editar', $multiple['idemision']),
                            array('title' => __('Editar Emisión'), 'alt' => __('Editar Emisión'), 'escape' => false)
                    );
                    echo ' &mdash; ';
                    echo $this->Form->postLink(
                            '<i class = "icon-trash"></i>',
                            array('controller' => 'emisions', 'action' => 'borrar', $multiple['idemision']),
                            array('title' => __('Eliminar Emisión'), 'alt' => __('Eliminar Emisión'), 'escape' => false), '¿'.__('Seguro que desea eliminar el múltiple').' '.$multiple['nombre'].' '.__('del Centro')." '" . $centro['Centro']['centro'] . "'?"
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
        <p><?php echo __('No se han encontrado canales emitidos desde este centro'); ?></p>
    </div>
<?php
}
?>

<h3><?php echo __('Agregar nuevo múltiple al Centro'); ?></h3>

<?php
echo $this->Form->create('Emision',array(
    'action' => 'agregar',
    'inputDefaults' => array(
        'label' => false,
        'div' => false),
    'class' => 'ink-form'
));
echo $this->Form->hidden('centro_id', array('default' => $centro['Centro']['id']));
?>

<fieldset class="column-group gutters">
    <div class="control-group large-30 required validation error">
        <?php
        echo $this->Form->label('Emision.multiple_id', __('Múltiple'));
        echo $this->Form->input('Emision.multiple_id', array(
            'options' => $multisel,
            'empty' => __('Seleccionar '),
            'div' => array('class' => 'control')
            )
        );
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Emision.canal', __('Canal'));
        echo $this->Form->input('Emision.canal', array('div' => array('class' => 'control')));
        ?>
    </div>
    <div class="control-group large-30">
        <?php
        echo $this->Form->label('Emision.tipo', __('Tipo'));
        echo $this->Form->input('Emision.tipo', array(
            'options' => array('Emisor' => 'Emisor', 'Gap-Filler' => 'Gap-Filler'),
            'empty' => __('Seleccionar '),
            'div' => array('class' => 'control')
            )
        );
        ?>
    </div>
    <div class="control-group large-20">
        <?php
        echo $this->Form->label('Emision.canal', __('Retardo') . ' (x100 ns)');
        echo $this->Form->input('Emision.retardo', array('div' => array('class' => 'control')));
        ?>
    </div>
</fieldset>

<div class='content-center'>
    <?php
    echo $this->Form->button(
            '<i class = "icon-save"></i> '.__('Añadir múltiple'),
            array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Añadir múltiple'), 'alt' => __('Añadir múltiple'), 'escape' => false)
    );
    echo $this->Form->button(
            '<i class = "icon-undo"></i> '.__('Cancelar cambios'),
            array('type' => 'reset', 'class' => 'ink-button blue', 'title' => __('Cancelar cambios'), 'alt' => __('Cancelar cambios'), 'escape' => false)
    );
    echo $this->Html->Link(
            '<i class = "icon-arrow-left"></i> '.__('Volver'),
            array('controller' => 'centros', 'action' => 'detalle', $centro['Centro']['id']),
            array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
    );
    ?>
</div>
<?php
echo $this->Form->end();
?>
