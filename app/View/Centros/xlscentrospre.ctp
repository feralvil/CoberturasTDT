<?php
$ncentros = count($centros);
$totHabitantes = 0;
?>
<h1><?php echo __('Centros TDT de la Generalitat');?></h1>
<h4>
    <?php
        echo __('Resultados de la Búsqueda');
        if ($ncentros > 0){
            echo ' &mdash; ' . $ncentros . ' ' . __('Centros');
        }
    ?>
</h4>
<?php
if ($ncentros > 0){
?>
    <table class="ink-table bordered alternating hover">
        <tr>
            <th><?php echo __('ID');?></th>
            <th><?php echo __('Centro');?></th>
            <th><?php echo __('Tipología');?></th>
            <th><?php echo __('Nº Múx');?></th>
            <?php
            foreach ($multiples as $nom_mux) {
            ?>
                <th><?php echo $nom_mux;?></th>
            <?php
            }
            ?>
            <th><?php echo __('Equipo');?></th>
            <th><?php echo __('Nº Municipios cubiertos');?></th>
            <th><?php echo __('Habitantes cubiertos');?></th>
        </tr>
<?php
        foreach ($centros as $centro) {
?>
            <tr>
                <td class="content-center"><?php echo $centro['Centro']['id'];?></td>
                <td><?php echo $centro['Centro']['centro'];?></td>
                <td class="content-center"><?php echo $centro['Centro']['tipologia'];?></td>
                <td class="content-center"><?php echo count($centro['Emision']);?></td>
                <?php
                    foreach ($multiples as $id_mux => $nom_mux) {
                ?>
                        <td class="content-center"><?php echo $centro['Centro'][$nom_mux];?></td>
                <?php
                    }
                ?>
                <td class="content-center"><?php echo $centro['Centro']['equipo'];?></td>
                <td class="content-center"><?php echo count($centro['Cobertura']);?></td>
                <td class="content-center"><?php echo $this->Number->format($centro['Centro']['habCubiertos'], array('places' => 0, 'before' => '', 'thousands' => '.'));?></td>
            </tr>
<?php
            $totHabitantes = $totHabitantes + $centro['Centro']['habCubiertos'];
        }
?>
        <tr>
            <th colspan="12"><?php echo __('Total Habitantes');?></th>
            <td class="content-center"><?php echo $this->Number->format($totHabitantes, array('places' => 0, 'before' => '', 'thousands' => '.'));?></td>
        </tr>
    </table>
<?php
}
else{
?>
    <div class="ink-alert block error">
        <h4>Error en la búsqueda</h4>
        <p><?php echo __('No se han encontrado Centros con los criterios de búsqueda seleccionados');?></p>
    </div>
<?php
}
echo $this->Form->end();
?>
