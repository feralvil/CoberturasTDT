<?php
// Funciones JQuery:
$functab = "$('div[class*=\"tabs-content\"]').addClass('hide-all');";
$functab .= "$('ul li').removeClass('active');";
$functab .= "$(this).parent().addClass('active');";
$functab .= "var divshow = $(this).attr('id');";
$functab .= "$('div#' + $(this).attr('id')).removeClass('hide-all');";
$this->Js->get("div#principal ul li a");
$this->Js->event('click', $functab);
?>
<h1><?php echo __('Centro TDT de ').$centro['Centro']['centro'];?></h1>
<div id="principal" class="ink-tabs top" data-prevent-url-change="true">
    <ul class="tabs-nav">
        <li class="tabs-tab active"><a id="multiples" href="#"><i class = "icon-desktop"></i> <?php echo __('Múltiples');?></a></li>
        <li class="tabs-tab"><a id="coberturas" href="#"><i class = "icon-signal"></i> <?php echo __('Coberturas');?></a></li>
        <li class="tabs-tab"><a id="info" href="#"><i class = "icon-info"></i> <?php echo __('Información');?></a></li>
        <li class="tabs-tab"><a id="sims" href="#"><i class = "icon-dashboard"></i> <?php echo __('Supervisión');?></a></li>
        <li class="tabs-tab"><a id="equipos" href="#"><i class = "icon-cogs"></i> <?php echo __('Equipos');?></a></li>
        <li class="tabs-tab"><a id="contactos" href="#"><i class = "icon-user"></i> <?php echo __('Contactos');?></a></li>
    </ul>

    <div id="multiples" class="tabs-content">
        <h2><?php echo __('Programas Emitidos');?> &mdash; <?php echo count($centro['Emision']).' '.__('Múltiples'); ?></h2>
        <?php
        $totProgramas = 0;
        if (!empty($centro['Emisionext'])){
        ?>
            <table class="ink-table hover alternating">
                <tr>
                    <th><?php echo __('Múltiple'); ?></th>
                    <th><?php echo __('Canal'); ?></th>
                    <th><?php echo __('Frecuencia'); ?></th>
                    <th><?php echo __('Tipo'); ?></th>
                    <th><?php echo __('Retardo') . ' (x100 ns)'; ?></th>
                    <th><?php echo __('Programas'); ?> (<span class="ink-badge grey">Nº</span> )</th>
                </tr>
                <?php
                foreach ($centro['Emisionext'] as $emision) {
                    $nprogramas = count($emision['programas']);
                    $totProgramas = $totProgramas + $nprogramas;
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
                        <td><?php echo $emision['nombre']; ?></td>
                        <td class="content-center"><?php echo $emision['canal']; ?></td>
                        <td class="content-center"><?php echo ($emision['canal'] - 21) * 8 + 474; ?> MHz</td>
                        <td class="content-center"><?php echo $emision['tipo']; ?></td>
                        <td class="content-center">
                            <?php
                            if ($emision['tipo'] == "Emisor"){
                                echo $emision['retardo'];
                            }
                            else{
                                echo '&mdash;';
                            }
                            ?>
                        </td>
                        <td>
                            <div class="column-group">
                                <div class="large-5 content-center">
                                    <span class="ink-badge grey"><?php echo $nprogramas; ?></span>
                                </div>
                                <div class="large-95">
                                    <div class="column-group">
                                        <?php
                                        foreach ($emision['programas'] as $programa) {
                                        ?>
                                            <div class="content-center <?php echo $clase;?>">
                                                <figure class="ink-image">
                                                    <?php
                                                    echo $this->Html->image($programa['Programa']['logo'], array(
                                                        'alt' => 'Logo '.$programa['Programa']['nombre'], 'title' => 'Logo '.$programa['Programa']['nombre'])
                                                    );
                                                    ?>
                                                </figure>
                                                <figcaption><?php echo $programa['Programa']['nombre']; ?></figcaption>
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
                <tr>
                    <th colspan="5"><?php echo __('Total Programas'); ?></th>
                    <th class="content-center"><?php echo $totProgramas; ?></th>
                </tr>
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
        <div class='content-center'>
            <?php
            if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                echo $this->Html->Link(
                        '<i class = "icon-edit"></i> '.__('Editar Múltiples'),
                        array('controller' => 'centros', 'action' => 'multiples', $centro['Centro']['id']),
                        array('class' => 'ink-button blue', 'title' => __('Editar Múltiples'), 'alt' => __('Editar Múltiples'), 'escape' => false)
                    );
            }
            if (((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) && ($centro['Centro']['activo'] == 'SI')) {
                echo $this->Form->postLink(
                        '<i class = "icon-power-off"></i> '.__('Apagar Centro'),
                        array('controller' => 'centros', 'action' => 'apagar', $centro['Centro']['id']),
                        array('class' => 'ink-button blue', 'title' => __('Apagar Centro'), 'alt' => __('Apagar Centro'), 'escape' => false), __('¿Seguro que desea apagar el Centro') . " '" . $centro['Centro']['centro'] . "'?"
                );
            }
            ?>
        </div>
    </div>

    <div id="coberturas" class="tabs-content hide-all">
        <h2><?php echo __('Municipios Cubiertos');?></h2>
        <?php
        $totCubiertos = 0;
        if (!empty($centro['Cobertura'])){
        ?>
            <table class="ink-table bordered hover alternating">
                <tr>
                    <th><?php echo __('Nº'); ?></th>
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
                        <td><?php echo $i; ?></td>
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
            <h2><?php echo __('Tipología de incidencias del centro');?></h2>
            <?php
            if ($totCubiertos >= 1000){
                $tipoCentro = 'C1';
            }
            else{
                $tipoCentro = 'C2';
            }
            if ($centro['Centro']['tipologia'] == 'C1'){
                $tiempoA1 = 6;
                $tiempoA2 = 12;
            }
            else{
                $tiempoA1 = 12;
                $tiempoA2 = 24;
            }
            if ($centro['Centro']['tipologia'] == $tipoCentro){
                $icono = "icon-ok";
            }
            else {
                $icono = "icon-remove";
            }
            ?>
            <table class="ink-table bordered hover alternating">
                <tr>
                    <th><?php echo __('Tipología de Centro'); ?></th>
                    <th><?php echo __('Activo'); ?></th>
                    <th><?php echo __('Tiempo de respuesta A1'); ?></th>
                    <th><?php echo __('Tiempo de respuesta A2'); ?></th>
                </tr>
                <tr>
                    <td class="content-center"><?php echo $centro['Centro']['tipologia']; ?> &mdash; <i class="<?php echo $icono;?>"></i></td>
                    <td class="content-center"><?php echo $centro['Centro']['activo']; ?></td>
                    <td class="content-center"><?php echo $tiempoA1; ?> h.</td>
                    <td class="content-center"><?php echo $tiempoA2; ?> h.</td>
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
        <div class='content-center'>
            <?php
            if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                echo $this->Html->Link(
                        '<i class = "icon-signal"></i> '.__('Editar Coberturas'),
                        array('controller' => 'coberturas', 'action' => 'cobcentro', $centro['Centro']['id']),
                        array('class' => 'ink-button blue', 'title' => __('Editar Coberturas'), 'alt' => __('Editar Coberturas'), 'escape' => false)
                );
            }
            ?>
        </div>
    </div>

    <div id="info" class="tabs-content hide-all">
        <?php
        switch ($centro['Centro']['polaridad']) {
            case 'H':
                $polaridad = __('Horizontal');
                break;

            case 'V':
                $polaridad = __('Vertical');
                break;

            default:
                $polaridad = "-";
                break;
        }
        $latgra = floor($centro['Centro']['latitud']);
        $latmin = ($centro['Centro']['latitud'] - $latgra) * 60;
        $latseg = ($latmin - floor($latmin)) * 60;
        $latgms = $latgra . "º " . floor($latmin) . "' " . number_format($latseg, 4) . "''";
        if ($centro['Centro']['longitud'] > 0){
            $longgra = floor($centro['Centro']['longitud']);
        }
        else{
            $longgra = ceil($centro['Centro']['longitud']);
        }
        $longmin = abs(($centro['Centro']['longitud'] - $longgra)) * 60;
        $longseg = ($longmin - floor($longmin)) * 60;

        $longgms = $longgra . "º " . floor($longmin) . "' " . number_format($longseg, 4) . "''";
        ?>
        <h2><?php echo __('Ubicación del centro');?></h2>
        <h3><?php echo __('Coordenadas');?></h3>
        <table class="ink-table bordered hover alternating">
            <tr>
                <th><?php echo __('UTMY'); ?></th>
                <th><?php echo __('UTMX'); ?></th>
                <th><?php echo __('Latitud'); ?></th>
                <th><?php echo __('Longitud'); ?></th>
            </tr>
            <tr>
                <td><?php echo $centro['Centro']['utmy']; ?></td>
                <td><?php echo $centro['Centro']['utmx']; ?></td>
                <td><?php echo $centro['Centro']['latitud'] . " / " . $latgms; ?></td>
                <td><?php echo $centro['Centro']['longitud'] . " / " . $longgms; ?></td>
            </tr>
        </table>
        <h3><?php echo __('Datos de ubicación');?></h3>
        <table class="ink-table bordered hover alternating">
            <tr>
                <th><?php echo __('Provincia'); ?></th>
                <th><?php echo __('Ref. Catastral'); ?></th>
                <th><?php echo __('Dimensiones de la parcela'); ?></th>
            </tr>
            <tr>
                <td><?php echo $centro['Centro']['provincia']; ?></td>
                <td><?php echo $centro['Centro']['catastro']; ?></td>
                <td><?php echo $centro['Centro']['dimensiones']; ?></td>
            </tr>
        </table>
        <h4><?php echo __('Notas'); ?></h4>
        <p><?php echo $centro['Centro']['info']; ?></p>
        <h2><?php echo __('Datos de propiedad del centro');?></h2>
        <table class="ink-table bordered hover alternating">
            <tr>
                <th><?php echo __('Suelo'); ?></th>
                <th><?php echo __('Caseta'); ?></th>
                <th><?php echo __('Torre'); ?></th>
                <th><?php echo __('Suministro eléctrico'); ?></th>
            </tr>
            <tr>
                <td><?php echo $centro['Centro']['suelo']; ?></td>
                <td><?php echo $centro['Centro']['caseta']; ?></td>
                <td><?php echo $centro['Centro']['torre']; ?></td>
                <td><?php echo $centro['Centro']['electrico']; ?></td>
            </tr>
        </table>
        <h2><?php echo __('Otros datos del centro');?></h2>
        <table class="ink-table bordered hover alternating">
            <tr>
                <th><?php echo __('Potencia ajustada'); ?></th>
                <th><?php echo __('Tipo de equipo'); ?></th>
                <th><?php echo __('Polaridad'); ?></th>
            </tr>
            <tr>
                <td><?php echo $centro['Centro']['pajustada']; ?> W</td>
                <td><?php echo $centro['Centro']['equipo']; ?></td>
                <td><?php echo $polaridad; ?></td>
            </tr>
        </table>
        <div class='content-center'>
            <?php
            if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                echo $this->Html->Link(
                        '<i class = "icon-edit"></i> '.__('Editar Centro'),
                        array('controller' => 'centros', 'action' => 'editar', $centro['Centro']['id']),
                        array('class' => 'ink-button blue', 'title' => __('Editar Centro'), 'alt' => __('Editar Centro'), 'escape' => false)
                );
            }
            ?>
        </div>
    </div>

    <div id="sims" class="tabs-content hide-all">
        <h2><?php echo __('Supervisión del Centro');?></h2>
        <?php
        $ntarjetas = count($centro['Sim']);
        if ($ntarjetas > 0){
        ?>
            <table class="ink-table bordered hover alternating">
                <tr>
                    <th><?php echo __('Número'); ?></th>
                    <th><?php echo __('ICC'); ?></th>
                    <th><?php echo __('Uso'); ?></th>
                    <th><?php echo __('Cobertura'); ?></th>
                    <th><?php echo __('Dirección IP'); ?></th>
                    <th><?php echo __('Usuario'); ?></th>
                    <th><?php echo __('Contraseña'); ?></th>
                </tr>
        <?php
            foreach ($centro['Sim'] as $sim) {
                if ($sim['uso'] == "TDT"){
                    $uso = __("Supervisión TDT");
                }
                elseif ($sim['uso'] == "SUP"){
                    $uso = __("Supervisión Centro");
                }
                else{
                    $uso = __("Otros usos");
                }
        ?>
                <tr>
                    <td><?php echo $sim['numero']; ?></td>
                    <td><?php echo $sim['icc']; ?></td>
                    <td><?php echo $uso; ?></td>
                    <td><?php echo $sim['cobertura']; ?></td>
                    <td><?php echo $sim['dir_ip']; ?></td>
                    <td><?php echo $sim['usuario']; ?></td>
                    <td><?php echo $sim['contrasenya']; ?></td>
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
                <p><?php echo __('No se han encontrado tarjetas de supervisión en este centro'); ?></p>
            </div>
        <?php
        }
        ?>
        <div class='content-center'>
            <?php
            if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                echo $this->Html->Link(
                    '<i class = "icon-phone"></i> '.__('Editar Tarjetas'),
                    array('controller' => 'centros', 'action' => 'supervision', $centro['Centro']['id']),
                    array('class' => 'ink-button blue', 'title' => __('Editar Tarjetas'), 'alt' => __('Editar Tarjetas'), 'escape' => false)
                );
            }
            ?>
        </div>
    </div>
    <div id="equipos" class="tabs-content hide-all">
        <h2><?php echo __('Equipos del Centro');?></h2>
        <?php
        $nequipos = count($centro['Equipo']);
        if ($nequipos > 0){
            $idrack = $idcofre = $idcanal = $idtarjeta = 0;
            $ntarjetas = 0;
            // Tipos de tarjeta
            $tipotarjeta = array(
                'TX' => 'Transmisión', 'MOD' => 'Modulador', 'RX' => 'Recepción',
                'EXC' => 'Excitador', 'CONV' => 'Convertidor', 'FI' => 'FI Transmisión Digital'
            );
            $equipos = array();
            $cofres = array();
            $tarjetas = array();
            $canal = array();
            foreach ($centro['Equipo'] as $equipo) {
                if ($equipo['rango'] == 0){
                    $idrack = $equipo['id'];
                    $equipos[$idrack] = $equipo;
                }
                if ($equipo['rango'] == 1){
                    if (($idcofre > 0) && ($idcofre <> $equipo['id'])){
                        $cofres [$idcofre]['tarjetas'] = $tarjetas;
                        $tarjetas = array();
                    }
                    $idcofre = $equipo['id'];
                    $cofres[$idcofre] = $equipo;
                    if ($equipo['centro_id'] == 13){
                        $idcanal = substr($equipo['nombre'], -2);
                    }
                    if ($ntarjetas > 0){
                        $cofres [$idcofre]['tarjetas'] = $tarjetas;
                        $ntarjetas = 0;
                        $canal = array();
                    }
                }
                if ($equipo['rango'] == 2){
                    $cofres[$idcofre]['txcont'] = $equipo;
                }
                if ($equipo['rango'] == 3){
                    $indice = $equipo['nombre'];
                    $idcanal = $equipo['canal'];
                    $tarjetas[$idcanal][$indice] = $equipo;
                    $ntarjetas++;
                }
            }
            $cofres [$idcofre]['tarjetas'] = $tarjetas;
            $equipos[$idrack]['cofres'] = $cofres;
        ?>
            <h3><?php echo __('Equipos TDT');?></h3>
        <?php
            foreach ($equipos as $bastidor) {
        ?>
                <div class="ink-grid">
                    <div class="large-100">
                        <table class="ink-table bordered alternating hover">
                            <tr>

                                <th><?php echo __('Supervisor'); ?></th>
                                <th><?php echo __('Marca'); ?></th>
                                <th><?php echo __('Código Hardware'); ?></th>
                                <th><?php echo __('Código Software'); ?></th>
                                <th><?php echo __('Número de Serie'); ?></th>
                                <th><?php echo __('Fecha de importación'); ?></th>
                            </tr>
                            <tr>
                                <td><?php echo $bastidor['nombre']; ?></td>
                                <td><?php echo $bastidor['marca']; ?></td>
                                <td><?php echo $bastidor['codhw']; ?></td>
                                <td><?php echo $bastidor['codsw']; ?></td>
                                <td><?php echo $bastidor['nserie']; ?></td>
                                <td><?php echo $bastidor['fecha']; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="large-10">
                        &nbsp;
                    </div>
                    <div class="large-90 top-space">
                    <?php
                    foreach ($bastidor['cofres'] as $cofre) {
                    ?>
                        <h4>Cofre <?php echo $cofre['nombre']; ?></h4>
                        <table class="ink-table bordered alternating hover">
                            <tr>
                                <th><?php echo __('Marca'); ?></th>
                                <th><?php echo __('Modelo'); ?></th>
                                <th><?php echo __('Número de Serie'); ?></th>
                            </tr>
                            <tr>
                                <td><?php echo $cofre['marca']; ?></td>
                                <td><?php echo $cofre['codhw']; ?></td>
                                <td><?php echo $cofre['nserie']; ?></td>
                            </tr>
                        </table>
                        <div class="large-10">
                            &nbsp;
                        </div>
                        <div class="large-90 top-space bottom-space">
                            <table class="ink-table bordered alternating hover">
                                <tr>
                                    <?php
                                    if ($cofre['txcont']['tipo'] == 'CONTPAN') {
                                    ?>
                                        <th><?php echo __('Panel de Control'); ?></th>
                                    <?php
                                    }
                                    else{
                                    ?>
                                        <th><?php echo __('Transmisor'); ?></th>
                                    <?php
                                    }
                                    ?>
                                    <th><?php echo __('Marca'); ?></th>
                                    <th><?php echo __('Modelo'); ?></th>
                                    <th><?php echo __('Número de Serie'); ?></th>
                                    <?php
                                    if ($cofre['txcont']['tipo'] == 'TRANSMISOR') {
                                    ?>
                                        <th><?php echo __('Potencia'); ?></th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <tr>
                                    <td><?php echo $cofre['txcont']['nombre']; ?></td>
                                    <td><?php echo $cofre['txcont']['marca']; ?></td>
                                    <td><?php echo $cofre['txcont']['codhw']; ?></td>
                                    <td><?php echo $cofre['txcont']['nserie']; ?></td>
                                    <?php
                                    if ($cofre['txcont']['tipo'] == 'TRANSMISOR') {
                                    ?>
                                        <td><?php echo $cofre['txcont']['potencia']; ?></td>
                                    <?php
                                    }
                                    ?>
                                </tr>
                            </table>
                            <table class="ink-table bordered alternating hover">
                                <tr>
                                    <?php
                                    if ($cofre['txcont']['tipo'] == 'CONTPAN') {
                                    ?>
                                        <th colspan="2"><?php echo __('Tarjeta'); ?></th>
                                    <?php
                                    }
                                    else {
                                    ?>
                                        <th><?php echo __('Tarjeta'); ?></th>
                                    <?php
                                    }
                                    ?>
                                    <th><?php echo __('Marca'); ?></th>
                                    <th><?php echo __('Modelo'); ?></th>
                                    <th><?php echo __('Código Software'); ?></th>
                                    <th><?php echo __('Número de Serie'); ?></th>
                                    <?php
                                    if ($cofre['txcont']['tipo'] == 'CONTPAN') {
                                    ?>
                                        <th><?php echo __('Potencia'); ?></th>
                                    <?php
                                    }
                                    ?>
                                </tr>
                                <?php
                                foreach ($cofre['tarjetas'] as $canal => $tarjeta) {
                                    $inicio = true;
                                    foreach ($tarjeta as $modulo => $valores) {
                                ?>
                                    <tr>
                                        <?php
                                        if ($inicio && ($cofre['txcont']['tipo'] == 'CONTPAN')) {
                                        ?>
                                            <th rowspan="2">CH <?php echo $canal; ?></th>
                                        <?php
                                            $inicio = !($inicio);
                                        }
                                        ?>
                                        <td><?php echo $tipotarjeta[$modulo]; ?></td>
                                        <td><?php echo $valores['marca']; ?></td>
                                        <td><?php echo $valores['codhw']; ?></td>
                                        <td><?php echo $valores['codsw']; ?></td>
                                        <td><?php echo $valores['nserie']; ?></td>
                                        <?php
                                        if ($cofre['txcont']['tipo'] == 'CONTPAN') {
                                        ?>
                                            <td><?php echo $valores['potencia']; ?></td>
                                        <?php
                                        }
                                        ?>
                                    </tr>

                                <?php
                                    }
                                }
                                ?>
                        </table>
                        </div>
                    <?php
                    }
                    ?>
                </div>
        <?php
            }
        }
        else{
        ?>
            <div class="ink-alert block warning">
                <h4><?php echo __('No hay resultados');?></h4>
                <p><?php echo __('No se han encontrado equipos en este centro'); ?></p>
            </div>
        <?php
        }
        ?>
        <div class='content-center'>
            <?php
            if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                echo $this->Html->Link(
                    '<i class = "icon-edit"></i> '.__('Editar Equipos'),
                    array('controller' => 'equipos', 'action' => 'centro', $centro['Centro']['id']),
                    array('class' => 'ink-button blue', 'title' => __('Modificar equipos'), 'alt' => __('Modificar equipos'), 'escape' => false)
                );
            }
            ?>
        </div>
    </div>
    </div>
    <div id="contactos" class="tabs-content hide-all">
        <h2><?php echo __('Contactos del Centro');?></h2>
        <?php
        $ncontactos = count($centro['Contacto']);
        if ($ncontactos > 0){
        ?>
            <table class="ink-table bordered alternating hover">
                <tr>
                    <th><?php echo __('Nombre'); ?></th>
                    <th><?php echo __('Cargo'); ?></th>
                    <th><?php echo __('Teléfono'); ?></th>
                    <th><?php echo __('Mail'); ?></th>
                </tr>
                <?php
                foreach ($centro['Contacto'] as $contacto) {
                ?>
                    <tr>
                        <td><?php echo $contacto['nombre']; ?></td>
                        <td><?php echo $contacto['cargo']; ?></td>
                        <td><?php echo $contacto['telefono']; ?></td>
                        <td><?php echo $contacto['mail']; ?></td>
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
            if ((AuthComponent::user('role') == 'admin') || (AuthComponent::user('role') == 'colab')) {
                echo $this->Html->Link(
                    '<i class = "icon-edit"></i> '.__('Editar Contactos'),
                    array('controller' => 'contactos', 'action' => 'centro', $centro['Centro']['id']),
                    array('class' => 'ink-button blue', 'title' => __('Modificar contactos'), 'alt' => __('Modificar contactos'), 'escape' => false)
                );
            }
            ?>
        </div>
    </div>
</div>
