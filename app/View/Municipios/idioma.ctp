<h1><?php echo __('Importar Idioma de Municipios');?></h1>
<?php
$nmuni = count($municipios);
?>
<h2><?php echo __('Datos de Idioma encontrados de') . ' (' . $nmuni . ' ' . __('Municipios') . ')';?></h2>
<?php
if ($nmuni > 0){
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
            <th><?php echo __('Municipio Excel');?></th>
            <th><?php echo __('Idioma');?></th>
        </tr>
        <?php
        foreach ($municipios as $municipio) {
        ?>
            <tr>
                <td class="content-center"><?php echo $municipio['Municipio']['id'];?></td>
                <td><?php echo $municipio['Municipio']['nombre'];?></td>
                <td><?php echo $municipio['Municipio']['nommuni'];?></td>
                <td class="content-center"><?php echo $municipio['Municipio']['lengua'];?></td>
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
