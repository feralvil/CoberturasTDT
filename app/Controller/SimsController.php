<?php
class SimsController extends AppController {
    // Opciones de paginación por defecto:
    public $paginate = array(
        'limit' => 30,
    );


    // Autorización
    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
         if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array('index', 'detalle', 'editar', 'agregar', 'borrar', 'xlssims');
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
        // Fijamos el título de la vista
        $this->set('title_for_layout', __('Tarjetas de Supervisón TDT'));

        // Select de Centros:
        $opciones = array(
            'fields' => array('Centro.id', 'Centro.centro'),
            'order' => 'Centro.centro'
        );
        $this->set('centrosel', $this->Sim->Centro->find('list', $opciones));

        // Array de condiciones para la búsqueda:
        $condiciones = array();

        // Select de Centro
        if (!empty($this->request->data['Sim']['centro_id'])) {
            $addcond = array('Sim.centro_id' => $this->request->data['Sim']['centro_id']);
            $condiciones = array_merge($addcond, $condiciones);
        }
        // Select de Uso
        if (!empty($this->request->data['Sim']['uso'])) {
            $addcond = array('Sim.uso' => $this->request->data['Sim']['uso']);
            $condiciones = array_merge($addcond, $condiciones);
        }
        // Select de Cobertura
        if (!empty($this->request->data['Sim']['cobertura'])) {
            $addcond = array('Sim.cobertura' => $this->request->data['Sim']['cobertura']);
            $condiciones = array_merge($addcond, $condiciones);
        }

        // Campo de Número
        if (!empty($this->request->data['Sim']['numero'])) {
            $addcond = array('Sim.numero LIKE '  => '%' . $this->request->data['Sim']['numero'] . '%');
            $condiciones = array_merge($addcond, $condiciones);
        }
        // Campo de ICC
        if (!empty($this->request->data['Sim']['icc'])) {
            $addcond = array('Sim.icc LIKE '  => '%' . $this->request->data['Sim']['icc'] . '%');
            $condiciones = array_merge($addcond, $condiciones);
        }
        // Campo de IP
        if (!empty($this->request->data['Sim']['dir_ip'])) {
            $addcond = array('Sim.dir_ip LIKE '  => '%' . $this->request->data['Sim']['dir_ip'] . '%');
            $condiciones = array_merge($addcond, $condiciones);
        }
        // Cambio de página
        if (!empty($this->request->data['Sim']['irapag'])&&($this->request->data['Sim']['irapag'] > 0)){
            $this->paginate['page'] = $this->request->data['Sim']['irapag'];
        }
        // Tamaño de página
        if (!empty($this->request->data['Sim']['regPag'])&&($this->request->data['Sim']['regPag'] > 0)){
            $this->paginate['limit'] = $this->request->data['Sim']['regPag'];
        }

        // Buscamos los datos
        $this->paginate['conditions'] = $condiciones;
        $sims = $this->paginate();
        $this->set('sims', $sims);
    }

    // Editar
    public function editar($id=null){
        $this->Sim->id = $id;
        if (!$this->Sim->exists()) {
            throw new NotFoundException(__('Error: la tarjeta seleccionada no existe'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $idcentro = $this->request->data['Sim']['centro_id'];
            // Guardamos los datos:
            if ($this->Sim->save($this->request->data)) {
                $this->Session->setFlash(__('Tarjeta modificada correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'centros', 'action' => 'supervision', $idcentro));
            }
            else {
                $this->Session->setFlash(__('Error al guardar la tarjeta.') . ' ' . $this->validationErrors , 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'sims', 'action' => 'editar', $id));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Modificar Tarjeta SIM de Centro TDT'));
            $this->request->data = $this->Sim->read(null, $id);
        }
    }

    // Editar
    public function agregar($idcentro=null){
        $this->Sim->Centro->id = $idcentro;
        if (!$this->Sim->Centro->exists()) {
            throw new NotFoundException(__('Error: el centro seleccionado no existe'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $idcentro = $this->request->data['Sim']['centro_id'];
            $this->Sim->create();
            // Guardamos los datos:
            if ($this->Sim->save($this->request->data)) {
                $this->Session->setFlash(__('Tarjeta modificada correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'centros', 'action' => 'supervision', $idcentro));
            }
            else {
                $this->Session->setFlash(__('Error al guardar la tarjeta') . ' ' . $this->validationErrors, 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'sims', 'action' => 'agregar', $idcentro));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Agregar Tarjeta SIM a Centro TDT'));
            $centro = $this->Sim->Centro->read(null, $idcentro);
            $this->set('centro', $centro);
        }
    }

    // Borrar
    public function borrar($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Sim->id = $id;
        if (!$this->Sim->exists()) {
            throw new NotFoundException(__('Error: la tarjeta seleccionada no existe'));
        }

        $sim = $this->Sim->read(null, $id);
        if ($this->Sim->delete()) {
            $this->Session->setFlash(__('Tarjeta SIM') . ' ' . $sim['Sim']['numero'] . ' ' . __('eliminada correctamente'), 'default', array('class' => 'ink-alert basic success'));
            $this->redirect(array('controller' => 'centros', 'action' => 'supervision', $sim['Sim']['centro_id']));
        }
        else{
            $this->Session->setFlash(__('Error al borrar la tarjeta SIM ' . $sim['Sim']['numero']), 'default', array('class' => 'ink-alert basic error'));
            $this->redirect(array('controller' => 'centros', 'action' => 'supervision', $sim['Sim']['centro_id']));
        }
    }

    public function xlssims(){
        // Buscamos los centros
        $sims = $this->Sim->find('all');
        $this->set('sims', $sims);

        $this->layout = 'xls';
    }
}
?>
