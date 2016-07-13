<h1><?php echo __('Cobertura del Municipio ').$municipio['Municipio']['nombre'];?></h1>
<h2><?php echo __('Datos del Municipio');?></h2>
<table class="ink-table bordered hover alternating">
    <tr>
        <th><?php echo __('Cod. INE'); ?></th>
        <th><?php echo __('Provincia'); ?></th>
        <th><?php echo __('Municipio'); ?></th>
        <th><?php echo __('Habitantes (2015)'); ?></th>
    </tr>
    <tr>
        <td><?php echo $municipio['Municipio']['id'];?></td>
        <td><?php echo $municipio['Municipio']['provincia'];?></td>
        <td><?php echo $municipio['Municipio']['nombre'];?></td>
        <td class='content-right'><?php echo $this->Number->format($municipio['Municipio']['poblacion'], array('places' => 0, 'before' => '', 'thousands' => '.'));?></td>
    </tr>
</table>
<h2><?php echo __('Resumen de cobertura del municipio');?></h2>
<?php
if (!empty($municipio['Cobertura'])){
?>
    <table class="ink-table bordered hover alternating">
        <tr>
            <th><?php echo __('Nº'); ?></th>
            <th><?php echo __('Centro'); ?></th>
            <th><?php echo __('Nº Múltiples'); ?></th>
            <th><?php echo __('Hab. Cubiertos (%)'); ?></th>
        </tr>
<?php
        $i = 0;
        $totCubiertos = 0;
        foreach ($municipio['Centros'] as $centro) {
            $i++;
            $totCubiertos += $centro['habcub'];
?>
            <tr>
                <td><?php echo $i; ?></td>
                <td>
                    <?php
                    echo $centro['centro'].' &mdash; ';
                    echo $this->Html->Link(
                            '<i class = "icon-circle-arrow-right"></i>',
                            array('controller' => 'centros', 'action' => 'detalle', $centro['centro_id']),
                            array('title' => __('Ir a Centro'), 'escape' => false)
                        );
                    ?>
                </td>
                <td><?php echo $centro['nmux']; ?></td>
                <td class='content-right'><?php echo $this->Number->format($centro['habcub'], array('places' => 0, 'before' => '', 'thousands' => '.')).' ('.$centro['cobertura'].' %)'; ?></td>
            </tr>
<?php
        }
        $porCubiertos = 100 * ($totCubiertos / $municipio['Municipio']['poblacion']);

?>
        <tr>
            <th colspan='3'><?php echo __('Totales'); ?></th>
            <th class='content-right'><?php echo $this->Number->format($totCubiertos, array('places' => 0, 'before' => '', 'thousands' => '.')).' ('.$this->Number->format($porCubiertos, array('places' => 2, 'before' => '', 'thousands' => '.')).' %)';?></th>
        </tr>
    </table>
<?php
}
else{
?>
    <div class="ink-alert block warning">
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado Centros TDT de la Generalitat que cubran este Municipio');?></p>
    </div>
<?php
}
?>

<h2>
    <?php
    echo __('Centros TDT de la Generalitat que cubren el municipio');
    if (!empty($municipio['Cobertura'])){
        echo " (". count($municipio['Cobertura']) . ") &mdash; " ;
        echo $this->Html->Link(
            '<i class = "icon-print"></i>  '.__('Exportar a PDF'),
            array('controller' => 'municipios', 'action' => 'pdfdetalle', $municipio['Municipio']['id']),
            array('class' => 'ink-button blue', 'title' => __('Exportar a PDF'), 'escape' => false, 'target' => '_blank')
        );
    }
    ?>
</h2>
<?php
foreach ($municipio['Centros']  as $centro) {
?>
    <h3><?php echo __('Centro') . ' &mdash; '  . $centro['centro']; ?></h3>
    <table class="ink-table bordered hover alternating">
        <tr>
            <th><?php echo __('Múltiple'); ?></th>
            <th><?php echo __('Canal'); ?></th>
            <th><?php echo __('Frecuencia'); ?></th>
            <th><?php echo __('Programas'); ?></th>
        </tr>
        <?php
        foreach ($centro['multiples'] as $multiple) {
            $nprogramas = count($multiple['Programas']);
            switch ($nprogramas) {
                case 6:
                    $clase = 'large-15';
                    break;

                case 5:
                    $clase = 'large-20';
                    break;

                case 4:
                    $clase = 'large-25';
                    break;

                case 3:
                    $clase = 'large-33';
                    break;

                case 2:
                    $clase = 'large-50';
                    break;

                default:
                    break;
            }
        ?>
            <tr>
                <td class="content-center"><?php echo $multiple['Multiple']['nombre']; ?></td>
                <td class="content-center"><?php echo $multiple['Multiple']['canal']; ?></td>
                <td class="content-center"><?php echo ($multiple['Multiple']['canal'] - 21) * 8 + 474; ?> MHz</td>
                <td>
                    <div class="column-group">
                        <div class="large-5 content-center">
                            <span class="ink-badge grey"><?php echo $nprogramas; ?></span>
                        </div>
                        <div class="large-95">
                            <div class="column-group">
                            <?php
                            foreach ($multiple['Programas'] as $programa) {
                            ?>
                                <div class="content-center <?php echo $clase;?>">
                                    <?php
                                    echo $this->Html->image($programa['Programa']['logo'], array(
                                        'alt' => 'Logo '.$programa['Programa']['nombre'], 'title' => 'Logo '.$programa['Programa']['nombre'])
                                    );
                                    echo '<br />';
                                    echo $programa['Programa']['nombre'];
                                    ?>
                                </div>
                            <?php
                            }
                            ?>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php
        }
        ?>
    </table>
<?php
}
?>
