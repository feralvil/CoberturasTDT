<?php
/**
* Descriptción de MunicipiosController
*
* @author alfonso_fer
*/
class CentrosController extends AppController {
    // Opciones de paginación por defecto:
    public $paginate = array(
        'limit' => 30,
        'order' => array('Centro.nombre' => 'asc')
    );

    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
        if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array(
                    'index', 'detalle', 'editar', 'agregar', 'cobertura', 'tipologia', 'updateTipo', 'emisiones',
                    'impemisiones', 'multiples', 'impkml', 'leerkml', 'mapacentro', 'xlscentros', 'xlsdescargar',
                    'apagar', 'mapadet', 'xlsanexo', 'supervision', 'importarhw', 'leerhw', 'propietarios', 'equipos'
                );
            }
            elseif ($rol == 'consum') {
                $accPerm = array('index', 'detalle', 'xlscentros', 'xlsdescargar');
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
        $this->set('title_for_layout', __('Centros TDT de la Generalitat'));

        // Select de Provincias
        // Buscamos la provincia:
        $this->loadModel('Municipio');
        $this->Municipio->recursive = -1;
        $opciones = array(
            'fields' => array('Municipio.cpro', 'Municipio.provincia'),
            'order' => 'Municipio.provincia'
        );
        $this->set('provsel', $this->Municipio->find('list', $opciones));

        // Select de Equipos
        $opciones = array(
            'fields' => array('Centro.equipo', 'Centro.equipo'),
            'order' => 'Centro.equipo'
        );
        $this->set('equiposel', $this->Centro->find('list', $opciones));

        // Comprobamos si hemos recibido datos del formulario:
        $condiciones = array();
        if ($this->request->is('post')){
            // Condiciones iniciales:
            $tampag = 30;
            $pagina = 1;
            // Select de Flota
            if (!empty($this->request->data['Centros']['provincia'])){
                $addcond = array('Centro.provincia'  => $this->request->data['Centros']['provincia']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Campo de Municipio
            if (!empty($this->request->data['Centros']['nombre'])){
                $addcond = array('Centro.centro LIKE '  => '%'.$this->request->data['Centros']['nombre'].'%');
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Select de Tipología
            if (!empty($this->request->data['Centros']['tipologia'])){
                $addcond = array('Centro.tipologia'  => $this->request->data['Centros']['tipologia']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Select de Activo
            if (!empty($this->request->data['Centros']['activo'])){
                $addcond = array('Centro.activo'  => $this->request->data['Centros']['activo']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Select de Equipo
            if (!empty($this->request->data['Centros']['equipo'])){
                $addcond = array('Centro.equipo'  => $this->request->data['Centros']['equipo']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Select de Polaridad
            if (!empty($this->request->data['Centros']['polaridad'])){
                $addcond = array('Centro.polaridad'  => $this->request->data['Centros']['polaridad']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Cambio de página
            if (!empty($this->request->data['Centros']['irapag'])&&($this->request->data['Centros']['irapag'] > 0)){
                $this->paginate['page'] = $this->request->data['Centros']['irapag'];
            }
            // Tamaño de página
            if (!empty($this->request->data['Centros']['regPag'])&&($this->request->data['Centros']['regPag'] > 0)){
                $this->paginate['limit'] = $this->request->data['Centros']['regPag'];
            }
        }
        $this->paginate['conditions'] = $condiciones;
        $centros = $this->paginate();

        // Cargamos el Modelo de Multiple
        $this->loadModel('Multiple');
        $multiples = $this->Multiple->find('list',
            array(
                'conditions' => array('Multiple.soportado' => 'SI'),
                'fields' => array('Multiple.id', 'Multiple.nombre')
            )
        );
        $this->set('multiples', $multiples);

        foreach ($centros as &$centro) {
            $muxcentro = array();
            foreach ($centro['Emision'] as $emision) {
                $tipo = "GF";
                if ($emision['tipo'] == "Emisor"){
                    $tipo = "E";
                }
                $muxcentro[$emision['multiple_id']] = $emision['canal'] . "-" . $tipo;
            }
            foreach ($multiples as $id_mux => $nom_mux) {
                $valmux = "-";
                if (array_key_exists($id_mux, $muxcentro)){
                    $valmux = $muxcentro[$id_mux];
                }
                $centro['Centro'][$nom_mux] = $valmux;
            }
            $habCubiertos = 0;
            if (count($centro['Emision']) > 0){
                foreach ($centro['Cobertura'] as $cobCentro) {
                    $idmuni = $cobCentro['municipio_id'];
                    $cobertura = $cobCentro['porcentaje'];
                    $municipio = $this->Municipio->read(null, $idmuni);
                    $habitantes = $municipio['Municipio']['poblacion'];
                    $habCubiertos = $habCubiertos + round($habitantes * $cobertura / 100);
                }
            }
            $centro['Centro']['habCubiertos'] = $habCubiertos;
        }
        $this->set('centros', $centros);
    }

    public function detalle($id = null) {
        $this->Centro->id = $id;
        if (!$this->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $centro = $this->Centro->read(null, $id);

        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Centro TDT de ') . $centro['Centro']['centro']);

        // Buscamos la provincia:
        $this->loadModel('Municipio');
        $this->Municipio->recursive = -1;
        $prov = $this->Municipio->find('first', array(
            'conditions' => array('Municipio.cpro' => $centro['Centro']['provincia']),
            'fields' => array('Municipio.provincia')
        ));
        $centro['Centro']['provincia'] = $prov['Municipio']['provincia'];

        // Buscamos los municipios de las coberturas:
        if (count($centro['Cobertura'] > 0)){
            $coberturas = array();
            foreach ($centro['Cobertura'] as $cobertura){
                $cobiter = array();
                $muni = $this->Municipio->find('first', array('conditions' => array('Municipio.id' => $cobertura['municipio_id'])));
                $cobiter['municipio'] = $muni['Municipio']['nombre'];
                $cobiter['municipio_id'] = $muni['Municipio']['id'];
                $cobiter['provincia'] = $muni['Municipio']['provincia'];
                $cobiter['poblacion'] = $muni['Municipio']['poblacion'];
                $cobiter['habcub'] = round(($muni['Municipio']['poblacion'] * $cobertura['porcentaje']) / 100);
                $cobiter['cobertura'] = '('.$cobertura['porcentaje'].' %)';
                $cobiter['idcob'] = $cobertura['id'];
                array_push($coberturas, $cobiter);
            }
            $centro['Cobmuni'] = $coberturas;
        }

        // Buscamos los múltiples y canales:
        if (count($centro['Emision'] > 0)){
            $this->loadModel('Programa');
            $emiext = array();
            foreach ($centro['Emision'] as $emision){
                $idmux = $emision['multiple_id'];
                $nombre_mux = $this->Centro->Emision->Multiple->field('nombre', array('Multiple.id' => $idmux));
                // Buscamos los programas
                $this->Programa->recursive = -1;
                $programas = $this->Programa->find('all', array('conditions' => array('Programa.multiple_id' => $idmux)));

                $muxcentro = array(
                    'nombre' => $nombre_mux,
                    'idmux' => $emision['multiple_id'],
                    'canal' => $emision['canal'],
                    'frecuencia' => $emision['frecuencia'],
                    'tipo' => $emision['tipo'],
                    'retardo' => $emision['retardo'],
                    'programas' => $programas
                );

                array_push($emiext, $muxcentro);

            }
            $centro['Emisionext'] = $emiext;
        }

        $this->set('centro', $centro);
    }

    public function cobertura($id = null) {
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Cobertura del Centro TDT'));

        $this->Centro->id = $id;
        if (!$this->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $centro = $this->Centro->read(null, $id);

        // Buscamos la provincia:
        $this->loadModel('Municipio');
        $this->Municipio->recursive = -1;
        $prov = $this->Municipio->find('first', array(
            'conditions' => array('Municipio.cpro' => $centro['Centro']['provincia']),
            'fields' => array('Municipio.provincia')
        ));
        $centro['Centro']['provincia'] = $prov['Municipio']['provincia'];

        // Buscamos los municipios de las coberturas:
        if (count($centro['Cobertura'] > 0)){
            $coberturas = array();
            foreach ($centro['Cobertura'] as $cobertura){
                $cobiter = array();
                $muni = $this->Municipio->find('first', array('conditions' => array('Municipio.id' => $cobertura['municipio_id'])));
                $cobiter['municipio'] = $muni['Municipio']['nombre'];
                $cobiter['municipio_id'] = $muni['Municipio']['id'];
                $cobiter['provincia'] = $muni['Municipio']['provincia'];
                $cobiter['poblacion'] = $muni['Municipio']['poblacion'];
                $cobiter['habcub'] = round(($muni['Municipio']['poblacion'] * $cobertura['porcentaje']) / 100);
                $cobiter['cobertura'] = '('.$cobertura['porcentaje'].' %)';
                array_push($coberturas, $cobiter);
            }
            $centro['Cobmuni'] = $coberturas;
        }
        $this->set('centro', $centro);
    }

    public function tipologia() {
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Tipología de Centros TDT de la Generalitat'));

        // Select de Provincias
        // Buscamos la provincia:
        $this->loadModel('Municipio');
        $this->Municipio->recursive = -1;
        $opciones = array(
            'fields' => array('Municipio.cpro', 'Municipio.provincia'),
            'order' => 'Municipio.provincia'
        );
        $this->set('provsel', $this->Municipio->find('list', $opciones));

        // Array de condiciones para la búsqueda:
        $condiciones = array();

        // Comprobamos si hemos recibido datos del formulario:
        if ($this->request->is('post')){
            // Select de Flota
            if (!empty($this->request->data['Centros']['provincia'])){
                $addcond = array('Centro.provincia'  => $this->request->data['Centros']['provincia']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Campo de Municipio
            if (!empty($this->request->data['Centros']['nombre'])){
                $addcond = array('Centro.centro LIKE '  => '%'.$this->request->data['Centros']['nombre'].'%');
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Cambio de página
            if (!empty($this->request->data['Centros']['irapag'])&&($this->request->data['Centros']['irapag'] > 0)){
                $this->paginate['page'] = $this->request->data['Centros']['irapag'];
            }
            // Tamaño de página
            if (!empty($this->request->data['Centros']['regPag'])&&($this->request->data['Centros']['regPag'] > 0)){
                $this->paginate['limit'] = $this->request->data['Centros']['regPag'];
            }
        }

        $this->paginate['conditions'] = $condiciones;
        $centros = $this->paginate();
        $this->set('centros', $centros);

        // Calculamos los habitantes cubiertos por la tipología:
        $centrosext = array();
        foreach ($centros as $centro){
            $nmuncub = count($centro['Cobertura']);
            $centroiter = array(
                'id' => $centro['Centro']['id'],
                'centro' => $centro['Centro']['centro'],
                'nmux' => $centro['Centro']['nmux'],
                'nmuni' => $nmuncub,
                'nhabcub' => 0,
                'tipobbdd' => $centro['Centro']['tipologia'],
                'tipologia' => 'C2',
                'ta1' => '12',
                'ta2' => '24'
            );
            if ($nmuncub > 0){
                $nhabcub = 0;
                foreach ($centro['Cobertura'] as $cobertura){
                    $muni = $this->Municipio->find('first', array('conditions' => array('Municipio.id' => $cobertura['municipio_id'])));
                    $habiter = round(($muni['Municipio']['poblacion'] * $cobertura['porcentaje']) / 100);
                    $nhabcub = $nhabcub + $habiter;
                }
                $centroiter['nhabcub'] = $nhabcub;
                if ($nhabcub >= 1000){
                    $centroiter['tipologia'] = 'C1';
                    $centroiter['ta1'] = $centroiter['ta1'] / 2;
                    $centroiter['ta2'] = $centroiter['ta2'] / 2;
                }
            }
            array_push($centrosext, $centroiter);
        }
        $this->set('centros', $centros);
        $this->set('centrosext', $centrosext);
    }

    public function agregar(){
        if ($this->request->is('post') || $this->request->is('put')) {
            // Guardamos los datos:
            $this->Centro->create();
            if ($this->Centro->save($this->request->data)) {
                $idcentro = $this->Centro->id;
                $this->Session->setFlash(__('Centro creado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $idcentro));
            } else {
                $this->Session->setFlash(__('Error al guardar el Centro'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'centros', 'action' => 'index'));
            }

        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Nuevo Centro TDT de la Generalitat'));
        }
    }

    public function editar($id = null) {
        $this->Centro->id = $id;
        if (!$this->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // Guardamos los datos:
            if ($this->Centro->save($this->request->data)) {
                $this->Session->setFlash(__('Centro modificado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $id));
            } else {
                $this->Session->setFlash(__('Error al guardar el Centro'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $id));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Modificar Centro TDT de la Generalitat'));
            $this->request->data = $this->Centro->read(null, $id);
        }
    }

    public function multiples($id = null) {
        $this->set('title_for_layout', __('Múltiples de Centro TDT'));

        $this->Centro->id = $id;
        if (!$this->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $centro = $this->Centro->read(null, $id);

        // Buscamos los múltiples:
        if (count($centro['Emision'] > 0)){
            $multiples = array();
            foreach ($centro['Emision'] as $emision){
                $idmux = $emision['multiple_id'];
                $nombre_mux = $this->Centro->Emision->Multiple->field('nombre', array('Multiple.id' => $idmux));
                $multiple = array(
                    'idemision' => $emision['id'],
                    'nombre' => $nombre_mux,
                    'idmux' => $emision['multiple_id'],
                    'canal' => $emision['canal'],
                    'frecuencia' => $emision['frecuencia'],
                    'tipo' => $emision['tipo'],
                    'retardo' => $emision['retardo']
                );
                array_push($multiples, $multiple);
            }
            $centro['Multiples'] = $multiples;
        }
        $this->set('centro', $centro);

        // Buscamos el listado de Múltiples:
        $this->Centro->Emision->Multiple->recursive = -1;
        $opciones = array(
            'fields' => array('Multiple.id', 'Multiple.nombre'),
            'order' => 'Multiple.nombre'
        );
        $this->set('multisel', $this->Centro->Emision->Multiple->find('list', $opciones));
    }

    public function impkml() {
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Centro->ficheroSubido($this->request->data['Centro']['fichero'])){
                // Generamos los ficheros
                App::uses('Folder', 'Utility');
                App::uses('File', 'Utility');
                $fichero = new File($this->request->data['Centro']['fichero']['tmp_name'],true,0644);
                if ($fichero->copy('files'.DS.'centros.kml', true)){
                    $this->Session->setFlash(__('Se ha guardado correctamente el fichero de coordenadas'), 'default', array('class' => 'ink-alert basic success'));
                    $this->redirect(array('controller' => 'centros', 'action' => 'leerkml'));
                }
            }
            else{
                $this->Session->setFlash(__('<b>Error:</b> No se ha seleccionado ningún archivo o ha habido un error al subirlo'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'centros', 'action' => 'impkml'));
            }
        }
        else{
            $this->set('title_for_layout', __('Importar Fichero de coordenadas'));
        }
    }

    public function leerkml() {
        $this->set('title_for_layout', __('Leer Fichero de coordenadas'));
        $opciones = array(
            'fields' => array('Centro.id', 'Centro.centro'),
            'order' => 'Centro.centro'
        );
        $this->set('centrosel', $this->Centro->find('list', $opciones));

        // Buscamos el fichero de coordenadas
        App::uses('Xml', 'Utility');
        $kml = Xml::build('files'.DS.'centros.kml');
        $centros = Xml::toArray($kml);
        $this->set('centros', $centros);
        $kmlsel = array();
        foreach ($centros['kml']['Document']['Folder']['Placemark'] as $indice => $punto) {
            array_push($kmlsel, $punto['name']);
        }
        $this->set('kmlsel', $kmlsel);

        // Comprobamos si hemos recibido datos del formulario:
        if ($this->request->is('post')){
            if (!empty($this->request->data['Centro']['centro_id'])){
                $this->Centro->id = $this->request->data['Centro']['centro_id'];
                if (!$this->Centro->exists()) {
                    throw new NotFoundException(__('Error: el centro seleccionado no existe'));
                }
                $centro = $this->Centro->read(null, $this->request->data['Centro']['centro_id']);
            }
            if (!empty($this->request->data['Centro']['kml_indice'])){
                $indice = $this->request->data['Centro']['kml_indice'];
                $nombrekml = $centros['kml']['Document']['Folder']['Placemark'][$indice]['name'];
                $coordenadas = explode(',', $centros['kml']['Document']['Folder']['Placemark'][$indice]['Point']['coordinates']);
            }
            $tupla = array(
                'id' => $centro['Centro']['id'],
                'centro' => $centro['Centro']['centro'],
                'indicekml' => $indice,
                'nombrekml' => $nombrekml,
                'longitud' => $coordenadas[0],
                'latitud' => $coordenadas[1]
            );
            $this->set('tupla', $tupla);
        }
    }

    public function mapacentro() {
        $this->set('title_for_layout', __('Mapa de Centro'));
        if ($this->request->is('post')){
            $centro = array(
                'id' => $this->request->data['Centro']['idcentro'],
                'centro' => $this->request->data['Centro']['nombre'],
                'longitud' => $this->request->data['Centro']['longitud'],
                'latitud' => $this->request->data['Centro']['latitud']

            );
            $this->set('centro', $centro);
        }
        else{
            throw new MethodNotAllowedException(__('Error: Método de Acceso no Autorizado'));
        }
    }

    public function mapadet($id = null){
        $this->set('title_for_layout', __('Mapa de Centro TDT'));
        $this->layout = 'mapa';

        $this->Centro->id = $id;
        if (!$this->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $centro = $this->Centro->read(null, $id);
        $this->set('centro', $centro);
    }

    public function xlscentros(){
        // Aumentamos el tiempo máximo de ejecución
        ini_set('max_execution_time', '60');

        // Cargamos el Modelo de Municipio:
        $this->loadModel('Municipio');
        // Buscamos la provincia:
        $this->Municipio->recursive = -1;
        $opciones = array(
            'fields' => array('Municipio.cpro', 'Municipio.provincia'),
            'order' => 'Municipio.provincia'
        );
        $provincias = $this->Municipio->find('list', $opciones);

        // Cargamos el Modelo de Multiple
        $this->loadModel('Multiple');
        $multiples = $this->Multiple->find('list',
            array(
                'conditions' => array('Multiple.soportado' => 'SI'),
                'fields' => array('Multiple.id', 'Multiple.nombre')
            )
        );
        $this->set('multiples', $multiples);

        $centros = $this->Centro->find('all',
            array('order' => 'Centro.centro')
        );
        foreach ($centros as &$centro) {
            $centro['Centro']['provincia'] = $provincias[$centro['Centro']['provincia']];
            $muxcentro = array();
            foreach ($centro['Emision'] as $emision) {
                $tipo = "GF";
                if ($emision['tipo'] == "Emisor"){
                    $tipo = "E";
                }
                $muxcentro[$emision['multiple_id']] = $emision['canal'] . "-" . $tipo;
            }
            foreach ($multiples as $id_mux => $nom_mux) {
                $valmux = "-";
                if (array_key_exists($id_mux, $muxcentro)){
                    $valmux = $muxcentro[$id_mux];
                }
                $centro['Centro'][$nom_mux] = $valmux;
            }
            $habCubiertos = 0;
            if (count($centro['Emision']) > 0){
                foreach ($centro['Cobertura'] as $cobCentro) {
                    $idmuni = $cobCentro['municipio_id'];
                    $cobertura = $cobCentro['porcentaje'];
                    $municipio = $this->Municipio->read(null, $idmuni);
                    $habitantes = $municipio['Municipio']['poblacion'];
                    $habCubiertos = $habCubiertos + round($habitantes * $cobertura / 100);
                }
            }
            $centro['Centro']['habCubiertos'] = $habCubiertos;
        }
        $this->set('centros', $centros);
        $this->layout = 'xls';
    }

    public function xlsanexo(){
        // Aumentamos el tiempo máximo de ejecución
        ini_set('max_execution_time', '60');

        // Cargamos el Modelo de Municipio:
        $this->loadModel('Municipio');
        // Buscamos la provincia:
        $this->Municipio->recursive = -1;
        $opciones = array(
            'fields' => array('Municipio.cpro', 'Municipio.provincia'),
            'order' => 'Municipio.provincia'
        );
        $provincias = $this->Municipio->find('list', $opciones);

        // Cargamos el Modelo de Multiple
        $this->loadModel('Multiple');
        $multiples = $this->Multiple->find('list',
            array(
                'conditions' => array('Multiple.soportado' => 'SI'),
                'fields' => array('Multiple.id', 'Multiple.nombre')
            )
        );
        $this->set('multiples', $multiples);

        $centros = $this->Centro->find('all',
            array(
                'order' => array('Centro.provincia', 'Centro.centro'),
                'conditions' => array('Centro.activo' => 'SI')
            )
        );
        foreach ($centros as &$centro) {
            $muxcentro = array();
            foreach ($centro['Emision'] as $emision) {
                $tipo = "GF";
                if ($emision['tipo'] == "Emisor"){
                    $tipo = "E";
                }
                $muxcentro[$emision['multiple_id']] = $emision['canal'] . "-" . $tipo;
            }
            foreach ($multiples as $id_mux => $nom_mux) {
                $valmux = "-";
                if (array_key_exists($id_mux, $muxcentro)){
                    $valmux = $muxcentro[$id_mux];
                }
                $centro['Centro'][$nom_mux] = $valmux;
            }
        }
        $this->set('centros', $centros);
        $this->layout = 'xls';
    }

    public function xlsdescargar(){
        // Buscamos los centros
        $centros = $this->Centro->find('all', array('order' => 'Centro.centro'));
        $this->set('centros', $centros);

        // Buscamos las emisiones
        $emisions = $this->Centro->Emision->find('all', array('order' => 'Emision.centro_id'));
        $this->set('emisions', $emisions);

        // Cargamos el Modelo de Multiple
        $this->loadModel('Multiple');
        $multiples = $this->Multiple->find('all', array('order' => array('Multiple.nombre')));
        $this->set('multiples', $multiples);

        // Cargamos el Modelo de Programa
        $this->loadModel('Programa');
        $programas = $this->Programa->find('all', array('order' => array('Programa.nombre')));
        $this->set('programas', $programas);

        // Cargamos el Modelo de Municipio
        $this->loadModel('Municipio');
        $municipios = $this->Municipio->find('all', array('order' => array('Municipio.Provincia', 'Municipio.Nombre')));
        $this->set('municipios', $municipios);

        // Buscamos las coberturas
        $coberturas = $this->Centro->Cobertura->find('all', array('order' => array('Cobertura.centro_id', 'Cobertura.municipio_id')));
        $this->set('coberturas', $coberturas);

        $this->layout = 'xls';
    }

    public function apagar($id = null){
        $this->Centro->id = $id;
        if ($this->Centro->exists()) {
            if (!$this->request->is('post')) {
                throw new MethodNotAllowedException(__('Error: Método de acceso no permitido'));
            }
            else{
                if ($this->Centro->Emision->deleteAll(array('Emision.centro_id' => $id), false)){
                    // Actualizamos el estado 'activo' del centro:
                    if ($this->Centro->saveField('activo', 'NO')) {
                        $this->Session->setFlash(__('Centro apagado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                        $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $id));
                    }
                    else {
                        $this->Session->setFlash(__('Error al apagar el Centro'), 'default', array('class' => 'ink-alert basic error'));
                        $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $id));
                    }
                }
                else{
                    $this->Session->setFlash(__('Error al borrar las emisiones del Centro'), 'default', array('class' => 'ink-alert basic error'));
                    $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $id));
                }
            }
        }
        else{
            throw new NotFoundException(__('Error: El Centro seleccionado no existe'));
        }
    }

    public function supervision ($id = null){
        $this->Centro->id = $id;
        if (!$this->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        $this->set('title_for_layout', __('Supervisión de Centro TDT'));
        $centro = $this->Centro->read(null, $id);
        $this->set('centro', $centro);
    }

    public function catastro(){
        // Buscamos el fichero
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        // Comrpobamos si existe
        $ruta = 'files' . DS . 'Catastro.ods';
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
            // Fijamos como hoja activa la segunda (sólo se importa una)
            $objPHPExcel->setActiveSheetIndex(1);
            // Obtenemos el número de filas de la hoja:
            $maxfila = $objPHPExcel->getActiveSheet()->getHighestRow();
            $fila = 2;
            $centros = array();
            while (($fila < $maxfila) && ($objPHPExcel->getActiveSheet()->getCell("A" . $fila)->getValue()!= "")){
                $idcentro = $objPHPExcel->getActiveSheet()->getCell("A" . $fila)->getValue();
                $refcast = $objPHPExcel->getActiveSheet()->getCell("F" . $fila)->getValue();
                if ($refcast != ""){
                    $centro = $this->Centro->read(null, $idcentro);
                    $centro['Centro']['refcast'] = $refcast;
                    $centro['Centro']['comcast'] = $objPHPExcel->getActiveSheet()->getCell("H" . $fila)->getValue();
                    $centros[] = $centro;
                }
                $fila++;
            }
            if ($this->request->is('post') || $this->request->is('put')) {
                if (count($centros) > 0){
                    $ncentros = 0;
                    foreach ($centros as $centro) {
                        if ($centro['Centro']['info'] != null){
                            $info = $centro['Centro']['info'] . '. ' . __('Nota Catastro') . ': ' . $centro['Centro']['comcast'];
                        }
                        else{
                            $info = __('Nota Catastro') . ': ' . $centro['Centro']['comcast'];
                        }
                        $this->Centro->read(null, $centro['Centro']['id']);
                        $this->Centro->set(array(
                            'catastro' => $centro['Centro']['refcast'],
                            'info' => $info,
                        ));
                        if ($this->Centro->save()){
                            $ncentros++;
                        }
                        else{
                            $this->Session->setFlash(__('Error al guardar datos de Catastro del Centro con') . ' ID ' . $centro['id'], 'default', array('class' => 'ink-alert basic error'));
                            $this->redirect(array('controller' => 'centros', 'action' => 'catastro'));
                        }
                    }
                    $this->Session->setFlash(__('Datos de catastro de') . ' ' . $ncentros . ' ' . __('centros importados correctamente'), 'default', array('class' => 'ink-alert basic success'));
                    $this->redirect(array('controller' => 'centros', 'action' => 'index'));
                }
            }
            else{
                $this->set('title_for_layout', __('Importar datos de catastros de Centro TDT'));
                $this->set('centros', $centros);
            }
        }
        else{
            $this->Session->setFlash(__('<b>Error:</b> No se ha encontrado el fichero de catastro'), 'default', array('class' => 'ink-alert basic error'));
            $this->redirect(array('controller' => 'centros', 'action' => 'index'));
        }
    }

    public function propietarios(){
        // Limitamos el alcance de las búsquedas:
        $this->Centro->recursive = -1;
        // Buscamos el fichero
        App::uses('Folder', 'Utility');
        App::uses('File', 'Utility');
        // Comrpobamos si existe
        $ruta = 'files' . DS . 'Propietarios.ods';
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
            $centros = array();
            while (($fila < $maxfila) && ($objPHPExcel->getActiveSheet()->getCell("A" . $fila)->getValue()!= "")){
                $idcentro = $objPHPExcel->getActiveSheet()->getCell("A" . $fila)->getValue();
                $centro = $this->Centro->read(null, $idcentro);
                $centro['Centro']['suelo'] = $objPHPExcel->getActiveSheet()->getCell("C" . $fila)->getValue();
                $centro['Centro']['caseta'] = $objPHPExcel->getActiveSheet()->getCell("D" . $fila)->getValue();
                $centro['Centro']['torre'] = $objPHPExcel->getActiveSheet()->getCell("E" . $fila)->getValue();
                $centro['Centro']['electrico'] = $objPHPExcel->getActiveSheet()->getCell("F" . $fila)->getValue();
                $centros[] = $centro;
                $fila++;
            }
            if ($this->request->is('post') || $this->request->is('put')) {
                $ncentros = 0;
                foreach ($centros as $centro) {
                    //$this->Centro->id = $centro['Centro']['id'];
                    $this->Centro->read(null, $centro['Centro']['id']);
                    $this->Centro->set(array(
                        'suelo' => $centro['Centro']['suelo'],
                        'caseta' => $centro['Centro']['caseta'],
                        'torre' => $centro['Centro']['torre'],
                        'electrico' => $centro['Centro']['electrico']
                    ));
                    if ($this->Centro->save()){
                        $ncentros++;
                    }
                    else{
                        $this->Session->setFlash(__('Error al guardar datos de Propiedad del Centro') . ' ' . $centro['Centro']['centro'], 'default', array('class' => 'ink-alert basic error'));
                        $this->redirect(array('controller' => 'centros', 'action' => 'propietarios'));
                    }
                }
                $this->Session->setFlash(__('Datos de propiedad de') . ' ' . $ncentros . ' ' . __('centros importados correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'centros', 'action' => 'index'));

            }
            else{
                $this->set('title_for_layout', __('Importar datos de propietarios de Centro TDT'));
                $this->set('centros', $centros);
            }
        }
        else{
            $this->Session->setFlash(__('Error: No se ha encontrado el fichero de datos de Propiedad de los Centros'), 'default', array('class' => 'ink-alert basic error'));
            $this->redirect(array('controller' => 'centros', 'action' => 'index'));
        }
    }
}
?>
