<?php
// Datos del fichero CSV;
$fecha = date('Y-m-d');
$ficherocsv = 'files/eventos_' . $fecha . '.csv';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo 'Importar datos CSV';?></title>
        <link rel="StyleSheet" type="text/css" href="css/ink.css">
    </head>
    <body>
        <div id="container" class="ink-grid">
            <h1><?php echo 'Importar datos CSV';?></h1>
            <?php
            $fila = 1;
            if (($puntero = fopen($ficherocsv, "r")) !== FALSE) {
            ?>
                <div class='content-center'>
                    <a class="ink-button blue" href="eventos_update.php" title="Guardar Eventos">
                        <i class = "icon-upload"></i> Guardar Eventos
                    </a>
                </div>
                <table class="ink-table bordered alternating hover">
                <?php
                while ((($datos = fgetcsv($puntero, 1000, ",")) !== FALSE)) {
                    $tag = 'td';
                    if ($fila == 1){
                        $tag = 'th';
                    }
                ?>
                    <tr>
                        <?php
                        $dato = ($fila - 1);
                        if ($fila == 1){
                            $dato = "Fila";
                        }
                        echo '<' . $tag . '>' . $dato . '</' . $tag . '>';
                        ?>

                        <?php
                        for ($i = 0; $i < count($datos) ; $i++){
                            if (($fila > 1) && ($i > 4) && ($i < 7)){
                                if ($datos[$i] != ''){
                                    $vectordato = explode(' ', $datos[$i]);
                                    $vectorfecha = explode('/', $vectordato[0]);
                                    $fechacsv = "20" . $vectorfecha[2] . '-' . $vectorfecha[1] . '-' . $vectorfecha[0];
                                    $anyoact = substr($fecha, 0, 2);
                                    if ($anyo != $anyoact){
                                        $anyo = "19" . $anyo;
                                    }
                                    else{
                                        $anyo = "20" . $anyo;
                                    }
                                    $fechacsv =  $anyo . '-' . $vectorfecha[1] . '-' . $vectorfecha[0];
                                    $hora = $vectordato[1];
                                    $datos[$i] = $fechacsv . ' ' .$hora;
                                }
                            }
                            echo '<' . $tag . '>' . utf8_encode ($datos[$i]) . '</' . $tag . '>';
                        }
                        ?>
                    </tr>
                <?php
                    $fila++;
                }
                fclose($puntero);
                ?>
                </table>
            <?php
            }
            else {
            ?>
                <div class="ink-alert block warning">
                    <h4><?php echo 'Error en la búsqueda del Fichero CSV';?></h4>
                    <p><?php echo 'No se han encontrado el fichero CSV' . ' ' . $ficherocsv; ?></p>
                </div>
            <?php
            }
            ?>

        </div>
    </body>
</html>
<?php
?>
