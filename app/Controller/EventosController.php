<?php
/*
    Controlador para eventos:
*/

class EventosController extends AppController {

    // Opciones de paginación por defecto:
    public $paginate = array(
        'limit' => 50,
        'order' => array('Evento.fecha_act' => 'desc')
    );

    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
        if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array(
                    'index', 'detalle', 'xlseventos', 'xlshistorico'
                );
            }
            elseif ($rol == 'consum') {
                $accPerm = array('index', 'detalle', 'xlseventos');
            }
            if (in_array($this->action, $accPerm)) {
                return true;
            }
            else{
                return parent::isAuthorized($user);
            }
        }
    }

    public function index(){
        // Clases para la lectura de ficheros:
        App::uses('Folder', 'Utility');
        //App::uses('File', 'Utility');

        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Eventos de Centros TDT de la Generalitat'));

        // Select de Centros
        $this->loadModel('Centro');
        $this->Centro->recursive = -1;
        $opciones = array(
            'fields' => array('Centro.evento_id', 'Centro.centro'),
            'order' => 'Centro.centro',
            'conditions' => array('Centro.evento_id >' => '-1')
        );
        $this->set('centrosel', $this->Centro->find('list', $opciones));

        // Buscamos las fechas
        $fecha = date('Y-m-d');
        $anyo = substr($fecha,0,4);
        $mesindex = substr($fecha,5,2);
        $meses = array(
            '01' => '12', '02' => '01', '03' => '02', '04' => '03', '05' => '04', '06' => '03',
            '07' => '06', '08' => '07', '09' => '08', '10' => '09', '11' => '10', '12' => '11',
        );
        $mes = $meses[$mesindex];
        if ($mes == '12'){
            $anyo = $anyo - 1;
        }
        $diasmes = array(
            '01' => 31, '02' => 28, '03' => 31, '04' => 30, '05' => 31, '06' => 30,
            '07' => 31, '08' => 31, '09' => 30, '10' => 31, '11' => 30, '12' => 31,
        );
        $dias = $diasmes[$mes];
        $fechas_inicio = array();
        for ($i = 0; $i < 2; $i++){
            if($mes == '13'){
                $mes = '01';
                $anyo = $anyo + 1;
            }
            for ($j = 1;$j <= $dias; $j++){
                $dia = $j;
                if ($j < 10){
                    $dia = '0' . $dia;
                }
                $fecha_iter = $anyo.'-'.$mes.'-'.$dia;
                if ($fecha_iter <= $fecha){
                    $fechas_inicio[] = $fecha_iter;
                }
            }
            $mes++;
            if ($mes < 10){
                $mes = '0' . $mes;
            }
        }
        $this->set('fechas', $fechas_inicio);

        // Buscamos los historicos:
        $dir = new Folder(WWW_ROOT . 'files');
        $ficheros = $dir->find('Historico.*\.csv', true);
        $historicos = array();
        $mesnom = array(
            '01' => __('Enero'), '02' => __('Febrero'), '03' => __('Marzo'),
            '04' => __('Abril'), '05' => __('Mayo'), '06' => __('Junio'),
            '07' => __('Julio'), '08' => __('Agosto'), '09' => __('Septiembre'),
            '10' => __('Octubre'), '11' => __('Noviembre'), '12' => __('Diciembre'),
        );
        foreach ($ficheros as $nom_fichero) {
            $fecha = substr($nom_fichero, 10, 7);
            $anyo = substr($nom_fichero, 10, 4);
            $mes = substr($nom_fichero, 15, 2);
            $mestxt = $mesnom[$mes];
            $historicos[$fecha] = $mestxt . ' ' . $anyo;
        }
        $this->set('historicos', $historicos);

        // Comprobamos si hemos recibido datos del formulario:
        $condiciones = array();
        if ($this->request->is('post')){
            // Select de Centro
            if (!empty($this->request->data['Evento']['centro_id'])){
                $addcond = array('Evento.centro_id'  => $this->request->data['Evento']['centro_id']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Select de Nivel
            if ($this->request->data['Evento']['id_nivel'] != ""){
                $addcond = array('Evento.id_nivel = '  => $this->request->data['Evento']['id_nivel']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Select de Tipo
            if ($this->request->data['Evento']['id_tipo'] != ""){
                $addcond = array('Evento.id_tipo = '  => $this->request->data['Evento']['id_tipo']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Fecha desde
            if ($this->request->data['Evento']['fechadesde'] != ""){
                $addcond = array('Evento.fecha_act >= '  => $this->request->data['Evento']['fechadesde']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Fecha hasta
            if ($this->request->data['Evento']['fechahasta'] != ""){
                $addcond = array('Evento.fecha_act <= '  => $this->request->data['Evento']['fechahasta']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Cambio de página
            if (!empty($this->request->data['Evento']['irapag'])&&($this->request->data['Evento']['irapag'] > 0)){
                $this->paginate['page'] = $this->request->data['Evento']['irapag'];
            }
            // Tamaño de página
            if (!empty($this->request->data['Evento']['regPag'])&&($this->request->data['Evento']['regPag'] > 0)){
                $this->paginate['limit'] = $this->request->data['Evento']['regPag'];
            }
        }
        $this->paginate['conditions'] = $condiciones;
        //$eventos = $this->paginate();
        $this->set('eventos', $this->paginate());
    }

    public function xlseventos(){
        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Evento']['centro_id'])){
                $idevento = $this->request->data['Evento']['centro_id'] ;
                // Buscamos el Centro
                $this->loadModel('Centro');
                $this->Centro->recursive = -1;
                $centro = $this->Centro->findByEventoId($idevento);
                if (count($centro) > 0){
                    $this->set('centro', $centro);
                    // Buscamos los Eventos:
                    $condiciones = array('Evento.centro_id'  => $idevento);
                    $criterios = array();
                    // Select de Nivel
                    if ($this->request->data['Evento']['id_nivel'] != ""){
                        $addcond = array('Evento.id_nivel = '  => $this->request->data['Evento']['id_nivel']);
                        $condiciones = array_merge($addcond, $condiciones);
                        $niveles = array(0 => __('Sistema'), 1 => __('Alarma'), 2 => __('Aviso'), 3 => __('Información'));
                        $criterios['nivel'] = $niveles[$this->request->data['Evento']['id_nivel']];
                    }
                    // Select de Tipo
                    if ($this->request->data['Evento']['id_tipo'] != ""){
                        $addcond = array('Evento.id_tipo = '  => $this->request->data['Evento']['id_tipo']);
                        $condiciones = array_merge($addcond, $condiciones);
                        $tipos = array(0 => __('Activación de Alarma'), 1 => __('Cese de Alarma'), 2 => __('Mensaje Espontáneo'), 3 => __('Evento Desconocido'));
                        $criterios['tipo'] = $tipos[$this->request->data['Evento']['id_tipo']];
                    }
                    // Fecha desde
                    if ($this->request->data['Evento']['fechadesde'] != ""){
                        $addcond = array('Evento.fecha_act >= '  => $this->request->data['Evento']['fechadesde']);
                        $condiciones = array_merge($addcond, $condiciones);
                        $criterios['fechadesde'] = $this->request->data['Evento']['fechadesde'];
                    }
                    // Fecha hasta
                    if ($this->request->data['Evento']['fechahasta'] != ""){
                        $addcond = array('Evento.fecha_act <= '  => $this->request->data['Evento']['fechahasta']);
                        $condiciones = array_merge($addcond, $condiciones);
                        $criterios['fechahasta'] = $this->request->data['Evento']['fechahasta'];
                    }
                    $this->set('criterios', $criterios);

                    $eventos = $this->Evento->find('all', array(
                        'conditions' => $condiciones,
                        'order' => array('Evento.fecha_act desc'),
                    ));
                    $neventos = count($eventos);
                    $this->set('eventos', $eventos);
                }
                else{
                    throw new NotFoundException('No se ha encontrado el centro seleccionado');
                }

            }
            else{
                throw new NotFoundException('No se ha definido un centro');
            }
            $this->Components->unload('DebugKit.Toolbar');
            $this->layout = 'xls';
        }
        else {
            throw new MethodNotAllowedException(__('Error: Método de Acceso no Autorizado'));
        }
    }

    public function xlshistorico(){
        // Clases para la lectura de ficheros:
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        if ($this->request->is('post') || $this->request->is('put')) {
            if (isset($this->request->data['Evento']['fecha'])){
                $fecha = $this->request->data['Evento']['fecha'];
                $nomFichero = 'Historico_' . $fecha . '.csv';
                $dir = new Folder(WWW_ROOT . 'files');
                $ficheros = $dir->find($nomFichero, true);
                if (count($ficheros) > 0){
                    $this->response->file(WWW_ROOT.'files/'. $nomFichero, array('download' => true, 'name' => $nomFichero));
                    return $this->response;
                }
                else{
                    throw new NotFoundException(__('Error: No se ha encontrado el fichero CSV'));
                }
            }
            else{
                throw new NotFoundException(__('Error: No se ha definido la fecha'));
            }
        }
        else {
            throw new MethodNotAllowedException(__('Error: Método de Acceso no Autorizado'));
        }
    }
}
?>
