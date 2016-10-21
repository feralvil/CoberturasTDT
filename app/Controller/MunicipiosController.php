<?php

/**
 * Descriptción de MunicipiosController
 *
 * @author alfonso_fer
 */
class MunicipiosController extends AppController {
    // Opciones de paginación por defecto:
    public $paginate = array(
        'limit' => 30,
        'order' => array(
            'Municipios.provincia' => 'asc',
            'Municipios.nombre' => 'asc',
        )
    );

    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
         if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array('index', 'detalle', 'carta', 'cartapdf', 'cartaserv', 'pdfdetalle', 'xlsmunicipios', 'hogares');
            }
            elseif ($rol == 'consum') {
                $accPerm = array('index', 'detalle', 'pdfdetalle', 'xlsmunicipios');
            }
            if (in_array($this->action, $accPerm)) {
                return true;
            }
            else{
                return parent::isAuthorized($user);
            }
        }
    }

    public function index() {
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Municipios de la Comunitat Valenciana'));

        // Select de Provincias
        $opciones = array(
            'fields' => array('Municipio.cpro', 'Municipio.provincia'),
            'order' => 'Municipio.provincia'
        );
        $this->set('provsel', $this->Municipio->find('list', $opciones));

        // Array de condiciones para la búsqueda:
        $condiciones = array();

        // Comprobamos si hemos recibido datos del formulario:
        if ($this->request->is('post')){
            // Condiciones iniciales:
            $tampag = 30;
            $pagina = 1;
            // Select de Flota
            if (!empty($this->request->data['Municipios']['provincia'])){
                $addcond = array('Municipio.cpro'  => $this->request->data['Municipios']['provincia']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Campo de Municipio
            if (!empty($this->request->data['Municipios']['nombre'])){
                $addcond = array('Municipio.nombre LIKE '  => '%'.$this->request->data['Municipios']['nombre'].'%');
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Cambio de página
            if (!empty($this->request->data['Municipios']['irapag'])&&($this->request->data['Municipios']['irapag'] > 0)){
                $this->paginate['page'] = $this->request->data['Municipios']['irapag'];
            }
            // Tamaño de página
            if (!empty($this->request->data['Municipios']['regPag'])&&($this->request->data['Municipios']['regPag'] > 0)){
                $this->paginate['limit'] = $this->request->data['Municipios']['regPag'];
            }
        }

        // Solo buscamos los datos de las flotas:
        $this->Municipio->recursive = 1;
        $this->paginate['conditions'] = $condiciones;
        $municipios = $this->paginate();
        $this->set('municipios', $municipios);
    }

    public function detalle($id = null) {
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Municipio de la Comunitat Valenciana'));

        $this->Municipio->id = $id;
        if (!$this->Municipio->exists()) {
            throw new NotFoundException(__('Error: el municipio seleccionado no existe'));
        }
        $municipio = $this->Municipio->read(null, $id);

        // Buscamos los centros que cubren el municipio:
         if (count($municipio['Cobertura'] > 0)){
             $this->loadModel('Centro');
             $this->loadModel('Programa');
             $centros = array();
             foreach ($municipio['Cobertura'] as $cobertura){
                 $centro = array();
                 $centroBBDD = $this->Centro->find('first', array('conditions' => array('Centro.id' => $cobertura['centro_id'])));
                 $centro['centro_id'] = $centroBBDD['Centro']['id'];
                 $centro['centro'] = $centroBBDD['Centro']['centro'];
                 $centro['nmux'] = count($centroBBDD['Emision']);
                 $centro['cobertura'] = $cobertura['porcentaje'];
                 $centro['habcub'] = round(($municipio['Municipio']['poblacion'] * $cobertura['porcentaje']) / 100);
                 if (count($centroBBDD['Emision']) > 0){
                     $multiples = array();
                     foreach ($centroBBDD['Emision'] as $emision) {
                         $multiple = $this->Centro->Emision->Multiple->find('first', array('conditions' => array('Multiple.id' => $emision['multiple_id'])));
                         $programas = $this->Programa->find('all', array('conditions' => array('Programa.multiple_id' => $emision['multiple_id'])));
                         $multiple['Multiple']['canal'] = $emision['canal'];
                         $multiple['Programas'] = $programas;
                         array_push($multiples, $multiple);
                     }
                 }
                 $centro['multiples'] = $multiples;

                 array_push($centros, $centro);
             }
             $municipio['Centros'] = $centros;
         }
         $this->set('municipio', $municipio);
    }

    public function carta() {
        // Leemos todos los municipios:
        $municipios = $this->Municipio->find('all');

        // Fijamos el título para la vista:
        $this->set('title_for_layout', __('Municipios con Centros TDT de la Generalitat'));

        $municarta = array();

        // Modelos auxiliares
        $this->loadModel('Multiple');
        $this->loadModel('Programa');

        foreach ($municipios as $idmuni => $municipio) {
            if (!empty($municipio['Cobertura'])){
                $muni['Municipio'] = $municipio['Municipio'];
                $centrosMuni = array();
                foreach ($municipio['Cobertura'] as $cobertura) {
                    $this->Municipio->Cobertura->Centro->id = $cobertura['centro_id'];
                    if (!$this->Municipio->Cobertura->Centro->exists()) {
                        throw new NotFoundException(__('Error: el Centro seleccionado no existe'));
                    }
                    $centro = $this->Municipio->Cobertura->Centro->read(null, $cobertura['centro_id']);
                    $centroRedux['Centro'] = array('id_centro' => $centro['Centro']['id'], 'centro' => $centro['Centro']['centro']);
                    $centroRedux['Emision'] = $centro['Emision'];
                    $multiples = array();
                    foreach ($centro['Emision'] as $emision) {
                        $idmux = $emision['multiple_id'];
                        $nombre_mux = $this->Multiple->field('nombre', array('Multiple.id' => $idmux));
                        // Buscamos los programas
                        $this->Programa->recursive = -1;
                        $programas = $this->Programa->find('all', array('conditions' => array('Programa.multiple_id' => $idmux)));
                        $muxcentro = array(
                            'nombre' => $nombre_mux,
                            'canal' => $emision['canal'],
                            'tipo' => $emision['tipo'],
                            'programas' => $programas
                        );
                        array_push($multiples, $muxcentro);
                    }
                    $centroRedux['Emision'] = $multiples;
                    array_push($centrosMuni, $centroRedux);
                }
                $muni['Centros'] = $centrosMuni;
                array_push($municarta, $muni);
            }
        }
        $this->set('municipios', $municarta);
    }

    public function cartapdf() {
        // Aumentamos el tiempo máximo de ejecución
        ini_set('max_execution_time', '60');

        // Nos cargamos todos los datos:
        $municipios = $this->Municipio->find('all');//, array('limit' => 30));

        $municarta = array();
        // Modelos auxiliares
        $this->loadModel('Multiple');
        $this->loadModel('Programa');

        foreach ($municipios as $municipio) {
            if (!empty($municipio['Cobertura'])) {
                $muni['Municipio'] = $municipio['Municipio'];
                $centrosMuni = array();
                foreach ($municipio['Cobertura'] as $cobertura) {
                    $this->Municipio->Cobertura->Centro->id = $cobertura['centro_id'];
                    if (!$this->Municipio->Cobertura->Centro->exists()) {
                        throw new NotFoundException(__('Error: el Centro seleccionado no existe'));
                    }
                    $centro = $this->Municipio->Cobertura->Centro->read(null, $cobertura['centro_id']);
                    $centroRedux['Centro'] = array('id_centro' => $centro['Centro']['id'], 'centro' => $centro['Centro']['centro']);
                    $centroRedux['Emision'] = $centro['Emision'];
                    $multiples = array();
                    foreach ($centro['Emision'] as $emision) {
                        $idmux = $emision['multiple_id'];
                        $nombre_mux = $this->Multiple->field('nombre', array('Multiple.id' => $idmux));
                        // Buscamos los programas
                        $this->Programa->recursive = -1;
                        $programas = $this->Programa->find('all', array('conditions' => array('Programa.multiple_id' => $idmux)));
                        $muxcentro = array(
                            'nombre' => $nombre_mux,
                            'canal' => $emision['canal'],
                            'tipo' => $emision['tipo'],
                            'programas' => $programas
                        );
                        array_push($multiples, $muxcentro);
                    }
                    $centroRedux['Emision'] = $multiples;
                    array_push($centrosMuni, $centroRedux);
                }
                $muni['Centros'] = $centrosMuni;
                array_push($municarta, $muni);
            }
        }
        $this->set('municipios', $municarta);

        $this->response->header(array('Content-type: application/pdf'));
        $this->response->type('pdf');
        $this->layout = 'pdf';
    }

    public function cartaserv() {
        // Aumentamos el tiempo máximo de ejecución
        ini_set('max_execution_time', '60');

        // Nos cargamos todos los datos:
        $municipios = $this->Municipio->find('all');//, array('limit' => 50));

        $municarta = array();
        // Modelos auxiliares
        $this->loadModel('Multiple');
        $this->loadModel('Programa');

        foreach ($municipios as $municipio) {
            if (!empty($municipio['Cobertura'])) {
                $muni['Municipio'] = $municipio['Municipio'];
                $centrosMuni = array();
                foreach ($municipio['Cobertura'] as $cobertura) {
                    $this->Municipio->Cobertura->Centro->id = $cobertura['centro_id'];
                    if (!$this->Municipio->Cobertura->Centro->exists()) {
                        throw new NotFoundException(__('Error: el Centro seleccionado no existe'));
                    }
                    $centro = $this->Municipio->Cobertura->Centro->read(null, $cobertura['centro_id']);
                    $centroRedux['Centro'] = array('id_centro' => $centro['Centro']['id'], 'centro' => $centro['Centro']['centro']);
                    $centroRedux['Emision'] = $centro['Emision'];
                    $multiples = array();
                    foreach ($centro['Emision'] as $emision) {
                        $idmux = $emision['multiple_id'];
                        $nombre_mux = $this->Multiple->field('nombre', array('Multiple.id' => $idmux));
                        // Buscamos los programas
                        $this->Programa->recursive = -1;
                        $programas = $this->Programa->find('all', array('conditions' => array('Programa.multiple_id' => $idmux)));
                        $muxcentro = array(
                            'nombre' => $nombre_mux,
                            'canal' => $emision['canal'],
                            'tipo' => $emision['tipo'],
                            'programas' => $programas
                        );
                        array_push($multiples, $muxcentro);
                    }
                    $centroRedux['Emision'] = $multiples;
                    array_push($centrosMuni, $centroRedux);
                }
                $muni['Centros'] = $centrosMuni;
                array_push($municarta, $muni);
            }
        }
        $this->set('municipios', $municarta);

        header('Content-type: application/zip');
        $this->layout = 'zip';
    }

    public function pdfdetalle($id = null) {
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Municipio de la Comunitat Valenciana'));

        $this->Municipio->id = $id;
        if (!$this->Municipio->exists()) {
            throw new NotFoundException(__('Error: el municipio seleccionado no existe'));
        }
        $municipio = $this->Municipio->read(null, $id);

        // Buscamos los centros que cubren el municipio:
         if (count($municipio['Cobertura'] > 0)){
             $this->loadModel('Centro');
             $this->loadModel('Programa');
             $centros = array();
             foreach ($municipio['Cobertura'] as $cobertura){
                 $centro = array();
                 $centroBBDD = $this->Centro->find('first', array('conditions' => array('Centro.id' => $cobertura['centro_id'])));
                 $centro['centro_id'] = $centroBBDD['Centro']['id'];
                 $centro['centro'] = $centroBBDD['Centro']['centro'];
                 $centro['nmux'] = count($centroBBDD['Emision']);
                 $centro['cobertura'] = $cobertura['porcentaje'];
                 $centro['habcub'] = round(($municipio['Municipio']['poblacion'] * $cobertura['porcentaje']) / 100);
                 if (count($centroBBDD['Emision']) > 0){
                     $multiples = array();
                     foreach ($centroBBDD['Emision'] as $emision) {
                         $multiple = $this->Centro->Emision->Multiple->find('first', array('conditions' => array('Multiple.id' => $emision['multiple_id'])));
                         $programas = $this->Programa->find('all', array('conditions' => array('Programa.multiple_id' => $emision['multiple_id'])));
                         $multiple['Multiple']['canal'] = $emision['canal'];
                         $multiple['Programas'] = $programas;
                         array_push($multiples, $multiple);
                     }
                 }
                 $centro['multiples'] = $multiples;

                 array_push($centros, $centro);
             }
             $municipio['Centros'] = $centros;
         }
         $this->set('municipio', $municipio);

         $this->response->header(array('Content-type: application/pdf'));
         $this->response->type('pdf');
         $this->layout = 'pdf';
    }

    public function xlsmunicipios(){
        // Solo buscamos los datos de los Municipios:
        $this->Municipio->recursive = 1;
        $municipios = $this->Municipio->find('all');
        $this->set('municipios', $municipios);
        $this->layout = 'xls';
    }

    public function hogares(){
        $this->Municipio->recursive = -1;
        // Buscamos el fichero
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        // Comrpobamos si existe
        $ruta = 'files' . DS . 'BBDD-MunicipiosHogares.ods';
        $fichero = new File($ruta, false);
        if ($fichero->exists()){
            // Cargamos la clase para leer el fichero Excel:
            App::import('Vendor', 'Classes/PHPExcel');
            // Intentamos cargar el fichero
            try{
                $tipoFich = PHPExcel_IOFactory::identify($ruta);
                $objReader = PHPExcel_IOFactory::createReader($tipoFich);
                // Sólo nos interesa cargar los datos:
                $objReader->setReadDataOnly(true);
                $objPHPExcel = $objReader->load($ruta);
            }
            catch(Exception $e){
                die("Error al cargar el fichero de datos: ".$e->getMessage());
            }
            // Nos vamos a la hoja de Catastro
            // Fijamos como hoja activa la primera (sólo se importa una)
            $objPHPExcel->setActiveSheetIndex(0);
            // Obtenemos el número de filas de la hoja:
            $maxfila = $objPHPExcel->getActiveSheet()->getHighestRow() + 1;
            $fila = 2;
            $municipios = array();
            while (($fila < $maxfila) && ($objPHPExcel->getActiveSheet()->getCell("A" . $fila)->getValue()!= "")){
                $idmuni = $objPHPExcel->getActiveSheet()->getCell("A" . $fila)->getValue();
                $municipio = $this->Municipio->read(null, $idmuni);
                $municipio['Municipio']['hogares'] = $objPHPExcel->getActiveSheet()->getCell("C" . $fila)->getValue();
                $municipios[] = $municipio;
                $fila++;
            }
            if ($this->request->is('post') || $this->request->is('put')) {
                // Aquí
                $nmunicipios = 0;
                foreach ($municipios as $municipio) {
                    $this->Municipio->read(null, $municipio['Municipio']['id']);
                    $this->Municipio->set(array(
                        'hogares' => $municipio['Municipio']['hogares'],
                    ));
                    if ($this->Municipio->save()){
                        $nmunicipios++;
                    }
                    else{
                        $this->Session->setFlash(__('Error al guardar datos de Hogares del Municipio') . ' ' . $municipio['Municipio']['nombre'], 'default', array('class' => 'ink-alert basic error'));
                        $this->redirect(array('controller' => 'municipios', 'action' => 'hogares'));
                    }
                }
                $this->Session->setFlash(__('Datos de Hogares de') . ' ' . $nmunicipios . ' ' . __('Municipios importados correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'municipios', 'action' => 'index'));

            }
            else{
                $this->set('title_for_layout', __('Importar datos de Hogares de Municipios'));
                $this->set('municipios', $municipios);
            }
        }
    }
}


?>
