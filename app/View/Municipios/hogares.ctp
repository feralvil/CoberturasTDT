<h1><?php echo __('Importar Hogares de Municipios');?></h1>
<?php
$nhogares = count($municipios);
?>
<h2><?php echo __('Datos de Hogares encontrados') . ' (' . $nhogares . ' ' . __('Municipios') . ')';?></h2>
<?php
if ($nhogares > 0){
?>
    <?php
    echo $this->Form->create('Municipio',array(
        'inputDefaults' => array(
            'label' => false,
            'div' => false),
        'class' => 'ink-form'
    ));
    ?>
    <div class='content-center'>
        <?php
        echo $this->Form->button(
                '<i class = "icon-save"></i> '.__('Guardar Datos'),
                array('type' => 'submit', 'class' => 'ink-button blue', 'title' => __('Guardar Datos'), 'alt' => __('Guardar Datos'), 'escape' => false)
        );
        echo $this->Html->Link(
                '<i class = "icon-arrow-left"></i> '.__('Volver'),
                array('controller' => 'municipios', 'action' => 'index'),
                array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
        );
        ?>
    </div>
    <?php
    echo $this->Form->end();
    ?>
    <table class="ink-table hover alternating">
        <tr>
            <th><?php echo __('Código INE');?></th>
            <th><?php echo __('Municipio');?></th>
            <th><?php echo __('Habitantes (2015)');?></th>
            <th><?php echo __('Hogares (2011)');?></th>
        </tr>
        <?php
        foreach ($municipios as $municipio) {
        ?>
            <tr>
                <td class="content-center"><?php echo $municipio['Municipio']['id'];?></td>
                <td><?php echo $municipio['Municipio']['nombre'];?></td>
                <td class="content-right"><?php echo $municipio['Municipio']['poblacion'];?></td>
                <td class="content-right"><?php echo $municipio['Municipio']['hogares'];?></td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
}
else{
?>
    <div class='content-center'>
        <?php
        echo $this->Html->Link(
                '<i class = "icon-arrow-left"></i> '.__('Volver'),
                array('controller' => 'centros', 'action' => 'index'),
                array('class' => 'ink-button blue', 'title' => __('Volver'), 'alt' => __('Volver'), 'escape' => false)
        );
        ?>
    </div>
    <div class="ink-alert block warning">
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado datos de hogares de los Municipios'); ?></p>
    </div>
<?php
}
?>
