<?php

/**
 * Descriptción de CoberturasController
 *
 * @author alfonso_fer
 */
class CoberturasController extends AppController {
    public $paginate = array(
        'limit' => 20,
        'order' => 'Cobertura.centro_id'
    );

    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
         if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array('index', 'detalle', 'editar', 'agregar', 'cobcentro', 'borrar', 'xlscoberturas');
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

    public function index() {
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Coberturas de los Centros TDT de la Generalitat'));

        // Select de Centros
        $opciones = array(
            'fields' => array('Centro.id', 'Centro.centro'),
            'order' => 'Centro.centro'
        );
        $this->set('centrosel', $this->Cobertura->Centro->find('list', $opciones));

        // Select de Municipios
        $opciones = array(
            'fields' => array('Municipio.id', 'Municipio.nombre'),
            'order' => 'Municipio.nombre'
        );
        $this->set('munisel', $this->Cobertura->Municipio->find('list', $opciones));

        // Array de condiciones para la búsqueda:
        $condiciones = array();

        // Comprobamos si hemos recibido datos del formulario:
        if ($this->request->is('post')){
            // Condiciones iniciales:
            $tampag = 20;
            $pagina = 1;
            // Select de Centro
            if (!empty($this->request->data['Coberturas']['centro'])){
                $addcond = array('Cobertura.centro_id'  => $this->request->data['Coberturas']['centro']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Select de Municipio
            if (!empty($this->request->data['Coberturas']['municipio'])){
                $addcond = array('Cobertura.municipio_id'  => $this->request->data['Coberturas']['municipio']);
                $condiciones = array_merge($addcond, $condiciones);
            }

            // Cambio de página
            if (!empty($this->request->data['Coberturas']['irapag'])&&($this->request->data['Coberturas']['irapag'] > 0)){
                $this->paginate['page'] = $this->request->data['Coberturas']['irapag'];
            }
            // Tamaño de página
            if (!empty($this->request->data['Coberturas']['regPag'])&&($this->request->data['Coberturas']['regPag'] > 0)){
                $this->paginate['limit'] = $this->request->data['Coberturas']['regPag'];
            }
        }

        // Solo buscamos los datos de las flotas:
        //$this->Municipio->recursive = -1;
        $this->paginate['conditions'] = $condiciones;
        $coberturas = $this->paginate();
        foreach ($coberturas as &$cobertura) {
            $cobertura['nmux'] = 0;
            $nmux = $this->Cobertura->Centro->Emision->find('count', array(
                'conditions' => array(
                    'Emision.centro_id' => $cobertura['Cobertura']['centro_id'],
                )
            ));
            $cobertura['nmux'] = $nmux;
        }
        $this->set('coberturas', $coberturas);
    }

    public function agregar(){
        if ($this->request->is('post') || $this->request->is('put')) {
            // Redirigimos:
            $this->Session->setFlash(__('Redirigido desde agregar'), 'default', array('class' => 'ink-alert basic success'));
            $this->redirect(array('controller' => 'coberturas', 'action' => 'index'));
        }
        else{
            $this->set('title_for_layout', __('Nueva Cobertura de Centro TDT de la Generalitat'));

            // Select de Centros
            $opciones = array(
                'fields' => array('Centro.id', 'Centro.centro'),
                'order' => 'Centro.centro'
            );
            $this->set('centrosel', $this->Cobertura->Centro->find('list', $opciones));

            // Select de Municipios
            $opciones = array(
                'fields' => array('Municipio.id', 'Municipio.nombre'),
                'order' => 'Municipio.nombre'
            );
            $this->set('munisel', $this->Cobertura->Municipio->find('list', $opciones));
        }
    }

    public function cobcentro($idcentro = null){
        if ($this->request->is('post') || $this->request->is('put')) {
            // Comprobamos si ya existe una tupla para el centro y municipio seleccionados
            $ncob = $this->Cobertura->find('count', array(
                'conditions' => array(
                    'Cobertura.municipio_id' => $this->request->data['Cobertura']['municipio_id'],
                    'Cobertura.centro_id' => $idcentro
                )
            ));
            if ($ncob > 0){
                $this->Session->setFlash(__('Error: el municipio seleccionado ya se encuentra en la cobertura del centro'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'coberturas', 'action' => 'cobcentro', $idcentro));
            }
            else{
                // Guardamos el centro
                $this->request->data['Cobertura']['centro_id'] = $idcentro;
                if ($this->Cobertura->save($this->request->data)) {
                   $this->Session->setFlash(__('Municipio agregado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                   $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $idcentro));
                }
                else{
                    $this->Session->setFlash(__('Error al agregar el municipio'), 'default', array('class' => 'ink-alert basic error'));
                    $this->redirect(array('controller' => 'coberturas', 'action' => 'cobcentro', $idcentro));
                }
            }
        }
        else {
            $this->set('title_for_layout', __('Nueva Cobertura de Centro TDT de la Generalitat'));
             // Select de Municipios
            $opciones = array(
                'fields' => array('Municipio.id', 'Municipio.nombre'),
                'order' => 'Municipio.nombre'
            );
            $this->set('munisel', $this->Cobertura->Municipio->find('list', $opciones));

            // Buscamos el centro:
            $this->Cobertura->Centro->id = $idcentro;
            if (!$this->Cobertura->Centro->exists()) {
                throw new NotFoundException(__('Error: el centro seleccionado no existe'));
            }
            $centro = $this->Cobertura->Centro->read(null, $idcentro);
            // Buscamos los municipios de las coberturas:
            if (count($centro['Cobertura'] > 0)){
                $coberturas = array();
                foreach ($centro['Cobertura'] as $cobertura){
                    $cobiter = array();
                    $muni = $this->Cobertura->Municipio->find('first', array('conditions' => array('Municipio.id' => $cobertura['municipio_id'])));
                    $cobiter['idcob'] = $cobertura['id'];
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
    }

    public function editar($id = null){
        $this->Cobertura->id = $id;
        if (!$this->Cobertura->exists()) {
            throw new NotFoundException(__('Error: la cobertura seleccionada no existe'));
        }
        // Buscamos los datos de cobertura:
        $cobertura = $this->Cobertura->read(null, $id);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Cobertura->save($this->request->data)) {
                $this->Session->setFlash(__('Cobertura modificada correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $cobertura['Cobertura']['centro_id']));
            }
            else {
                $this->Session->setFlash(__('Error al modificadar la cobertura'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'coberturas', 'action' => 'cobcentro', $idcentro));
            }
        }
        else{
            $this->set('title_for_layout', __('Modificar Cobertura de Centro TDT de la Generalitat'));
             // Select de Municipios
            $opciones = array(
                'fields' => array('Municipio.id', 'Municipio.nombre'),
                'order' => 'Municipio.nombre'
            );
            $this->set('munisel', $this->Cobertura->Municipio->find('list', $opciones));

            // Pasamos los datos de cobertura al formulario de la vista
            $this->request->data = $cobertura;

        }
    }

    public function borrar($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Cobertura->id = $id;
        if (!$this->Cobertura->exists()) {
            throw new NotFoundException(__('Error: la cobertura seleccionada no existe'));
        }
        // Buscamos los datos de cobertura:
        $cobertura = $this->Cobertura->read(null, $id);

        if ($this->Cobertura->delete()) {
            $this->Session->setFlash(__('Cobertura eliminada correctamente'), 'default', array('class' => 'ink-alert basic success'));
            $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $cobertura['Cobertura']['centro_id']));
        }
        else{
            $this->Session->setFlash(__('Error al eliminar la Cobertura seleccionada'), 'default', array('class' => 'ink-alert basic error'));
            $this->redirect(array('controller' => 'centros', 'action' => 'detalle', $cobertura['Cobertura']['centro_id']));
        }
    }

    public function xlscoberturas(){
        $coberturas = $this->Cobertura->find('all');
        foreach ($coberturas as &$cobertura) {
            $cobertura['nmux'] = 0;
            $nmux = $this->Cobertura->Centro->Emision->find('count', array(
                'conditions' => array(
                    'Emision.centro_id' => $cobertura['Cobertura']['centro_id'],
                )
            ));
            $cobertura['nmux'] = $nmux;
        }
        $this->set('coberturas', $coberturas);
        $this->layout = 'xls';
    }
}
?>
