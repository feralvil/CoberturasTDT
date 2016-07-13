<?php

/**
 * Descriptción de EmisionsController
 *
 * @author alfonso_fer
 */
class EmisionsController extends AppController {
    //put your code here
    // Opciones de paginación por defecto:
    public $paginate = array(
        'limit' => 30,
        'order' => array('Programa.nombre' => 'asc')
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

    public function index(){
        /* Completar */
    }

    public function agregar() {
        if ($this->request->is('post') || $this->request->is('put')) {
            // Comprobamos que el múltiple no se esté emitiendo ya en este centro:
            $nmuxexist = $this->Emision->find('count', array(
                'conditions' => array(
                    'Emision.centro_id' => $this->request->data['Emision']['centro_id'],
                    'Emision.multiple_id' => $this->request->data['Emision']['multiple_id'],
                )
            ));
            if ($nmuxexist > 0){
                $this->Session->setFlash(__('Error: el múltiple seleccionado ya se emite en este centro'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $this->request->data['Emision']['centro_id']));
            }
            else{
                // Comprobamos el número múltiples que está emitiendo este centro:
                $nmux = $this->Emision->find('count', array(
                    'conditions' => array(
                        'Emision.centro_id' => $this->request->data['Emision']['centro_id'],
                    )
                ));
                // Guardamos los datos:
                $this->Emision->create();
                if ($this->Emision->save($this->request->data)) {
                    // Actualizamos el estado del Centro:
                    if ($nmux > 0){
                        $this->Session->setFlash(__('Múltiple agregado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                        $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $this->request->data['Emision']['centro_id']));
                    }
                    else{
                        $this->Emision->Centro->id = $this->request->data['Emision']['centro_id'];
                        // Actualizamos el estado 'activo' del centro:
                        if ($this->Emision->Centro->saveField('activo', 'SI')) {
                            $this->Session->setFlash(__('Múltiple agregado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                            $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $this->request->data['Emision']['centro_id']));
                        }
                        else {
                            $this->Session->setFlash(__('Error al activar el Centro'), 'default', array('class' => 'ink-alert basic error'));
                            $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $this->request->data['Emision']['centro_id']));
                        }
                    }
                }
                else {
                    $this->Session->setFlash(__('Error al agregar el múltiple'), 'default', array('class' => 'ink-alert basic error'));
                    $this->redirect(array('controller' => 'programas', 'action' => 'multiples'), $this->request->data['Emision']['centro_id']);
                }
            }
        }
        else {
            throw new MethodNotAllowedException();
        }
    }

    public function editar($id = null){
        $this->Emision->id = $id;
        if (!$this->Emision->exists()) {
            throw new NotFoundException(__('Error: la emisión seleccionada no existe'));
        }
        // Buscamos los datos de la emisión:
        $emision = $this->Emision->read(null, $id);

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Emision->save($this->request->data)) {
                $this->Session->setFlash(__('Emisión modificada correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $emision['Emision']['centro_id']));
            }
            else {
                $this->Session->setFlash(__('Error al modificar la emisión'), 'default', array('class' => 'ink-alert basic error'));
                $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $emision['Emision']['centro_id']));
            }
        }
        else{
            $this->set('title_for_layout', __('Modificar Emisión de Centro TDT de la Generalitat'));
             // Select de Municipios
            $opciones = array(
                'fields' => array('Multiple.id', 'Multiple.nombre'),
                'order' => 'Multiple.nombre'
            );
            $this->set('multisel', $this->Emision->Multiple->find('list', $opciones));

            // Pasamos los datos de cobertura al formulario de la vista
            $this->request->data = $emision;
        }
    }

    public function borrar($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Emision->id = $id;
        if (!$this->Emision->exists()) {
            throw new NotFoundException(__('Error: la emisión seleccionada no existe'));
        }
        // Buscamos los datos de cobertura:
        $emision = $this->Emision->read(null, $id);

        if ($this->Emision->delete()) {
            // Comprobamos el número múltiples que está emitiendo este centro:
            $nmux = $this->Emision->find('count', array(
                'conditions' => array(
                    'Emision.centro_id' => $emision['Emision']['centro_id'],
                )
            ));
            if ($nmux > 0){
                $this->Session->setFlash(__('Múltiple eliminado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $emision['Emision']['centro_id']));
            }
            else{
                // Actualizamos el estado 'activo' del centro:
                $this->Emision->Centro->id = $emision['Emision']['centro_id'];
                if ($this->Emision->Centro->saveField('activo', 'NO')) {
                    $this->Session->setFlash(__('Múltiple eliminado correctamente'), 'default', array('class' => 'ink-alert basic success'));
                    $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $emision['Emision']['centro_id']));
                }
                else {
                    $this->Session->setFlash(__('Error al apagar el Centro'), 'default', array('class' => 'ink-alert basic error'));
                    $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $emision['Emision']['centro_id']));
                }
            }
        }
        else{
            $this->Session->setFlash(__('Error al eliminar el Múltiple seleccionado'), 'default', array('class' => 'ink-alert basic error'));
            $this->redirect(array('controller' => 'centros', 'action' => 'multiples', $cobertura['Cobertura']['centro_id']));
        }
    }
}

?>
