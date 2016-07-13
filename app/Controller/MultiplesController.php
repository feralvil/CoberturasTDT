<?php

/**
 * Descriptción de MultiplesController
 *
 * @author alfonso_fer
 */
class MultiplesController extends AppController {
    // Opciones de paginación por defecto:
    public $paginate = array(
        'limit' => 30,
        'order' => array('Multiple.nombre' => 'asc')
    );

    public function isAuthorized($user) {
        // Comprobamos el rol del usuario
         if (isset($user['role'])) {
            $rol = $user['role'];
            // Acciones por defecto
            $accPerm = array();
            if ($rol == 'colab') {
                $accPerm = array('index', 'detalle', 'editar', 'agregar', 'borrar');
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
        $this->set('title_for_layout', __('Múltiples TDT'));

        // Array de condiciones para la búsqueda:
        $condiciones = array();

        // Comprobamos si hemos recibido datos del formulario:
        if ($this->request->is('post')){
            // Condiciones iniciales:
            $tampag = 30;
            $pagina = 1;
            // Select de Ambito
            if (!empty($this->request->data['Multiples']['ambito'])){
                $addcond = array('Multiple.ambito'  => $this->request->data['Multiples']['ambito']);
                $condiciones = array_merge($addcond, $condiciones);
            }
            // Select de Soportado
            if (!empty($this->request->data['Multiples']['soportado'])){
                $addcond = array('Multiple.soportado'  => $this->request->data['Multiples']['soportado']);
                $condiciones = array_merge($addcond, $condiciones);
            }
        }

        $this->paginate['conditions'] = $condiciones;
        $multiples = $this->paginate();
        $this->set('multiples', $multiples);

    }

    public function agregar(){
        if ($this->request->is('post') || $this->request->is('put')) {
            // Guardamos los datos:
            $this->Multiple->create();
            if ($this->Multiple->save($this->request->data)) {
                $this->Session->setFlash(__('Múltiple creado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'multiples', 'action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('Error al crear el múltiple'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'multiples', 'action' => 'index'));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Nuevo Múltiple TDT de la Comunitat'));
        }
    }

    public function editar($id = null){
        $this->Multiple->id = $id;
        if (!$this->Multiple->exists()) {
            throw new NotFoundException(__('Error: el múltiple seleccionado no existe'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            // Guardamos los datos:
            if ($this->Multiple->save($this->request->data)) {
                $this->Session->setFlash(__('Múltiple creado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'multiples', 'action' => 'index'));
            }
            else {
                $this->Session->setFlash(__('Error al guardar el múltiple'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'multiples', 'action' => 'index'));
            }
        }
        else{
            // Fijamos el título de la vista
            $this->set('title_for_layout', __('Modificar Múltiple TDT de la Comunitat'));
            $this->request->data = $this->Multiple->read(null, $id);
        }
    }

    public function borrar($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Multiple->id = $id;
        if (!$this->Multiple->exists()) {
            throw new NotFoundException(__('Error: el multiple seleccionado no existe'));
        }
        // Buscamos los datos de cobertura:
        $multiple = $this->Multiple->read(null, $id);

        if ($this->Multiple->delete()) {
            $this->Session->setFlash(__('Multiple') . ' ' . $multiple['Multiple']['nombre'] . ' ' . __('eliminado correctamente'), 'default', array('class' => 'ink-alert basic success'));
            $this->redirect(array('controller' => 'multiples', 'action' => 'index'));
        }
        else{
            $this->Session->setFlash(__('Error al eliminar el Múltiple ' . $Multiple['Multiple']['nombre']), 'default', array('class' => 'ink-alert basic error'));
            $this->redirect(array('controller' => 'multiples', 'action' => 'index'));
        }
    }
}

?>
