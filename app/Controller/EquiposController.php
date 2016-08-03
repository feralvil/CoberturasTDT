<?php
class EquiposController extends AppController {
    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
        if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array(
                    'index', 'detalle', 'centro', 'importarhw', 'leerhw', 'editar', 'agregar'
                );
            }
            elseif ($rol == 'consum') {
                $accPerm = array('index', 'detalle');
            }
            if (in_array($this->action, $accPerm)) {
                return true;
            }
            else{
                return parent::isAuthorized($user);
            }
        }
    }

    public function centro($idcentro = null) {
        // Fijamos el Título de la vista
        $this->set('title_for_layout', __('Equipos de Centro TDT'));

        // Leemos el centro:
        $this->Equipo->Centro->id = $idcentro;
        if (!$this->Equipo->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $this->Equipo->Centro->recursive = -1;
        $centro = $this->Equipo->Centro->read(null, $idcentro);
        $this->set('centro', $centro);

        // Leemos los equipos:
        $equiposdb = $this->Equipo->findAllByCentroId($idcentro, array(), array('Equipo.rango', 'Equipo.padre', 'Equipo.canal', 'Equipo.tipo'));
        $equipos = array();
        $racks = array();
        $tarjetas = array();
        $idrack = 0;
        foreach ($equiposdb as $equipodb) {
            $rango = $equipodb['Equipo']['rango'];
            $id = $equipodb['Equipo']['id'];
            $padre = $equipodb['Equipo']['padre'];
            $canal = $equipodb['Equipo']['canal'];
            $nombre = $equipodb['Equipo']['nombre'];
            switch ($rango) {
                case 0:
                    $idrack = $id;
                    $equipos[$id] = $equipodb['Equipo'];
                    break;
                case 1:
                    $equipos[$padre]['COFRES'][$id] = $equipodb['Equipo'];
                    break;
                case 2:
                    $indice = 'CONTPAN';
                    if ($idcentro == 13){
                        $indice = 'TRANSMISOR';
                    }
                    $equipos[$idrack]['COFRES'][$padre][$indice] = $equipodb['Equipo'];
                    break;
                case 3:
                    $equipos[$idrack]['COFRES'][$padre]['TARJETAS']["CH " . $canal][$nombre] = $equipodb['Equipo'];
                    break;
                default:
                    break;
            }
        }
        $this->set('equipos', $equipos);
    }

    public function importarhw($idcentro = null) {
        // Fijamos el título de la vista:
        $this->set('title_for_layout', __('Equipos de Centro TDT'));

        // Leemos el centro:
        $this->Equipo->Centro->id = $idcentro;
        if (!$this->Equipo->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }

        // Leemos el fichro de datos:
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Equipo->uploadHw($this->request->data['Equipo']['fichhw'])){
                // Generamos los ficheros
                App::uses('Folder', 'Utility');
                App::uses('File', 'Utility');
                $nomFichero = 'Inventario-' . $idcentro . '.txt';
                $fichero = new File($this->request->data['Equipo']['fichhw']['tmp_name'], true, 0644);
                if ($fichero->copy('files' . DS . $nomFichero, true)){
                    $this->Session->setFlash(__('Fichero de datos guardado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                    $this->redirect(array('controller' => 'equipos', 'action' => 'leerhw', $idcentro));
                }
                else{
                    $this->Session->setFlash(__('<b>Error:</b> No se ha podido guardar el fichero de datos'), 'default', array('class' => 'ink-alert basic error'));
                    $this->redirect(array('controller' => 'equipos', 'action' => 'importarhw', $idcentro));
                }
            }
            else{
                $this->Session->setFlash(__('<b>Error:</b> No se ha seleccionado ningún archivo o ha habido un error al subirlo'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'equipos', 'action' => 'importarhw', $idcentro));
            }
        }
        else{
            $this->Equipo->Centro->recursive = -1;
            $centro = $this->Equipo->Centro->read(null, $idcentro);
            $this->set('centro', $centro);
        }
    }

    public function leerhw ($idcentro = null){
        $this->Equipo->Centro->id = $idcentro;
        if (!$this->Equipo->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $this->set('centro', $this->Equipo->Centro->read(null, $idcentro));
        $nomFichero = 'Inventario-' . $idcentro . '.txt';
        $delim = '_______________________________________________';
        $fichero = fopen('files' . DS . $nomFichero, 'r');
        $equipos = array();
        $equipo = array();
        if ($fichero){
            while (($linea = fgets($fichero)) !== false) {
                $linea = trim($linea);
                if ($linea == $delim){
                    $equipos[] = $equipo;
                    $equipo = array();
                }
                else{
                    $equipo['CENTRO_ID'] = $idcentro;
                    $pos = strpos($linea, '=');
                    if ($pos !== false){
                        $par = explode('=', $linea);
                        $parametro = utf8_encode(trim($par[0]));
                        $valor = utf8_encode(trim($par[1]));
                        switch ($parametro) {
                            case 'Equipo':
                                $indice = 'EQUIPO';
                                $equipo[$indice] = $valor;
                                break;
                            case 'Código HW':
                                $indice = 'CODHW';
                                $equipo[$indice] = $valor;
                                break;
                            case 'Código SW':
                                $indice = 'CODSW';
                                $equipo[$indice] = $valor;
                                break;
                            case 'Número de Serie':
                                $indice = 'NSERIE';
                                $equipo[$indice] = $valor;
                                break;
                            case 'Nombre':
                                $indice = 'NOMBRE';
                                $equipo[$indice] = $valor;
                                break;
                            case 'Dirección IP del Centro Nodal':
                                $indice = 'DIRIP';
                                $equipo[$indice] = $valor;
                                break;
                            case 'PD':
                                $indice = 'POTENCIA';
                                $equipo[$indice] = $valor;
                                break;
                            case 'Potencia Directa':
                                $indice = 'POTENCIA';
                                $equipo[$indice] = $valor;
                                break;
                            case 'RF Antena':
                                $indice = 'POTENCIA';
                                $equipo[$indice] = $valor;
                                break;
                            case 'Protocolo SNMP':
                                $indice = 'SNMP';
                                $equipo[$indice] = $valor;
                                break;
                            case 'Horario':
                                $indice = 'FECHA';
                                $fechabruto = explode(' ', $valor);
                                $fechacomp = explode('/', $fechabruto[0]);
                                $fecha = '20' . $fechacomp[2] . '-' . $fechacomp[1] . '-' . $fechacomp[0];
                                $equipo[$indice] = $fecha;
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
            if (!feof($fichero)) {
                echo "Error: fallo inesperado de fgets()\n";
            }
            fclose($fichero);
            $hwcentro = array();
            $tipotarjeta = array(
                'TX' => 'Transmisión', 'MOD' => 'Modulador', 'RX' => 'Recepción',
                'EXC' => 'Excitador', 'CONV' => 'Convertidor', 'FI' => 'FI Transmisión Digital'
            );
            foreach ($equipos as $indice => $equipo) {
                if ($indice == 0){
                    $nomequipo = $equipo['EQUIPO'];
                    $equipo['TIPO'] = 'SUP';
                    $equipo['RANGO'] = 0;
                    $hwcentro['RACK'] = $equipo;
                    $modelo = 'TRD050';
                    $tarjetas = array();
                    $tarjcanal = array();
                    $canal = "CH 0";
                    $ntarjetas = 0;
                }
                else{
                    $nomequipo = $equipo['EQUIPO'];
                    if (in_array($nomequipo, $tipotarjeta)){
                        $ntarjetas++;
                        $equipo['TIPO'] = 'TARJETA';
                        $equipo['RANGO'] = 3;
                        $clave = array_search($nomequipo, $tipotarjeta);
                        $tarjcanal[$clave] = $equipo;
                        if ($clave == 'MOD'){
                            $modelo = 'TTD050';
                            if ($idcentro == 13){
                                $modelo = 'TTD21';
                            }
                        }
                        $hwcentro['RACK']['COFRES'][$nomcofre]['CODHW'] = $modelo;
                        if ($clave == 'TX'){
                            $canal = $equipo['NOMBRE'];
                            $tarjetas[$canal] = $tarjcanal;
                            $tarjcanal = array();
                        }
                    }
                    else{
                        if ($ntarjetas > 0){
                            if ($idcentro == 13){
                                $hwcentro['RACK']['COFRES'][$nomcofre]['TARJETAS'][$canal] = $tarjcanal;
                            }
                            else{
                                $hwcentro['RACK']['COFRES'][$nomcofre]['TARJETAS'] = $tarjetas;
                            }
                            $tarjetas = array();
                            $tarjcanal = array();
                            $ntarjetas = 0;
                        }
                        $equipo['TIPO'] = 'CONTPAN';
                        if ($idcentro == 13){
                            $equipo['TIPO'] = 'TRANSMISOR';
                            $canal = "CH " . substr($nomequipo, -2);
                            $equipo['CANAL'] = substr($nomequipo, -2);
                        }
                        $equipo['RANGO'] = 2;
                        $nomcofre = $nomequipo;
                        $hwcentro['RACK']['COFRES'][$nomcofre]['CENTRO_ID'] =  $equipo['CENTRO_ID'];
                        $hwcentro['RACK']['COFRES'][$nomcofre]['EQUIPO'] = $nomcofre;
                        $hwcentro['RACK']['COFRES'][$nomcofre]['CODHW'] =  '';
                        $hwcentro['RACK']['COFRES'][$nomcofre]['CODSW'] =  '';
                        $hwcentro['RACK']['COFRES'][$nomcofre]['NSERIE'] =  '';
                        $hwcentro['RACK']['COFRES'][$nomcofre]['RANGO'] = 1;
                        $hwcentro['RACK']['COFRES'][$nomcofre]['FECHA'] = $equipo['FECHA'];
                        $hwcentro['RACK']['COFRES'][$nomcofre]['TIPO'] = 'COFRE';
                        $hwcentro['RACK']['COFRES'][$nomcofre]['CONTPAN'] = $equipo;
                    }
                }
            }
            if ($idcentro == 13){
                $hwcentro['RACK']['COFRES'][$nomcofre]['TARJETAS'][$canal] = $tarjcanal;
            }
            else{
                $hwcentro['RACK']['COFRES'][$nomcofre]['TARJETAS'] = $tarjetas;
            }
        }
        else{
            throw new NotFoundException(__('Error: No se ha encontrado el fichero de inventario'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // Borramos los equipos del centro
            $this->Equipo->deleteAll(array('Equipo.centro_id' => $idcentro), false);
            // Guardamos el rack>
            $equipodb = array(
                'nombre' => $hwcentro['RACK']['EQUIPO'], 'centro_id' => $idcentro,
                'marca' => 'BTESA', 'codhw' => $hwcentro['RACK']['CODHW'],
                'codsw' => $hwcentro['RACK']['CODSW'], 'nserie' => $hwcentro['RACK']['NSERIE'],
                'snmp' => $hwcentro['RACK']['SNMP'], 'canal' => 0, 'fecha' => $hwcentro['RACK']['FECHA'],
                'tipo' => 'SUP', 'rango' => 0, 'padre' => 0,
            );
            $this->Equipo->create();
            if ($this->Equipo->save($equipodb)){
                $idrack = $this->Equipo->id;
                // Guardamos los cofres
                foreach ($hwcentro['RACK']['COFRES'] as $equipo => $cofre) {
                    $equipodb = array(
                        'nombre' =>  $cofre['EQUIPO'], 'centro_id' => $idcentro,
                        'marca' => 'BTESA', 'codhw' => $cofre['CODHW'], 'fecha' => $cofre['FECHA'],
                        'canal' => 0, 'tipo' => 'COFRE', 'rango' => 1, 'padre' => $idrack,
                    );
                    $this->Equipo->create();
                    if ($this->Equipo->save($equipodb)){
                        $idcofre = $this->Equipo->id;
                        $potencia = 0;
                        if ($idcentro == 13){
                            $potencia = $cofre['CONTPAN']['POTENCIA'];
                        }
                        $equipodb = array(
                            'nombre' => $cofre['CONTPAN']['EQUIPO'], 'centro_id' => $idcentro,
                            'codhw' => $cofre['CONTPAN']['CODHW'], 'codsw' => $cofre['CONTPAN']['CODSW'],
                            'nserie' => $cofre['CONTPAN']['NSERIE'], 'fecha' => $cofre['CONTPAN']['FECHA'],
                            'canal' => 0, 'tipo' => $cofre['CONTPAN']['TIPO'], 'marca' => 'BTESA',
                            'rango' => 2, 'padre' => $idcofre, 'potencia' => $potencia,
                        );
                        $this->Equipo->create();
                        if ($this->Equipo->save($equipodb)){
                            foreach ($cofre['TARJETAS'] as $canal => $tarjetas) {
                                $canal = substr($canal, -2);
                                foreach ($tarjetas as $modulo => $tarjeta) {
                                    $potencia = "-";
                                    if(isset($tarjeta['POTENCIA'])){
                                        $potencia = $tarjeta['POTENCIA'];
                                    }
                                    $equipodb = array(
                                        'nombre' => $modulo, 'centro_id' => $idcentro,
                                        'codhw' => $tarjeta['CODHW'], 'codsw' => $tarjeta['CODSW'],
                                        'nserie' => $tarjeta['NSERIE'], 'canal' => $canal,'marca' => 'BTESA',
                                        'rango' => 3, 'tipo' => 'TARJETA',  'padre' => $idcofre, 'potencia' => $potencia
                                    );
                                    $this->Equipo->create();
                                    if (!$this->Equipo->save($equipodb)){
                                        $this->Session->setFlash(__('<b>Error:</b> No se ha podido guardar la tarjeta' . ' ' . $canal . '-' . $equipodb['nombre']), 'default', array('class' => 'ink-alert basic error'));
                                        $this->redirect(array('controller' => 'centros', 'action' => 'leerhw', $idcentro));
                                    }
                                }
                            }
                        }
                        else{
                            $this->Session->setFlash(__('<b>Error:</b> No se ha podido guardar el Panel de Control' . ' ' . $equipodb['nombre']), 'default', array('class' => 'ink-alert basic error'));
                            $this->redirect(array('controller' => 'equipos', 'action' => 'leerhw', $idcentro));
                        }
                    }
                    else{
                        $this->Session->setFlash(__('<b>Error:</b> No se ha podido guardar el cofre' . ' ' . $equipodb['nombre']), 'default', array('class' => 'ink-alert basic error'));
                        $this->redirect(array('controller' => 'equipos', 'action' => 'leerhw', $idcentro));
                    }
                }
            }
            else{
                $this->Session->setFlash(__('<b>Error:</b> No se ha podido guardar el bastidor' . ' ' . $hwcentro['RACK']['EQUIPO']), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'equipos', 'action' => 'leerhw', $idcentro));
            }
            $this->Session->setFlash(__('Equipos guardados correctamente'), 'default', array('class' => 'ink-alert basic success'));
            $this->redirect(array('controller' => 'equipos', 'action' => 'centro', $idcentro));
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Leer inventario de Centro TDT'));
            $this->set('hwcentro', $hwcentro);
            $this->set('equipos', $equipos);
        }
    }

    public function editar ($id = null){
        $this->Equipo->id = $id;
        if (!$this->Equipo->exists()) {
            throw new NotFoundException(__('Error: el equipo seleccionado no existe'));
        }
        $equipo = $this->Equipo->read(null, $id);
        if ($this->request->is('post') || $this->request->is('put')) {
            // Guardamos los datos:
            if ($this->Equipo->save($this->request->data)) {
                $this->Session->setFlash(__('Equipo modificado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'equipos', 'action' => 'centro', $equipo['Equipo']['centro_id']));
            }
            else {
                $this->Session->setFlash(__('Error al guardar el equipo'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'equipos', 'action' => 'centro', $equipo['Equipo']['centro_id']));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Modificar Equipo TDT'));
            $this->request->data = $equipo;
        }
    }

    public function agregar ($idcentro = null){
        $this->Equipo->Centro->id = $idcentro;
        if (!$this->Equipo->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $centro = $this->Equipo->Centro->read(null, $idcentro);
        if ($this->request->is('post') || $this->request->is('put')) {
            // Guardamos los datos:
            if ($this->Equipo->save($this->request->data)) {
                $this->Session->setFlash(__('Equipo modificado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'equipos', 'action' => 'centro', $equipo['Equipo']['centro_id']));
            }
            else {
                $this->Session->setFlash(__('Error al guardar el equipo'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'equipos', 'action' => 'centro', $equipo['Equipo']['centro_id']));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Agregar Equipo a Centro TDT'));
            $this->set('centro', $centro);
        }
    }


}
?>
